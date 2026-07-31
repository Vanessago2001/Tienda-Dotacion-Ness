<?php

namespace App\Http\Controllers;

class VisitanteController extends Controller
{
    public function index()
    {
        return view('visitante.dashboard');
    }
}
