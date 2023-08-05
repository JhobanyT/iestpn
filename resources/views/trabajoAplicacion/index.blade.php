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
                            <button class="btn btn-block w-100">Año de publicación</button>
                            <form action="{{ route('trabajoAplicacion.index') }}" method="GET">
                                <div class="input-group mb-3">
                                    <div>
                                        <label>
                                            <input type="checkbox" name="q" value="Informatica">
                                            Informática
                                        </label>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="checkbox" name="q" value="Contabilidad">
                                            Contabilidad
                                        </label>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="checkbox" name="q" value="Interdisciplinario">
                                            Interdisciplinario
                                        </label>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="checkbox" name="q" value="Normal">
                                            Normal
                                        </label>
                                    </div>
                                    <br>
                                    </div>
                                    <div>
                                        <button class="btn btn-primary" type="submit">Filtrar</button>
                                    </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-10 order-md-1">
            <h4>Repositorio institucional de trabajos de Aplicación de la IESTPN</h4>
            <form action="{{ route('trabajoAplicacion.index') }}" method="GET">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Buscar..." name="q">
                    <div class="col-md-3">
                        <input type="date" class="form-control" name="fecha">
                    </div>
                    <button class="btn btn-primary" type="submit">Buscar</button>
                </div>
            </form>
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
    const showMoreLink = document.getElementById('show-more-link');
    const paginationButtons = document.querySelector('.pagination-buttons');

    showMoreLink.addEventListener('click', () => {
        // Ocultar el enlace "Mostrar más" y los botones de paginación
        showMoreLink.style.display = 'none';
        paginationButtons.style.display = 'none';
    });

    // Ocultar el enlace "Mostrar más" si ya se han mostrado 10 registros
    if ({{ $trabajoAplicacion->perPage() }} >= 10) {
        showMoreLink.style.display = 'none';
    }

    // Ocultar botones de paginación si no hay más páginas
    if (!{{ $trabajoAplicacion->hasMorePages() }}) {
        paginationButtons.style.display = 'none';
    }
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
    function openPdfModal(pdfUrl, modalId) {
        var modal = $('#' + modalId);
        var modalLabel = modal.find('.modal-title');
        var modalBody = modal.find('.modal-body');
        var downloadLink = modal.find('#pdfDownloadLink');

        modalLabel.text(getFileNameFromUrl(pdfUrl));
        modalBody.html('<embed src="' + pdfUrl + '" type="application/pdf" width="100%" height="500px" />');
        downloadLink.attr('href', pdfUrl);
        modal.modal('show');
    }

    function getFileNameFromUrl(url) {
        var index = url.lastIndexOf("/");
        var filename = (index !== -1) ? url.substring(index + 1) : url;
        return filename;
    }
</script>
<script>
    $(document).ready(function() {
        $('.btn-close').click(function() {
            $(this).closest('.modal').modal('hide');
        });
    });
</script>
@stop
