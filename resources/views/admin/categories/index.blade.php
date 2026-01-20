<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Categories</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto">
        <a href="{{ route('admin.categories.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
            Add Category
        </a>

        <table class="mt-4 w-full bg-white shadow rounded table-auto table-fixed">
            <thead>
                <tr>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-center">Status</th> <!-- Match desired alignment -->
                    <th class="p-3 text-center">Actions</th> <!-- Match desired alignment -->
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr class="border-t">
                        <td class="p-3 text-center">{{ $category->name }}</td> <!-- Explicit align -->
                        <td class="p-3 text-center">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600">Edit</a>
                        </td>
                        <td class=" text-left">
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="bg-red-300 text-black px-4 py-2 rounded hover:bg-red-700">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</x-app-layout>