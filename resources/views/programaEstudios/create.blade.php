@extends('layout/template')

@section('title', 'Crear Programa de Estudio')

@section('content')
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
<script>
    // Verificar si existe el mensaje de éxito
    $(document).ready(function() {
        @if(Session::has('success'))
            toastr.options = {
                "positionClass": "toast-bottom-right",
            };
            toastr.success("{{ Session::get('success') }}");
        @endif
    });
    </script>
@stop
