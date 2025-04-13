<?php
$title = "Changer mon mot de passe - Gestion d'Événements";
ob_start();
?>

<div class="form-container">
    <h1>Changer mon mot de passe</h1>

    <form action="/profile/change-password" method="post" class="profile-form" id="change-password-form">
        <!-- Protection CSRF -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

        <div class="form-group">
            <label for="current_password">Mot de passe actuel</label>
            <input type="password" id="current_password" name="current_password" required>
        </div>

        <div class="form-group">
            <label for="new_password">Nouveau mot de passe</label>
            <input type="password" id="new_password" name="new_password" required>
            <small>Le mot de passe doit contenir au moins 8 caractères</small>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirmation du nouveau mot de passe</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Changer mon mot de passe</button>
            <a href="/profile" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('change-password-form');

        form.addEventListener('submit', function(e) {
            const currentPassword = document.getElementById('current_password').value;
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            let hasError = false;
            let errorMessages = [];

            // Vérifier que le mot de passe actuel n'est pas vide
            if (!currentPassword) {
                errorMessages.push("Le mot de passe actuel est requis");
                hasError = true;
            }

            // Valider la longueur du nouveau mot de passe
            if (newPassword.length < 8) {
                errorMessages.push("Le nouveau mot de passe doit contenir au moins 8 caractères");
                hasError = true;
            }

            // Vérifier que les mots de passe correspondent
            if (newPassword !== confirmPassword) {
                errorMessages.push("Les nouveaux mots de passe ne correspondent pas");
                hasError = true;
            }

            if (hasError) {
                e.preventDefault();
                alert(errorMessages.join("\n"));
            }
        });
    });
</script>

<?php
$content = ob_get_clean();
/* Les vues ne sont pas censées charger le layout directement, c'est le contrôleur qui s'en charge */
/* require_once 'app/views/layouts/main.php'; */
?>