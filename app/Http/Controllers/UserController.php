<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('roles')->orderBy('name')->paginate(15);
        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::orderBy('name')->get();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::min(8)],
            'phone' => ['nullable', 'string', 'max:50'],
            'designation' => ['nullable', 'string', 'max:150'],
            'status' => ['required', 'in:Active,Inactive,Suspended'],
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'designation' => $validated['designation'],
            'status' => $validated['status'],
        ]);

        $user->roles()->sync($validated['roles']);

        AuditLog::log(
            action: 'Created User',
            module: 'Users',
            recordId: $user->id,
            recordIdentifier: $user->email,
            newValues: ['name' => $user->name, 'email' => $user->email, 'roles' => $validated['roles']]
        );

        return redirect()->route('users.index')
            ->with('success', "User account for [{$user->name}] created successfully.");
    }

    public function edit(User $user): View
    {
        $roles = Role::orderBy('name')->get();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', Password::min(8)],
            'phone' => ['nullable', 'string', 'max:50'],
            'designation' => ['nullable', 'string', 'max:150'],
            'status' => ['required', 'in:Active,Inactive,Suspended'],
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $oldValues = $user->toArray();

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'designation' => $validated['designation'],
            'status' => $validated['status'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);
        $user->roles()->sync($validated['roles']);

        AuditLog::log(
            action: 'Updated User Account',
            module: 'Users',
            recordId: $user->id,
            recordIdentifier: $user->email,
            oldValues: $oldValues,
            newValues: $user->toArray()
        );

        return redirect()->route('users.index')
            ->with('success', "User [{$user->name}] updated successfully.");
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Cannot delete your own active administrator account.');
        }

        if ($user->isSuperAdmin() && User::whereHas('roles', fn ($q) => $q->where('slug', 'super-admin'))->count() <= 1) {
            return back()->with('error', 'Cannot delete the sole Super Admin account.');
        }

        $name = $user->name;
        $id = $user->id;
        $email = $user->email;
        $user->delete();

        AuditLog::log(
            action: 'Deleted User',
            module: 'Users',
            recordId: $id,
            recordIdentifier: $email
        );

        return redirect()->route('users.index')
            ->with('success', "User account [{$name}] has been removed.");
    }
}
