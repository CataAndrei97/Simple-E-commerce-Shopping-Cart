import React from "react";
import {Link, router} from "@inertiajs/react";

export default function Cart({ cart, total }) {
    const handleUpdate = (id, quantity) => {
        router.post(route("cart.update"), { id, quantity });
    };

    const handleRemove = (id) => {
        router.delete(route("cart.remove"), { data: { id } });
    };

    const handleClear = () => {
        router.post(route("cart.clear"));
    };

    return (
        <div className="max-w-4xl mx-auto mt-8">
            <h1 className="text-2xl font-bold mb-4">Your Cart</h1>

            {Object.keys(cart).length === 0 ? (
                <div className="mt-4 flex items-start flex-col">
                    <p className="text-gray-500">Your cart is empty.</p>
                    <Link
                        href="/"
                        className="bg-gray-800 text-white px-4 py-2 rounded"
                    >
                        Continue Shopping
                    </Link>
                </div>
            ) : (
                <div className="bg-white rounded-lg shadow p-4">
                    <table className="w-full text-left">
                        <thead>
                        <tr>
                            <th className="p-2">Product</th>
                            <th className="p-2">Price</th>
                            <th className="p-2">Qty</th>
                            <th className="p-2">Total</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        {Object.values(cart).map((item) => (
                            <tr key={item.id}>
                                <td className="p-2">{item.name}</td>
                                <td className="p-2">${item.price}</td>
                                <td className="p-2">
                                    <input
                                        type="number"
                                        min="1"
                                        value={item.quantity}
                                        onChange={(e) =>
                                            handleUpdate(item.id, e.target.value)
                                        }
                                        className="border rounded w-16 text-center"
                                    />
                                </td>
                                <td className="p-2">
                                    ${(item.price * item.quantity).toFixed(2)}
                                </td>
                                <td className="p-2">
                                    <button
                                        onClick={() => handleRemove(item.id)}
                                        className="text-red-500 hover:text-red-700"
                                    >
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        ))}
                        </tbody>
                    </table>

                    <div className="mt-4 flex justify-between items-center">
                        <p className="font-semibold text-lg">Total: ${total.toFixed(2)}</p>
                        <div className="flex gap-2">
                            <button
                                onClick={handleClear}
                                className="bg-red-500 text-white px-4 py-2 rounded"
                            >
                                Clear Cart
                            </button>
                            <button
                                onClick={() => router.post(route("cart.checkout"))}
                                className="bg-green-600 text-white px-4 py-2 rounded"
                            >
                                Checkout
                            </button>
                            <Link
                                href="/"
                                className="bg-gray-800 text-white px-4 py-2 rounded"
                            >
                                Continue Shopping
                            </Link>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}
