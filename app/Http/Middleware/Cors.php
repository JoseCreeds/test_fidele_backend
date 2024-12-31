<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Ajout des en-têtes CORS pour autoriser les requêtes depuis un frontend spécifique
        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:5173');  // Remplacez par l'URL de votre frontend
        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-Requested-With');
        $response->headers->set('Access-Control-Allow-Credentials', 'true'); // Si vous utilisez des cookies ou des tokens

        // Si la méthode est OPTIONS, renvoyer une réponse vide avec les bons en-têtes CORS
        if ($request->getMethod() == "OPTIONS") {
            return response()->make('', 200);
        }

        return $response;
    }
}
