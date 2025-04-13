<?php
// Ne pas définir le titre ici, il est déjà défini dans le contrôleur
// $title = "500 - Erreur serveur";
?>

<div class="error-container text-center">
    <h1 class="display-1">500</h1>
    <h2 class="mb-4">Erreur serveur</h2>
    <p class="lead mb-4">Désolé, une erreur inattendue s'est produite. Nos équipes techniques ont été notifiées.</p>
    <div class="error-actions">
        <a href="<?= url('') ?>" class="btn btn-primary btn-lg">
            <i class="fas fa-home"></i> Retour à l'accueil
        </a>
    </div>
</div>

<style>
.error-container {
    padding: 40px 0;
    max-width: 600px;
    margin: 0 auto;
}

.error-container h1 {
    font-size: 120px;
    color: #dc3545;
    margin-bottom: 20px;
}

.error-container h2 {
    color: #343a40;
}

.error-actions {
    margin-top: 30px;
}

.error-actions .btn {
    margin: 0 10px;
}
</style> 