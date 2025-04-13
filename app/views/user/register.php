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

    <form action="/register" method="post" class="auth-form" id="register-form">
        <!-- Protection CSRF -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

        <div class="form-group">
            <label for="firstname">Prénom</label>
            <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($_POST['firstname'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="lastname">Nom</label>
            <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($_POST['lastname'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Adresse email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
            <small>Le mot de passe doit contenir au moins 8 caractères</small>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirmation du mot de passe</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>

        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </form>

    <div class="auth-footer">
        <p>Vous avez déjà un compte ? <a href="/login">Connectez-vous</a></p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('register-form');

        form.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const email = document.getElementById('email').value;
            let hasError = false;
            let errorMessages = [];

            // Valider l'email
            if (!isValidEmail(email)) {
                errorMessages.push("L'adresse email n'est pas valide");
                hasError = true;
            }

            // Valider la longueur du mot de passe
            if (password.length < 8) {
                errorMessages.push("Le mot de passe doit contenir au moins 8 caractères");
                hasError = true;
            }

            // Vérifier que les mots de passe correspondent
            if (password !== confirmPassword) {
                errorMessages.push("Les mots de passe ne correspondent pas");
                hasError = true;
            }

            if (hasError) {
                e.preventDefault();
                alert(errorMessages.join("\n"));
            }
        });

        function isValidEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
    });
</script>

<?php
// Ne pas terminer la capture du contenu ici, c'est fait dans le contrôleur
// $content = ob_get_clean();
?>