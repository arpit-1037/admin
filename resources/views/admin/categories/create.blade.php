<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Create Category</h2>
    </x-slot>
    <x-slot name="sidebar">
        @include('partials.sidebar')
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf

            <!-- Parent Category -->
            <select name="parent_id" class="border p-2 w-full mb-4">
                <option value="">No Parent</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <!-- Category Name -->
            <input type="text" name="name" class="border p-2 w-full mb-4" placeholder="Category Name" required>

            <!-- Status -->
            <div class="mb-4">
                <label class="block mb-1 font-medium">Status</label>

                <label class="mr-4">
                    <input type="radio" name="is_active" value="1" checked>
                    Active
                </label>

                <label>
                    <input type="radio" name="is_active" value="0">
                    Inactive
                </label>
            </div>

            <button class="bg-blue-300 text-black hover:bg-blue-500 px-4 py-2 rounded border">
                Save
            </button>
        </form>
    </div>
</x-app-layout>