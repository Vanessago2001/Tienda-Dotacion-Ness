<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'logo'    => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $data = $request->except('logo');
        $data['logo'] = $request->file('logo')->store('companies', 'public');

        Company::create($data);

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
            'logo'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            if ($company->logo
                && !str_starts_with($company->logo, 'http')
                && Storage::disk('public')->exists($company->logo)) {
                Storage::disk('public')->delete($company->logo);
            }

            $data['logo'] = $request->file('logo')->store('companies', 'public');
        }

        $company->update($data);

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