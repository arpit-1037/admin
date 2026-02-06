<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Product</h2>
    </x-slot>
    <x-slot name="sidebar">
        @include('partials.sidebar')
    </x-slot>

    <div class="py-10 m-9">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <form method="POST"
                          action="{{ route('admin.products.update', $product) }}"
                          enctype="multipart/form-data"
                          class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Product Name --}}
                        <div>
                            <label class="block text-sm font-medium mb-1">Product Name</label>
                            <input name="name" type="text" required
                                   value="{{ $product->name }}"
                                   class="w-full border-gray-300 rounded-md">
                        </div>

                        {{-- Category --}}
                        <div>
                            <label class="block text-sm font-medium mb-1">Category</label>
                            <select name="category_id" required class="w-full border-gray-300 rounded-md">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Description --}}
                        <div>
                            <label class="block text-sm font-medium mb-1">Description</label>
                            <textarea name="description" rows="4"
                                class="w-full border-gray-300 rounded-md">{{ $product->description }}</textarea>
                        </div>

                        {{-- Price & Stock --}}
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-1">Price</label>
                                <input name="price" type="number" step="0.01"
                                       value="{{ $product->price }}"
                                       class="w-full border-gray-300 rounded-md">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Stock</label>
                                <input name="stock" type="number"
                                       value="{{ $product->stock }}"
                                       class="w-full border-gray-300 rounded-md">
                            </div>
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-sm font-medium mb-1">Status</label>
                            <div class="flex gap-6">
                                <label>
                                    <input type="radio" name="is_active" value="1"
                                        {{ $product->is_active ? 'checked' : '' }}>
                                    Active
                                </label>
                                <label>
                                    <input type="radio" name="is_active" value="0"
                                        {{ !$product->is_active ? 'checked' : '' }}>
                                    Inactive
                                </label>
                            </div>
                        </div>

                        {{-- Existing Images --}}
                        <div>
                            <label class="block text-sm font-medium mb-2">Existing Images</label>

                            <div class="grid grid-cols-4 gap-4">
                                @foreach($product->images as $image)
                                    <div class="relative border rounded p-2">
                                        <img src="{{ asset('storage/'.$image->path) }}"
                                             class="w-full h-24 object-cover rounded">

                                        {{-- Primary --}}
                                        <div class="mt-2 text-xs">
                                            <input type="radio"
                                                   name="primary_image"
                                                   value="{{ $image->id }}"
                                                   {{ $image->is_primary ? 'checked' : '' }}>
                                            Primary
                                        </div>

                                        {{-- Delete --}}
                                        {{-- <form method="POST"
                                              action="{{ route('admin.products.images.destroy', $image) }}"
                                              class="absolute top-1 right-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="confirmDelete(this)"
                                                class="text-red-600 text-xs">
                                                ✕
                                            </button>
                                        </form> --}}
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Add New Images --}}
                        <div>
                            <label class="block text-sm font-medium mb-1">Add New Images</label>
                            <input type="file" name="images[]" multiple
                                   class="block w-full text-sm">
                        </div>

                        {{-- Actions --}}
                        <div class="flex justify-end gap-4 pt-4 border-t">
                            <a href="{{ route('admin.products.index') }}"
                               class="bg-gray-200 px-4 py-2 rounded">
                                Cancel
                            </a>
                            <button class="bg-blue-700 text-white px-6 py-2 rounded">
                                Update Product
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>