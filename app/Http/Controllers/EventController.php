<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        return response()->json(Event::all());
    }

    // public function create(Request $request)
    // {
    //     $userId = Auth::user()->id;
    //     $fields = $request->validate([
    //         'eventTitle' => 'required|string',
    //         'country' => 'required|string',
    //         'sector' => 'required|string',
    //         'photo' => '',
    //         'tags' => 'required|string',
    //         'summary' => 'required|string',
    //         'description' => 'required|string|min:200',
    //         'startingDate' => 'required|date',
    //         'endingDate' => 'required|date',
    //     ]);
    
    //     // Create the event with the provided fields, including the user_id
    //     $event = Event::create([
    //         'user_id' => $userId, // Set the user_id to the current user's ID
    //         'eventTitle' => $fields['eventTitle'],
    //         'country' => $fields['country'],
    //         'sector' => $fields['sector'],
    //         'photo' => $fields['photo'] ?? null,
    //         'tags' => $fields['tags'],
    //         'summary' => $fields['summary'],
    //         'description' => $fields['description'],
    //         'startingDate' => $fields['startingDate'],
    //         'endingDate' => $fields['endingDate'],
    //     ]);
    //         // Rest of your code


    //         // // Rest of your code
    //         //      $admin = User::role('admin')->get();
    //         //     $event_manager = auth()->user()->first_name;
    //         //     Notification::send($admin , new NewNotification($event->id , $event->eventTitle , $event_manager));

    //     return response()->json($event, 200);
    // }
    // public function create(Request $request)
    // {
    //     try {
    //         $userId = Auth::user()->id;
    
    //         // Extract fields directly from the request
    //         $fields = $request->all();
    
    //         // Create the event with the provided fields, including the user_id
    //         $event = Event::create([
    //             'user_id' => $userId,
    //             'eventTitle' => $fields['eventTitle'] ?? null,
    //             'country' => $fields['country'] ?? null,
    //             'sector' => $fields['sector'] ?? null,
    //             // 'photo' => $fields['photo'] ?? null,
    //             'tags' => $fields['tags'] ?? null,
    //             'summary' => $fields['summary'] ?? null,
    //             'description' => $fields['description'] ?? null,
    //             'startingDate' => $fields['startingDate'] ?? null,
    //             'endingDate' => $fields['endingDate'] ?? null,
    //             'photo' => $fields->file('image')->store('images', 'public'),
    //         ]);
    
    //         // Additional logic, if needed
    
    //         return response()->json($event, 200);
    //     } catch (\Exception $e) {
    //         // Handle exceptions or errors
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

//     public function create(Request $request)
// {
//     try {
//         $userId = Auth::user()->id;

//         // Ensure the form has the enctype="multipart/form-data" attribute
//         // Example Blade view form:
//         // <form action="{{ route('create.event') }}" method="POST" enctype="multipart/form-data">
//         // ...

//         // Check if the 'image' file exists in the request
//         if ($request->hasFile('image')) {
//             $photoPath = $request->getScemeAndHttpHost() . '/storage/' . time() . '.' . $request->image->extension();
//             $request->image->move(public_path('/storage/'), $request->image);
//             // Now you can use $photoPath as needed
//         }

//         // Extract fields directly from the request
//         $fields = $request->all();
//         dd($request->all());

//         // Create the event with the provided fields, including the user_id
//         $event = Event::create([
//             'user_id' => $userId,
//             'eventTitle' => $fields['eventTitle'] ?? null,
//             'country' => $fields['country'] ?? null,
//             'sector' => $fields['sector'] ?? null,
//             'tags' => $fields['tags'] ?? null,
//             'summary' => $fields['summary'] ?? null,
//             'description' => $fields['description'] ?? null,
//             'startingDate' => $fields['startingDate'] ?? null,
//             'endingDate' => $fields['endingDate'] ?? null,
//             'photo' => $photoPath ?? null, // Use the $photoPath if it exists
//         ]);

//         // Additional logic, if needed

//         return response()->json($event, 200);
//     } catch (\Exception $e) {
//         // Handle exceptions or errors
//         return response()->json(['error' => $e->getMessage()], 500);
//     }
// }
// public function create(Request $request)
// {
//     try {
//         $userId = Auth::user()->id;

//         $imagePath =null;
//         // Handle image upload
//         if ($request->hasFile('photo')&& $request->file('photo')->isValid()) {
//             $imagePath = Storage::disk('public')->put('storage', $request->file('photo'));
//             $fields['photo'] = $imagePath;
//         }

//         // Extract fields directly from the request
//         $fields = $request->all();
//         // dd($fields);

//         // Create the event with the provided fields, including the user_id
//         $event = Event::create([
//             'user_id' => $userId,
//             'eventTitle' => $fields['eventTitle'] ?? null,
//             'country' => $fields['country'] ?? null,
//             'sector' => $fields['sector'] ?? null,
//             'tags' => $fields['tags'] ?? null,
//             'summary' => $fields['summary'] ?? null,
//             'description' => $fields['description'] ?? null,
//             'startingDate' => $fields['startingDate'] ?? null,
//             'endingDate' => $fields['endingDate'] ?? null,
//             'photo' => $imagePath, // Use the $photoPath
//         ]);

//         // Additional logic, if needed

//         return response()->json($event, 200);
//     } catch (\Exception $e) {
//         // Handle exceptions or errors
//         return response()->json(['error' => $e->getMessage()], 500);
//     }
// }

// Assuming Tag model exists in App\Models namespace
// public function create(Request $request)
// {
//     try {
//         $userId = Auth::user()->id;

//         $imagePath = null;
//         // Handle image upload
//         if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
//             $imagePath = Storage::disk('public')->put('storage', $request->file('photo'));
//         }

//         // Extract fields directly from the request
//         $fields = $request->all();

//         // Initialize $tags outside of the if block
//         $tags = [];

//         // Create the event with the provided fields, including the user_id and photo
//         $event = Event::create([
//             'user_id' => $userId,
//             'eventTitle' => $fields['eventTitle'] ?? null,
//             'country' => $fields['country'] ?? null,
//             'sector' => $fields['sector'] ?? null,
//             'summary' => $fields['summary'] ?? null,
//             'description' => $fields['description'] ?? null,
//             'startingDate' => $fields['startingDate'] ?? null,
//             'endingDate' => $fields['endingDate'] ?? null,
//             'tags' => $fields['tags'] ?? null,
//             'photo' => $imagePath,
//         ]);

//         // Sync tags with the event
//         if (isset($fields['tags']) && is_array($fields['tags'])) {
//             foreach ($fields['tags'] as $tagName) {
//                 $tag = Tag::firstOrCreate(['name' => $tagName]);
//                 $tags[] = $tag->id;
//             }

//             $event->tags()->attach($tags); // Assuming you have a tags relationship defined in your Event model
//         }

//         return response()->json(['event' => $event, 'tags' => $tags], 200);
//     } catch (\Exception $e) {
//         // Handle exceptions or errors
//         return response()->json(['error' => $e->getMessage()], 500);
//     }
// }

public function create(Request $request)
{
    try {
        $userId = Auth::user()->id;

        $imagePath = null;
        // Handle image upload
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $imagePath = Storage::disk('public')->put('event', $request->file('photo'));
        }

        // Extract fields directly from the request
        $fields = $request->all();

        // Initialize $tags outside of the if block
        $tags = [];

        // Create the event with the provided fields, excluding tags
        $eventData = [
            'user_id' => $userId,
            'eventTitle' => $fields['eventTitle'] ?? null,
            'country' => $fields['country'] ?? null,
            'sector' => $fields['sector'] ?? null,
            'summary' => $fields['summary'] ?? null,
            'description' => $fields['description'] ?? null,
            'startingDate' => $fields['startingDate'] ?? null,
            'endingDate' => $fields['endingDate'] ?? null,
            'photo' => $imagePath,
            // 'tags' => $fields['tags'] ?? [], 
        ];

        // Create the event
        $event = Event::create($eventData);

        // Sync tags with the event
        if (isset($fields['tags']) && is_array($fields['tags'])) {
            foreach ($fields['tags'] as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                $tags[] = $tag->id;
            }

            $event->tags()->attach($tags); // Assuming you have a tags relationship defined in your Event model
        }

        return response()->json(['event' => $event, 'tags' => $tags], 200);

    } catch (\Exception $e) {
        // Handle exceptions or errors
        return response()->json(['error' => $e->getMessage()], 500);
    }
}






    
    public function notifCount()
    {
        $notifs = auth()->check() ? auth()->user()->unreadNotifications->count() : 0;
        return response()->json($notifs);
    }
    
    


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function eventsOfCurrentUser()
    {
        $user = Auth::user();
        // Get the events for the current user
        $events = $user->events;

        return response()->json($events , 200);
    }
    // public function show($id){
    //     // $event = Event::with('eventManager')->find($id);
    //     // ->where('user_id');
    //     // $event = Event::find($id);
    //     // $user = Auth::user();
            
    // $event = Event::with('user')->find($id);
    // // Assuming you have a 'user' relationship defined in your Event model
    // $user = $event->user;
    //     return response()->json(['event' => $event, 'user' => $user], 200);
    // }
    public function show($id)
{
    $event = Event::with('eventManager')->find($id);

    if (!$event) {
        return response()->json(['message' => 'Event not found'], 404);
    }

    // Check if the eventManager relationship is loaded
    if ($event->eventManager) {
        // Assuming you have an 'eventManager' relationship defined in your Event model
        $eventManager = $event->eventManager;

        return response()->json(['event' => $event, 'eventManager' => $eventManager], 200);
    } else {
        return response()->json(['message' => 'EventManager not found for this event'], 404);
    }
}

   
    // public function show(){
    //     $user = User::with('events')->find($id)
    // }

    public function allevents()
    {
        $events = Event::with('eventManager')->get();
        
        if ($events->isEmpty()) {
            return response()->json(['message' => 'Events not found'], 404);
        }
    
        $eventData = [];
    
        foreach ($events as $event) {
            // Check if the eventManager relationship is loaded for each event
            if ($event->eventManager) {
                // Assuming you have an 'eventManager' relationship defined in your Event model
                $eventManager = $event->eventManager;
    
                $eventData[] = [
                    'event' => $event,
                    'eventManager' => $eventManager,
                ];
            } else {
                return response()->json(['message' => 'EventManager not found for an event'], 404);
            }
        }
    
        return response()->json($eventData, 200);
    }
    

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(string $id)
    // {
        
    // }

    function eventsOfUser($id){
        // Example: Retrieve events for a user with ID 1
    $user = User::find($id);
    // Get the events associated with the user
    $events = $user->events;
    return response()->json($events, 200);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id, Request $request) {
        try {
    //         // Find the event by ID
            $event = Event::findOrFail($id);
            $userId = Auth::user()->id;
    
            $imagePath = $event->photo; // Get the existing photo path
    
    //         // Handle image upload if a new photo is provided
            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                // Delete the existing photo if it exists
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                    // Storage::disk('public')->delete($imagePath); 
                }
                // Upload the new photo
                $imagePath = Storage::disk('public')->put('event', $request->file('photo'));
            }
    
    //         // Extract fields directly from the request
            $fields = $request->all();
    
    //         // Initialize $tags outside of the if block
            $tags = [];
    
    //         // Update the event with the provided fields
            $event->update([
                'eventTitle' => $fields['eventTitle'] ?? $event->eventTitle,
                'country' => $fields['country'] ?? $event->country,
                'sector' => $fields['sector'] ?? $event->sector,
                'summary' => $fields['summary'] ?? $event->summary,
                'description' => $fields['description'] ?? $event->description,
                'startingDate' => $fields['startingDate'] ?? $event->startingDate,
                'endingDate' => $fields['endingDate'] ?? $event->endingDate,
                'photo' => $imagePath,
            ]);
    
    //         // Sync tags with the event
            if (isset($fields['tags']) && is_array($fields['tags'])) {
                foreach ($fields['tags'] as $tagName) {
                    $tag = Tag::firstOrCreate(['name' => $tagName]);
                    $tags[] = $tag->id;
                }
    
                $event->tags()->sync($tags); // Use sync() instead of attach() to sync the tags
            }
    
            return response()->json([
                'event' => $event,
                'photo_url' => $imagePath ,
                // 'tags' => $tags
            ], 200);
                
        } catch (\Exception $e) {
            // Handle exceptions or errors
            return response()->json(['error' => $e->getMessage()], 500);
        }
     }
    
    
    

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id){
        $event = Event::destroy($id);

        return response()->json($event, 200); 
    }

    public function EventCount() {
        $count = Event::count();
        return response()->json($count);
    }
    public function EventCountOfTheCurrentUser() {
        $user = Auth::user();
        $events = $user->events;
        $count = $events->count();
        return response()->json($count);
    }
    public function search($eventTitle){
        $event = Event::find($eventTitle);
        return response()->json($event);
    }

}
