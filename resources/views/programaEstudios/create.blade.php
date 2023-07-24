@extends('layout/template')

@section('title', 'Crear Programa de Estudio')

@section('content')

<!-- @if ($errors->any())
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif -->

<form action="{{ url('programaEstudios') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input placeholder="Marketing" type="text" id="nombre" name="nombre" class="form-control" required>
    </div>
    <a href="{{ url('programaEstudios') }}" class="btn btn-warning" tabindex="3"><i class="fas fa-backspace"></i>
        Cancelar</a>
    <button type="submit" class="btn btn-success" tabindex="4"><i class="fas fa-file-download"></i> Guardar</button>
</form>

@stop