<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::withCount(['users', 'permissions'])->get();
        return view('roles.index', compact('roles'));
    }

    public function edit(Role $role): View
    {
        $role->load('permissions');
        $permissionsByModule = Permission::all()->groupBy('module');

        return view('roles.edit', compact('role', 'permissionsByModule'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $validated = $request->validate([
            'description' => ['nullable', 'string', 'max:255'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role->update(['description' => $validated['description'] ?? $role->description]);
        $role->permissions()->sync($validated['permissions'] ?? []);

        AuditLog::log(
            action: 'Updated Role Permissions',
            module: 'Roles',
            recordId: $role->id,
            recordIdentifier: $role->name,
            newValues: ['permissions_count' => count($validated['permissions'] ?? [])]
        );

        return redirect()->route('roles.index')
            ->with('success', "Permissions for role [{$role->name}] updated successfully.");
    }
}
