<?php
$title = "Modifier mon profil - Gestion d'Événements";
ob_start();
?>

<div class="form-container">
    <h1>Modifier mon profil</h1>

    <form action="/profile/update" method="post" class="profile-form" id="update-profile-form">
        <!-- Protection CSRF -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

        <div class="form-group">
            <label for="firstname">Prénom</label>
            <input type="text" id="firstname" name="firstname" value="<?= htmlspecialchars($_POST['firstname'] ?? $user['firstname']) ?>" required>
        </div>

        <div class="form-group">
            <label for="lastname">Nom</label>
            <input type="text" id="lastname" name="lastname" value="<?= htmlspecialchars($_POST['lastname'] ?? $user['lastname']) ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Adresse email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? $user['email']) ?>" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            <a href="/profile" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('update-profile-form');

        form.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const firstname = document.getElementById('firstname').value;
            const lastname = document.getElementById('lastname').value;
            let hasError = false;
            let errorMessages = [];

            // Valider l'email
            if (!isValidEmail(email)) {
                errorMessages.push("L'adresse email n'est pas valide");
                hasError = true;
            }

            // Vérifier que le prénom n'est pas vide
            if (!firstname.trim()) {
                errorMessages.push("Le prénom est requis");
                hasError = true;
            }

            // Vérifier que le nom n'est pas vide
            if (!lastname.trim()) {
                errorMessages.push("Le nom est requis");
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
$content = ob_get_clean();
/* Les vues ne sont pas censées charger le layout directement, c'est le contrôleur qui s'en charge */
/* require_once 'app/views/layouts/main.php'; */
?>