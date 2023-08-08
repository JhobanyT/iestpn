@extends('layout/template')

@section('title', 'Trabajos de Aplicación')

@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('trabajoAplicacion.create') }}" class="btn btn-agregar">
        <i class="fa fa-plus" aria-hidden="true"></i> CREAR
    </a>
</div>
<div class="container">
    <div class="row">
        <!-- Div de la derecha -->
        <div class="col-md-2 order-md-2">
            <div class="row">
                <div class="col-12">
                    <h4>Listar</h4>
                    <div class="row">
                        <div class="col-12">
                            <button class="btn btn-block w-100">Año de publicación</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button class="btn btn-block w-100">Autores</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button class="btn btn-block w-100">Títulos</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <h4>Filtros</h4>
                    <div class="row">
                        <div class="col-12">
                            <form action="{{ route('trabajoAplicacion.index') }}" method="GET" id="filtroForm">
                                <div class="input-group mb-3">
                                    <div>
                                        <label style="margin-right: 10px;">
                                            <input type="checkbox" name="pestudio[]" value="Informatica" {{ in_array('Informatica', $selectedPestudios) ? 'checked' : '' }}>
                                            Informática
                                        </label>
                                    </div>
                                    <div>
                                        <label style="margin-right: 10px;">
                                            <input type="checkbox" name="pestudio[]" value="Contabilidad" {{ in_array('Contabilidad', $selectedPestudios) ? 'checked' : '' }}>
                                            Contabilidad
                                        </label>
                                    </div>
                                    <div>
                                        <label style="margin-right: 10px;">
                                            <input type="checkbox" name="tipo[]" value="Interdisciplinario" {{ in_array('Interdisciplinario', $selectedTipos) ? 'checked' : '' }}>
                                            Interdisciplinario
                                        </label>
                                    </div>
                                    <div>
                                        <label style="margin-right: 10px;">
                                            <input type="checkbox" name="tipo[]" value="Normal" {{ in_array('Normal', $selectedTipos) ? 'checked' : '' }}>
                                            Normal
                                        </label>
                                    </div>
                                    <br>
                                    <div style="display: block; margin-bottom: 10px; width: 100%;">
                                        <button class="btn btn-primary" type="submit" value="1">Ejecutar Filtro</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-10 order-md-1">
            <h4>Repositorio institucional de trabajos de Aplicación de la IESTPN</h4>
            <form action="{{ route('trabajoAplicacion.index') }}" method="GET" id="primerBuscador">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Buscar..." name="q">
                    <div class="col-md-3">
                        <input type="date" class="form-control" name="fecha">
                    </div>
                    <button class="btn btn-primary" type="submit">Buscar</button>
                </div>
            </form>

            <div id="buscadorContainer" style="display: none;">
                <form class="d-flex" role="search" action="{{ route('trabajoAplicacion.index') }}" method="GET">
                    <input class="form-control me-2" type="text" placeholder="Buscar en resultados filtrados..." id="buscador" name="q">
                    <button class="btn btn-primary" type="submit" id="aplicarBusqueda">Buscar</button>
                </form>
            </div>

            <div id="resultadosFiltrados">
                <!-- Aquí se mostrarán los elementos filtrados -->

            </div>
            <div id="mensajeNoCoincidencias" style="display: none;">
                No se encontraron coincidencias con los filtros y términos de búsqueda seleccionados.
            </div>

            @if ($searchTerm || $fecha)
                <p>
                    Resultados de búsqueda de:
                    <!-- Mostrar término de búsqueda -->
                    @if ($searchTerm)
                        <strong>{{ $searchTerm }}</strong>
                    @endif

                    <!-- Mostrar fecha de búsqueda -->
                    @if ($fecha)
                        @if ($searchTerm) y @endif
                        <strong>{{ $fecha }}</strong>
                    @endif
                    <a href="{{ route('trabajoAplicacion.index') }}">
                        <i class="fa fa-times" style="color: red;" aria-hidden="true"></i>
                    </a>
                </p>
            @endif
            <h6 class="mb-3">Añadido Recientemente</h6>
            @php
                $showMore = isset($_GET['show_more']) && $_GET['show_more'] === 'true';
            @endphp
            @foreach ($trabajoAplicacion as $trabajo)
                <div class="row">
                    <div class="col-xl-2 previwe-pdf">
                        <div class="archivo-preview" style="overflow: hidden">
                            <div style="margin-right: -16px;">
                                <iframe id="pdfIframe" src="{{ asset('storage/archivos/' . basename($trabajo->archivo)) }}" type="application/pdf" style="display: block; overflow: hidden scroll; height: 160px; width: 100%; pointer-events: none;" frameborder="0" loading="lazy"></iframe>
                            </div>
                        </div>
                    </div>
                    <div class="trabajo-item col-md-12 col-lg-12 col-xl-10 d-md-block">
                        <h5><a class="a-titulo" href="{{ route('trabajoAplicacion.show', ['trabajoAplicacion' => $trabajo->id]) }}">
                            {!! str_replace($searchTerm, '<mark>'.$searchTerm.'</mark>', $trabajo->titulo) !!}
                        </a></h5>
                        @php
                            $autores = $trabajo->autores->pluck('nombre')->toArray();
                            $institucion = 'INSTITUTO DE EDUCACION SUPERIOR TECNOLOGICO PUBLICO DE NUÑOA';
                            $fechaPublicacion = date('Y-m-d', strtotime($trabajo->created_at));
                        @endphp
                        <p class="p-autor">
                            @foreach ($autores as $autor)
                                {!! str_replace($searchTerm, '<mark>'.$searchTerm.'</mark>', $autor) !!}{{ !$loop->last ? '; ' : '' }}
                            @endforeach
                            ({{ $institucion }}, {{ $fechaPublicacion }})
                        </p>
                        <p class="p-tipo">
                            {!! str_replace($searchTerm, '<mark>'.$searchTerm.'</mark>', $trabajo->tipo) !!} -> {!! str_replace($searchTerm, '<mark>'.$searchTerm.'</mark>', $trabajo->programaEstudiosMasComun) !!}
                        </p>
                        <p class="p-resumen">
                            {!! str_replace($searchTerm, '<mark>'.$searchTerm.'</mark>', substr($trabajo->resumen, 0, 300)) !!}...
                        </p>
                        <hr>
                    </div>
                </div>
            @endforeach
            @if ($trabajoAplicacion->isEmpty())
                <p>No se encontraron resultados para la búsqueda.</p>
            @endif
            <div class="pagination-buttons">
                <div class="float-start">
                    @if ($trabajoAplicacion->currentPage() > 1)
                        <a href="{{ $trabajoAplicacion->previousPageUrl() }}" class="dark-button"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
                    @endif
                </div>
                <div class="text-center">
                    @if ($trabajoAplicacion->count() > 1)
                        Mostrando ítems {{ $trabajoAplicacion->firstItem() }}-{{ $trabajoAplicacion->lastItem() }} de {{ $trabajoAplicacion->total() }}
                    @else
                        Mostrando ítem {{ $trabajoAplicacion->firstItem() }} de {{ $trabajoAplicacion->total() }}
                    @endif
                </div>
                <div class="float-end">
                    @if ($trabajoAplicacion->hasMorePages())
                        <a href="{{ $trabajoAplicacion->nextPageUrl() }}" class="dark-button"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obtenemos el elemento del botón con el icono de flecha hacia la izquierda
        const backIcon = document.getElementById('back-icon');

        // Verificar si el elemento existe antes de ocultarlo
        if (backIcon !== null) {
            // Ocultar el botón de "back" si no hay más páginas anteriores
            if (!{{ $trabajoAplicacion->onFirstPage() }}) {
                backIcon.style.display = 'none';
            }
        }
    });
</script>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        const buscadorContainer = document.getElementById('buscadorContainer');
        const filtroForm = document.getElementById('filtroForm');

        // Función para actualizar la visibilidad del buscador
        function actualizarVisibilidadBuscador() {
            const checkboxesSeleccionados = document.querySelectorAll('input[type="checkbox"]:checked');
            buscadorContainer.style.display = checkboxesSeleccionados.length > 0 ? 'block' : 'none';
        }

        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', actualizarVisibilidadBuscador);
        });

        filtroForm.addEventListener('submit', function () {
            actualizarVisibilidadBuscador();
        });

        // Verificar estado inicial al cargar la página
        actualizarVisibilidadBuscador();
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        const aplicarBusqueda = document.getElementById('aplicarBusqueda');
        const primerBuscador = document.getElementById('primerBuscador');
        const segundoBuscadorContainer = document.getElementById('segundoBuscadorContainer');

        aplicarBusqueda.addEventListener('click', function () {
            primerBuscador.style.display = 'none';
            segundoBuscadorContainer.style.display = 'block';
        });

        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const anyCheckboxChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
                if (anyCheckboxChecked) {
                    primerBuscador.style.display = 'none';
                    segundoBuscadorContainer.style.display = 'none';
                } else {
                    primerBuscador.style.display = 'block';
                }
            });
        });
        // Implementa la lógica de búsqueda aquí

    });
</script>

<!--<script>
    document.addEventListener("DOMContentLoaded", function() {
        let resultadosFiltrados = [];

        function actualizarResultadosFiltrados() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            resultadosFiltrados = []; // Limpiar los resultados previos

            checkboxes.forEach(function(checkbox) {
                if (checkbox.checked) {
                    const filtro = checkbox.getAttribute("name");
                    const valor = checkbox.value;
                    // Aquí aplicar lógica para filtrar los resultados según el checkbox seleccionado
                    // Luego, agregar los resultados filtrados a la variable resultadosFiltrados
                }
            });

            mostrarResultados();
        }

        function mostrarResultados() {
            const campoBusqueda = document.getElementById("buscador").value.toLowerCase();

            const resultadosMostrados = resultadosFiltrados.filter(resultado => {
                // Aplicar la búsqueda a cada resultado y retornar true o false
            });

            const contenedorResultados = document.getElementById("resultadosFiltrados");
            const buscadorContainer = document.getElementById("buscadorContainer");
            const mensajeNoCoincidencias = document.getElementById("mensajeNoCoincidencias");

            if (resultadosMostrados.length > 0) {
                contenedorResultados.innerHTML = ""; // Limpiar resultados anteriores
                resultadosMostrados.forEach(resultado => {
                    contenedorResultados.appendChild(resultado);
                });
                mensajeNoCoincidencias.style.display = "none";
                buscadorContainer.style.display = "none"; // Ocultar buscador general
            } else {
                contenedorResultados.innerHTML = ""; // Limpiar resultados anteriores
                mensajeNoCoincidencias.style.display = "block";
                buscadorContainer.style.display = "none"; // Ocultar buscador general
            }
        }

        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener("change", actualizarResultadosFiltrados);
        });

        const botonBuscar = document.getElementById("aplicarBusqueda");
        botonBuscar.addEventListener("click", mostrarResultados);
    });
</script>-->



<!--
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        const buscadorContainer = document.getElementById('buscadorContainer');
        const filtroForm = document.getElementById('filtroForm');

        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const checkboxesSeleccionados = document.querySelectorAll('input[type="checkbox"]:checked');
                if (checkboxesSeleccionados.length > 0) {
                    buscadorContainer.style.display = 'none';
                }
            });
        });

        filtroForm.addEventListener('submit', function (event) {
            event.preventDefault(); // Evita que el formulario se envíe

            const checkboxesSeleccionados = document.querySelectorAll('input[type="checkbox"]:checked');
            if (checkboxesSeleccionados.length > 0) {
                buscadorContainer.style.display = 'block';
            } else {
                buscadorContainer.style.display = 'none';
            }
        });

        // Verificar estado inicial al cargar la página
        const checkboxesSeleccionadosInicial = document.querySelectorAll('input[type="checkbox"]:checked');
        if (checkboxesSeleccionadosInicial.length === 0) {
            buscadorContainer.style.display = 'none';
        }

        // Ocultar buscador si se quitan todos los checkboxes marcados
        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const checkboxesSeleccionados = document.querySelectorAll('input[type="checkbox"]:checked');
                if (checkboxesSeleccionados.length === 0) {
                    buscadorContainer.style.display = 'none';
                }
            });
        });
    });
</script>-->

@stop
