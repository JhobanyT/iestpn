@extends('layout.app')

@section('title', 'Login')

@section('content')

<form action="/categorias" method="POST">
    @csrf
    <div class="mb-3">
        <label for="" class="form-label">Nombre</label>
        <input placeholder="Principiante" type="text" id="no_categoria" name="no_categoria" class="form-control" tabindex="1">
        <label for="" class="form-label">Descripcion</label>
        <input placeholder="esta categoria es para los que recien empiezan en la asociación" type="text" id="de_categoria" name="de_categoria" class="form-control" tabindex="2">
    </div>
    <a href="/categorias" class="btn btn-warning" tabindex="3"><i class="fas fa-backspace"></i> Cancelar</a>
    <button type="submit" class="btn btn-success" tabindex="4"><i class="fas fa-file-download"></i> Guardar</button>
</form>
