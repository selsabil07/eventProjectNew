<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventManagerController extends Controller
{
    public function index(){
        $eventManagers = User::role('eventManager')->where('approved',1)->get();
        return response()->json($eventManagers);
    }
    public function approvedEventManagersCount(){
        $eventManagers = User::role('eventManager')->where('approved',1)->count();
        return response()->json($eventManagers);
    }
    public function requests(){
        $eventManagers = User::role('eventManager')->where('approved',0)->get();
        return response()->json($eventManagers);
    }
    public function requestCount(){
        $eventManagers = User::role('eventManager')->where('approved',0)->count();
        return response()->json($eventManagers);
    }

    public function search(Request $request)
    {
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $email = $request->input('email');
        $sector = $request->input('sector');
        $organization = $request->input('organization');
        $phone = $request->input('phone');
    
        $user = User::query();
    
        if ($first_name) {
            $user->where('first_name', 'like', '%' . $first_name . '%');
        }
        if ($last_name) {
            $user->where('last_name', 'like', '%' . $last_name . '%');
        }
        if ($email) {
            $user->where('email', 'like', '%' . $email . '%');
        }
        if ($organization) {
            $user->where('organization', 'like', '%' . $organization . '%');
        }
        if ($phone) {
            $user->where('phone', 'like', '%' . $phone . '%');
        }
    
        $result = $user->get();
        return response()->json($result);
    }
    

    public function destroy($id){
        return  response()->json(User::destroy($id));
    }

    // public function update( Request $request , User $user ){
    //     $request->validate([
    //         'first_name' => 'string',
    //         'last_name' => 'string',
    //         'birthday' => 'date',
    //         'phone' => 'string',
    //         'email' => 'string|unique:users,email,' . $user->id,
    //         'organization' => 'string',
    //         'password' => 'confirmed|string|min:6'
    //         // Add other validation rules for other fields
    //     ]);

    //     $user->update($request->all());

    //     return response()->json($user);
    // }

    // public function update(Request $request)
    // {
    //     $user = Auth::user();
    //     // Validate the request data
    //     $request->validate([
    //         'first_name' => 'nullable|string',
    //         'last_name' => 'nullable|string',
    //         'birthday' => 'nullable|date',
    //         'phone' => 'nullable|string',
    //         'organization' => 'nullable|string',
    //         'password' => 'nullable|string|confirmed|min:6',
    //         // Add other validation rules for other fields
    //     ]);

    //     // Update the user with the provided data
    //     $user->update($request->all());

    //     // Optionally, you may hash the password if it's provided in the request
    //     if ($request->has('password')) {
    //         $user->update(['password' => bcrypt($request->input('password'))]);
    //     }

    //     // Return a response
    //     return response()->json($user, 200 , ['message' => 'updated']);
    // }
    public function update(Request $request)
{
    $user = Auth::user();

    // Validate the request data
    $request->validate([
        'first_name' => 'nullable|string',
        'last_name' => 'nullable|string',
        'phone' => 'nullable|string',
        'organization' => 'nullable|string',
        'password' => 'nullable|string|confirmed|min:6',
        // Add other validation rules for other fields
    ]);

    // Update the user with the provided data
    if ($request->filled('first_name')) {
        $user->first_name = $request->input('first_name');
    }

    if ($request->filled('last_name')) {
        $user->last_name = $request->input('last_name');
    }

    if ($request->filled('phone')) {
        $user->phone = $request->input('phone');
    }

    if ($request->filled('organization')) {
        $user->organization = $request->input('organization');
    }

    // Optionally, you may hash the password if it's provided in the request
    if ($request->filled('password')) {
        $user->password = bcrypt($request->input('password'));
    }

    // Save the user model
    $user->save();

    // Return a response
    return response()->json($user, 200, ['message' => 'User details updated successfully']);
}

    
    // public function edit(User $user)
    // {
    //     return response()->json($user, 200);
    // }


    public function approveEventManager($id) 
    {
        $EventManager = User::role('eventManager')->find($id);
        if($EventManager)
        {
            $EventManager->approved = 1;
            $EventManager->save();
            return response()->json("the event manager approved");
        }
    }

    public function rejectEventManager($id) 
    {
        $EventManager = User::role('eventManager')->find($id);
        if($EventManager)
    {
            $EventManager->approved = 0;
            $EventManager->save();
            return response()->json (User::destroy($id));
    }
    }

    public function activate($id) 
    {
        $EventManager = User::role('eventManager')->find($id);
        if($EventManager)
    {
            $EventManager->status = 1;
            $EventManager->save();
            return response()->json("activate");
    }
    }
    public function deactivate($id) 
    {
        $EventManager = User::role('eventManager')->find($id);
        if($EventManager)
    {
            $EventManager->status = 0;
            $EventManager->save();
            return response()->json ("deactivate");
    }
    }

    public function sector() {
        // Group events by sector and count occurrences of each sector
        $sectorCounts = Event::groupBy('sector')->select('sector', \DB::raw('count(*) as count'))->get();
    
        // Transform the result into an associative array
        $sectorCountsArray = $sectorCounts->pluck('count', 'sector')->toArray();
    
        return response()->json($sectorCountsArray);
    }
    

}
