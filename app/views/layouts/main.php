<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Système de Gestion d\'Événements' ?></title>
    <link rel="stylesheet" href="/css/styles.css">
    <style>
        /* Styles d'urgence pour s'assurer que le contenu est visible */
        main {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 1200px;
            min-height: 400px;
        }

        .debug-info {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 10px;
            margin-top: 20px;
            font-family: monospace;
            white-space: pre-wrap;
        }

        /* Styles supplémentaires pour les formulaires */
        .auth-container {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 2rem auto;
            max-width: 500px;
            padding: 2rem;
        }

        .auth-container h1 {
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .auth-form {
            margin-bottom: 1.5rem;
        }

        .auth-footer {
            text-align: center;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <div class="logo">
                <a href="/">Gestion d'Événements</a>
            </div>
            <ul class="nav-links">
                <li><a href="/">Accueil</a></li>
                <li><a href="/events">Événements</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="/profile">Mon Profil</a></li>
                    <li><a href="/logout">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="/login">Connexion</a></li>
                    <li><a href="/register">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <?php
        // Vérifier et afficher le contenu principal
        if (isset($content) && !empty($content)) {
            echo $content;
        } else {
            echo '<div class="alert alert-warning">Aucun contenu à afficher. Vérifiez que la variable $content est correctement définie.</div>';
        }
        ?>

        <?php if (ini_get('display_errors')): ?>
            <div class="debug-info">
                <h3>Informations de débogage</h3>
                <strong>Route actuelle:</strong> <?= $_SERVER['REQUEST_URI'] ?><br>
                <strong>Contrôleur:</strong> <?= $GLOBALS['current_controller'] ?? ($currentController ?? ($controller ?? 'Non défini')) ?><br>
                <strong>Méthode:</strong> <?= $GLOBALS['current_method'] ?? ($currentMethod ?? ($method ?? 'Non définie')) ?><br>
                <?php if (isset($content)): ?>
                    <strong>Taille du contenu:</strong> <?= strlen($content) ?> caractères<br>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> - Système de Gestion d'Événements</p>
    </footer>

    <script src="/js/script.js"></script>
</body>

</html>