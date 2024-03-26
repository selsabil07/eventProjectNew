<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class adminController extends Controller
{
    public function index(){
        $admin = User::role('admin')->get();
        return response()->json($admin);
    }

    public function updateinfo(Request $request) {
        // Validate the request data
        $validatedData = $request->validate([
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'birthday' => 'nullable|date',
            'phone' => 'nullable|string',
            'email' => 'nullable|string|unique:users,email',
            'organization' => 'nullable|string',
            'profile_photo' => 'nullable',
            'password' => 'confirmed|required|string|min:6',
        ]);
    
        // Get the authenticated user
        $user = auth()->user();
    
        // Update only the provided fields
        $user->fill($validatedData);
    
        // Save the changes to the user
        $user->save();
    
        // Return the updated user information
        return response()->json($user);
    }
    
    

    public function adminInfo($id){
        $admin = User::role('admin')->where($id)->get();
        return response()->json($admin);
    }
}
