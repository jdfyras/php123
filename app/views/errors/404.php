<?php
// Ne pas définir le titre ici, il est déjà défini dans le contrôleur
// $title = "404 - Page non trouvée";
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="display-1">404</h1>
            <h2 class="mb-4">Page non trouvée</h2>
            <p class="lead mb-4">La page que vous recherchez n'existe pas ou a été déplacée.</p>
            <a href="<?= url('') ?>" class="btn btn-primary">Retour à l'accueil</a>
        </div>
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