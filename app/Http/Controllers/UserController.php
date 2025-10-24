<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles', 'permissions')->get();
        $roles = Role::all();
        $permissions = Permission::all();

        return view('users.index', compact('users', 'roles', 'permissions'));
    }

    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('users.create', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        // تعيين الأدوار
        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        // تعيين الصلاحيات المباشرة
        if ($request->has('permissions')) {
            $user->permissions()->sync($request->permissions);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $userRoles = $user->roles->pluck('id')->toArray();
        $userPermissions = $user->permissions->pluck('id')->toArray();

        return view('users.edit', compact('user', 'roles', 'permissions', 'userRoles', 'userPermissions'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,'.$user->id,
            'password' => 'nullable|min:6',
        ]);

        $updateData = [
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
        ];

        // تحديث كلمة المرور إذا تم إدخالها
        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }

        $user->update($updateData);

        // تحديث الأدوار
        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        } else {
            $user->roles()->detach();
        }

        // تحديث الصلاحيات المباشرة
        if ($request->has('permissions')) {
            $user->permissions()->sync($request->permissions);
        } else {
            $user->permissions()->detach();
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }

    // دالة لإدارة الأدوار والصلاحيات بشكل منفصل
    public function managePermissions(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $userRoles = $user->roles->pluck('id')->toArray();
        $userPermissions = $user->permissions->pluck('id')->toArray();

        return view('users.manage-permissions', compact('user', 'roles', 'permissions', 'userRoles', 'userPermissions'));
    }

    public function updatePermissions(Request $request, User $user)
    {
        // تحديث الأدوار
        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        } else {
            $user->roles()->detach();
        }

        // تحديث الصلاحيات المباشرة
        if ($request->has('permissions')) {
            $user->permissions()->sync($request->permissions);
        } else {
            $user->permissions()->detach();
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User permissions updated successfully');
    }
}
