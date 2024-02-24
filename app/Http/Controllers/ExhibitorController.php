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

    public function numberOfExhibitors(){
        $user = Auth::user();

        // Retrieve the last event
        $lastEvent = Event::latest()->first();

        if ($lastEvent) {
            // Count the number of exhibitors in the last event
            $numberOfExhibitors = $lastEvent->exhibitors()->where('approved', 1)->count();

            $eventTitle = $lastEvent->eventTitle;

            // $all = [
            //     $numberOfExhibitors,
            //     $eventTitle,
            // ];
            // Now, $numberOfExhibitors contains the count of exhibitors in the last event
            return response()->json($numberOfExhibitors);
        } else {
            // Handle the case where there are no events
            echo "No events found.";
        }

    
        return response()->json(error);
    }
   

public function requestCount(){
    $user = Auth::user();

    // Retrieve the last event
    $lastEvent = Event::latest()->first();

    if ($lastEvent) {
        // Count the number of exhibitors in the last event
        $numberOfExhibitors = $lastEvent->exhibitors()->where('approved', 0)->count();

        // Now, $numberOfExhibitors contains the count of exhibitors in the last event
        return response()->json($numberOfExhibitors);
    } else {
        // Handle the case where there are no events
        echo "No events found.";
    }


    return response()->json(error);
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
    // $all = [$event , $exhibitors ];

    // return response()->json($exhibitors);
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
    $events = Event::get();
    $exhibitors = Event::with('exhibitors:id,first_name,last_name,organization,approved:1')->get();

    // foreach ($events as $event) {

    //     $exhibitors = $event->exhibitors->where('approved', 1);

         return response()->json($exhibitors);

}

// public function allRequests()
// {
//     // Retrieve the event by its ID
//     $events = Event::get();
//     $exhibitorRequests = Event::with('exhibitors:id,first_name,approved:0')->get();

//         return response()->json($exhibitorRequests);

// }
public function allRequests()
{
    // Retrieve the event by its ID
    $events = Event::get();
    
    // Retrieve exhibitors with approved = 0
    $exhibitorRequests = Event::with(['exhibitors' => function ($query) {
        $query->where('approved', 0)->select('first_name', 'last_name' , 'organization' , 'email' ,'approved');
    }])->get();

    return response()->json($exhibitorRequests);
}

    public function approveExhibitor($id) 
    {
        $exhibitor = User::role('exhibitor')->find($id);
        if($exhibitor)
        {
            $exhibitor->approved = 1;
            $exhibitor->save();
            return response()->json("the Exhibitor approved");
        }
    }

    public function rejectExhibitor($id) 
    {
        $exhibitor = User::role('exhibitor')->find($id);
        if($exhibitor)
    {
            $exhibitor->approved = 0;
            $exhibitor->save();
            return response()->json (User::destroy($id));
    }
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

    public function update( Request $request ){
        $request->validate([
            'first_name' => 'string',
            'last_name' => 'string',
            'birthday' => 'date',
            'phone' => 'string',
            'email' => 'string|unique:users,email',
            'organization' => 'string',
            'password' => 'confirmed|string|min:6'
            // Add other validation rules for other fields
        ]);

        $user->update($request->all());

        return response()->json($user);
    }
}
