@extends('companies.layout')
@section('content')
    <h1>Detalle de la Empresa</h1>
    <div style="display: flex; gap: 30px; margin-top: 20px;">
        <img src="{{ $company->logo }}" width="150" height="150" style="object-fit: contain; border: 1px solid #eee; padding: 10px;">
        <div>
            <h2>{{ $company->name }}</h2>
            <p><strong>NIT:</strong> {{ $company->nit }}</p>
            <p><strong>Ubicación:</strong> {{ $company->address }} - {{ $company->city }}</p>
            <p><strong>Contacto:</strong> {{ $company->phone }} | {{ $company->email }}</p>
        </div>
    </div>
    <hr>
    <a href="{{ route('companies.index') }}">Volver</a> | 
    <a href="{{ route('companies.edit', $company) }}">Editar Información</a>
@endsection