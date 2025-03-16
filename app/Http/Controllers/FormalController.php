<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormalController extends Controller
{
    public function index()
    {
        return view('dashboard.admin.formal');
    }
}
