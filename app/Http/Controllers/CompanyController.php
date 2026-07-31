<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::latest()->get();
        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|min:3|max:255',
            'nit'     => 'required|numeric',
            'address' => 'required',
            'phone'   => 'required|numeric',
            'email'   => 'required|email|unique:companies,email',
            'city'    => 'required',
            'logo'    => 'required|url'
        ]);

        Company::create($request->all());

        return redirect()->route('companies.index')
            ->with('success', 'Empresa registrada correctamente.');
    }

    public function show(Company $company)
    {
        return view('companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name'    => 'required|min:3|max:255',
            'nit'     => 'required|numeric',
            'address' => 'required',
            'phone'   => 'required|numeric',
            'email'   => 'required|email|unique:companies,email,' . $company->id,
            'city'    => 'required',
            'logo'    => 'required|url'
        ]);

        $company->update($request->all());

        return redirect()->route('companies.index')
            ->with('success', 'Información de la empresa actualizada.');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index')
            ->with('success', 'Empresa eliminada del sistema.');
    }
}