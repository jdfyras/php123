<?php
// Affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// En-tête
echo "<h1>Initialisation de la base de données</h1>";

// Connexion à la base de données
try {
    $config = require __DIR__ . '/../app/config/database.php';

    // Connexion sans sélectionner la base de données pour pouvoir la créer
    $pdo = new PDO("mysql:host={$config['host']};charset={$config['charset']}", $config['username'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Création de la base de données
    $sql = "CREATE DATABASE IF NOT EXISTS `{$config['dbname']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $pdo->exec($sql);
    echo "<p style='color:green'>✓ Base de données '{$config['dbname']}' créée ou déjà existante.</p>";

    // Sélection de la base de données
    $pdo->exec("USE `{$config['dbname']}`");

    // Chargement du script SQL pour la structure
    $sqlFile = file_get_contents(__DIR__ . '/../app/config/database.sql');
    $queries = explode(';', $sqlFile);

    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            $pdo->exec($query);
        }
    }
    echo "<p style='color:green'>✓ Structure de la base de données créée avec succès.</p>";

    // Vérifier si les tables sont vides avant d'ajouter les données de test
    $tables = [
        'users' => 'Utilisateurs',
        'events' => 'Événements',
        'reservations' => 'Réservations',
        'reviews' => 'Avis'
    ];

    $emptyTables = [];
    foreach ($tables as $table => $name) {
        $query = $pdo->query("SELECT COUNT(*) FROM $table");
        $count = $query->fetchColumn();
        if ($count == 0) {
            $emptyTables[] = $table;
        }
    }

    // Ajouter des données de test si les tables sont vides
    if (!empty($emptyTables)) {
        echo "<p>Ajout de données de test...</p>";

        // Ajouter des utilisateurs
        if (in_array('users', $emptyTables)) {
            // Créer un mot de passe administrateur et vérifier qu'il est correctement haché
            $admin_password = 'password123';
            $admin_password_hash = password_hash($admin_password, PASSWORD_DEFAULT);

            // Vérifier que le hachage fonctionne
            if (!password_verify($admin_password, $admin_password_hash)) {
                echo "<p style='color:red'>ERREUR: Le hachage de mot de passe ne fonctionne pas correctement!</p>";
            }

            $users = [
                ["John", "Doe", "john@example.com", password_hash('password123', PASSWORD_DEFAULT), 'user', 'actif'],
                ["Jane", "Smith", "jane@example.com", password_hash('password123', PASSWORD_DEFAULT), 'user', 'actif'],
                ["Admin", "User", "admin@example.com", $admin_password_hash, 'admin', 'actif']
            ];

            // Afficher les informations pour le débogage
            echo "<p>Info de débogage - Utilisateur admin créé:</p>";
            echo "<ul>";
            echo "<li><strong>Email:</strong> admin@example.com</li>";
            echo "<li><strong>Mot de passe:</strong> password123</li>";
            echo "<li><strong>Hachage généré:</strong> " . substr($admin_password_hash, 0, 20) . "...</li>";
            echo "</ul>";

            $stmt = $pdo->prepare("
                INSERT INTO users (firstname, lastname, email, password_hash, role, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");

            foreach ($users as $user) {
                $stmt->execute($user);
            }
            echo "<p style='color:green'>✓ Utilisateurs de test ajoutés.</p>";
        }

        // Ajouter des événements
        if (in_array('events', $emptyTables)) {
            $events = [
                ["Concert de jazz", "Venez profiter d'une soirée jazz exceptionnelle avec des artistes de renommée internationale.", "2025-05-15 20:00:00", "Salle Pleyel, Paris", 45.00, 200],
                ["Exposition d'art contemporain", "Une exposition mettant en valeur les œuvres des artistes contemporains les plus en vue.", "2025-06-10 10:00:00", "Galerie Moderne, Lyon", 12.50, 500],
                ["Festival de la gastronomie", "Découvrez les saveurs de la cuisine française avec des chefs étoilés.", "2025-07-20 11:00:00", "Place des Festivals, Marseille", 30.00, 1000],
                ["Conférence sur l'intelligence artificielle", "Une journée de conférences sur les dernières avancées en IA.", "2025-05-25 09:00:00", "Centre des congrès, Lille", 20.00, 300],
                ["Match de football caritatif", "Un match opposant d'anciennes gloires du football français pour une bonne cause.", "2025-06-05 15:00:00", "Stade Municipal, Bordeaux", 25.00, 5000]
            ];

            $stmt = $pdo->prepare("
                INSERT INTO events (title, description, date, location, price, available_tickets, created_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");

            foreach ($events as $event) {
                $stmt->execute($event);
            }
            echo "<p style='color:green'>✓ Événements de test ajoutés.</p>";
        }

        // Ajouter des réservations
        if (in_array('reservations', $emptyTables) && !in_array('users', $emptyTables) && !in_array('events', $emptyTables)) {
            $reservations = [
                [1, 1, 2, 90.00, 'payé'],
                [1, 3, 4, 120.00, 'payé'],
                [2, 2, 1, 12.50, 'en attente'],
                [2, 4, 2, 40.00, 'payé'],
                [3, 5, 3, 75.00, 'annulé']
            ];

            $stmt = $pdo->prepare("
                INSERT INTO reservations (user_id, event_id, quantity, total_price, status, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");

            foreach ($reservations as $reservation) {
                $stmt->execute($reservation);
            }
            echo "<p style='color:green'>✓ Réservations de test ajoutées.</p>";
        }

        // Ajouter des avis
        if (in_array('reviews', $emptyTables) && !in_array('users', $emptyTables) && !in_array('events', $emptyTables)) {
            $reviews = [
                [1, 1, 5, "Excellent concert, j'ai adoré l'ambiance !"],
                [2, 1, 4, "Très bonne soirée, mais les sièges n'étaient pas très confortables."],
                [1, 3, 5, "Un festival incroyable, j'y retournerai l'année prochaine !"],
                [2, 2, 3, "Exposition intéressante mais un peu chère pour ce que c'est."],
                [3, 4, 4, "Conférence très enrichissante, des intervenants de qualité."]
            ];

            $stmt = $pdo->prepare("
                INSERT INTO reviews (user_id, event_id, rating, comment, created_at)
                VALUES (?, ?, ?, ?, NOW())
            ");

            foreach ($reviews as $review) {
                $stmt->execute($review);
            }
            echo "<p style='color:green'>✓ Avis de test ajoutés.</p>";
        }
    } else {
        echo "<p style='color:orange'>⚠ Les tables contiennent déjà des données. Aucune donnée de test n'a été ajoutée.</p>";
    }

    echo "<h2>Opération terminée</h2>";
    echo "<p>La base de données a été initialisée avec succès.</p>";
    echo "<p><a href='/custom_debug.php'>Retour à la page de débogage</a></p>";
    echo "<p><a href='/'>Aller à la page d'accueil</a></p>";
} catch (PDOException $e) {
    echo "<h2>Erreur</h2>";
    echo "<p style='color:red'>Une erreur est survenue : " . $e->getMessage() . "</p>";
}
