@extends('customers.layout')
@section('content')
    <h1>Perfil del Cliente</h1>
    <div style="display: flex; gap: 40px; margin-top: 20px;">
        <img src="{{ $customer->photo }}" width="200" style="border-radius: 15px; border: 4px solid #eee;">
        <div>
            <h2>{{ $customer->name }}</h2>
            <p><strong>{{ $customer->document_type }}:</strong> {{ $customer->document }}</p>
            <p><strong>Email:</strong> {{ $customer->email }}</p>
            <p><strong>Teléfono:</strong> {{ $customer->phone }}</p>
            <p><strong>Dirección:</strong> {{ $customer->address }}</p>
            <p><strong>Cupo Asignado:</strong> <span style="color: #198754; font-weight: bold;">${{ number_format($customer->quota, 0) }}</span></p>
            <p><strong>Registrado desde:</strong> {{ $customer->created_at->format('d/m/Y') }}</p>
        </div>
    </div>
    <hr style="margin: 25px 0;">
    <a href="{{ route('customers.edit', $customer) }}" style="background: #ffc107; padding: 10px 20px; border-radius: 6px; text-decoration: none; color: black;">Editar Información</a>
    <a href="{{ route('customers.index') }}" style="margin-left: 15px; text-decoration: none; color: #666;">Volver</a>
@endsection