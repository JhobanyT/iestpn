<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Exception) {
            if (Auth::check()) {
                // Usuario autenticado: redirigir a la vista 'trabajoAplicacion.index'
                return Redirect::route('trabajoAplicacion.index');
            } else {
                // Usuario no autenticado: redirigir a la vista 'publics.index'
                return Redirect::route('publics.index');
            }
        }

        return parent::render($request, $exception);
    }

}