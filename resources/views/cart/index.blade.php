<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="max-w-5xl mx-auto px-4 py-10">

        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            Your Cart
        </h1>

        @if ($cartItems->isEmpty())
            <div class="bg-white p-6 rounded shadow text-center text-gray-600">
                Your cart is empty.
            </div>
        @else

            <div class="bg-white rounded shadow overflow-hidden">

                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr class="text-left text-gray-600">
                            <th class="p-4">Product</th>
                            <th class="p-4 text-center">Price</th>
                            <th class="p-4 text-center">Quantity</th>
                            <th class="p-4 text-right">Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($cartItems as $item)
                            <tr class="border-b">
                                <td class="p-4">
                                    {{ $item->product->name }}
                                </td>

                                <td class="p-4 text-center">
                                    ₹{{ number_format($item->product->price, 2) }}
                                </td>

                                <td class="p-4 text-center">
                                    {{ $item->quantity }}
                                </td>

                                <td class="p-4 text-right font-semibold">
                                    ₹{{ number_format($item->product->price * $item->quantity, 2) }}
                                </td>
                                <td class="p-4 text-center">
                                    <form method="POST" action="{{ route('cart.remove', $item->id) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-semibold">
                                            Remove
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="p-4 flex justify-end bg-gray-50">
                    <div class="text-lg font-bold text-gray-800">
                        Total: ₹{{ number_format($total, 2) }}
                    </div>
                </div>

            </div>

        @endif

        <div class="mt-6">
            <a href="{{ route('guest.products.index') }}" class="text-sm text-indigo-600 hover:underline">
                ← Continue Shopping
            </a>
        </div>
        <div class="mt-6">
            <form action="{{ route('order.summary') }}" method="get">
                @csrf
                <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold">
                    Place Order
                </button>
            </form>

        </div>
    </div>


</body>

</html>