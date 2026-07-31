<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use Illuminate\Http\Request;

class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        $base = Auditoria::with('usuario');

        if ($request->filled('modulo')) {
            $base->where('modulo', $request->modulo);
        }
        if ($request->filled('accion')) {
            $base->where('accion', $request->accion);
        }
        if ($request->filled('desde')) {
            $base->whereDate('created_at', '>=', $request->desde);
        }
        if ($request->filled('hasta')) {
            $base->whereDate('created_at', '<=', $request->hasta);
        }

        $auditorias = $base->latest('id')->paginate(30)->withQueryString();

        // Para los selectores de filtro
        $modulos = Auditoria::select('modulo')->distinct()->orderBy('modulo')->pluck('modulo');
        $acciones = Auditoria::select('accion')->distinct()->orderBy('accion')->pluck('accion');

        return view('auditoria.index', compact('auditorias', 'modulos', 'acciones'));
    }
}
