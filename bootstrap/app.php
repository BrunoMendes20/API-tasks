<?php

use App\Services\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(
            function (Throwable $e, $request) {
                if (!$request->expectsJson()) {
                    return null;
                }

                // Does not log validation or 404 as a critical error.
                if (!($e instanceof ValidationException) && !($e instanceof ModelNotFoundException)) {
                    Log::error('API exception captured', [
                        'exception' => $e,
                    ]);
                }

                return match (true) {
                    $e instanceof ValidationException => ApiResponse::validationError($e->errors(), 'Falha na validação'),

                    $e instanceof ModelNotFoundException => ApiResponse::notFound('Recurso não encontrado'),

                    $e instanceof AuthenticationException => ApiResponse::unauthorized('Não autenticado'),

                    $e instanceof AuthorizationException => ApiResponse::forbidden('Acesso negado'),

                    $e instanceof HttpExceptionInterface => ApiResponse::error($e->getMessage() ?: 'Erro na requisição', $e->getStatusCode()),

                    default => ApiResponse::error('Erro interno do servidor', 500),
                };
            }
        );
        //
    })->create();
