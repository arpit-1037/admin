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
                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

                    @if ($errors->any())
                        <div class="mb-4 rounded-lg p-4 text-red-700">
                            <strong class="block mb-2">Please fix the following errors:</strong>
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="bg-white shadow-lg rounded-xl p-6 sm:p-8">

                        {{-- MAIN UPDATE FORM --}}
                        <form method="POST"
                              action="{{ route('admin.products.update', $product) }}"
                              enctype="multipart/form-data"
                              class="space-y-8">
                            @csrf
                            @method('PUT')

                            <!-- Product Name -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Product Name
                                </label>
                                <input
                                    name="name"
                                    type="text"
                                    required
                                    value="{{ $product->name }}"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-700
                                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>

                            <!-- Category -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Category
                                </label>
                                <select
                                    name="category_id"
                                    required
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-700
                                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $product->category_id == $category->id ? 'selected' : '' }}>
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
                                <textarea
                                    name="description"
                                    rows="4"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-700
                                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                           resize-none transition">{{ $product->description }}</textarea>
                            </div>

                            <!-- Price & Stock -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Price
                                    </label>
                                    <input
                                        name="price"
                                        type="number"
                                        step="0.01"
                                        value="{{ $product->price }}"
                                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-700
                                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Stock
                                    </label>
                                    <input
                                        name="stock"
                                        type="number"
                                        value="{{ $product->stock }}"
                                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-700
                                               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                </div>
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    Status
                                </label>

                                <div class="flex items-center gap-8">
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="radio"
                                               name="is_active"
                                               value="1"
                                               {{ $product->is_active ? 'checked' : '' }}
                                               class="text-green-600 focus:ring-green-500">
                                        <span class="text-sm text-gray-700">Active</span>
                                    </label>

                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="radio"
                                               name="is_active"
                                               value="0"
                                               {{ !$product->is_active ? 'checked' : '' }}
                                               class="text-red-600 focus:ring-red-500">
                                        <span class="text-sm text-gray-700">Inactive</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Existing Images -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    Existing Images
                                </label>

                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                    @foreach($product->images as $image)
                                        <div class="relative border border-gray-200 rounded-lg p-2 group">

                                            <img
                                                src="{{ asset('storage/'.$image->path) }}"
                                                class="w-full h-28 object-cover rounded-md">

                                            <div class="mt-2 flex items-center gap-2 text-xs text-gray-600">
                                                <input type="radio"
                                                       name="primary_image"
                                                       value="{{ $image->id }}"
                                                       {{ $image->is_primary ? 'checked' : '' }}
                                                       class="text-blue-600 focus:ring-blue-500">
                                                <span>Primary</span>
                                            </div>

                                            <!-- DELETE BUTTON -->
                                            <button type="button"
                                                    onclick="deleteImage({{ $image->id }})"
                                                    class="absolute top-2 right-2 bg-red-600 text-white text-xs px-2 py-1 rounded
                                                           opacity-0 group-hover:opacity-100 transition">
                                                ✕
                                            </button>

                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Add New Images -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Add New Images
                                </label>
                                <input
                                    type="file"
                                    name="images[]"
                                    multiple
                                    class="block w-full text-sm text-gray-600
                                           file:mr-4 file:py-2.5 file:px-4
                                           file:rounded-lg file:border-0
                                           file:bg-blue-50 file:text-blue-700
                                           hover:file:bg-blue-100 transition">

                                           <div class="mt-3 flex items-center gap-2 text-sm text-gray-600">
    <input type="radio"
           name="primary_image"
           value="1"
           class="text-blue-600 focus:ring-blue-500">
    <span>Set first uploaded image as primary</span>
</div>
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                                <a href="{{ route('admin.products.index') }}"
                                   class="inline-flex items-center justify-center
                                          rounded-lg bg-gray-200 px-5 py-2.5
                                          text-sm font-semibold text-gray-700
                                          hover:bg-gray-300 transition">
                                    Cancel
                                </a>

                                <button type="submit"
                                        class="inline-flex items-center justify-center
                                               rounded-lg bg-blue-700 px-6 py-2.5
                                               text-sm font-semibold text-white
                                               hover:bg-blue-800 transition">
                                    Update Product
                                </button>
                            </div>

                        </form>

                        <!-- Hidden Delete Form -->
                        <form id="delete-image-form" method="POST" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function deleteImage(imageId) {

        Swal.fire({
            title: 'Delete Image?',
            text: 'This image will be permanently removed.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {

            if (!result.isConfirmed) return;

            const form = document.getElementById('delete-image-form');

            form.action = "{{ route('admin.products.images.destroy', '__id__') }}"
                .replace('__id__', imageId);

            form.submit();
        });
    }
</script>
</x-app-layout>