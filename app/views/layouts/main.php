<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Gestion d\'Événements' ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
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

        .user-profile-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: rgba(255, 255, 255, .75);
            text-decoration: none;
            padding: 0.5rem 1rem;
        }

        .user-profile-link:hover {
            color: rgba(255, 255, 255, 1);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <?php if (!in_array($currentPage ?? '', ['login', 'register'])): ?>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="<?= url('') ?>">Gestion d'Événements</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('') ?>">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('events') ?>">Événements</a>
                        </li>
                        <?php if (isLoggedIn()): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= url('reservations') ?>">Mes Réservations</a>
                            </li>
                            <?php if (isAdmin()): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= url('admin/dashboard') ?>">Admin Dashboard</a>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                    </ul>
                    <ul class="navbar-nav">
                        <?php if (isLoggedIn()): ?>
                            <li class="nav-item">
                                <a href="<?= url('profile') ?>" class="user-profile-link">
                                    <div class="user-avatar">
                                        <?= strtoupper(substr($_SESSION['user_firstname'] ?? 'U', 0, 1)) ?>
                                    </div>
                                    <span><?= htmlspecialchars($_SESSION['user_firstname'] ?? 'Utilisateur') ?></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= url('logout') ?>">Déconnexion</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= url('login') ?>">Connexion</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= url('register') ?>">Inscription</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    <?php endif; ?>

    <main class="container py-4">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?= $content ?? '' ?>
    </main>

    <footer class="bg-dark text-white mt-5 py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Gestion d'Événements</h5>
                    <p>Plateforme de gestion et réservation d'événements</p>
                </div>
                <div class="col-md-6 text-end">
                    <p>&copy; <?php echo date('Y'); ?> Tous droits réservés</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="<?= url('js/script.js') ?>"></script>

    <!-- Place before </body> -->
    <script>
        var botmanWidget = {
            frameEndpoint: '/event_management/public/botman-widget.html',
            chatServer: '/event_management/public/botman',
            title: 'Event Assistant',
            introMessage: '👋 Hi! Type \"hello\" to start.',
            mainColor: '#456765',
            bubbleBackground: '#ff76a3',
            aboutText: 'Event Management Chatbot'
        };
    </script>
    <script src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/widget.js'></script>
</body>

</html>