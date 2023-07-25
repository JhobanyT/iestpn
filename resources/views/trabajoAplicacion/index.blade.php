@extends('layout/template')

@section('title', 'Trabajos de Aplicación')

@section('content')

<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('trabajoAplicacion.create') }}" class="btn btn-agregar"><i class="fa fa-plus" aria-hidden="true"></i> CREAR</a>
</div>
<div class="card-body">
    <div id="content_ta_wrapper" class="dataTables_wrapper">
        <div class="table-responsive">
            <table id="content_ta" class="table table-striped mt-4 table-hover custom-table" role="grid" aria-describedby="content_ta_info">
                <thead>
                    <tr role="row">
                        <th class="d-none">Fecha Envio</th>
                        <th class="text-center">Título</th>
                        <th class="text-center">Autor</th>
                        <th class="text-center">Programa de Estudio</th>
                        <th class="text-center">Archivo</th>
                        <th class="text-center">Detalles</th>
                    </tr>  
                </thead>
                <tbody class="text-center">
                    @foreach ($trabajoAplicacion as $taplicacion)
                    <tr class="odd">
                        <td class="d-none">{{ $taplicacion->created_at }}</td>
                        <td>{{ $taplicacion->titulo }}</td>
                        <td>{{ $taplicacion->autor }}</td>
                        <td>{{ $taplicacion->pestudio->nombre }}</td>
                        <td class="">
                            <a class="iconos_index" href="#" onclick="openPdfModal('{{ asset('storage/archivos/'.basename($taplicacion->archivo)) }}', 'pdfModal-{{ $taplicacion->id }}')">
                                <i class="fa fa-file-pdf-o fa-2x" aria-hidden="true"></i>
                            </a>
                        </td>
                        <td class="">
                            <a class="iconos_index" href="{{ route('trabajoAplicacion.show', $taplicacion->id) }}">
                                <i class="fa fa-eye fa-2x" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                    <div class="modal fade" id="pdfModal-{{ $taplicacion->id }}" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="pdfModalLabel"></h5>
                                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" id="pdfModalBody"></div>
                                <div class="modal-footer">
                                    <a href="#" id="pdfDownloadLink" class="btn btn-info" target="_blank"><i class="fa fa-external-link-square" aria-hidden="true"></i> Abrir en otra ventana</a>
                                    <a href="{{ asset('storage/archivos/'.basename($taplicacion->archivo)) }}" download="{{ basename($taplicacion->archivo) }}" class="btn btn-dark"><i class="fa fa-download" aria-hidden="true"></i> Descargar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Traducción español
    var espanol = {
        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix": "",
        "sSearch": "Buscar:",
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst": "Primero",
            "sLast": "Último",
            "sNext": "<i class='fa fa-chevron-right' aria-hidden='true'></i>",
            "sPrevious": "<i class='fa fa-chevron-left' aria-hidden='true'></i>"
        },
        "oAria": {
            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    };

    // Inicializar DataTables con opciones de búsqueda, paginación y ordenamiento
    $(document).ready(function() {
        $('#content_ta').DataTable({
            "language": espanol,
            "paging": true, // Habilitar paginación
            "ordering": true, // Habilitar ordenamiento de columnas
            "order": [[0, "desc"]], // Ordenar por la primera columna (0) de manera descendente (más reciente primero)
            "lengthMenu": [5, 10, 25, 50], // Opciones de longitud de página para el usuario
            "pageLength": 5, // Por defecto, mostrar 5 registros por página
            "dom": '<"row" <"col-sm-12 col-md-6" l><"col-sm-12 col-md-6" f>>rtip',
            "responsive": true
        });

        // funcionalidad de búsqueda
        $('#search').on('keyup', function () {
            $('#content_ta').DataTable().search(this.value).draw();
        });
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