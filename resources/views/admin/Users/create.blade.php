<x-app-layout>
    <x-slot name="sidebar">
        @include('partials.sidebar')
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Create User</h2>
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

                        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-8">
                            @csrf

                            <!-- Name -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Full Name
                                </label>
                                <input name="name" type="text" value="{{ old('name') }}" required
                                    placeholder="Enter full name" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-700
                                              focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500
                                              transition duration-150">
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email Address
                                </label>
                                <input name="email" type="email" value="{{ old('email') }}" required
                                    placeholder="Enter email address" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-700
                                              focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500
                                              transition duration-150">
                            </div>

                            <!-- Password -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Password
                                </label>
                                <input name="password" type="password" required placeholder="Enter password" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-700
                                              focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500
                                              transition duration-150">
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Confirm Password
                                </label>
                                <input name="password_confirmation" type="password" required
                                    placeholder="Confirm password" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-700
                                              focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500
                                              transition duration-150">
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

                            <!-- Role -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Role
                                </label>
                                <select name="role" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-gray-700
                                               focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500
                                               transition duration-150">
                                    <option value="">Select Role</option>
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center
                                          rounded-lg bg-gray-200 px-5 py-2.5
                                          text-sm font-semibold text-gray-700
                                          hover:bg-gray-300 transition">
                                    Cancel
                                </a>

                                <button type="submit" class="inline-flex items-center justify-center
                                               rounded-lg bg-green-700 px-6 py-2.5
                                               text-sm font-semibold text-white
                                               hover:bg-green-800 transition">
                                    Save User
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>