<?php
// Affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// En-tête
echo "<h1>Page de débogage</h1>";
echo "<p>Cette page permet de tester différentes fonctionnalités de l'application.</p>";

// Test de la base de données
echo "<h2>1. Test de connexion à la base de données</h2>";
try {
    $config = require __DIR__ . '/../app/config/database.php';
    $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
    $db = new PDO($dsn, $config['username'], $config['password']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color:green'>✓ Connexion à la base de données réussie!</p>";

    // Vérification des tables
    echo "<h3>Tables existantes:</h3>";
    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    if (empty($tables)) {
        echo "<p style='color:orange'>⚠ Aucune table trouvée dans la base de données. Veuillez visiter <a href='/init_db.php'>init_db.php</a> pour initialiser la base de données.</p>";
    } else {
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";
    }

    // Vérification des données
    if (in_array('events', $tables)) {
        echo "<h3>Événements disponibles:</h3>";
        $events = $db->query("SELECT * FROM events")->fetchAll(PDO::FETCH_ASSOC);
        if (empty($events)) {
            echo "<p style='color:orange'>⚠ Aucun événement trouvé. Veuillez visiter <a href='/init_db.php'>init_db.php</a> pour ajouter des données de test.</p>";
        } else {
            echo "<table border='1' cellpadding='5'>";
            echo "<tr><th>ID</th><th>Titre</th><th>Date</th><th>Lieu</th><th>Prix</th><th>Billets disponibles</th></tr>";
            foreach ($events as $event) {
                echo "<tr>";
                echo "<td>{$event['id']}</td>";
                echo "<td>{$event['title']}</td>";
                echo "<td>{$event['date']}</td>";
                echo "<td>{$event['location']}</td>";
                echo "<td>{$event['price']} €</td>";
                echo "<td>{$event['available_tickets']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    }
} catch (PDOException $e) {
    echo "<p style='color:red'>✗ Erreur de connexion à la base de données: " . $e->getMessage() . "</p>";
}

// Test du chargement des vues
echo "<h2>2. Test de chargement des vues</h2>";
$viewsPath = __DIR__ . '/../app/views';
$views = [
    'layouts/main.php',
    'home/index.php',
    'user/login.php',
    'user/register.php'
];

foreach ($views as $view) {
    $fullPath = $viewsPath . '/' . $view;
    if (file_exists($fullPath)) {
        echo "<p style='color:green'>✓ Vue trouvée: $view</p>";
    } else {
        echo "<p style='color:red'>✗ Vue introuvable: $view</p>";
    }
}

// Test des contrôleurs
echo "<h2>3. Test de chargement des contrôleurs</h2>";
$controllersPath = __DIR__ . '/../app/controllers';
$controllers = [
    'HomeController.php',
    'UserController.php',
    'EventController.php',
    'ReservationController.php',
    'ReviewController.php'
];

foreach ($controllers as $controller) {
    $fullPath = $controllersPath . '/' . $controller;
    if (file_exists($fullPath)) {
        echo "<p style='color:green'>✓ Contrôleur trouvé: $controller</p>";
    } else {
        echo "<p style='color:red'>✗ Contrôleur introuvable: $controller</p>";
    }
}

// Test des ressources statiques
echo "<h2>4. Test des ressources statiques</h2>";
$cssPath = __DIR__ . '/css/styles.css';
$jsPath = __DIR__ . '/js/script.js';

if (file_exists($cssPath)) {
    echo "<p style='color:green'>✓ Fichier CSS trouvé: /css/styles.css</p>";
} else {
    echo "<p style='color:red'>✗ Fichier CSS introuvable: /css/styles.css</p>";
}

if (file_exists($jsPath)) {
    echo "<p style='color:green'>✓ Fichier JavaScript trouvé: /js/script.js</p>";
} else {
    echo "<p style='color:red'>✗ Fichier JavaScript introuvable: /js/script.js</p>";
}

// Liens utiles
echo "<h2>5. Liens utiles</h2>";
echo "<ul>";
echo "<li><a href='/' target='_blank'>Page d'accueil</a></li>";
echo "<li><a href='/login' target='_blank'>Page de connexion</a></li>";
echo "<li><a href='/register' target='_blank'>Page d'inscription</a></li>";
echo "<li><a href='/events' target='_blank'>Liste des événements</a></li>";
echo "<li><a href='/init_db.php' target='_blank'>Initialisation de la base de données</a></li>";
echo "</ul>";

// Information système
echo "<h2>6. Informations système</h2>";
echo "<p><strong>Version PHP:</strong> " . phpversion() . "</p>";
echo "<p><strong>Extensions PHP chargées:</strong></p>";
echo "<ul>";
$extensions = get_loaded_extensions();
sort($extensions);
foreach ($extensions as $extension) {
    echo "<li>$extension</li>";
}
echo "</ul>";

// Variables de session
echo "<h2>7. Variables de session</h2>";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Variables serveur
echo "<h2>8. Variables serveur</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Clé</th><th>Valeur</th></tr>";
foreach ($_SERVER as $key => $value) {
    if (is_string($value)) {
        echo "<tr><td>$key</td><td>" . htmlspecialchars($value) . "</td></tr>";
    }
}
echo "</table>";
