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
            <div id="autors-section">
                            @foreach($taplicacion->autores as $key => $autor)
                            <div class="autor-container" style="border: 1px solid #DCDCDC; padding: 10px;">
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="form-group row">
                                            <label for="autor" class="col-md-4 col-form-label">Autor:</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" name="autors[]" placeholder="Apellidos, Nombres" required value="{{ $autor->nombre }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="pestudio_id" class="col-md-4 col-form-label">Programa de Estudios:</label>
                                            <div class="col-md-8">
                                                <select class="form-select" name="pestudio_id[]" required>
                                                    @foreach ($pestudios as $pestudio)
                                                    <option value="{{ $pestudio->id }}" @if($autor->pestudio_id == $pestudio->id) selected @endif>{{ $pestudio->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1 d-flex flex-column justify-content-center align-items-center">
                                        <i class="fa fa-plus fa-lg icon-plus" aria-hidden="true" onclick="agregarAutor()"></i>
                                        @if ($key > 0)
                                        <i class="fa fa-minus fa-lg mt-3 icon-minus" aria-hidden="true" onclick="eliminarAutor(this)"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
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
        @if($errors->any())
            @foreach($errors->all() as $error)
                toastr.options = {
                    "positionClass": "toast-top-right",
                    "timeOut": 5000,
                };
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    });
</script>
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
<script>
    function mostrarIconoMas() {
    var autorsSection = document.getElementById("autors-section");
    var autorContainers = autorsSection.querySelectorAll(".autor-container");

    // Mostrar el ícono de "+" si ya hay al menos un autor-container
    if (autorContainers.length > 0) {
      var ultimoAutorContainer = autorContainers[autorContainers.length - 1];
      var iconoMas = ultimoAutorContainer.querySelector(".fa-plus");
      iconoMas.style.display = "block";
    }

    // Ocultar el ícono de "-" si solo hay un autor-container
    if (autorContainers.length === 1) {
      var unicoAutorContainer = autorContainers[0];
      var iconoMenos = unicoAutorContainer.querySelector(".fa-minus");
      iconoMenos.style.display = "none";
    } else {
      // Mostrar el ícono de "-" en todos los demás autor-containers
      autorContainers.forEach(function(container) {
        var iconoMenos = container.querySelector(".fa-minus");
        iconoMenos.style.display = "block";
      });
    }
  }
  function mostrarIconoMasEnUltimoContenedor() {
    var autorsSection = document.getElementById("autors-section");
    var autorContainers = autorsSection.querySelectorAll(".autor-container");

    // Ocultar el ícono de "+" en todos los contenedores
    autorContainers.forEach(function(container) {
        var iconoMas = container.querySelector(".fa-plus");
        iconoMas.style.display = "none";
    });

    // Mostrar el ícono de "+" solo en el último contenedor de autor
    if (autorContainers.length > 0) {
        var ultimoAutorContainer = autorContainers[autorContainers.length - 1];
        var iconoMas = ultimoAutorContainer.querySelector(".fa-plus");
        iconoMas.style.display = "block";
        iconoMas.style.color = "#08a093";
    }
}

function agregarAutor() {
    console.log("Se hizo clic en el ícono de +");
    var autorsSection = document.getElementById("autors-section");
    var autorContainers = autorsSection.querySelectorAll(".autor-container");

    var autorContainer = crearAutorContainer();
    autorsSection.appendChild(autorContainer);

    // Si hay más de un contenedor, oculta el ícono de "-" en el contenedor anterior
    if (autorContainers.length > 0) {
        var iconoMenosAnterior = autorContainers[autorContainers.length - 1].querySelector(".fa-minus");
        iconoMenosAnterior.style.display = "none";
    }

    // Muestra el ícono de "+" solo en el nuevo último contenedor de autor
    mostrarIconoMasEnUltimoContenedor();

    // Si hay más de un contenedor, muestra el ícono de "-" en el contenedor anterior
    if (autorContainers.length > 0) {
        var iconoMenosAnterior = autorContainers[autorContainers.length - 1].querySelector(".fa-minus");
        iconoMenosAnterior.style.display = "block";
    }
}

    function crearAutorContainer() {
        var autorContainer = document.createElement("div");
        autorContainer.classList.add("autor-container", "my-3", "py-3", "border");
        autorContainer.style.border = "1px solid black";
        autorContainer.style.padding = "10px";

        var row = document.createElement("div");
        row.classList.add("row");

        var col11 = document.createElement("div");
        col11.classList.add("col-md-11");

        var formGroupRow1 = document.createElement("div");
        formGroupRow1.classList.add("form-group", "row");

        var label1 = document.createElement("label");
        label1.classList.add("col-md-4", "col-form-label");
        label1.textContent = "Autor:";

        var col8 = document.createElement("div");
        col8.classList.add("col-md-8");

        var inputAutor = document.createElement("input");
        inputAutor.classList.add("form-control");
        inputAutor.setAttribute("type", "text");
        inputAutor.setAttribute("name", "autors[]");
        inputAutor.setAttribute("placeholder", "Apellidos, Nombres");
        inputAutor.required = true;

        col8.appendChild(inputAutor);
        formGroupRow1.appendChild(label1);
        formGroupRow1.appendChild(col8);

        var formGroupRow2 = document.createElement("div");
        formGroupRow2.classList.add("form-group", "row");

        var label2 = document.createElement("label");
        label2.classList.add("col-md-4", "col-form-label");
        label2.textContent = "Programa de Estudios:";

        var col8_2 = document.createElement("div");
        col8_2.classList.add("col-md-8");

        var selectPestudio = document.createElement("select");
        selectPestudio.classList.add("form-select");
        selectPestudio.setAttribute("name", "pestudio_id[]");
        selectPestudio.required = true;

        var optionDefault = document.createElement("option");
        optionDefault.textContent = "Seleccione el programa de estudio";
        optionDefault.setAttribute("value", "");
        selectPestudio.appendChild(optionDefault);

        @foreach ($pestudios as $pestudio)
            var option = document.createElement("option");
            option.textContent = "{{ $pestudio->nombre }}";
            option.setAttribute("value", "{{ $pestudio->id }}");
            selectPestudio.appendChild(option);
        @endforeach

        col8_2.appendChild(selectPestudio);
        formGroupRow2.appendChild(label2);
        formGroupRow2.appendChild(col8_2);

        col11.appendChild(formGroupRow1);
        col11.appendChild(formGroupRow2);

        var col1 = document.createElement("div");
        col1.classList.add("col-md-1", "d-flex", "flex-column", "justify-content-center", "align-items-center");

        var iconPlus = document.createElement("i");
        iconPlus.classList.add("fa", "fa-plus", "fa-lg");
        iconPlus.setAttribute("aria-hidden", "true");
        iconPlus.setAttribute("style", "color: #08a093;");
        iconPlus.onclick = agregarAutor;

        var iconMinus = document.createElement("i");
        iconMinus.classList.add("fa", "fa-minus", "fa-lg", "mt-3");
        iconMinus.setAttribute("aria-hidden", "true");
        iconMinus.setAttribute("style", "color: #ff0331;"); 
        iconMinus.onclick = function() { eliminarAutor(autorContainer); };

        col1.appendChild(iconPlus);
        col1.appendChild(iconMinus);

        row.appendChild(col11);
        row.appendChild(col1);

        autorContainer.appendChild(row);

        return autorContainer;
    }

    function eliminarAutor(element) {
    console.log("Se hizo clic en el ícono de -");
    var autorContainer = element.closest(".autor-container");
    var autorsSection = document.getElementById("autors-section");
    var autorContainers = autorsSection.querySelectorAll(".autor-container");

    // Verificar si hay solo un autor-container y no eliminarlo
    if (autorContainers.length === 1) {
      return;
    }

    autorContainer.parentNode.removeChild(autorContainer);
    mostrarIconoMas();
  }

  // Ejecutar al cargar la página para manejar los íconos "+" y "-"
  document.addEventListener("DOMContentLoaded", function() {
    mostrarIconoMasEnUltimoContenedor();
});
</script>
 

@stop