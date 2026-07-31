<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    public function index()
    {
        $people = Person::latest()->get();
        return view('persons.index', compact('people'));
    }
    public function create()
    {
        return view('persons.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'lastname' => 'required|min:3|max:255',
            'age' => 'required|numeric',
            'email' => 'required|email|unique:persons,email',
            'phone' => 'nullable|max:20',
        ]);
        Person::create($request->all());
        return redirect()->route('persons.index')
            ->with('success', 'Persona creada correctamente.');
    }
    public function show(Person $person)
    {
        return view('persons.show', compact('person'));
    }
    public function edit(Person $person)
    {
        return view('persons.edit', compact('person'));
    }
    public function update(Request $request, Person $person)
    {
        $request->validate([
            'lastname' => 'required|min:3|max:255',
            'age' => 'required|numeric',
            'email' => 'required|email|unique:persons,email,' . $person->id,
            'phone' => 'nullable|max:20',
        ]);

        $person->update($request->all());

        return redirect()->route('persons.index')
            ->with('success', 'Persona actualizada correctamente.');
    }
    public function destroy(Person $person)
    {
        $person->delete();
        return redirect()->route('persons.index')
            ->with('success', 'Persona eliminada correctamente.');
    }
}
