<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserAdminController extends Controller
{
    // Show the list of users
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // Process the role change
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'role' => 'required|in:visitor,hotel_manager,ferry_staff,theme_park_staff,admin',
        ]);

        $user->role = $request->role;
        $user->save();

        return back()->with('success', "User role updated to {$request->role}");
    }
    
    // Add aan user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:4',
            'role' => 'required|in:visitor,hotel_manager,ferry_staff,theme_park_staff,admin',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return back()->with('success', 'User created successfully!');
    }

    // Delete a user (optional but useful)
    public function destroy(string $id)
    {
        User::destroy($id);
        return back()->with('success', 'User deleted successfully.');
    }
}
