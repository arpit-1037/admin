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
                    <div class="space-y-5">

                        @php $sum = 0; @endphp

                        @foreach ($order->items as $item)

                            @php
                                $product = $item->product;
                                $imageUrl = $product && $product->primaryImage
                                    ? asset('storage/' . $product->primaryImage->path)
                                    : asset('storage/products/placeholders/product.png');

                                $line = $item->price * $item->quantity;
                                $sum += $line;
                            @endphp

                            <div
                                class="flex items-center gap-5 bg-white border border-gray-200 rounded-2xl p-5 hover:shadow-sm transition">

                                {{-- Product Image --}}
                                <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                                    <img src="{{ $imageUrl }}" class="w-full h-full object-cover"
                                        onerror="this.src='{{ asset('storage/products/placeholders/product.png') }}'">
                                </div>

                                {{-- Product Info --}}
                                <div class="flex-1 min-w-0">

                                    <h3 class="text-sm font-semibold text-gray-900 truncate">
                                        {{ $product?->name ?? 'Product unavailable' }}
                                    </h3>

                                    <div class="mt-2 flex items-center gap-6 text-sm text-gray-600">

                                        <div>
                                            <span class="text-gray-400">Qty</span>
                                            <div class="font-medium text-gray-800">
                                                {{ $item->quantity }}
                                            </div>
                                        </div>

                                        <div>
                                            <span class="text-gray-400">Price</span>
                                            <div class="font-medium text-gray-800">
                                                ₹{{ number_format($item->price, 2) }}
                                            </div>
                                        </div>

                                    </div>

                                </div>

                                {{-- Line Total (Right Aligned) --}}
                                <div class="text-right min-w-[120px]">
                                    <span class="text-xs text-gray-400 uppercase tracking-wide">
                                        Line Total
                                    </span>
                                    <div class="text-base font-semibold text-gray-900 mt-1">
                                        ₹{{ number_format($line, 2) }}
                                    </div>
                                </div>

                            </div>

                        @endforeach


                        {{-- Order Summary --}}
                        <div class="flex justify-end pt-6 border-t border-gray-200">

                            <div class="w-full max-w-[200px] bg-gray-50 rounded-xl p-3">

                                <div class="flex justify-between mt-2 text-base font-semibold text-gray-900">
                                    <span>Total</span>
                                    <span>₹{{ number_format($sum, 2) }}</span>
                                </div>

                            </div>

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
                <div class="bg-white border border-blue-100 rounded-2xl p-6 shadow-sm">

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-blue-600 uppercase tracking-wide">
                                Orders Summary
                            </p>
                            <p class="text-sm text-gray-500 mt-1">
                                Total Orders
                            </p>
                        </div>
                        <div class="text-2xl font-bold text-blue-700">
                            {{ $orders->count() }}
                        </div>
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