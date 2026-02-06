<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Category</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <form method="POST" action="{{ route('admin.categories.update', $category) }}">
            @csrf
            @method('PUT')

            <!-- Parent Category -->
            <select name="parent_id" class="border p-2 w-full mb-4">
                <option value="">No Parent</option>
                @foreach ($categories as $parent)
                    <option value="{{ $parent->id }}" {{ $category->parent_id == $parent->id ? 'selected' : '' }}>
                        {{ $parent->name }}
                    </option>
                @endforeach
            </select>

            <!-- Category Name -->
            <input type="text" name="name" value="{{ $category->name }}" class="border p-2 w-full mb-4" required>

            <!-- Status -->
            <div class="mb-4">
                <label class="block mb-1 font-medium">Status</label>

                <label class="mr-4">
                    <input type="radio" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}>
                    Active
                </label>

                <label>
                    <input type="radio" name="is_active" value="0" {{ !$category->is_active ? 'checked' : '' }}>
                    Inactive
                </label>
            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Update
            </button>
        </form>
    </div>
</x-app-layout>