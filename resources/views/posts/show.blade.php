@extends('posts.layout') 
@section('title', 'Detalle del Post') 
@section('content') 
    <h1>{{ $post->title }}</h1> 
    <p><strong>Slug:</strong> {{ $post->slug }}</p> 
    <p><strong>Categoría:</strong> {{ $post->category }}</p> 
    <p><strong>Contenido:</strong></p> 
    <p>{{ $post->content }}</p> 
    <a href="{{ route('posts.edit', $post) }}">Editar</a> 
@endsection	