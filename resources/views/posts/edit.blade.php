@extends('posts.layout') 
@section('title', 'Editar Post') 
@section('content') 
<h1>Editar Post</h1> 

<form action="{{ route('posts.update', $post) }}" method="POST"> 
        @csrf 
        @method('PUT') 

        <label>Título</label> 
        <input type="text" name="title" value="{{ old('title', $post->title) }}"> 

        <label>Slug</label> 
        <input type="text" name="slug" value="{{ old('slug', $post->slug) 
}}"> 

        <label>Categoría</label> 
        <input type="text" name="category" value="{{ old('category', 
$post->category) }}"> 

        <label>Contenido</label> 
<textarea name="content" rows="6">{{ old('content', $post->content) }}</textarea> 
<button type="submit">Actualizar</button> 
</form> 
@endsection	