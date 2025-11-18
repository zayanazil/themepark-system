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

    // Delete a user (optional but useful)
    public function destroy(string $id)
    {
        User::destroy($id);
        return back()->with('success', 'User deleted successfully.');
    }
}
