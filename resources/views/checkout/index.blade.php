<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black min-h-screen flex items-center justify-center p-2">
    <div class="w-full max-w-md">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-6">
            <div class="mb-5 text-center">
                <h1 class="text-xl font-bold text-gray-900">Checkout</h1>
            </div>
            {{-- Total --}}
            {{-- <div class="bg-gray-50 rounded-lg p-4 mb-6 flex justify-between items-center border border-gray-100">
                <span class="text-gray-600 text-sm font-medium">Total to pay</span>
                <span class="text-2xl font-bold text-indigo-600">
                    ₹{{ number_format($subtotal, 2) }}
                </span>
            </div> --}}
            <form method="POST" action="{{ route('checkout.placeOrder') }}">
                @csrf
                {{-- Payment Method --}}
                <div class="space-y-3 mb-6">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Payment Method
                    </p>
                    <label class="relative flex items-center p-3 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-50 transition
                           has-[:checked]:border-indigo-600
                           has-[:checked]:bg-indigo-50/50
                           has-[:checked]:ring-1
                           has-[:checked]:ring-indigo-600">
                        <input type="radio" name="payment_method" value="cod" checked required
                            class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                        <span class="ml-3 text-sm font-medium text-gray-900">
                            Cash on Delivery
                        </span>
                    </label>
                    <label class="relative flex items-center p-3 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-50 transition
                           has-[:checked]:border-indigo-600
                           has-[:checked]:bg-indigo-50/50
                           has-[:checked]:ring-1
                           has-[:checked]:ring-indigo-600">
                        <input type="radio" name="payment_method" value="stripe" required
                            class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                        <span class="ml-3 text-sm font-medium text-gray-900">
                             Pay with Stripe
                        </span>
                    </label>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('cart.view') }}"
                        class="flex justify-center items-center px-4 py-2.5 rounded-lg bg-white text-sm font-semibold text-gray-700 border border-gray-300 hover:bg-gray-50 transition">
                        Back
                    </a>
                    <button type="submit"
                        class="flex justify-center items-center px-4 py-2.5 rounded-lg bg-indigo-600 text-white text-sm font-bold hover:bg-indigo-700 transition shadow-sm">
                        Pay Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>