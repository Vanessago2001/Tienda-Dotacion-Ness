@extends('persons.layout')

@section('title', 'Editar Persona')

@section('content')
    <h1>Editar Datos: {{ $person->lastname }}</h1>

    @if($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('persons.update', $person) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Apellido</label>
        <input type="text" name="lastname" value="{{ old('lastname', $person->lastname) }}">

        <label>Edad</label>
        <input type="number" name="age" value="{{ old('age', $person->age) }}">

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email', $person->email) }}">

        <label>Teléfono</label>
        <input type="text" name="phone" value="{{ old('phone', $person->phone) }}">

        <div style="margin-top: 10px;">
            <button type="submit">Actualizar Cambios</button>
            <a href="{{ route('persons.index') }}" style="margin-left: 10px; text-decoration: none; color: #666;">Volver</a>
        </div>
    </form>
@endsection