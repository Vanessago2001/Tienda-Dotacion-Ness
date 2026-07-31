<?php

namespace App\Http\Controllers;

use App\Models\Product;

class CatalogoController extends Controller
{
    public function index()
    {
        // Todos los productos ordenados por nombre.
        $products = Product::orderBy('name')->get();

        return view('catalogo.index', compact('products'));
    }
}

