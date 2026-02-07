<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use App\Models\Role;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view-users', ['only' => ['index']]);
        $this->middleware('permission:create-users', ['only' => ['store']]);
        $this->middleware('permission:edit-users', ['only' => ['update']]);
        $this->middleware('permission:delete-users', ['only' => ['destroy']]);
    }

    public function index()
    {
        $this->authorize('view-users');
        
        $users = User::with('roles')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
                'created_at' => $user->created_at->format('d.m.Y H:i'),
            ];
        });

        $roles = Role::all()->pluck('name');

        return Inertia::render('Dashboard/Users/List', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create-users');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'nullable|string|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        if ($request->has('role') && $request->role) {
            $user->syncRoles([$request->role]);
        }

        return redirect()->back()->with('success', 'Uživatel byl úspěšně vytvořen.');
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('edit-users');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'nullable|string|exists:roles,name',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        if ($request->has('role')) {
            if ($request->role) {
                $user->syncRoles([$request->role]);
            } else {
                $user->syncRoles([]);
            }
        }

        return redirect()->back()->with('success', 'Uživatel byl úspěšně aktualizován.');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete-users');
        
        $user->delete();
        return redirect()->back()->with('success', 'Uživatel byl úspěšně smazán.');
    }
}
