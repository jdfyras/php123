<?php
// Afficher toutes les erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialiser la session
session_start();

// Si aucun token CSRF n'existe, en créer un
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Journalisation pour le débogage
error_log("--- Nouvelle requête: " . $_SERVER['REQUEST_URI'] . " ---");

// Configuration de l'application
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/config/routes.php';

// Autoloader pour les classes
spl_autoload_register(function ($class) {
    // Rechercher d'abord dans le dossier des modèles
    $modelFile = __DIR__ . '/../app/models/' . $class . '.php';
    if (file_exists($modelFile)) {
        require_once $modelFile;
        return;
    }

    // Ensuite dans le dossier des contrôleurs
    $controllerFile = __DIR__ . '/../app/controllers/' . $class . '.php';
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        return;
    }

    // Si ce n'est pas un modèle ou un contrôleur, chercher dans le chemin de base
    $baseFile = __DIR__ . '/../app/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($baseFile)) {
        require_once $baseFile;
    }
});

// Connexion à la base de données
try {
    $config = require __DIR__ . '/../app/config/database.php';
    $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
    $db = new PDO($dsn, $config['username'], $config['password']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupération de l'URL demandée
$request_uri = $_SERVER['REQUEST_URI'];
$base_path = dirname($_SERVER['SCRIPT_NAME']);
$path = substr($request_uri, strlen($base_path));

// Gestion des paramètres de requête
$questionPos = strpos($path, '?');
if ($questionPos !== false) {
    $path = substr($path, 0, $questionPos);
}

// Nettoyage de l'URL
$path = trim($path, '/');
if (empty($path)) {
    $path = '/';
} else {
    $path = '/' . $path;
}

// Récupération des routes
$routes = require __DIR__ . '/../app/config/routes.php';

// Recherche de la route correspondante
$matchedRoute = null;
$params = [];
$currentController = 'Non défini';
$currentMethod = 'Non définie';

foreach ($routes as $route => $handler) {
    // Conversion du pattern de route en expression régulière
    $pattern = preg_replace('/{([^\/]+)}/', '(?P<$1>[^/]+)', $route);
    $pattern = str_replace('/', '\/', $pattern);
    $pattern = '/^' . $pattern . '$/';

    // Vérification si la route correspond
    if (preg_match($pattern, $path, $matches)) {
        $matchedRoute = $route;

        // Extraction des paramètres
        foreach ($matches as $key => $value) {
            if (!is_numeric($key)) {
                $params[$key] = $value;
            }
        }

        break;
    }
}

if ($matchedRoute) {
    list($controller, $method) = explode('@', $routes[$matchedRoute]);
    $currentController = $controller;
    $currentMethod = $method;

    // Variables pour le débogage
    $GLOBALS['current_controller'] = $currentController;
    $GLOBALS['current_method'] = $currentMethod;

    // Instanciation du contrôleur
    $controllerInstance = new $controller($db);

    // Appel de la méthode avec les paramètres
    call_user_func_array([$controllerInstance, $method], $params);
} else {
    // Route non trouvée
    header("HTTP/1.0 404 Not Found");
    echo "Page non trouvée";
}
