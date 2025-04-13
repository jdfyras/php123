<div class="event-detail-container">
    <div class="event-header">
        <h1><?= htmlspecialchars($event['title']) ?></h1>
        <div class="event-meta">
            <div class="event-date">
                <strong>Date:</strong> <?= date('d/m/Y H:i', strtotime($event['date'])) ?>
            </div>
            <div class="event-location">
                <strong>Lieu:</strong> <?= htmlspecialchars($event['location']) ?>
            </div>
            <div class="event-price">
                <strong>Prix:</strong> <?= number_format($event['price'], 2) ?> €
            </div>
            <div class="event-tickets">
                <strong>Billets disponibles:</strong> <?= number_format($event['available_tickets']) ?>
            </div>
            <?php if (count($reviews) > 0): ?>
                <div class="event-rating">
                    <strong>Note:</strong>
                    <div class="stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="star<?= $i <= round($averageRating) ? ' filled' : '' ?>">★</span>
                        <?php endfor; ?>
                    </div>
                    <span class="rating-value">(<?= number_format($averageRating, 1) ?>/5 - <?= count($reviews) ?> avis)</span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="event-content">
        <div class="event-description">
            <h2>Description</h2>
            <p><?= nl2br(htmlspecialchars($event['description'])) ?></p>
        </div>

        <div class="event-actions">
            <?php if ($event['available_tickets'] > 0): ?>
                <a href="/events/<?= $event['id'] ?>/book" class="btn btn-primary">Réserver</a>
            <?php else: ?>
                <button class="btn btn-primary" disabled>Complet</button>
            <?php endif; ?>
            <a href="/events" class="btn btn-secondary">Retour à la liste</a>
        </div>
    </div>

    <div class="event-reviews">
        <h2>Avis (<?= count($reviews) ?>)</h2>

        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if (!$userHasReviewed && $event['date'] < date('Y-m-d H:i:s')): ?>
                <div class="review-actions">
                    <a href="/events/<?= $event['id'] ?>/review" class="btn btn-secondary">Laisser un avis</a>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (empty($reviews)): ?>
            <p>Aucun avis pour le moment.</p>
        <?php else: ?>
            <div class="reviews-list">
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <div class="review-author">
                                <?= htmlspecialchars($review['firstname'] . ' ' . $review['lastname'][0] . '.') ?>
                            </div>
                            <div class="review-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="star<?= $i <= $review['rating'] ? ' filled' : '' ?>">★</span>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="review-content">
                            <p><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                            <p class="review-date">Posté le <?= date('d/m/Y', strtotime($review['created_at'])) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .event-detail-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .event-header {
        margin-bottom: 2rem;
    }

    .event-meta {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
        padding: 1.5rem;
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
    }

    .event-content {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .event-description {
        background-color: white;
        padding: 1.5rem;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
    }

    .event-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .event-reviews {
        background-color: white;
        padding: 1.5rem;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        margin-bottom: 2rem;
    }

    .review-actions {
        margin: 1.5rem 0;
    }

    .reviews-list {
        margin-top: 1.5rem;
    }

    .review-card {
        padding: 1rem;
        margin-bottom: 1rem;
        background-color: #f8f9fa;
        border-radius: var(--border-radius);
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    .review-author {
        font-weight: bold;
    }

    .stars {
        display: inline-block;
    }

    .star {
        color: #ddd;
    }

    .star.filled {
        color: #f39c12;
    }

    .review-date {
        margin-top: 0.5rem;
        font-size: 0.875rem;
        color: #6c757d;
    }

    @media (max-width: 768px) {
        .event-meta {
            grid-template-columns: 1fr;
        }
    }
</style>