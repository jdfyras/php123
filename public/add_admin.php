<?php
// Affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// En-tête
echo "<h1>Création d'un nouvel administrateur</h1>";

// Connexion à la base de données
try {
    $config = require __DIR__ . '/../app/config/database.php';

    // Connexion à la base de données
    $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
    $pdo = new PDO($dsn, $config['username'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<p style='color:green'>✓ Connexion à la base de données réussie.</p>";

    // Définir les informations du nouvel administrateur
    $firstname = "TestAdmin";
    $lastname = "User";
    $email = "testadmin@example.com";
    $password = "admin123";
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Vérifier que l'email n'existe pas déjà
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        echo "<p style='color:orange'>⚠ Un utilisateur avec l'email $email existe déjà. Un nouvel administrateur ne sera pas créé.</p>";
    } else {
        // Insérer le nouvel administrateur
        $stmt = $pdo->prepare("
            INSERT INTO users (firstname, lastname, email, password_hash, role, status, created_at)
            VALUES (?, ?, ?, ?, 'admin', 'actif', NOW())
        ");
        $stmt->execute([$firstname, $lastname, $email, $password_hash]);

        echo "<p style='color:green'>✓ Nouvel administrateur créé avec succès.</p>";

        // Afficher les informations de connexion
        echo "<div style='background-color: #f0f0f0; padding: 15px; border: 1px solid #ddd; margin-top: 20px;'>";
        echo "<h2>Informations de connexion</h2>";
        echo "<p><strong>Email:</strong> $email</p>";
        echo "<p><strong>Mot de passe:</strong> $password</p>";
        echo "<p><strong>Rôle:</strong> Administrateur</p>";
        echo "</div>";
    }

    echo "<p><a href='/login'>Aller à la page de connexion</a></p>";
    echo "<p><a href='/'>Retour à l'accueil</a></p>";
} catch (PDOException $e) {
    echo "<h2>Erreur</h2>";
    echo "<p style='color:red'>Une erreur est survenue : " . $e->getMessage() . "</p>";
}
