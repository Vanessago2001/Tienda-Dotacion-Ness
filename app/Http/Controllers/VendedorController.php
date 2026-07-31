<?php

namespace App\Http\Controllers;

class VendedorController extends Controller
{
    public function index()
    {
        return view('vendedor.dashboard');
    }
}
