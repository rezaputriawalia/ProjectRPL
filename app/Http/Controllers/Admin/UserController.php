<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with([
            'role',
            'ward'
        ])
            ->orderBy('id')
            ->get();

        return view('admin.users.index', [
            'users' => $users,
            'navItems' => $this->adminNavigation(),
        ]);
    }

    public function create(): View
    {
        $roles = Role::orderBy('display_name')->get();

        $wards = Ward::orderBy('name')->get();

        return view('admin.users.create', [
            'roles' => $roles,
            'wards' => $wards,
            'navItems' => $this->adminNavigation(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => 'required|string|max:255',

            'email' => 'required|email|unique:users,email',

            'phone' => 'nullable|string|max:20',

            'role_id' => 'required|exists:roles,id',

            'ward_id' => 'nullable|exists:wards,id',

            'password' => 'required|min:6',

            'status' => 'required|in:active,inactive',

        ]);

        $role = Role::find($validated['role_id']);

        if ($role->name !== 'nurse') {
            $validated['ward_id'] = null;
        }

        User::create([

            'name' => $validated['name'],

            'email' => $validated['email'],

            'phone' => $validated['phone'],

            'role_id' => $validated['role_id'],

            'ward_id' => $validated['ward_id'],

            'status' => $validated['status'],

            'password' => Hash::make($validated['password']),

        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        $roles = Role::orderBy('display_name')->get();

        $wards = Ward::orderBy('name')->get();

        return view('admin.users.edit', [
            'user' => $user,
            'roles' => $roles,
            'wards' => $wards,
            'navItems' => $this->adminNavigation(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'role_id' => 'required|exists:roles,id',
            'status' => 'required|in:active,inactive',
            'ward_id' => 'nullable|exists:wards,id',
        ]);

        $role = Role::find($validated['role_id']);

        if ($role->name !== 'nurse') {
            $validated['ward_id'] = null;
        }

        $user->update($validated);

        if ($request->filled('password')) {

            $validated['password'] = Hash::make($request->password);

            $user->update($validated);
        } else {

            $user->update($validated);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil diupdate.');
    }

    public function destroy(User $user)
    {
        try {

            $user->delete();

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User berhasil dihapus.');
        } catch (QueryException $e) {

            return redirect()
                ->route('admin.users.index')
                ->with(
                    'error',
                    'User tidak dapat dihapus karena masih memiliki data monitoring.'
                );
        }
    }

    private function adminNavigation(): array
    {
        return \App\Support\AdminMenu::items();
    }
}
