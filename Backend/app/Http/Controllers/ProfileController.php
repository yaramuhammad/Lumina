<?php

namespace App\Http\Controllers;

use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();

        return response()->json(['message' => 'Profile updated successfully'], 200);
    }

    // Change Password
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 400);
        }

        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return response()->json(['message' => 'Password changed successfully'], 200);
    }

    public function getProfile(Request $request)
    {
        return Auth::user();
    }
}
