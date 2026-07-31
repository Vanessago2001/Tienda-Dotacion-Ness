<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::latest()->get();
        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        return view('reports.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'    => 'required|max:255',
            'date'    => 'required|date',
            'state'   => 'required',
            'invoice' => 'required|max:100',
            'cashier' => 'required|max:255',
        ]);

        Report::create($request->all());

        return redirect()->route('reports.index')
            ->with('success', 'Reporte generado con éxito.');
    }

    public function show(Report $report)
    {
        return view('reports.show', compact('report'));
    }

    public function edit(Report $report)
    {
        return view('reports.edit', compact('report'));
    }

    public function update(Request $request, Report $report)
    {
        $request->validate([
            'type'    => 'required|max:255',
            'date'    => 'required|date',
            'state'   => 'required',
            'invoice' => 'required|max:100',
            'cashier' => 'required|max:255',
        ]);

        $report->update($request->all());

        return redirect()->route('reports.index')
            ->with('success', 'Reporte actualizado correctamente.');
    }

    public function destroy(Report $report)
    {
        $report->delete();
        return redirect()->route('reports.index')
            ->with('success', 'Reporte eliminado correctamente.');
    }
}