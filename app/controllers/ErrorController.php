<?php

class ErrorController {
    public function __construct() {
        // No database connection needed for error pages
    }

    public function notFound() {
        header("HTTP/1.0 404 Not Found");
        $title = "404 - Page non trouvée";
        
        // S'assurer que le buffer est vide
        if (ob_get_level()) {
            ob_end_clean();
        }

        ob_start();
        require_once __DIR__ . '/../views/errors/404.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function serverError() {
        header("HTTP/1.0 500 Internal Server Error");
        $title = "500 - Erreur serveur";
        
        // S'assurer que le buffer est vide
        if (ob_get_level()) {
            ob_end_clean();
        }

        ob_start();
        require_once __DIR__ . '/../views/errors/500.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layouts/main.php';
    }
} 