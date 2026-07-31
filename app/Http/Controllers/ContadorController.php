<?php

namespace App\Http\Controllers;

class ContadorController extends Controller
{
    public function index()
    {
        return view('contador.dashboard');
    }
}
