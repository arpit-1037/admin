@extends('layouts.user')

@section('title', 'My Orders')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-7xl mx-auto px-4 py-10">

        {{-- ORDERS --}}
        <div class="lg:col-span-2 space-y-6">

            @forelse ($orders as $order)

                <div class="bg-white border rounded-2xl shadow-sm p-5 space-y-4">

                    {{-- Order Header --}}
                    <div class="flex justify-between items-center bg-blue-50 p-3 rounded-lg">
                        <div>
                            <p class="text-sm text-gray-700">
                                Order #{{ $order->id }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ $order->created_at->format('d M Y') }}
                            </p>
                        </div>

                        <span class="px-3 py-1 rounded-full text-xs font-medium
                    {{ $order->status === 'paid'
                ? 'bg-green-100 text-green-700'
                : ($order->status === 'failed'
                    ? 'bg-red-100 text-red-700'
                    : 'bg-yellow-100 text-yellow-700') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    {{-- Items --}}
                    <div class="space-y-4">

                        @php
                            $sum = 0;
                        @endphp

                        @foreach ($order->items as $item)

                            @php
                                $product = $item->product;
                                $imageUrl = $product && $product->primaryImage
                                    ? asset('storage/' . $product->primaryImage->path)
                                    : asset('storage/products/placeholders/product.png');

                                $line = $item->price * $item->quantity;
                                $sum += $line;
                            @endphp

                            <div class="flex gap-4 items-center border rounded-xl p-4">

                                {{-- Image --}}
                                <div class="w-20 h-20 rounded-lg overflow-hidden bg-gray-100 flex-shrink-0">
                                    <img src="{{ $imageUrl }}" class="w-full h-full object-cover"
                                        onerror="this.src='{{ asset('storage/products/placeholders/product.png') }}'">
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-semibold text-gray-900 truncate">
                                        {{ $product?->name ?? 'Product unavailable' }}
                                    </h3>

                                    <div class="mt-1 text-xs text-gray-500 flex justify-between">
                                        <span>Qty: {{ $item->quantity }}</span>
                                        <span>₹{{ number_format($item->price, 2) }}</span>
                                        <span>Line total ₹{{ number_format($line, 2) }}</span>
                                    </div>
                                </div>

                            </div>
                        @endforeach

                        {{-- Order Total --}}
                        <div class="flex justify-end text-sm font-semibold text-gray-900 pt-2 border-t">
                            Total = ₹{{ number_format($sum, 2) }}
                        </div>

                    </div>

                </div>

            @empty
                <div class="bg-white p-6 rounded-xl text-center text-gray-500">
                    You have no orders yet.
                </div>
            @endforelse
        </div>

        {{-- SUMMARY --}}
        <div>
            <div class="bg-blue-50 border rounded-2xl shadow-sm p-5 space-y-3">
                <div class="bg-blue-50">
                    <h3 class="text-lg font-semibold text-gray-900">Orders Summary</h3>

                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Total Orders</span>
                        <span>{{ $orders->count() }}</span>
                    </div>
                </div>

                <div class="bg-blue-50 p-3 rounded-lg border-t pt-3 flex justify-between font-semibold text-gray-900">
                    <span>Total Spent</span>
                    <span>₹{{ number_format($orders->sum('total'), 2) }}</span>
                </div>
            </div>
        </div>

    </div>
@endsection