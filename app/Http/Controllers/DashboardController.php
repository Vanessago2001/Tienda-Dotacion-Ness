<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Report;
use App\Models\Category;
use App\Models\Factura;
use App\Models\Company;

class DashboardController extends Controller
{
    public function index()
    {
        // Totales para las tarjetas y gráficos nuevos
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalInvoices = Factura::count();
        $totalCustomers = Customer::count();

        // Últimos registros para los módulos (tablas) originales
        $products   = Product::latest()->take(5)->get();
        $suppliers  = Supplier::latest()->take(5)->get();
        $customers  = Customer::latest()->take(5)->get();
        $reports    = Report::latest()->take(5)->get();
        $categories = Category::latest()->take(5)->get();
        $invoices   = Factura::latest()->take(5)->get();
        $companies  = Company::latest()->take(5)->get();

        // Renombramos $invoices a $latestInvoices también para mantener la compatibilidad con la guía
        $latestInvoices = $invoices;

        return view('dashboard.index', compact(
            'totalUsers', 'totalProducts', 'totalInvoices', 'totalCustomers', 'latestInvoices',
            'products', 'suppliers', 'customers', 'reports', 'categories', 'invoices', 'companies'
        ));
    }
}