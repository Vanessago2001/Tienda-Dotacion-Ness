<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->get();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|min:3|max:255',
            'email'         => 'required|email|unique:suppliers,email',
            'company_email' => 'required|email',
            'phone'         => 'required|numeric',
            'company_phone' => 'required|numeric',
            'product'       => 'required',
            'company'       => 'required',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $data = $request->except('photo');

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('suppliers', 'public');
        }

        Supplier::create($data);

        return redirect()->route('suppliers.index')
            ->with('success', 'Proveedor registrado correctamente.');
    }

    public function show(Supplier $supplier)
    {
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name'          => 'required|min:3|max:255',
            'email'         => 'required|email|unique:suppliers,email,' . $supplier->id,
            'company_email' => 'required|email',
            'phone'         => 'required|numeric',
            'company_phone' => 'required|numeric',
            'product'       => 'required',
            'company'       => 'required',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $data = $request->except('photo');

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('suppliers', 'public');
        }

        $supplier->update($data);

        return redirect()->route('suppliers.index')
            ->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')
            ->with('success', 'Proveedor eliminado.');
    }
}