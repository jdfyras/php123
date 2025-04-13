<?php
$title = "Supprimer mon compte - Gestion d'Événements";
ob_start();
?>

<div class="form-container danger-container">
    <h1>Supprimer mon compte</h1>

    <div class="alert alert-danger">
        <p><strong>Attention !</strong> La suppression de votre compte est irréversible et entraînera les conséquences suivantes :</p>
        <ul>
            <li>Toutes vos données personnelles seront marquées comme supprimées dans notre système</li>
            <li>Vos réservations seront conservées pour des raisons légales, mais ne seront plus accessibles</li>
            <li>Vos avis seront conservés mais anonymisés</li>
            <li>Vous ne pourrez plus vous connecter avec vos identifiants actuels</li>
            <li><strong>Cette action est irréversible</strong></li>
        </ul>
    </div>

    <form action="/profile/delete" method="post" class="confirm-form" id="delete-form">
        <!-- Protection CSRF -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

        <div class="form-group">
            <label>
                <input type="checkbox" name="confirm" required>
                Je confirme vouloir supprimer définitivement mon compte
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-danger">Supprimer définitivement mon compte</button>
            <a href="/profile" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
/* Les vues ne sont pas censées charger le layout directement, c'est le contrôleur qui s'en charge */
/* require_once 'app/views/layouts/main.php'; */
?>