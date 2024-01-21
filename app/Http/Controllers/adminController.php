<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class adminController extends Controller
{
    public function index(){
        $admin = User::role('admin')->get();
        return response()->json($admin);
    }

    public function update(Request $request) {
        $user = auth()->user(); // Use auth() to get the authenticated user
        $user->update($request->all());
    
        return response()->json($user);
    }

    public function adminInfo($id){
        $admin = User::role('admin')->where($id)->get();
        return response()->json($admin);
    }
}
