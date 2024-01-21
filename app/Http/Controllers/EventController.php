<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        return response()->json(Event::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $fields = $request->validate([
            'eventTitle' => 'required|string',
            'country' => 'required|string',
            'sector' => 'required|string',
            'photo' => '',
            'tags' => 'required|string',
            'summary' => 'required|string',
            'description' => 'required|string',
            'startingDate' => 'required|date',
            'endingDate' => 'required|date',
           //  'eventManagerId' => 'required|exists:event_managers,id', // Ensure the existence of the event manager
        ]);
    
        // Create the event with the provided fields
        $event = Event::create([
            'eventTitle' => $fields['eventTitle'],
            'country' => $fields['country'],
            'sector' => $fields['sector'],
            'photo' => $fields['photo'] ?? null,
            'tags' => $fields['tags'],
            'summary' => $fields['summary'],
            'description' => $fields['description'],
            'startingDate' => $fields['startingDate'],
            'endingDate' => $fields['endingDate'],
           //  'EventManager_id' => $fields['eventManagerId'], // Set the event manager ID
        ]);

        $admin = User::role('admin')->get();
        $event_manager = auth()->user()->first_name;
        Notification::send($admin , new NewNotification($event->id , $event->eventTitle , $event_manager));

        return response()->json($event, 200);
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
    public function show(string $id)
    {
        $Event = Event::find($id);

        return response()->json($Event);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id , Request $request ){
        $Event = Event::find($id);
        $Event->update($request->all());
        return response()->json($Event);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id){
        return Event::destroy($id);
    }

    public function EventCount() {
        $count = Event::count();
        return response()->json($count);
    }
    public function search($eventTitle){
        $event = Event::find($eventTitle);
        return response()->json($event);
    }

}
