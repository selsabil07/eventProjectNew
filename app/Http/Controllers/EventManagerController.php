<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class eventManagerController extends Controller
{
    public function index(){
        $eventManagers = User::role('eventManager')->where('approved',1)->get();
        return response()->json($eventManagers);
    }
    public function approvedEventManagers(){
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

    public function search($first_name){
        return response()->json(User::where('first_name', 'like', '%'.$first_name.'%')->get());
    }

    public function destroy($id){
        return  response()->json(User::destroy($id));
    }

    public function update(string $id , Request $request ){
        $user = User::find($id);
        $user->update($request->all());
        return response()->json($user);
    }

    // public function EventManagerCount() {
    //     return response()->json(User::role('eventManager')->count());
    // }

    public function eventsOfUser($id) {
        $events = Event::with('EventManager')->where('id');
        return response()->json();
    }

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

}
