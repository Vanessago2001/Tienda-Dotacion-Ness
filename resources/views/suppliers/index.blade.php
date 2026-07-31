@extends('suppliers.layout')

@section('title', 'Listado de Proveedores')

@section('content')


<style>

    .header-card{
        background: rgba(255,255,255,.90);
        backdrop-filter: blur(12px);
        border-radius:24px;
        padding:25px;
        margin-bottom:25px;
        box-shadow:0 10px 30px rgba(0,0,0,.06);
    }

    .header-card h1{
        margin:0;
        color:#1e293b;
        font-size:32px;
        font-weight:700;
    }

    .header-card p{
        margin-top:8px;
        color:#64748b;
    }

    .table-card{
        background:white;
        border-radius:24px;
        overflow:hidden;
        box-shadow:0 10px 30px rgba(0,0,0,.06);
    }

    .table-modern{
        width:100%;
        border-collapse:collapse;
    }

    .table-modern thead{
        background:#faf5ff;
    }

    .table-modern th{
        padding:18px;
        text-align:left;
        color:#6b21a8;
        font-weight:700;
        border-bottom:1px solid #e9d5ff;
    }

    .table-modern td{
        padding:16px;
        border-bottom:1px solid #f1f5f9;
    }

    .table-modern tbody tr:hover{
        background:#faf5ff;
        transition:.3s;
    }

    .badge{
        padding:6px 12px;
        border-radius:20px;
        font-size:12px;
        font-weight:600;
    }

    .actions{
        display:flex;
        gap:8px;
        flex-wrap:wrap;
    }

    .btn{
        text-decoration:none;
        padding:8px 14px;
        border-radius:10px;
        font-size:13px;
        font-weight:600;
        transition:.3s;
        border:none;
        cursor:pointer;
    }

    .btn:hover{
        transform:translateY(-2px);
    }

    .btn-view{
        background:#e0e7ff;
        color:#4338ca;
    }

    .btn-edit{
        background:#ede9fe;
        color:#0f766e;
    }

    .btn-delete{
        background:#fee2e2;
        color:#dc2626;
    }

    .empty-card{
        background:white;
        padding:40px;
        border-radius:24px;
        text-align:center;
        box-shadow:0 10px 30px rgba(0,0,0,.06);
    }

</style>


<div class="header-card">
    <h1>🚚 Gestión de Proveedores</h1>
    <p>Consulta, administra y controla todos los registros.</p>
</div>

@if($suppliers->count())

<div class="table-card">

    <table class="table-modern">

        <thead>
            <tr>
                    <th>Empresa</th>
                    <th>Contacto</th>
                    <th>Email Empresa</th>
                    <th>Producto</th>
                    <th>Acciones</th>
                </tr>
        </thead>

        <tbody>

            @foreach ($suppliers as $supplier)

            <tr>
                <td>{{ $supplier->company }}</td>
                <td>{{ $supplier->name }}</td>
                <td>{{ $supplier->company_email }}</td>
                <td>{{ $supplier->product }}</td>
                <td>
                    <div class="actions">
                        <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-view">Ver</a>
                        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-edit">Editar</a>
                        <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete" onclick="return confirm('¿Eliminar registro?')">Eliminar</button>
                        </form>
                    </div>
                </td>
            </tr>

            @endforeach

        </tbody>

    </table>

</div>

@else

<div class="empty-card">
    <h3>No hay registros</h3>
    <p>Cuando registres proveedores aparecerán aquí.</p>
</div>

@endif

@endsection
