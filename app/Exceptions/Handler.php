<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;



use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


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

    /**
     * Enregistrer les rapports d'exception.
     *
     * @param \Throwable $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Convertir l'exception en une réponse HTTP.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request, Throwable $exception): JsonResponse
    {
        // Gérer les exceptions spécifiques
        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'message' => 'Resource not found',
                'error' => $exception->getMessage()  // Afficher le message d'erreur spécifique
            ], 404);
        }

        // Gérer une exception de validation ou autre exception de type spécifique
        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $exception->errors()  // Afficher les erreurs de validation spécifiques
            ], 422);
        }

        // Gérer les erreurs d'authentification
        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            return response()->json([
                'message' => 'Authentication failed',
                'error' => $exception->getMessage()  // Afficher le message d'erreur spécifique
            ], 401);
        }

        // Gérer les autres erreurs (par exemple, erreurs de serveur génériques)
        return response()->json([
            'message' => 'An error occurred, please try again later.',
            'error' => $exception->getMessage()  // Afficher le message d'erreur général
        ], 500);
    }
}
