@extends('layouts.user')

@section('title', 'Edit Profile')

@push('scripts')

@endpush

@section('content')

    <div class="max-w-3xl mx-auto p-6">
        <div class="bg-white shadow rounded-lg p-6">

            @if (session('status'))
                <div class="mb-4 text-green-600 font-medium">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('profile-user.update') }}" class="space-y-4">
                @csrf
                @method('PATCH')

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="mt-1 block w-full border rounded-md px-3 py-2">
                    @error('name')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="mt-1 block w-full border rounded-md px-3 py-2">
                    @error('email')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Save -->
                <div class="pt-4">
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md font-semibold">
                        Save Changes
                    </button>
                </div>
            </form>
            <hr class="my-8">

            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                Change Password
            </h3>

            <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-4">
                @csrf
                @method('PATCH')

                <!-- Current Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Current Password</label>
                    <input type="password" name="current_password" class="mt-1 block w-full border rounded-md px-3 py-2">
                    @error('current_password')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">New Password</label>
                    <input type="password" name="password" class="mt-1 block w-full border rounded-md px-3 py-2">
                    @error('password')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                    <input type="password" name="password_confirmation"
                        class="mt-1 block w-full border rounded-md px-3 py-2">
                </div>

                <div class="pt-2">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-md font-semibold">
                        Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
    @if (session('status'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                showSweetAlert(@json(session('status')));
            });
        </script>
    @endif


@endsection