<?php

class ErrorController {
    public function __construct() {
        // No database connection needed for error pages
    }

    public function notFound() {
        http_response_code(404);
        $title = "404 - Page non trouvée";
        $currentPage = 'error';
        
        ob_start();
        require_once __DIR__ . '/../views/errors/404.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function serverError() {
        http_response_code(500);
        $title = "500 - Erreur serveur";
        $currentPage = 'error';
        
        ob_start();
        require_once __DIR__ . '/../views/errors/500.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layouts/main.php';
    }
} 