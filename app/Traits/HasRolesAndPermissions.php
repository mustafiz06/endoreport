<?php

namespace App\Traits;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasRolesAndPermissions
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_has_roles');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_has_permissions');
    }

    public function hasRole(string|array $roles): bool
    {
        if (is_array($roles)) {
            return $this->roles->pluck('name')->intersect($roles)->isNotEmpty();
        }

        return $this->roles->contains('name', $roles) || $this->roles->contains('slug', $roles);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('Super Admin') || $this->hasRole('super-admin');
    }

    public function hasPermission(string $permissionName): bool
    {
        // Super Admin has all permissions automatically
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Direct user permission check
        if ($this->permissions->contains('name', $permissionName)) {
            return true;
        }

        // Role-based permission check
        foreach ($this->roles as $role) {
            if ($role->permissions->contains('name', $permissionName)) {
                return true;
            }
        }

        return false;
    }

    public function hasAnyPermission(array $permissions): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    public function assignRole(string|Role $role): self
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->orWhere('slug', $role)->firstOrFail();
        }

        $this->roles()->syncWithoutDetaching([$role->id]);
        return $this;
    }

    public function syncRoles(array $roleIdsOrNames): self
    {
        $roleIds = collect($roleIdsOrNames)->map(function ($item) {
            if (is_numeric($item)) {
                return (int) $item;
            }
            $role = Role::where('name', $item)->orWhere('slug', $item)->first();
            return $role ? $role->id : null;
        })->filter()->all();

        $this->roles()->sync($roleIds);
        return $this;
    }
}
