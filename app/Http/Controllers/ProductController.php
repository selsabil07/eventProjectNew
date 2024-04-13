<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class productController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $event = Event::find($id);
        
        // Retrieve all users associated with the event
        $users = $event->exhibitors;
        
        // Retrieve all products associated with the users
        $products = collect();
    
        foreach ($users as $user) {
            $products = $products->merge($user->products);
        }
        
        return response()->json($users, 200);
    }
    
    public function products($id){
        $user = User::find($id);
        $products = $user->products;
        return response()->json($products, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $id, Request $request)
{
    try {
        // Find the event by ID
        $event = Event::findOrFail($id);

        // Assuming you have authentication and each exhibitor has a unique ID
        $user = Auth::user(); // Get the ID of the logged-in exhibitor

        $fields = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|string',
            'quantity' => 'string',
            'photo' => 'nullable|image', // Add validation for image files
        ]);

        $imagePath = null;

        // Handle image upload
        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            $imagePath = Storage::disk('public')->put('storage', $request->file('photo'));
            $fields['photo'] = $imagePath;
        }

        // Create the product with the correct event and exhibitor IDs
        $product = Product::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'name' => $fields['name'],
            'description' => $fields['description'],
            'price' => $fields['price'],
            'quantity' => $fields['quantity'],
            'photo' => $imagePath,
        ]);

        return response()->json($product, 200);
    } catch (\Exception $e) {
        // Handle exceptions or errors
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function showUserProducts(){

        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $products = $user->products;

            return response()->json($products, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }


    public function show($id)
    {
     
        $product = Product::find($id);

        return response()->json($product , 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        try {
            // Retrieve the product by its ID
            $product = Product::findOrFail($id);
            
            // Check if the current user is authorized to edit the product
            if ($product->user_id !== Auth::user()->id) {
                return response()->json(['error' => 'You are not authorized to edit this product.'], 403);
            }
    
            // Validate the request fields
            $fields = $request->validate([
                'name' => 'required|string',
                'description' => 'required|string',
                'price' => 'required|string',
                'quantity' => 'string',
                'photo' => 'nullable|image', // Add validation for image files
            ]);
    
            // Handle image upload if a new photo is provided
            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                $imagePath = Storage::disk('public')->put('storage', $request->file('photo'));
                $fields['photo'] = $imagePath;
            }
    
            // Update the product with the new details
            $product->update([
                'name' => $fields['name'],
                'description' => $fields['description'],
                'price' => $fields['price'],
                'quantity' => $fields['quantity'],
                'photo' => $fields['photo'] ?? $product->photo, // Keep the existing photo if a new one is not provided
            ]);
    
            return response()->json($product, 200);
        } catch (\Exception $e) {
            // Handle exceptions or errors
            return response()->json(['error' => $e->getMessage()], 500);
        }
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

    // public function showAllProducts($id ){
    //     $event = Event::find($id);

    //     $products = $event->products;

    //     return response()->json($products, 200);
    // }
}
