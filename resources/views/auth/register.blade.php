@extends('layout.app')

@section('title', 'Register')

@section('content')

<div class="container">
    <h1>Register</h1>
    <form action="" method="POST">
        @csrf
        <input type="text" placeholder="Name" id="name" name="name">
        @error('name')
            <p>* {{ $message }}</p>
        @enderror
        <input type="text" placeholder="Email" id="email" name="email">
        @error('email')
            <p>* {{ $message }}</p>
        @enderror
        <input type="password" placeholder="Password" id="password" name="password">
        @error('password')
            <p>* {{ $message }}</p>
        @enderror
        <input type="password" placeholder="Password Confirmation" id="password_confirmation" name="password_confirmation">
        <input type="role" placeholder="Rol" id="role" name="role">
        <button type="submit"> Registrar </button>
        <a href=""></a>
        <a href="{{ route('programaEstudios.index') }}">regresar</a>
    </form>
</div>


@endsection
