<?php

namespace App\Http\Controllers;

use App\Support\PermissionCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RoleManagementController extends Controller
{
    private const SYSTEM_ROLES = ['admin', 'boss', 'caretaker'];

 
    public function index(): View
    {
        $roles = Role::with('permissions', 'users')
            ->orderBy('name')
            ->get();

        return view('roles.index', [
            'roles' => $roles,
            'permissionSections' => PermissionCatalog::sections(),
        ]);
    }

    public function create(): View
    {
        return view('roles.create', [
            'role' => new Role(),
            'permissionSections' => PermissionCatalog::sections(),
            'selectedPermissions' => [],
            'isSystemRole' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['array'],
            'permissions.*' => ['string', 'in:' . implode(',', array_keys(PermissionCatalog::all()))],
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Rol creado correctamente.');
    }

    public function edit(Role $role): View
    {
        return view('roles.edit', [
            'role' => $role,
            'permissionSections' => PermissionCatalog::sections(),
            'selectedPermissions' => $role->permissions->pluck('name')->all(),
            'isSystemRole' => in_array($role->name, self::SYSTEM_ROLES, true),
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $isSystemRole = in_array($role->name, self::SYSTEM_ROLES, true);

        $validated = $request->validate([
            'name' => [$isSystemRole ? 'nullable' : 'required', 'string', 'max:255', 'unique:roles,name,' . $role->id],
            'permissions' => ['array'],
            'permissions.*' => ['string', 'in:' . implode(',', array_keys(PermissionCatalog::all()))],
        ]);

        if (! $isSystemRole && isset($validated['name'])) {
            $role->update(['name' => $validated['name']]);
        }

        $role->syncPermissions($validated['permissions'] ?? []);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Rol actualizado correctamente.');
    }
}
