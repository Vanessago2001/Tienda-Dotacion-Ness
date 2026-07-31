<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

use App\Models\Factura;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Factura::with(['cliente', 'venta', 'detalles.producto', 'notasCredito']);

        if ($request->filled('numero')) {
            $query->where('numero_factura', 'like', '%' . $request->numero . '%');
        }

        if ($request->filled('fecha')) {
            $query->whereDate('fecha_emision', $request->fecha);
        }

        if ($request->filled('cliente')) {
            $query->whereHas('cliente', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->cliente . '%');
            });
        }

        $facturas = $query->latest()->get();

        return view('invoices.index', compact('facturas'));
    }

    public function create()
    {
        return view('invoices.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|min:5',
            'amount'      => 'required|numeric|min:1',
            'price'       => 'required|numeric|min:0',
            'state'       => 'required',
            'customer'    => 'required|max:255',
            'date'        => 'required|date',
        ]);

        Invoice::create($request->all());

        return redirect()->route('invoices.index')
            ->with('success', 'Factura creada exitosamente.');
    }

    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        return view('invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'description' => 'required|min:5',
            'amount'      => 'required|numeric|min:1',
            'price'       => 'required|numeric|min:0',
            'state'       => 'required',
            'customer'    => 'required|max:255',
            'date'        => 'required|date',
        ]);

        $invoice->update($request->all());

        return redirect()->route('invoices.index')
            ->with('success', 'Factura actualizada correctamente.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')
            ->with('success', 'Factura eliminada.');
    }
}