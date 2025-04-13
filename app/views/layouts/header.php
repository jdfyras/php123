<?php
// Check if user is logged in and is admin
$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : "Gestion d'Événements" ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/event_management/public/">Gestion d'Événements</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/event_management/public/events">Événements</a>
                    </li>
                    <?php if ($isAdmin): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                            Administration
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/event_management/public/admin/dashboard">Dashboard</a></li>
                            <li><a class="dropdown-item" href="/event_management/public/admin/users">Utilisateurs</a></li>
                            <li><a class="dropdown-item" href="/event_management/public/admin/events">Événements</a></li>
                            <li><a class="dropdown-item" href="/event_management/public/admin/reservations">Réservations</a></li>
                            <li><a class="dropdown-item" href="/event_management/public/admin/reviews">Avis</a></li>
                        </ul>
                    </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if ($isLoggedIn): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/event_management/public/user/profile">Mon Profil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/event_management/public/user/logout">Déconnexion</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/event_management/public/user/login">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/event_management/public/user/register">Inscription</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4"> 