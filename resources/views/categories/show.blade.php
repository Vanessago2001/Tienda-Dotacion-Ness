@extends('categories.layout')
@section('content')
    <h1>Detalle: {{ $category->name }}</h1>
    <p><strong>ID:</strong> {{ $category->id }}</p>
    <p><strong>Nombre:</strong> {{ $category->name }}</p>
    <p><strong>Estado:</strong> {{ $category->state }}</p>
    <p><strong>Fecha Creación:</strong> {{ $category->created_at }}</p>
    <hr>
    <a href="{{ route('categories.index') }}">Volver</a>
@endsection