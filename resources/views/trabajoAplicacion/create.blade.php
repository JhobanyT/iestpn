@extends('layout/template')

@section('title', 'Enviar Trabajo de Aplicación')

@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <form action="{{ route('trabajoAplicacion.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <div class="col-md-3 mb-3">
            <div class="row">
              <div class="col-md-12 d-flex justify-content-center">
                <img class="img_file" src="{{ asset('images/icons/upload-file.png') }}" />
              </div>
              <div class="col-md-12 container-input">
                <input type="file" name="archivo" id="archivo" class="inputfile inputfile-1" accept=".pdf"/>
                <label for="archivo">
                  <svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                  <span class="iborrainputfile">Seleccionar archivo</span>
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-9">
            <div class="form-group mb-2">
              <label for="titulo" class="form-label">Título:</label>
              <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Ingrese el título" required>
            </div>
            <div class="form-group mb-2">
              <label for="autor" class="form-label">Autor:</label>
              <input type="text" class="form-control" name="autor" id="autor" placeholder="Ingrese el nombre del autor" required>
            </div>
            <div class="form-group mb-2">
              <label for="pestudio_id" class="form-label">Programa de Estudios:</label>
              <select class="form-select" name="pestudio_id" id="pestudio_id">
                @foreach ($pestudios as $pestudio)
                <option value="{{ $pestudio->id }}">{{ $pestudio->nombre }}</option required>
                @endforeach
              </select>
            </div>
            <div class="form-group mb-2">
              <label for="resumen" class="form-label">Resumen:</label>
              <textarea class="form-control" name="resumen" id="resumen" rows="4" placeholder="Ingrese el resumen" required></textarea>
            </div>
            <div class="col-md-12 col-12 mb-2 d-flex align-items-end justify-content-end">
              <a href="{{ url('trabajoAplicacion') }}" class="btn btn-warning btn-cancel"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Cancelar</a>
              <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    "use strict";

    (function (document, window, index) {
        var inputs = document.querySelectorAll(".inputfile");
        Array.prototype.forEach.call(inputs, function (input) {
            var label = input.nextElementSibling,
                labelVal = label.innerHTML;

            input.addEventListener("change", function (e) {
                var fileName = "";
                if (this.files && this.files.length > 1)
                    fileName = (this.getAttribute("data-multiple-caption") || "").replace(
                        "{count}",
                        this.files.length
                    );
                else
                    fileName = e.target.value.split("\\").pop();

                if (fileName)
                    label.querySelector("span").innerHTML = fileName;
                else
                    label.querySelector("span").innerHTML = labelVal;
            });
        });
    })(document, window, 0);
</script>
<script>
    $(document).ready(function() {
        $('input[type="file"]').change(function() {
            var fileInput = $(this);
            var fileName = fileInput.val().split('\\').pop();
            var fileExtension = fileName.split('.').pop().toLowerCase();
            var allowedExtensions = ['pdf'];

            if (allowedExtensions.indexOf(fileExtension) === -1) {
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
            }

            fileInput.siblings('.inputfile').text(fileName); // Mostrar el nombre del archivo seleccionado o vacío si no es PDF
        });

        $('form').submit(function() {
            var fileInput = $('input[type="file"]');
            var fileName = fileInput.val().split('\\').pop();
            var fileExtension = fileName.split('.').pop().toLowerCase();
            var allowedExtensions = ['pdf'];

            if (allowedExtensions.indexOf(fileExtension) === -1) {
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

                return false; // Evita que el formulario se envíe si el archivo no cumple con los requisitos
            }
        });
    });
</script>



@stop