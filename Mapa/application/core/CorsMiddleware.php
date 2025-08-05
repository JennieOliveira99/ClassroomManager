<?php
class CorsMiddleware {
    public static function handle() {
        // Remove qualquer saída anterior
        if (ob_get_length() > 0) {
            ob_clean();
        }

        // Define os headers CORS
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Access-Control-Allow-Credentials: true");
        header("Vary: Origin");

        // Se for OPTIONS, retorna apenas os headers
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header('HTTP/1.1 204 No Content');
            header('Content-Length: 0');
            exit();
        }
    }
}