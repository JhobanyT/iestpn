@extends('layout/template')

@section('title', 'Detalles del Trabajo de Aplicación')

@section('content')

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <form action="{{ route('trabajoAplicacion.destroy', $taplicacion->id) }}" method="POST" id="deleteForm">
        @csrf
        @method('DELETE')
        <div class="row">
          <div class="col-md-3 mb-3">
            <div class="row">
                <div class="col-md-12 d-flex justify-content-center">
                    <img class="img_file" src="{{ asset('images/icons/pdf.png') }}" />
                </div>
                <div class="col-md-12 container-input">
                    <p class="nombre_archivo">{{ basename($taplicacion->archivo) }}</p>
                    <a href="#" class="btn btn-success" onclick="openPdfModal()"><i class="fa fa-eye" aria-hidden="true"></i> Visualizar</a>
                </div>
                <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="pdfModalLabel">{{ basename($taplicacion->archivo) }}</h5>
                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="pdfModalBody"></div>
                            <div class="modal-footer">
                              <a href="{{ asset('storage/archivos/'.basename($taplicacion->archivo)) }}" class="btn btn-info" target="_blank"><i class="fa fa-external-link-square" aria-hidden="true"></i> Abrir en otra ventana</a>
                              <a href="{{ asset('storage/archivos/'.basename($taplicacion->archivo)) }}" download="{{ basename($taplicacion->archivo) }}" class="btn btn-dark"><i class="fa fa-download" aria-hidden="true"></i> Descargar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
          <div class="col-md-9">
            <div class="form-group mb-2">
              <label for="titulo" class="form-label">Título:</label>
              <input type="text" class="form-control" name="titulo" id="titulo" value="{{ $taplicacion->titulo }}" readonly>
            </div>
            <div class="form-group mb-2">
              <label for="autor" class="form-label">Autor:</label>
              <input type="text" class="form-control" name="autor" id="autor" value="{{ $taplicacion->autor }}" readonly>
            </div>
            <div class="form-group mb-2">
              <label for="pestudio_id" class="form-label">Programa de Estudios:</label>
              <input type="text" class="form-control" name="pestudio_id" id="pestudio_id" value="{{ $taplicacion->pestudio ? $taplicacion->pestudio->nombre : '' }}" readonly>
            </div>
            <div class="form-group mb-2">
              <label for="resumen" class="form-label">Resumen:</label>
              <textarea class="form-control" name="resumen" id="resumen" rows="4" readonly>{{ $taplicacion->resumen }}</textarea>
            </div>
            <div class="col-md-12 col-12 mb-2 d-flex align-items-end justify-content-end">
              <a href="{{ url('trabajoAplicacion') }}" class="btn btn-warning btn-cancel"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Volver</a>
              @if (auth()->user()->role == 'estudiante')
                <a href="{{ route('trabajoAplicacion.edit', $taplicacion->id) }}" class="btn btn-info btn-edit"><i class="fa fa-pencil" aria-hidden="true"></i> Editar</a>
                <button type="button" class="btn btn-danger" onclick="showConfirmationModal()">
                    <i class="fa fa-trash" aria-hidden="true"></i> Eliminar
                </button>
              @endif
            </div>
          </div>
        </div>
      </form>
      <div class="modal" tabindex="-1" role="dialog" id="confirmationModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de eliminar este trabajo de aplicación? Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-no" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" onclick="deleteRecord()">Sí</button>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
    function openPdfModal() {
        var pdfUrl = "{{ asset('storage/archivos/'.basename($taplicacion->archivo)) }}";
        var modalBody = document.getElementById('pdfModalBody');
        modalBody.innerHTML = '<embed src="' + pdfUrl + '" type="application/pdf" width="100%" height="500px" />';
        $('#pdfModal').modal('show');
    }
</script>
<script>
    function showConfirmationModal() {
        $('#confirmationModal').modal('show');
    }

    function deleteRecord() {
        document.getElementById('deleteForm').submit();
    }
</script>
<script>
    $(document).ready(function() {
        $('.btn-close, .btn-no').click(function() {
            $('#confirmationModal, #pdfModal').modal('hide');
        });
    });
</script>
 

@stop