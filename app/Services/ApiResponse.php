<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * Return a successful JSON response.
     */
    public static function success(
        mixed $data = null,
        string $message = 'Operação realizada com sucesso',
        int $status = 200
    ): JsonResponse {
        return response()->json(
            [
                'success' => true,
                'message' => $message,
                'data' => $data,
            ],
            $status
        );
    }

    /**
     * Return an error JSON response.
     */
    public static function error(
        string $message,
        int $status = 400,
        array $errors = []
    ): JsonResponse {
        return response()->json(
            [
                'success' => false,
                'message' => $message,
                'errors' => $errors ?: null,

            ],
            $status
        );
    }

    /**
     * Return a validation error (422) JSON response.
     */
    public static function validationError(array $errors, string $message = 'Dados inválidos'): JsonResponse
    {
        return self::error($message, 422, $errors);
    }

    /**
     * Return an unauthorized (401) JSON response.
     */
    public static function unauthorized(string $message = 'Não autenticado'): JsonResponse
    {
        return self::error($message, 401);
    }

    /**
     * Return a forbidden (403) JSON response.
     */

    public static function forbidden(string $message = 'Acesso não autorizado'): JsonResponse
    {
        return self::error($message, 403);
    }

    /**
     * Return a not found (404) JSON response.
     */
    public static function notFound(string $message = 'Recurso não encontrado'): JsonResponse
    {
        return self::error($message, 404);
    }

    /**
     * Return an internal server error (500) JSON response.
     */
    public static function serverError(string $message = 'Erro interno do servidor'): JsonResponse
    {
        return self::error($message, 500);
    }

    /**
     * Return a created (201) JSON response.
     */
    public static function created(mixed $data = null, string $message = 'Recurso criado com sucesso'): JsonResponse
    {
        return self::success($data, $message, 201);
    }

    /**
     * Return a no content (204) JSON response. Useful for delete operations.
     */
    public static function noContent(): JsonResponse
    {
        return response()->json(null, 204);
    }
}
