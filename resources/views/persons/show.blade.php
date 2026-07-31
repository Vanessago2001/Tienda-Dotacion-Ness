@extends('persons.layout')

@section('title', 'Detalle de Persona')

@section('content')
    <h1>Información de {{ $person->last_name }}</h1>

    <div style="line-height: 1.8;">
        <p><strong>ID del sistema:</strong> {{ $person->id }}</p>
        <p><strong>Apellido:</strong> {{ $person->lastname }}</p>
        <p><strong>Edad:</strong> {{ $person->age }} años</p>
        <p><strong>Correo Electrónico:</strong> {{ $person->email }}</p>
        <p><strong>Teléfono:</strong> {{ $person->phone ?? 'No registrado' }}</p>
        <p><strong>Fecha de registro:</strong> {{ $person->created_at }}</p>
    </div>

    <hr>
    <a href="{{ route('persons.edit', $person) }}">Editar esta información</a> | 
    <a href="{{ route('persons.index') }}">Volver al listado</a>
@endsection