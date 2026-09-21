<?php
declare(strict_types=1);

namespace Config;

/**
 * Clase Response - Estandarizador de respuestas HTTP en formato JSON
 * Formato uniforme: { "success": bool, "data": mixed, "message": string }
 */
class Response {
    /**
     * Emite una respuesta JSON con código de estado HTTP y termina la ejecución
     */
    public static function json(
        int $statusCode = 200,
        bool $success = true,
        mixed $data = null,
        string $message = ''
    ): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

        $response = [
            'success' => $success,
            'message' => $message,
            'data'    => $data
        ];

        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Helpers rápidos para respuestas comunes
     */
    public static function ok(mixed $data = null, string $message = 'Operación exitosa'): void {
        self::json(200, true, $data, $message);
    }

    public static function created(mixed $data = null, string $message = 'Recurso creado exitosamente'): void {
        self::json(201, true, $data, $message);
    }

    public static function badRequest(string $message = 'Datos de solicitud inválidos', mixed $errors = null): void {
        self::json(400, false, $errors, $message);
    }

    public static function notFound(string $message = 'Recurso no encontrado'): void {
        self::json(404, false, null, $message);
    }

    public static function serverError(string $message = 'Error interno del servidor', mixed $errorDetail = null): void {
        self::json(500, false, $errorDetail, $message);
    }
}
