<?php
// Afficher toutes les erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Load configuration
require_once __DIR__ . '/../app/config/config.php';

// Load helper functions
require_once __DIR__ . '/../app/helpers/functions.php';

// Load database configuration
require_once __DIR__ . '/../app/config/database.php';

// Load Database class
require_once __DIR__ . '/../app/Database.php';

// Debug information
error_log("Request URI: " . $_SERVER['REQUEST_URI']);
error_log("BASE_URL: " . BASE_URL);
error_log("Script Name: " . $_SERVER['SCRIPT_NAME']);

// Si aucun token CSRF n'existe, en créer un
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Journalisation pour le débogage
error_log("--- Nouvelle requête: " . $_SERVER['REQUEST_URI'] . " ---");

// Autoloader for classes
spl_autoload_register(function ($class) {
    // Look in models directory first
    $modelFile = __DIR__ . '/../app/models/' . $class . '.php';
    if (file_exists($modelFile)) {
        require_once $modelFile;
        return;
    }

    // Then in controllers directory
    $controllerFile = __DIR__ . '/../app/controllers/' . $class . '.php';
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        return;
    }

    // If not a model or controller, look in base path
    $baseFile = __DIR__ . '/../app/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($baseFile)) {
        require_once $baseFile;
    }
});

try {
    // Get the requested URL
    $request_uri = $_SERVER['REQUEST_URI'];
    error_log("Original Request URI: " . $request_uri);
    error_log("SERVER variables: " . print_r($_SERVER, true));

    // Remove query string if present
    $request_uri = parse_url($request_uri, PHP_URL_PATH);
    error_log("After parse_url: " . $request_uri);

    // Remove base URL from request
    $base_path = parse_url(BASE_URL, PHP_URL_PATH);
    error_log("Base path: " . $base_path);
    if (!empty($base_path) && strpos($request_uri, $base_path) === 0) {
        $request_uri = substr($request_uri, strlen($base_path));
        error_log("After removing base path: " . $request_uri);
    }

    // Clean up the path
    $path = '/' . trim($request_uri, '/');
    error_log("Final processed path: " . $path);

    // Get routes
    $routes = require __DIR__ . '/../app/config/routes.php';
    error_log("Available routes: " . print_r(array_keys($routes), true));

    // Find matching route
    $matchedRoute = null;
    $params = [];

    foreach ($routes as $route => $handler) {
        // Convert route pattern to regex
        $pattern = preg_replace('/{([^\/]+)}/', '(?P<$1>[^/]+)', $route);
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = '/^' . $pattern . '$/';
        
        error_log("Checking route pattern: " . $pattern . " against path: " . $path);

        if (preg_match($pattern, $path, $matches)) {
            $matchedRoute = $route;
            error_log("Route matched: " . $route);
            
            // Extract parameters
            foreach ($matches as $key => $value) {
                if (!is_numeric($key)) {
                    $params[$key] = $value;
                }
            }
            break;
        }
    }

    // If a route is found
    if ($matchedRoute !== null) {
        list($controller, $method) = explode('@', $routes[$matchedRoute]);
        error_log("Executing controller: " . $controller . ", method: " . $method);
        
        // Create controller instance with PDO connection
        $db = Database::getInstance()->getConnection();
        $controllerInstance = new $controller($db);
        
        // Call the method with parameters
        call_user_func_array([$controllerInstance, $method], $params);
    } else {
        // Route not found - use ErrorController
        error_log("No matching route found for path: " . $path);
        $errorController = new ErrorController();
        $errorController->notFound();
    }
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    $errorController = new ErrorController();
    $errorController->serverError();
}
