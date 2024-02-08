<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ExhibitorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function approvedExibitors(){
        $exhibitors = User::role('exhibitor')->where('approved',1)->get();
        return response()->json($exhibitors);
    }

    public function approvedExibitorsCount(){
        $exhibitors = User::role('exhibitor')->where('approved',1)->count();
        return response()->json($exhibitors);
    }
    public function requests(){
        $exhibitors = User::role('exhibitor')->where('approved',0)->get();
        return response()->json($exhibitors);
    }
    public function requestCount(){
        $exhibitors = User::role('exhibitor')->where('approved',0)->count();
        return response()->json($exhibitors);
    }

    public function approveExhibitor($id) 
    {
        $Exhibitor = User::role('exhibitor')->find($id);
        if($Exhibitor)
        {
            $Exhibitor->approved = 1;
            $Exhibitor->save();
            return response()->json("the Exhibitor approved");
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
