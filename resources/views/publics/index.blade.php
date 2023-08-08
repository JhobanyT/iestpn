@extends('layout/templatep')

@section('content')
<div class="container">
    <div class="row">
        <!-- Div de la derecha -->
        <div class="col-md-2 order-md-2">
            <div class="row">
                <div class="col-12">
                    <h4>Listar</h4>
                    <div class="row">
                        <div class="col-12">
                            <form action="{{ route('publics.index') }}" method="GET" class="d-inline">
                                <button type="submit" class="btn btn-block w-100 {{ !$filtroAnio ? 'btn-dark' : 'btn-light' }}">
                                    Todos
                                </button>
                            </form>
                            @foreach ($availableYears as $year)
                                <form action="{{ route('publics.index') }}" method="GET" class="d-inline">
                                    <input type="hidden" name="anio" value="{{ $year }}">
                                    <button type="submit" class="btn btn-block w-100 {{ $filtroAnio == $year ? 'btn-dark' : 'btn-light' }}">
                                        {{ $year }}
                                    </button>
                                </form>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12">
                    <h4>Filtros</h4>
                    <div class="row">
                        <div class="col-12">
                            <form action="{{ route('publics.index') }}" method="GET" id="filtroForm">
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
                                        <button class="btn btn-primary" type="submit">Ejecutar Filtro</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-10 order-md-1">
            <h4 class="mb-3">Repositorio institucional de trabajos de Aplicación de la IESTPN</h4>
            <form action="{{ route('publics.index') }}" method="GET">
                <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Buscar..." name="q" value="{{ $searchTerm }}">
                    <div class="col-md-3">
                        <input type="date" class="form-control" name="fecha" value="{{ $fecha }}">
                    </div>
                    <input type="hidden" name="anio" value="{{ $filtroAnio }}">
                    @foreach($selectedPestudios as $selected)
                        <input type="hidden" name="pestudio[]" value="{{ $selected }}">
                    @endforeach

                    @foreach($selectedTipos as $selected)
                        <input type="hidden" name="tipo[]" value="{{ $selected }}">
                    @endforeach
                    <button class="btn btn-primary" type="submit">Buscar</button>
                </div>
            </form>
            @if ($searchTerm || $fecha || $filtroAnio ||  !empty($selectedPestudios) || !empty($selectedTipos))
            <p>
                Resultados de búsqueda de:
                @if ($filtroAnio)
                    <strong>Año: {{ $filtroAnio }}</strong>
                @endif
                <!-- Mostrar término de búsqueda -->
                @if ($searchTerm)
                    @if ($filtroAnio) y @endif
                    <strong>Término: {{ $searchTerm }}</strong>
                @endif

                @if (!empty($selectedPestudios))
                    @if ($filtroAnio || $searchTerm) y @endif
                    <strong>Programa de Estudios: {{ implode(', ', $selectedPestudios) }}</strong>
                @endif

                @if (!empty($selectedTipos))
                    @if ($filtroAnio || $searchTerm || !empty($selectedPestudios)) y @endif
                    <strong>Tipo: {{ implode(', ', $selectedTipos) }}</strong>
                @endif

                <!-- Mostrar fecha de búsqueda -->
                @if ($fecha)
                    @if ($filtroAnio || $searchTerm || !empty($selectedPestudios) || !empty($selectedTipos)) y @endif
                    <strong>Fecha: {{ $fecha }}</strong>
                @endif
                @if ($filtroAnio || $searchTerm || !empty($selectedPestudios) || !empty($selectedTipos) || $fecha)
                    <a href="{{ route('publics.index') }}">
                        <i class="fa fa-times" style="color: red;" aria-hidden="true"></i>
                    </a>
                @endif
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
                    <h5><a class="a-titulo" href="{{ route('publics.show', ['id' => $trabajo->id]) }}">
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
                        <a href="{{ $trabajoAplicacion->previousPageUrl() }}" class="dark-button" id="back-icon"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>
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
                        <a href="{{ $trabajoAplicacion->nextPageUrl() }}" class="dark-button" id="next-icon"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
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
@stop

