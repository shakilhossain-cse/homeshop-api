<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //
    public function update(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);

        $validate  = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'birthday' => 'nullable|date',
            'avatar' => 'nullable|string',
        ]);

        $dateOfBirth = date('Y-m-d H:i:s', strtotime($validate['birthday']));
        $user->update([
            'first_name' => $validate['first_name'],
            'last_name' =>  $validate['last_name'],
            'phone' =>  $validate['phone'],
            'gender' =>  $validate['gender'],
            'date_of_birth' =>  $dateOfBirth,
            'avatar' =>  $validate['avatar'],
        ]);

        return response()->json(['data' => $user, 'message' => 'Your data updated']);
    }
}
