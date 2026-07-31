@extends('posts.layout') 
@section('title', 'Crear Post') 
@section('content') 
    <h1>Crear Post</h1> 

    <form action="{{ route('posts.store') }}" method="POST"> 
        @csrf 
        <label>Título</label> 
        <input type="text" name="title" value="{{ old('title') }}"> 

        <label>Slug</label> 
        <input type="text" name="slug" value="{{ old('slug') }}"> 

        <label>Categoría</label> 
        <input type="text" name="category" value="{{ old('category') }}"> 
	
        <label>Contenido</label> 
        <textarea name="content" rows="6">{{ old('content') }}</textarea> 

        <button type="submit">Guardar</button> 
    </form> 
@endsection	