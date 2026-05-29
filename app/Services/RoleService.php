<?php

namespace App\Services;

use App\Models\Permission;
use App\Repositories\RoleRepository;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Throwable;

class RoleService
{
    public function __construct(
        protected RoleRepository $roleRepository
    ) {
    }

    /**
     * Create a new user with role and clusters
     *
     * @throws Throwable
     */
    public function createRole(array $roleData, array $permissions = []): Role
    {
        return DB::transaction(function () use ($roleData, $permissions) {
            $role = $this->roleRepository->create($roleData);
            $validPermissions = Permission::whereIn('name', $permissions)->pluck('id');
            $role->syncPermissions($validPermissions);

            return $role->fresh();
        });
    }

    /**
     * Update user with role and clusters
     *
     * @throws Throwable
     */
    public function updateRole(Role $role, array $roleData, array $permissions = []): Role
    {
        return DB::transaction(function () use ($role, $roleData, $permissions) {
            $this->roleRepository->update($role, $roleData);
            $validPermissions = Permission::whereIn('name', $permissions)->pluck('id');
            $role->syncPermissions($validPermissions);

            return $role->fresh();
        });
    }

    /**
     * Delete user
     *
     * @throws Throwable
     */
    public function deleteRole(Role $role): bool
    {
        return $this->roleRepository->delete($role);
    }
}
