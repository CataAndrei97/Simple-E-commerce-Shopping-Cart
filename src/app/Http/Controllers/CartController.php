<?php

namespace App\Http\Controllers;

use App\Jobs\NotifyLowStock;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = session()->get('cart', []);

        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        return Inertia::render('Cart/Index', [
            'cart' => $cart,
            'total' => $total,
        ]);
    }

    public function add(Request $request)
    {
        $product = Product::findOrFail($request->id);

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);

        return back();
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$request->id])) {
            $cart[$request->id]['quantity'] = max(1, (int) $request->quantity);
            session()->put('cart', $cart);
        }

        return back();
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        unset($cart[$request->id]);
        session()->put('cart', $cart);

        return back();
    }

    public function clear()
    {
        session()->forget('cart');
        return back();
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Cart is empty.');
        }

        try {
            DB::transaction(function () use ($cart) {
                $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

                $order = Order::create([
                    'customer_name' => 'Test User',
                    'customer_email' => 'test@example.com',
                    'total' => $total,
                ]);

                foreach ($cart as $item) {
                    $product = Product::lockForUpdate()->find($item['id']);
                    if (!$product) {
                        throw new \Exception("Product not found: {$item['id']}");
                    }

                    if ($product->stock_quantity < $item['quantity']) {
                        throw new \Exception("Not enough stock for {$product->name}");
                    }

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                    ]);

                    $product->decrement('stock_quantity', $item['quantity']);

                    if ($product->stock_quantity < 5) {
                        dispatch(new NotifyLowStock($product));
                    }
                }

                session()->forget('cart');
            });

            return redirect()->route('products.index')->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }
}
