<?php
$title = "Désactiver mon compte - Gestion d'Événements";
ob_start();
?>

<div class="form-container danger-container">
    <h1>Désactiver mon compte</h1>

    <div class="alert alert-warning">
        <p><strong>Attention !</strong> La désactivation de votre compte entraînera les conséquences suivantes :</p>
        <ul>
            <li>Vous ne pourrez plus vous connecter avec vos identifiants actuels</li>
            <li>Vos réservations seront conservées, mais vous ne pourrez plus y accéder</li>
            <li>Vous pourrez réactiver votre compte en contactant notre support</li>
        </ul>
    </div>

    <form action="/profile/deactivate" method="post" class="confirm-form" id="deactivate-form">
        <!-- Protection CSRF -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

        <div class="form-group">
            <label>
                <input type="checkbox" name="confirm" required>
                Je confirme vouloir désactiver mon compte
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-warning">Désactiver mon compte</button>
            <a href="/profile" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
/* Les vues ne sont pas censées charger le layout directement, c'est le contrôleur qui s'en charge */
/* require_once 'app/views/layouts/main.php'; */
?>