<?php
/* 
 * Vue pour la page d'inscription
 * Cette vue est chargée par le UserController, méthode register()
 */

// Ne pas définir le titre ici, il est déjà défini dans le contrôleur
// $title = "Inscription - Gestion d'Événements";

// Ne pas démarrer la capture du contenu ici, c'est fait dans le contrôleur
// ob_start();
?>

<div class="auth-container">
    <h1>Inscription</h1>

    <?php if (isset($errors) && !empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= url('register') ?>" method="post" class="auth-form" id="registrationForm">
        <!-- Protection CSRF -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

        <div class="form-group">
            <label for="firstname">Prénom</label>
            <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($_POST['firstname'] ?? '') ?>">
            <div id="firstname_error" class="error-message"></div>
        </div>

        <div class="form-group">
            <label for="lastname">Nom</label>
            <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($_POST['lastname'] ?? '') ?>">
            <div id="lastname_error" class="error-message"></div>
        </div>

        <div class="form-group">
            <label for="email">Adresse email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            <div id="email_error" class="error-message"></div>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password">
            <div id="password_error" class="error-message"></div>
            <small>Le mot de passe doit contenir au moins 8 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial</small>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirmation du mot de passe</label>
            <input type="password" id="confirm_password" name="confirm_password">
            <div id="confirm_password_error" class="error-message"></div>
        </div>

        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </form>

    <div class="auth-footer">
        <p>Vous avez déjà un compte ? <a href="<?= url('login') ?>">Connectez-vous</a></p>
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