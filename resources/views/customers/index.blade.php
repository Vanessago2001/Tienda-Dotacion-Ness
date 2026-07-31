@extends('customers.layout')

@section('title', 'Listado de Clientes')

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
        background:#f0fdfa;
    }

    .table-modern th{
        padding:18px;
        text-align:left;
        color:#0f766e;
        font-weight:700;
        border-bottom:1px solid #ccfbf1;
    }

    .table-modern td{
        padding:16px;
        border-bottom:1px solid #f1f5f9;
    }

    .table-modern tbody tr:hover{
        background:#f0fdfa;
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
        background:#e6fbf8;
        color:#0d3f3c;
    }

    .btn-edit{
        background:#ccfbf1;
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
    <h1>👥 Gestión de Clientes</h1>
    <p>Consulta, administra y controla todos los registros.</p>
</div>

@if($customers->count())

<div class="table-card">

    <table class="table-modern">

        <thead>
            <tr>
                    <th>Foto</th>
                    <th>Nombre</th>
                    <th>Documento</th>
                    <th>Email</th>
                    <th>Cupo</th>
                    <th>Acciones</th>
                </tr>
        </thead>

        <tbody>

            @foreach ($customers as $customer)

            <tr>
                <td><img src="{{ $customer->photo ? asset('storage/' . $customer->photo) : asset('images/logo_happy_store.png') }}" width="45" height="45" style="border-radius: 50%; object-fit: cover;"></td>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->document_type }}: {{ $customer->document }}</td>
                <td>{{ $customer->email }}</td>
                <td><strong>${{ number_format($customer->quota, 0) }}</strong></td>
                <td>
                    <div class="actions">
                        <a href="{{ route('customers.show', $customer) }}" class="btn btn-view">Ver</a>
                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-edit">Editar</a>
                        <form action="{{ route('customers.destroy', $customer) }}" method="POST">
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
    <p>Cuando registres clientes aparecerán aquí.</p>
</div>

@endif

@endsection
