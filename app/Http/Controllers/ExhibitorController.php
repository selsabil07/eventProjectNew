<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ExhibitorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function approvedExibitors(){

        $exhibitors = User::role('exhibitor')->where('approved',1)->get();
        return response()->json($exhibitors);
    }

    // public function approvedExibitorsCountt(){
    //     $exhibitors = User::role('exhibitor')->where('approved',1)->count();
    //     return response()->json($exhibitors);
    // }

    public function numberOfExhibitors()
    {
        // Retrieve the authenticated user
        $user = Auth::user();
    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Retrieve the count of exhibitors with approved status equal to 1
    $exhibitorCount = $user->events()->withCount(['exhibitors' => function ($query) {
        $query->where('approved', 1);
    }])->get()->sum('exhibitors_count');

    return response()->json( $exhibitorCount);
}

    


public function requestCount()
{
    // Retrieve the authenticated user
    $user = Auth::user();
if (!$user) {
    return response()->json(['error' => 'Unauthorized'], 401);
}

// Retrieve the count of exhibitors with approved status equal to 0
$exhibitorCount = $user->events()->withCount(['exhibitors' => function ($query) {
    $query->where('approved', 0);
}])->get()->count();

return response()->json( $exhibitorCount);
}




// public function requests()
// {
//     $user = Auth::user();

//     // Retrieve events for the authenticated user
//     $events = $user->events;

//     // Initialize a counter for unapproved exhibitors
//     $unapprovedExhibitorCount = 0;

//     // Loop through each event and count unapproved exhibitors
//     foreach ($events as $event) {
//         // Retrieve exhibitors for the current event
//         $eventExhibitors = $event->exhibitors;

//         // Count unapproved exhibitors for the current event
//         $unapprovedExhibitorCount = $eventExhibitors
//             ->where('approved', 0)
//             ->where('role', 'exhibitor')
//             ->get();
//     }

//     return response()->json($unapprovedExhibitorCount);
// }

// public function requests()
// {
//     $user = Auth::user();

//     // Retrieve events for the authenticated user
//     $events = $user->events;

//     $exhibitors = User::role('exhibitor')->where('approved',1)->get();
//     // Initialize an array for unapproved exhibitors
//     $unapprovedExhibitors = $events->$exhibitors;

//     return response()->json($unapprovedExhibitors);
// }
// public function exhibitorRquest(String $id)
// {

//     // Retrieve events for the authenticated user
//     $event = Event::find($id);
//     // dd($events);
//     // Retrieve exhibitors who requested approval for the events
//     $exhibitors = $event->exhibitors->where('approved', false)->get();

//     return response()->json( $exhibitors );
// }

public function exhibitorRequests(string $id)
{

    $event = Event::find($id);

    // Retrieve exhibitors for an event
    try {
    
        $exhibitors = $event->exhibitors->where('approved', 0);

        return response()->json($exhibitors->toArray());


    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function Exhibitors(string $id)
{
    // Retrieve the event by its ID
    $event = Event::find($id);

    // Retrieve exhibitors for an event
    $exhibitors = $event->exhibitors->where('approved', 1);

    return response()->json($exhibitors);

    
}

public function allExhibitors()
{
    // Retrieve the event by its ID
    {
        // Check if the user is authenticated
        if(auth()->check()) {
            // Retrieve the current authenticated user
            $currentUser = auth()->user();
    
            // Retrieve events where the current user is the owner
            $events = Event::where('user_id', $currentUser->id)->get();
    
            // Retrieve exhibitor requests for the current user's events
            $exhibitorRequests = Event::with(['exhibitors' => function ($query) {
                $query->where('approved', 1)
                    ->select('user_id', 'first_name','phone', 'last_name', 'profile_photo' ,'organization', 'email', 'approved', 'event_id');
            }])->where('user_id', $currentUser->id)
                ->get();
    
            return response()->json(
                $exhibitorRequests, 'success'
            );
        } else {
            // Handle case where user is not authenticated
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    }

}
// public function myevent()
// {
//     // Retrieve the event by its ID
//     $user = User::auth();

//     $exhibitors = User::with('events:id,eventTitle')->get();

//          return response()->json($exhibitors);

// }
// public function myevent()
// {
//     // Assuming User::auth() is a custom method to get the authenticated user
//     $user = Auth::user();

//     // Assuming you have a relationship named 'events' in your User model
//     // $exhibitors = $user->load('events:id,eventTitle');
//     $event = $user->with('events:id,eventTitle')->get();

//     return response()->json($event);
// }
public function myevent()
{
    // Retrieve the authenticated user
    $user = Auth::user()->load('events');

        return response()->json($user);
    
}

public function allRequests(Request $request )
{
    // Retrieve the authenticated user
    $user = Auth::user()->id;

    $exhibitors = Event::where('user_id' , $user)->with('exhibitors:id,first_name,last_name,email,phone,organization,user_name,profile_photo,approved:0')->get();

    return response()->json($exhibitors);

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

    public function update(Request $request, User $user)
    {
        $request->validate([
            'password' => 'nullable|string|min:6|confirmed',
            // Add other validation rules for other fields
        ]);
    
        // Check if the request contains a password and update it
        if ($request->has('password')) {
            $user->update([
                'password' => bcrypt($request->password),
                // Add other fields you want to update
            ]);
        } else {
            // If no password is provided, update other fields
            $user->update($request->except('password'));
        }
    
        return response()->json($user);
    }


    public function approveExhibitor($id) 
    {
        $exhibitor = User::role('exhibitor')->find($id);
        if($exhibitor)
        {
            $exhibitor->approved = 1;
            $exhibitor->save();
            return response()->json("the event manager approved");
        }
    }

    public function rejectexhibitor($id) 
    {
        $EventManager = User::role('eventManager')->find($id);
        if($EventManager)
    {
            $EventManager->approved = 0;
            $EventManager->save();
            return response()->json (User::destroy($id));
    }
    }
     public function show($id){
        $exhibitor = User::find($id);
        return response()->json($exhibitor, 200);
     }
}
