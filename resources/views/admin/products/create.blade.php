<x-app-layout>
    <x-slot name="sidebar">
        @include('partials.sidebar')
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Create Product</h2>
    </x-slot>
    {{-- MAIN CONTENT --}}
    <div class="py-10 m-9">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                    @if ($errors->any())
                        <div class="mb-4 rounded-lg  p-4 text-red-700">
                            <strong class="block mb-2">Please fix the following errors:</strong>
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="bg-white shadow-lg rounded-xl p-6 sm:p-8">

                        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data"
                            class="space-y-8">
                            @csrf

                            <!-- Product Name -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Product Name
                                </label>
                                <input name="name" type="text" required placeholder="Enter product name" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-700
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                           transition duration-150">
                            </div>

                            <!-- Category -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Category
                                </label>
                                <select name="category_id" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-700
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                           transition duration-150">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea name="description" rows="4" placeholder="Optional product description" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-700
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                           transition duration-150 resize-none"></textarea>
                            </div>

                            <!-- Price & Stock -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Price
                                    </label>
                                    <input name="price" type="number" step="0.01" required placeholder="0.00" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-700
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               transition duration-150">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Stock
                                    </label>
                                    <input name="stock" type="number" required placeholder="0" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-700
                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                               transition duration-150">
                                </div>
                            </div>

                            <!-- Images -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Product Images
                                </label>
                                <input type="file" name="images[]" multiple required class="block w-full text-sm text-gray-600
                           file:mr-4 file:py-2.5 file:px-4
                           file:rounded-lg file:border-0
                           file:bg-blue-50 file:text-blue-700
                           hover:file:bg-blue-100
                           transition">
                                <p class="mt-2 text-xs text-gray-500">
                                    Upload multiple images. The first image will be used as the primary image.
                                </p>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    Status
                                </label>

                                <div class="flex items-center gap-8">
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="is_active" value="1" checked
                                            class="text-green-600 focus:ring-green-500">
                                        <span class="text-sm text-gray-700">Active</span>
                                    </label>

                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="is_active" value="0"
                                            class="text-red-600 focus:ring-red-500">
                                        <span class="text-sm text-gray-700">Inactive</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                                <a href="{{ route('admin.products.index') }}" class="inline-flex items-center justify-center
                          rounded-lg bg-gray-200 px-5 py-2.5
                          text-sm font-semibold text-gray-700
                          hover:bg-gray-300 transition">
                                    Cancel
                                </a>

                                <button type="submit" class="inline-flex items-center justify-center
                               rounded-lg bg-green-700 px-6 py-2.5
                               text-sm font-semibold text-white
                               hover:bg-green-800 transition">
                                    Save Product
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>


    {{-- Page content --}}
</x-app-layout>