<?php
namespace App\Repositories\Contracts;
use App\Models\User;
interface UserRepositoryInterface
{
    public function getAll();
    public function findById(int $id): ?User;
    public function create(array $data): User;
    public function update(User $user, array $data): bool;
    public function delete(User $user): bool;
}