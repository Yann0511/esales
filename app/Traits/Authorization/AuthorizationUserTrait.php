<?php

namespace App\Traits\Authorization;

use App\Models\Role;

trait AuthorizationUserTrait
{

        /**
     * Vérifier si l'utilisateur a un role spécifique
     *
     * @param $roles
     * @return bool
     */
    public function hasRole($roles)
    {
        $roles = (array) $roles;

        foreach ($roles as $role) {
            if (str_contains($this->role->slug, $role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user can perform some action.
     * @param $permission
     * @param bool $allRequired
     * @return bool
     */
    public function hasPermission($permission, $allRequired = true)
    {
        $permission = (array) $permission;

        return $allRequired
            ? $this->hasAllPermissions($permission)
            : $this->hasAtLeastOnePermission($permission);
    }

    /**
     * Check if user has all provided permissions
     * (translates to AND logic between permissions).
     *
     * @param array $permissions
     * @return bool
     */
    private function hasAllPermissions(array $permissions)
    {
        $availablePermissions = $this->role->permissions()->pluck('slug')->toArray();

        foreach ($permissions as $perm) {
            if (! in_array($perm, $availablePermissions, true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if user has at least one of provided permissions
     * (translates to OR logic between permissions).
     *
     * @param array $permissions
     * @return bool
     */
    private function hasAtLeastOnePermission(array $permissions)
    {
        $availablePermissions = $this->role->permissions()->pluck('slug')->toArray();

        foreach ($permissions as $perm) {
            if (in_array($perm, $availablePermissions, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Set user's role.
     * @param Role $role
     * @return mixed
     */
    public function setRole(Role $role)
    {
        return $this->forceFill([
            'roleId' => $role instanceof Role ? $role->id : $role
        ])->save();
    }
}
