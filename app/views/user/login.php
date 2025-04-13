<?php
/* 
 * Vue pour la page de connexion
 * Cette vue est chargée par le UserController, méthode login()
 */

// Ne pas définir le titre ici, il est déjà défini dans le contrôleur
// $title = "Connexion - Gestion d'Événements";

// Ne pas démarrer la capture du contenu ici, c'est fait dans le contrôleur
// ob_start();
?>

<div class="auth-container">
    <h1>Connexion</h1>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- Message d'aide pour le débogage -->
    <?php if (ini_get('display_errors')): ?>
        <div class="alert alert-info">
            <p><strong>Conseil de connexion:</strong> Pour l'administrateur, utilisez:</p>
            <ul>
                <li>Email: admin@example.com</li>
                <li>Mot de passe: password123</li>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= url('login') ?>" method="post" class="auth-form" id="loginForm">
        <!-- Protection CSRF -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

        <div class="form-group">
            <label for="email">Adresse email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            <div id="email_error" class="error-message"></div>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password">
            <div id="password_error" class="error-message"></div>
        </div>

        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>

    <div class="auth-footer">
        <p>Vous n'avez pas de compte ? <a href="<?= url('register') ?>">Inscrivez-vous</a></p>
    </div>
</div>

<!-- Include validation script -->
<script src="<?= url('js/validation.js') ?>"></script>

<style>
.error-message {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: none;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group input {
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}

.form-group input:focus {
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

.form-group input.error {
    border-color: #dc3545;
}

.alert {
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 0.25rem;
}

.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
}

.alert-info {
    color: #0c5460;
    background-color: #d1ecf1;
    border: 1px solid #bee5eb;
}

.btn {
    display: inline-block;
    font-weight: 400;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    user-select: none;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}

.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    color: #fff;
    background-color: #0069d9;
    border-color: #0062cc;
}
</style>

<?php
// Ne pas terminer la capture du contenu ici, c'est fait dans le contrôleur
// $content = ob_get_clean();
?>