<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_has_roles');
    }

    public function hasPermission(string $permissionName): bool
    {
        return $this->permissions->contains('name', $permissionName);
    }

    public function givePermissionTo(...$permissions): self
    {
        $permissions = collect($permissions)->flatten()->map(function ($permission) {
            if (is_string($permission)) {
                return Permission::firstOrCreate(['name' => $permission], [
                    'module' => explode('.', $permission)[0] ?? 'general',
                    'description' => ucfirst(str_replace('.', ' ', $permission)),
                ]);
            }
            return $permission;
        });

        $this->permissions()->syncWithoutDetaching($permissions->pluck('id'));
        return $this;
    }
}
