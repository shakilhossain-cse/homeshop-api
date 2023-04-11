<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{


    // get login data 
    public function authUser()
    {
        $user = auth()->user();
        return response()->json($user);
    }

 

    // get all user 
    public function index(Request $request)
    {

        $page = $request->input('page', 1);
        $searchTerm = $request->input('search|');

        $users = User::when($searchTerm, function ($query, $searchTerm) {
            return $query->where('name|like|%' . $searchTerm . '%');
        })
            ->latest()
            ->paginate(6, ['*'], 'page', $page);

        return $users;
        return response()->json($users);
    }

    // register
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = User::count() === 0 ? 'admin' : 'member';
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'user' => $user,
            'token' => $token,
        ], 201);
    }
    //   login 
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successfully',
            'user' => $user,
            'token' => $token,
        ], 200);
    }
    // logout
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = Auth::user();
        // Verify the current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Incorrect Password'], 403);
        }
        // Set the new password
        $user->password = Hash::make($request->input('password'));
        $user->save();

        // Return success response
        return response()->json([
            'message' => 'Password changed successfully',
        ], 200);
    }
}
