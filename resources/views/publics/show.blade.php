@extends('layout/templatep')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
        <div class="row">
          <!-- Título del Trabajo de Aplicación -->
          <div class="row">
            <div>
              <h4 class="title_show">{{ $taplicacion->titulo }}</h4>
            </div>
          </div>
          <div class="col-md-12 mb-2">
            <p class="p-autor">
              @foreach($taplicacion->autores as $index => $autor)
                {{ $autor->nombre }}{{ $index !== count($taplicacion->autores) - 1 ? ';' : '' }}
              @endforeach
            </p>
          </div>
          <div class="col-md-3 mb-3">
            <div class="row">
                <div class="col-md-12 d-flex justify-content-center">
                    <img class="img_file" src="{{ asset('images/icons/pdf.png') }}" />
                </div>
                <div class="col-md-12 container-input">
                    <p class="nombre_archivo">{{ basename($taplicacion->archivo) }}</p>
                    <a href="#" class="btn btn-success" onclick="openPdfModal()"><i class="fa fa-eye" aria-hidden="true"></i> Visualizar</a>
                </div>
                <!-- Agregamos la fecha de creación y la lista de autores -->
                <div class="col-md-12 mb-2 mt-5 text-center">
                    <label for="created_at" class="form-label">Fecha de Creación:</label>
                    <div>
                        <p class="p-tipo">{{ $taplicacion->created_at->format('Y-m-d') }}</p>
                    </div>
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
            <div>
              <p class="p-resumen">{{ $taplicacion->resumen }}</p>
            </div>
            <div>
              <p class="p-tipo">{{ $taplicacion->tipo }}</p>
            </div>
            <div>
              <p class="p-tipo">{{ $mostCommonProgramaEstudios ? $mostCommonProgramaEstudios->nombre : 'Sin programa de estudios' }}</p>
            </div>
            <div class="col-md-12 col-12 mb-2 d-flex align-items-end justify-content-end">
              <a href="{{ url('/') }}" class="btn btn-warning btn-cancel"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Volver</a>
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
    $(document).ready(function() {
        $('.btn-close').click(function() {
            $('#pdfModal').modal('hide');
        });
    });
</script> 
@stop
