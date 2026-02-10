<x-app-layout>
    <x-slot name="sidebar">
        @include('partials.sidebar')
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Edit Category</h2>
    </x-slot>

    {{-- MAIN CONTENT --}}
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

                        <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-8">
                            @csrf
                            @method('PUT')

                            <!-- Parent Category -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Parent Category
                                </label>
                                <select name="parent_id"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-700
                                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                           transition duration-150">
                                    <option value="">No Parent</option>
                                    @foreach ($categories as $parent)
                                        <option value="{{ $parent->id }}"
                                            {{ $category->parent_id == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Category Name -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Category Name
                                </label>
                                <input type="text" name="name" value="{{ $category->name }}" required
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-700
                                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                                           transition duration-150">
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-3">
                                    Status
                                </label>

                                <div class="flex items-center gap-8">
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="is_active" value="1"
                                            {{ $category->is_active ? 'checked' : '' }}
                                            class="text-green-600 focus:ring-green-500">
                                        <span class="text-sm text-gray-700">Active</span>
                                    </label>

                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="is_active" value="0"
                                            {{ !$category->is_active ? 'checked' : '' }}
                                            class="text-red-600 focus:ring-red-500">
                                        <span class="text-sm text-gray-700">Inactive</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                                <a href="{{ route('admin.categories.index') }}"
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
                                    Update Category
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>