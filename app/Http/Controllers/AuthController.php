<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ResetPassword;
use Illuminate\Http\JsonResponse;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\DB;
use App\Models\Password_reset_token;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\NewNotification;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Auth\Events\PasswordReset;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ResetPasswordNotification;


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
            'password' => 'confirmed|required|string|min:6',
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
    
        $token = $user->createToken('token')->plainTextToken;

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


    // public function exhibitorRegiste( $id , Request $request)
    // {
    //     $event_id = Event::find($id);
    //     $fields = $request->validate([
    //         'first_name' => 'required|string',
    //         'last_name' => 'required|string',
    //         'birthday' => 'required|date',
    //         'phone' => 'required|string',
    //         'email' => 'required|string|unique:users,email',
    //         'organization' => 'required|string',
    //         'password' => 'required|string|min:6',
    //     ]);
    
    //     $user = User::create([
    //         'event_id' => $event_id->id,
    //         'first_name' => $fields['first_name'],
    //         'last_name' => $fields['last_name'],
    //         'birthday' => $fields['birthday'],
    //         'email' => $fields['email'],
    //         'organization' => $fields['organization'],
    //         'phone' => $fields['phone'],
    //         'password' => bcrypt($fields['password']),
    //     ]);
    
    //     $token = $user->createToken('userToken')->plainTextToken;

    //     $user->assignRole('exhibitor');

    //     $response = [
    //         'user' => $user,
    //         'token' => $token
    //     ];

    //     $eventManager = User::role('eventManager')->get();
    //     $notification = new NewNotification($user->first_name, $user->last_name);

    //     Notification::send($eventManager, $notification);
    
    //     return response()->json($response,200);
   
    // }
    public function exhibitorRegister(string $id , Request $request)
{
    $event = Event::find($id);
    $id = $event->id;
    $user = User::create([
        
        'first_name' => $request->input('first_name'),
        'last_name' => $request->input('last_name'),
        'birthday' => $request->input('birthday'),
        'email' => $request->input('email'),
        'organization' => $request->input('organization'),
        'phone' => $request->input('phone'),
        'password' => bcrypt($request->input('password')),
    ]);
    $role = 'exhibitor';
    $user->event()->attach($id, ['role' => $role]);

    $token = $user->createToken('userToken')->plainTextToken;

    $user->assignRole('exhibitor');

    $response = [
        'user' => $user,
        'token' => $token
    ];

    $eventManagers = User::role('eventManager')->get();
    $notification = new NewNotification($user->first_name, $user->last_name);

    Notification::send($eventManagers, $notification);

    return response()->json($response, 200);
}


    public function eventManagerNotifCount()
    {
        $notifs = auth()->check() ? auth()->user()->unreadNotifications->count() : 0;
        return response()->json($notifs);
    }
    public function eventManagerNotifs()
    {
        $notifs = auth()->check() ? auth()->user()->unreadNotifications->all():0;
        return response()->json($notifs);
    }


    // public function createUserAndRegisterForEvent(Request $request, $eventId)
    // {
    //     // Validate the request data for creating a new user
    //     $request->validate([
    //         'first_name' => 'required|string',
    //         'last_name' => 'required|string',
    //         'birthday' => 'required|date',
    //         'phone' => 'required|string',
    //         'email' => 'required|string|unique:users,email',
    //         'organization' => 'required|string',
    //         'password' => 'required|string|min:6',
    //     ]);
    
    //     // Create a new user
    //     $user = User::create([
    //         'first_name' => $request->input('first_name'),
    //         'last_name' => $request->input('last_name'),
    //         'birthday' => $request->input('birthday'),
    //         'phone' => $request->input('phone'),
    //         'email' => $request->input('email'),
    //         'organization' => $request->input('organization'),
    //         'password' => bcrypt($request->input('password')),
    //         'event_id' => $eventId, // Set the event_id on the user
    //     ]);
    //  // Attach the user to the event

    //     $token = $user->createToken('userToken')->plainTextToken;

    //     $user->assignRole('exhibitor');

    //     $response = [
    //         'user' => $user,
    //         'token' => $token
    //     ];

    //     return response()->json([$response,$eventId, 'message' => 'User created and registered for the event successfully.'], 201);
    // }

    public function showEventManager(){
        $user = Auth::user(); 
        return response()->json($user); 
    }

    public function showExhibitor(){
        $user = Auth::user(); 
        return response()->json($user); 
    }

    public function showAdmin(){
        $admin = Auth::user(); 
        return response()->json($admin); 
    }

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


    public function logoutEventManager(Request $request) {
        auth()->user()->tokens()->delete();

        return response()->json(200);
    }

    public function logoutExhibitor(Request $request) {
        auth()->user()->tokens()->delete();

        return response()->json(200);
    }


    public function forgot(Hasher $hasher, ForgotPasswordRequest $request)
    {
        $user = User::where('email', $request->input('email'))->first();
    
        if (!$user) {
            // Handle case where user does not exist
            return response()->json('No user found with that email address.' , 200 );

            // return redirect()->back()->with('error', 'No user found with that email address.');
        }
    
        $token = Str::random(6); // Generate a random token
    
        // Use Eloquent to create a record in the password_reset_tokens table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => Hash::make($token)]
        );
    
        // Send the notification with the token link
        $user->notify(new ResetPasswordNotification($token));
    
        // Redirect or return a response
        return response()->json('success', 200);
        // return redirect()->back()->with('', 'Password reset email sent successfully.');
    }
   

    public function reset(ResetPasswordRequest $request): JsonResponse
    {
    try {
        $attributes = $request->validated();

        $user = User::where('email', $attributes['email'])->first();

        if (!$user) {
            return response()->json(['message' => 'No Record Found', 'error' => 'Incorrect Email Address Provided'], 404);
        }

        $resetRequest = PasswordResetToken::where('email', $user->email)->first();
       
        if (!$resetRequest || !Hash::check($request->token, $resetRequest->token)){

            return response()->json(['message' => 'An Error Occurred. Please Try again.' , 'error' => 'Token mismatch.'], 400);
        }

        // Update User's Password
        $user->update([
            'password' => Hash::make($attributes['password']),
        ]);

        // Delete previous all tokens
        $user->tokens()->delete();

        // Delete the password reset request
        $resetRequest->delete();

        // Create a new API token for the user
        $token = $user->createToken('userToken')->plainTextToken;

        $logicResponse = [
            'user' => $user,
            'token' => $token,
        ];

        return response()->json(['data' => $logicResponse, 'message' => 'Password reset successfully'], 201);

    } catch (\Exception $e) {
        // Handle exceptions if needed
        return response()->json(['message' => 'An error occurred during password reset.', 'error' => $e->getMessage()], 500);
    }
  }
}