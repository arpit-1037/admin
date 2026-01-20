<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Category</h2>
    </x-slot>
    <div class="py-6 max-w-7xl mx-auto">
        <form method="POST" action="{{ route('admin.categories.update', $category) }}">
            @csrf
            @method('PUT')

            <input type="text" name="name"
                   value="{{ $category->name }}"
                   class="border p-2 w-full mb-4" required>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Update
            </button>
        </form>
    </div>
</x-app-layout>
