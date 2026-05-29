<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Role;

class RoleRepository
{
    public function __construct(
        protected Role $model
    ) {
    }

    /**
     * Get all users with optional filters
     */
    public function getAll(array $filters = []): Collection
    {
        $query = $this->model->query();

        return $query->get();
    }

    /**
     * Get paginated users
     */
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->paginate($perPage);
    }

    /**
     * Find user by ID
     */
    public function findById(int $id): ?Role
    {
        return $this->model->find($id);
    }

    /**
     * Find user by ID or fail
     */
    public function findByIdOrFail(int $id): Role
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?Role
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Create a new user
     */
    public function create(array $data): Role
    {
        return $this->model->create($data);
    }

    /**
     * Update user
     */
    public function update(Role $role, array $data): bool
    {
        return $role->update($data);
    }

    /**
     * Delete user
     */
    public function delete(Role $role): bool
    {
        return $role->delete();
    }

    /**
     * Get users with specific role
     */
    public function getUsersByRole(string $roleName): Collection
    {
        return $this->model->role($roleName)->get();
    }

    /**
     * Get users by cluster
     */
    public function getUsersByCluster(int $clusterId): Collection
    {
        return $this->model->whereJsonContains('clusters', $clusterId)->get();
    }

    /**
     * Check if email exists
     */
    public function emailExists(string $email, ?int $excludeUserId = null): bool
    {
        $query = $this->model->where('email', $email);

        if ($excludeUserId) {
            $query->where('id', '!=', $excludeUserId);
        }

        return $query->exists();
    }
}
