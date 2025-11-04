import { router, Link } from "@inertiajs/react";
import { useState } from "react";

export default function Products({ initialProducts }) {
    const [products, setProducts] = useState(initialProducts);

    const handleAddToCart = (productId) => {
        router.post(route("cart.add"), { id: productId }, {
            onSuccess: () => {
                setProducts((prev) =>
                    prev.map((p) =>
                        p.id === productId
                            ? { ...p, stock_quantity: p.stock_quantity - 1 }
                            : p
                    )
                );
            },
        });
    };

    return (
        <div className="max-w-4xl mx-auto mt-8">
            <h1 className="text-2xl font-bold mb-4">Products</h1>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                {products?.map((product) => (
                    <div key={product.id} className="border rounded-lg p-4 bg-white shadow">
                        <h2 className="text-lg font-semibold">{product.name}</h2>
                        <p className="text-gray-600 mb-2">${product.price}</p>
                        <p className="text-sm text-gray-500 mb-4">
                            Stock: {product.stock_quantity}
                        </p>
                        <button
                            onClick={() => handleAddToCart(product.id)}
                            className="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700"
                        >
                            Add to Cart
                        </button>
                    </div>
                ))}
            </div>
            <div className="mt-6 text-center">
                <Link href="/cart" className="text-blue-600 underline">
                    View Cart
                </Link>
            </div>
        </div>
    );
}
