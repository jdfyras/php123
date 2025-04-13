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

    <form action="/login" method="post" class="auth-form" id="login-form">
        <!-- Protection CSRF -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

        <div class="form-group">
            <label for="email">Adresse email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>

    <div class="auth-footer">
        <p>Vous n'avez pas de compte ? <a href="/register">Inscrivez-vous</a></p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('login-form');

        form.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            let hasError = false;
            let errorMessages = [];

            // Valider l'email
            if (!isValidEmail(email)) {
                errorMessages.push("L'adresse email n'est pas valide");
                hasError = true;
            }

            // Vérifier que le mot de passe n'est pas vide
            if (!password) {
                errorMessages.push("Le mot de passe est requis");
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