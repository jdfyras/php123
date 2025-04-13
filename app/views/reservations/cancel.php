<div class="form-container danger-container">
    <h1>Annuler ma réservation</h1>

    <div class="alert alert-warning">
        <p><strong>Attention !</strong> Vous êtes sur le point d'annuler votre réservation pour l'événement "<?= htmlspecialchars($reservation['event_title']) ?>".</p>
        <?php if ($reservation['status'] === 'payé'): ?>
            <p>Le montant de <?= number_format($reservation['total_price'], 2) ?> € vous sera remboursé selon les conditions générales de vente.</p>
        <?php endif; ?>
    </div>

    <div class="reservation-details">
        <h3>Détails de la réservation</h3>
        <p><strong>Événement:</strong> <?= htmlspecialchars($reservation['event_title']) ?></p>
        <p><strong>Nombre de billets:</strong> <?= $reservation['quantity'] ?></p>
        <p><strong>Prix total:</strong> <?= number_format($reservation['total_price'], 2) ?> €</p>
        <p><strong>Statut actuel:</strong> <?= ucfirst($reservation['status']) ?></p>
    </div>

    <form action="/reservations/<?= $reservation['id'] ?>/cancel" method="post" class="confirm-form">
        <!-- Protection CSRF -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

        <div class="form-group">
            <label>
                <input type="checkbox" name="confirm" required>
                Je confirme vouloir annuler cette réservation
            </label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-danger">Annuler la réservation</button>
            <a href="/profile" class="btn btn-secondary">Retour</a>
        </div>
    </form>
</div>

<style>
    .reservation-details {
        margin: 1.5rem 0;
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: var(--border-radius);
    }

    .reservation-details p {
        margin-bottom: 0.5rem;
    }
</style>