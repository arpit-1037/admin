<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);

        return $this->userRepository->create($data);
    }

    public function update(User $user, array $data): void
    {
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $this->userRepository->update($user, $data);
    }

    public function delete(User $user): void
    {
        if ($user->role === 'admin') {
            abort(403, 'Admin cannot be deleted.');
        }

        if ($user->id === Auth::id()) {
            abort(403, 'You cannot delete your own account.');
        }

        $this->userRepository->delete($user);
    }
}
