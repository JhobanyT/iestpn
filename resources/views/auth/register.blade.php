@extends('layout.app')

@section('title', 'Register')

@section('content')

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

    <div class="contenido_login">
        <div class="container">
            <div class="row">
                <div class="col">
                    <img class="logo" src="images/logo/logo.png">
                </div>
                <h2 class="fw-bold instituto">INSTITUTO DE EDUCACION SUPERIOR TECNOLOGICO PUBLICO DE NUÑOA</h2>
                <!-- Register-->
                <form action="" method="POST">
                    @csrf
                    <input type="text" placeholder="Nombre" id="name" name="name" class="form-control">
                    @error('name')
                        <p>* Necesita completar este campo con un Nombre</p>
                    @enderror
                    <input type="text" placeholder="Correo" id="email" name="email" class="form-control">
                    @error('email')
                        <p>* Necesita completar este campo con un Correo</p>
                    @enderror
                    <input type="password" placeholder="Contraseña" id="password" name="password" class="form-control">
                    @error('password')
                    <p>* Necesita completar este campo con una contraseña o las contraseñas no coinciden'</p>
                    @enderror
                    <input type="password" placeholder="Confirmar Contraseña" id="password_confirmation" name="password_confirmation" class="form-control">
                    @error('password_confirmation')
                    @enderror
                    <input type="role" placeholder="Rol" id="role" name="role" class="form-control">
                    @error('role')
                        <p>* Necesita completar este campo con un rol</p>
                    @enderror
                    <button type="submit" class="boton_registrar"> Registrar </button>
                </form>
                <a href="{{ route('programaEstudios.index') }}"  class="boton_cancelar"><button type="submit" class="boton_salir">Salir </button></a>

                <!-- registroExitoso.blade.php -->
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</body>

@endsection
