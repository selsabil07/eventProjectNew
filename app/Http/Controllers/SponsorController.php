<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SponsorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Sponsor::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, string $id)
    {
        $event = Event::find($id);
    
        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }
    
        $imagePath = null;
    
        // Handle image upload
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $imagePath = Storage::disk('public')->put('SponsorLogo', $request->file('logo'));
        }
    
        $fields = $request->validate([
            'name' => 'required|string',
        ]);
    
        $sponsor = Sponsor::create([
            'event_id' => $event->id,
            'name' => $fields['name'],
            'logo' => $imagePath,
        ]);
    
        return response()->json($sponsor, 200);
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
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
