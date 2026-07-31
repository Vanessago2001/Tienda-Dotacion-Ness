<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->get();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|min:3|max:255',
            'document_type' => 'required',
            'document'      => 'required|numeric',
            'email'         => 'required|email|unique:customers,email',
            'phone'         => 'nullable|numeric',
            'address'       => 'required',
            'quota'         => 'required|numeric',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('customers', 'public');
        }

        Customer::create($data);

        return redirect()->route('customers.index')
            ->with('success', 'Cliente registrado correctamente.');
    }

    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name'          => 'required|min:3|max:255',
            'document_type' => 'required',
            'document'      => 'required|numeric',
            'email'         => 'required|email|unique:customers,email,' . $customer->id,
            'phone'         => 'nullable|numeric',
            'address'       => 'required',
            'quota'         => 'required|numeric',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('customers', 'public');
        }

        $customer->update($data);

        return redirect()->route('customers.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}