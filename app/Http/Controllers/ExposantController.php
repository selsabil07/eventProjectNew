<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExposantController extends Controller
{
    public function index(){
        $exposants = User::role('exposant')->get();
        return response()->json($exposants);
    }
   
}
