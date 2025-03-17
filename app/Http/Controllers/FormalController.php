<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormalController extends Controller
{
    public function index()
    {
        return view('dashboard.admin.formal1');
    }

    public function courtPolicePrison(Request $request)
    {
        dd($request->all());
    }
}
