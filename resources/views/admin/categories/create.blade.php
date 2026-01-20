<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Create Category</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf

            <input type="text" name="name"
                   class="border p-2 w-full mb-4"
                   placeholder="Category Name" required>

            <button class="bg-green-600 text-black px-4 py-2 rounded">
                Save
            </button>
        </form>
    </div>
</x-app-layout>
