<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Product
        </h2>
    </x-slot>

    <div class="py-10 m-9">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        {{-- Product Name --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Product Name
                            </label>
                            <input name="name" type="text" required placeholder="Enter product name"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Category --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Category
                            </label>
                            <select name="category_id" required
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Description
                            </label>
                            <textarea name="description" rows="4" placeholder="Optional product description"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        {{-- Price & Stock --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Price
                                </label>
                                <input name="price" type="number" step="0.01" required placeholder="0.00"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Stock
                                </label>
                                <input name="stock" type="number" required placeholder="0"
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        {{-- Images --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Product Images
                            </label>
                            <input type="file" name="images[]" multiple required
                                class="block w-full text-sm text-gray-600">
                            <p class="text-xs text-gray-500 mt-1">
                                You can upload multiple images. The first image will be used as the primary image.
                            </p>
                        </div>

                        {{-- Actions --}}
                        <div class="flex justify-end gap-4 pt-4 border-t">
                            <a href="{{ route('admin.products.index') }}"
                                class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">
                                Cancel
                            </a>

                            <button type="submit" class="bg-green-700 text-gray px-6 py-2 rounded hover:bg-green-700">
                                Save Product
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>