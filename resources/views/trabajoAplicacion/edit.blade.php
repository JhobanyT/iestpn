@extends('layout/template')

@section('title', 'Editar Trabajo de Aplicación')

@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <form action="{{ route('trabajoAplicacion.update', $taplicacion->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
          <div class="col-md-3 mb-3">
            <div class="row">
              <div class="col-md-12 d-flex justify-content-center">
                  <img class="img_file" src="{{ asset('images/icons/pdf.png') }}" />
              </div>
              <div class="col-md-12 d-flex justify-content-center">
                  <p class="nombre_archivo text-center" data-original-name="{{ basename($taplicacion->archivo) }}">{{ basename($taplicacion->archivo) }}</p>
              </div>
              <div class="col-md-12 container-input">
                  <input type="file" name="archivo" id="archivo" class="inputfile inputfile-1" accept=".pdf" />
                  <label for="archivo">
                      <i class="fa fa-repeat" aria-hidden="true"></i>
                      <span class="iborrainputfile">Reemplazar archivo</span>
                  </label>
              </div>
            </div>
          </div>
          <div class="col-md-9">
            <div class="form-group mb-2">
              <label for="titulo" class="form-label">Título:</label>
              <input type="text" class="form-control" name="titulo" id="titulo" value="{{ $taplicacion->titulo }}" required>
            </div>
            <div class="form-group mb-2">
              <label for="autor" class="form-label">Autor:</label>
              <input type="text" class="form-control" name="autor" id="autor" value="{{ $taplicacion->autor }}" required>
            </div>
            <div class="form-group mb-2">
              <label for="pestudio_id" class="form-label">Programa de Estudios:</label>
              <select class="form-select" name="pestudio_id" id="pestudio_id">
                @foreach ($pestudios as $pestudio)
                  <option value="{{ $pestudio->id }}" {{ $taplicacion->pestudio_id == $pestudio->id ? 'selected' : '' }}>{{ $pestudio->nombre }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group mb-2">
              <label for="resumen" class="form-label">Resumen:</label>
              <textarea class="form-control" name="resumen" id="resumen" rows="4" required>{{ $taplicacion->resumen }}</textarea>
            </div>
            <div class="col-md-12 col-12 mb-2 d-flex align-items-end justify-content-end">
              <a href="{{ route('trabajoAplicacion.show', $taplicacion->id) }}" class="btn btn-warning btn-cancel"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Cancelar</a>
              <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    var originalFileName = '{{ basename($taplicacion->archivo) }}';

    $('input[type="file"]').change(function() {
        var fileInput = $(this);
        var fileName = fileInput.val().split('\\').pop();
        var fileExtension = fileName.split('.').pop().toLowerCase();
        var allowedExtensions = ['pdf'];

        var nombreArchivoElement = $('.nombre_archivo');
        var originalName = nombreArchivoElement.data('original-name');

        if (fileName !== '' && allowedExtensions.indexOf(fileExtension) === -1) {
            fileInput.val(''); // Limpiar el campo de archivo
            fileName = ''; // Vaciar el nombre del archivo

            var alertMessage = 'Solo se permiten archivos PDF.<br>Seleccione otro archivo por favor.';
            var alertDiv = $('<div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 mt-2 ms-2" role="alert" style="z-index: 999; background-color: #C71E42; color: #FFFFFF;">'
                + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>'
                + '<i class="fa fa-exclamation-triangle me-2" aria-hidden="true"></i>'
                + alertMessage
                + '</div>');
            $('body').append(alertDiv);

            // Desvanecer el alert después de 5 segundos
            setTimeout(function() {
                alertDiv.fadeOut(500, function() {
                    $(this).remove();
                });
            }, 5000);

            // Mostrar el nombre original en el <p>
            nombreArchivoElement.text(originalName);
        } else {
            // Mostrar el nombre del archivo seleccionado o vacío si no es PDF
            nombreArchivoElement.text(fileName);
        }
    });
  });
</script>
<script>
    document.getElementById('archivo').addEventListener('change', function(e) {
        var fileName = '';
        if (this.files && this.files.length > 0) {
            fileName = this.files[0].name;
        }
        var nombreArchivoElement = document.querySelector('.nombre_archivo');
        if (nombreArchivoElement) {
            nombreArchivoElement.textContent = fileName;
        }
    });
</script>
 

@stop