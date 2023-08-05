@extends('layout/template')

@section('title', 'Editar Programa De Estudio')

@section('content')
    <form action="/programaEstudios/{{$pestudio->id}}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input placeholder="Nuevo Programa De Estudio" type="text" id="nombre" name="nombre" class="form-control" value="{{$pestudio->nombre}}" required>
        </div>
        <a href="{{ url('programaEstudios') }}" class="btn btn-warning" tabindex="2"><i class="fas fa-backspace"></i> Cancelar</a>
        <button type="submit" class="btn btn-success"><i class="fas fa-file-download"></i> Guardar</button>
    </form>

@stop
