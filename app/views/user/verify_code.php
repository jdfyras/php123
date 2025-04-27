<div class="container mt-5">
    <h2>Vérification du code</h2>
    <p>Un code de vérification a été envoyé à votre adresse email. Veuillez le saisir ci-dessous pour valider votre inscription.</p>
    <?php if (isset(
        $_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="form-group">
            <label for="verification_code">Code de vérification</label>
            <input type="text" class="form-control" id="verification_code" name="verification_code" required>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Vérifier</button>
    </form>
</div> 