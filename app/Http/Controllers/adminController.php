<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class adminController extends Controller
{
    public function index(){
        $admin = User::role('admin')->get();
        return response()->json($admin);
    }

    public function update(string $id , Request $request ){
        $admin = User::find($id);
        $admin->update($request->all());
        return response()->json($admin);
    }
}
