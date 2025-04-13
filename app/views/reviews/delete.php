<div class="form-container danger-container">
    <h1>Supprimer mon avis</h1>

    <div class="alert alert-danger">
        <p><strong>Attention !</strong> Vous êtes sur le point de supprimer votre avis pour l'événement "<?= htmlspecialchars($review['event_title']) ?>".</p>
        <p>Cette action est irréversible.</p>
    </div>

    <div class="review-preview">
        <h3>Votre avis</h3>
        <div class="review-rating">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <span class="star<?= $i <= $review['rating'] ? ' filled' : '' ?>">★</span>
            <?php endfor; ?>
        </div>
        <div class="review-content">
            <p><?= htmlspecialchars($review['comment']) ?></p>
            <p class="review-date">Posté le <?= date('d/m/Y', strtotime($review['created_at'])) ?></p>
        </div>
    </div>

    <form action="/reviews/delete/<?= $review['id'] ?>" method="post" class="confirm-form">
        <!-- Protection CSRF -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

        <div class="form-group">
            <label>
                <input type="checkbox" name="confirm" required>
                Je confirme vouloir supprimer cet avis
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-danger">Supprimer cet avis</button>
            <a href="/profile" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>