@extends('layout.app')

@section('title', 'Home')

@section('content')
    <h1>Bienvenido a la aplicación</h1>
    <a href="{{ route('login.destroy')}}"> Cerrar Sesión</a>
@endsection

