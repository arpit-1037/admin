<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Order Successful
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-8 text-center">

                {{-- Success Icon --}}
                <div class="flex justify-center mb-6">
                    <svg class="w-16 h-16 text-green-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                {{-- Title --}}
                <h3 class="text-2xl font-semibold text-gray-800 mb-2">
                    Thank you for your order!
                </h3>

                {{-- Dynamic Payment Message --}}
                @if($order->payment_method === 'cod')
                    <p class="text-gray-600 mb-4">
                        Your order has been placed successfully using
                        <span class="font-semibold">Cash on Delivery</span>.
                        Please keep the payment ready at the time of delivery.
                    </p>
                @elseif($order->payment_method === 'stripe')
                    <p class="text-gray-600 mb-4">
                        Your payment was completed successfully via
                        <span class="font-semibold">Stripe</span>.
                        Your order is now being processed.
                    </p>
                @endif

                {{-- Order Summary --}}
                <div class="bg-gray-50 border rounded-lg p-4 text-left mb-6">
                    <p class="text-sm text-gray-700 mb-1">
                        <span class="font-semibold">Order ID:</span>
                        {{ $order->order_number ?? $order->id }}
                    </p>

                    <p class="text-sm text-gray-700 mb-1">
                        <span class="font-semibold">Payment Method:</span>
                        {{ strtoupper($order->payment_method) }}
                    </p>

                    <p class="text-sm text-gray-700 mb-1">
                        <span class="font-semibold">Payment Status:</span>
                        {{ ucfirst($order->status) }}
                    </p>

                    <p class="text-sm text-gray-700 mb-1">
                        <span class="font-semibold">Order Status:</span>
                        {{ ucfirst($order->status) }}
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex justify-center gap-4">
                    {{-- <a href="{{ route('orders.show', $order->id) }}"
                        class="px-5 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                        View Order
                    </a> --}}

                    <a href="{{ route('guest.products.index') }}"
                        class="px-5 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition mb-4">
                        Continue Shopping
                    </a>
                </div>

            </div>
        </div>
    </div>
    @if (session('order_success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                alertSuccess(@json(session('order_success')));
            });
        </script>
    @endif

</x-app-layout>