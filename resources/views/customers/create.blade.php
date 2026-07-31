@extends('customers.layout')
@section('content')
    <h1>Registrar Cliente</h1>
    @if($errors->any())
        <div class="error">
            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif
    <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label>Nombre Completo</label>
        <input type="text" name="name" value="{{ old('name') }}">

        <div style="display: flex; gap: 15px;">
            <div style="flex: 1;">
                <label>Tipo de Documento</label>
                <select name="document_type">
                    <option value="CC">Cédula de Ciudadanía</option>
                    <option value="TI">Tarjeta de Identidad</option>
                    <option value="CE">Cédula de Extranjería</option>
                </select>
            </div>
            <div style="flex: 1;">
                <label>Número de Documento</label>
                <input type="number" name="document" value="{{ old('document') }}">
            </div>
        </div>

        <label>Correo Electrónico</label>
        <input type="email" name="email" value="{{ old('email') }}">

        <label>Teléfono</label>
        <input type="number" name="phone" value="{{ old('phone') }}">

        <label>Dirección</label>
        <input type="text" name="address" value="{{ old('address') }}">

        <label>Cupo de Crédito</label>
        <input type="number" name="quota" value="{{ old('quota') }}">

        <label>Foto de Perfil</label>
        <input type="file" name="photo" accept="image/*">

        <button type="submit" style="margin-top: 20px;">Guardar Cliente</button>
    </form>
@endsection