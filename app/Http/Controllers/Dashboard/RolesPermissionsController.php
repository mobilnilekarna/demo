<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RolesPermissionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-roles', ['only' => ['index']]);
        $this->middleware('permission:create-roles', ['only' => ['storeRole']]);
        $this->middleware('permission:edit-roles', ['only' => ['updateRole']]);
        $this->middleware('permission:delete-roles', ['only' => ['destroyRole']]);
        $this->middleware('permission:assign-roles', ['only' => ['assignRole']]);
    }

    public function index()
    {
        $this->authorize('view-roles');
        
        $roles = Role::with('permissions')->get()->map(function ($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'permissions' => $role->permissions->pluck('name'),
                'users_count' => $role->users()->count(),
            ];
        });

        $permissions = Permission::all()->groupBy(function ($permission) {
            // Rozdělit oprávnění podle prefixu (např. view-, create-, edit-)
            $parts = explode('-', $permission->name);
            return $parts[0] ?? 'other';
        })->map(function ($group) {
            return $group->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                ];
            });
        });

        $users = User::with('roles')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
            ];
        });

        return Inertia::render('Dashboard/RolesPermissions', [
            'roles' => $roles,
            'permissions' => $permissions,
            'users' => $users,
        ]);
    }

    public function storeRole(Request $request)
    {
        $this->authorize('create-roles');
        
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
        ]);

        $role = Role::create(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->back()->with('success', 'Role byla úspěšně vytvořena.');
    }

    public function updateRole(Request $request, Role $role)
    {
        $this->authorize('edit-roles');
        
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);

        $role->update(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->back()->with('success', 'Role byla úspěšně aktualizována.');
    }

    public function destroyRole(Role $role)
    {
        $this->authorize('delete-roles');
        
        $role->delete();
        return redirect()->back()->with('success', 'Role byla úspěšně smazána.');
    }

    public function assignRole(Request $request, User $user)
    {
        $this->authorize('assign-roles');
        
        $request->validate([
            'roles' => 'required|array|min:1|max:1',
            'roles.*' => 'required|string|exists:roles,name',
        ]);

        $user->syncRoles($request->roles);

        return redirect()->back()->with('success', 'Role byla úspěšně přiřazena uživateli.');
    }

    public function storePermission(Request $request)
    {
        $this->authorize('create-roles');
        
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name]);

        return redirect()->back()->with('success', 'Oprávnění bylo úspěšně vytvořeno.');
    }

    public function updatePermission(Request $request, Permission $permission)
    {
        $this->authorize('edit-roles');
        
        $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update(['name' => $request->name]);

        return redirect()->back()->with('success', 'Oprávnění bylo úspěšně aktualizováno.');
    }

    public function destroyPermission(Permission $permission)
    {
        $this->authorize('delete-roles');
        
        $permission->delete();
        return redirect()->back()->with('success', 'Oprávnění bylo úspěšně smazáno.');
    }
}
