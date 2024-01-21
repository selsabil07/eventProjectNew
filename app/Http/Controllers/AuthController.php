<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\NewNotification;
use Illuminate\Support\Facades\Notification;

class AuthController extends Controller
{
    public function eventManagerRegister(Request $request)
    {
        $fields = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'birthday' => 'required|date',
            'phone' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'organization' => 'required|string',
            'password' => 'required|string|min:6',
        ]);
    
        $user = User::create([
            'first_name' => $fields['first_name'],
            'last_name' => $fields['last_name'],
            'birthday' => $fields['birthday'],
            'email' => $fields['email'],
            'organization' => $fields['organization'],
            'phone' => $fields['phone'],
            'password' => bcrypt($fields['password']),
        ]);
    
        $token = $user->createToken('userToken')->plainTextToken;

        $user->assignRole('eventManager');

        $response = [
            'user' => $user,
            'token' => $token
        ];
        
        $admin = User::role('admin')->get();
        $notification = new NewNotification($user->first_name, $user->last_name);

        Notification::send($admin, $notification);
    
        return response()->json($response,200);
   
    }
    public function notifCount()
    {
        $notifs = auth()->check() ? auth()->user()->unreadNotifications->count() : 0;
        return response()->json($notifs);
    }
    public function notifs()
    {
        $notifs = auth()->check() ? auth()->user()->unreadNotifications->all():0;
        return response()->json($notifs);
    }


    public function exposantRegister(Request $request)
    {
        $fields = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'birthday' => 'required|date',
            'phone' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'organization' => 'required|string',
            'password' => 'required|string|min:6',
        ]);
    
        $user = User::create([
            'first_name' => $fields['first_name'],
            'last_name' => $fields['last_name'],
            'birthday' => $fields['birthday'],
            'email' => $fields['email'],
            'organization' => $fields['organization'],
            'phone' => $fields['phone'],
            'password' => bcrypt($fields['password']),
        ]);
    
        $token = $user->createToken('userToken')->plainTextToken;

        $user->assignRole('exposant');

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return $response;
   
    }

    public function showUser(){
        $user = Auth::user(); 
        return response()->json($user); 
    }
    // public function showAdmin(){
    //     $admin = Auth::user(); 
    //     return response()->json($admin); 
    // }

    public function login(Request $request) {
    $fields = $request->validate([
        'email' => 'required|string',
        'password' => 'required|string'
    ]);

    // Check email
    $user = User::where('email', $fields['email'])->first();

    // Check password
    if(!$user || !Hash::check($fields['password'], $user->password)) {
        return response([
            'message' => 'Bad creds'
        ], 401);
    }
    
    $token = $user->createToken('myapptoken')->plainTextToken;

    $response = [
        'user' => $user,
        'token' => $token
    ];

    return response()->json($response, 201);
}
    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return response()->json(200);
    }


}