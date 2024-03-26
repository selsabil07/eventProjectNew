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
use Illuminate\Support\Facades\Storage;
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
            'profile_photo' => 'nullable',
            'password' => 'confirmed|required|string|min:6',
        ]);
    
        $imagePath =null;
        // Handle image upload
        if ($request->hasFile('profile_photo')&& $request->file('profile_photo')->isValid()) {
            $imagePath = Storage::disk('public')->put('EventManagerProfile', $request->file('profile_photo'));
            $fields['profile_photo'] = $imagePath;
        }
        $user = User::create([
            'first_name' => $fields['first_name'],
            'last_name' => $fields['last_name'],
            'birthday' => $fields['birthday'],
            'email' => $fields['email'],
            'organization' => $fields['organization'],
            'phone' => $fields['phone'],
            'profile_photo' => $imagePath,
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


//     public function exhibitorRegister(string $id , Request $request)
// {
//     $event = Event::find($id);
//     $id = $event->id;

//     $fields = $request->validate([
//         'first_name' => 'required|string',
//         'last_name' => 'required|string',
//         'birthday' => 'required|date',
//         'phone' => 'required|string',
//         'email' => 'required|string|unique:users,email',
//         'organization' => 'required|string',
//         'profile_photo' => 'nullable',
//         'password' => 'confirmed|required|string|min:6',
//     ]);

//     $imagePath =null;
//         // Handle image upload
//         if ($request->hasFile('profile_photo')&& $request->file('profile_photo')->isValid()) {
//             $imagePath = Storage::disk('public')->put('ExhibitorProfile', $request->file('profile_photo'));
//             $fields['profile_photo'] = $imagePath;
//         }

//         $user = User::create([
//             'first_name' => $fields['first_name'],
//             'last_name' => $fields['last_name'],
//             'birthday' => $fields['birthday'],
//             'email' => $fields['email'],
//             'organization' => $fields['organization'],
//             'phone' => $fields['phone'],
//             'profile_photo' => $imagePath,
//             'password' => bcrypt($fields['password']),
//         ]);

//     $role = 'exhibitor';
//     $user->event()->attach($id, ['role' => $role]);

//     $token = $user->createToken('userToken')->plainTextToken;

//     $user->assignRole('exhibitor');

//     $response = [
//         'user' => $user,
//         'token' => $token
//     ];

//     $eventManagers = User::role('eventManager')->get();
//     $notification = new NewNotification($user->first_name, $user->last_name);

//     Notification::send($eventManagers, $notification);

//     return response()->json($response, 200);
// }

public function exhibitorRegister(Request $request)
{
    // $event = Event::find($id);
    // $id = $event->id;

    $fields = $request->validate([
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'birthday' => 'required|date',
        'phone' => 'required|string',
        'email' => 'required|string|unique:users,email',
        'organization' => 'required|string',
        'profile_photo' => 'nullable',
        'password' => 'confirmed|required|string|min:6',
    ]);

    $imagePath =null;
        // Handle image upload
        if ($request->hasFile('profile_photo')&& $request->file('profile_photo')->isValid()) {
            $imagePath = Storage::disk('public')->put('ExhibitorProfile', $request->file('profile_photo'));
            $fields['profile_photo'] = $imagePath;
        }

        $user = User::create([
            'first_name' => $fields['first_name'],
            'last_name' => $fields['last_name'],
            'birthday' => $fields['birthday'],
            'email' => $fields['email'],
            'organization' => $fields['organization'],
            'phone' => $fields['phone'],
            'profile_photo' => $imagePath,
            'password' => bcrypt($fields['password']),
        ]);

    $role = 'exhibitor';
    // $user->event()->attach($id, ['role' => $role]);

    $token = $user->createToken('userToken')->plainTextToken;

    $user->assignRole('exhibitor');

    $response = [
        'user' => $user,
        'token' => $token
    ];

    // $eventManagers = User::role('eventManager')->get();
    // $notification = new NewNotification($user->first_name, $user->last_name);

    // Notification::send($eventManagers, $notification);

    return response()->json($response, 200);
}


public function join(string $id , Request $request)
{
    // Retrieve the authenticated user
    $userId = auth()->user()->id;
    
    // Find the event
    $event = Event::find($id);
    if (!$event) {
        return response()->json(['error' => 'Event not found'], 404);
    }
    
    // Attach the user to the event as an exhibitor
    $event->exhibitors()->attach($userId);
    
    return response()->json(['message' => 'User related to event successfully']);
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


    public function show(){
        $user = Auth::user(); 
        return response()->json($user); 
    }


    public function showExhibitor()
    {
        // $user = User::role("exhibitor")->where('id' , $id)->with('event_user')->get(); // Assuming Exhibitor is related to User
        // $exhibitor = User::where('user_id', $user->id)->first();
        $user = auth()->user();
        // Retrieve the events for the current exhibitor
        // $exhibitorEvents = $exhibitor->events;

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


    public function showUser($id) {

        return response()->json(User::find($id));
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