@extends('persons.layout')

@section('title', 'Crear Persona')

@section('content')
    <h1>Registrar Nueva Persona</h1>

    @if($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('persons.store') }}" method="POST">
        @csrf
        <label>Apellido</label>
        <input type="text" name="lastname" value="{{ old('lastname') }}" placeholder="Ingrese el apellido">

        <label>Edad</label>
        <input type="number" name="age" value="{{ old('age') }}" placeholder="Ingrese la edad">

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="ejemplo@correo.com">

        <label>Teléfono</label>
        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Número de contacto">

        <div style="margin-top: 10px;">
            <button type="submit">Guardar Registro</button>
            <a href="{{ route('persons.index') }}" style="margin-left: 10px; text-decoration: none; color: #666;">Cancelar</a>
        </div>
    </form>
@endsection