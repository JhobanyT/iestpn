@extends('layout/templatep')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12">
        <div class="row">
          <!-- Título del Trabajo de Aplicación -->
          <div class="row mb-3">
            <div>
              <h4 class="title_show">{{ $taplicacion->titulo }}</h4>
            </div>
          </div>
          <div class="col-md-3">
            <div class="row">
              <div class="col-md-12 mb-2">
                  <label class="fp-autor">Autor(es):</label>
                  @foreach($taplicacion->autores as $autor)
                      <div class="p-autor">
                          {{ $autor->nombre }}
                      </div>
                  @endforeach
              </div>
                <!-- Agregamos la fecha de creación y la lista de autores -->
                <div class="col-md-12 mb-2 mt-2">
                    <label for="created_at" class="form-label">Fecha:</label>
                    <div>
                        <p class="p-autor">{{ $taplicacion->created_at->format('Y-m-d') }}</p>
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
@stop
