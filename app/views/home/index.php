<section class="hero">
    <div class="container">
        <h1>Découvrez des événements extraordinaires</h1>
        <p>Réservez vos billets en ligne pour des événements inoubliables</p>
        <a href="/events" class="btn btn-primary">Voir tous les événements</a>
    </div>
</section>

<section class="upcoming-events">
    <div class="container">
        <h2>Événements à venir</h2>

        <?php if (empty($upcomingEvents)): ?>
            <p>Aucun événement à venir pour le moment.</p>
        <?php else: ?>
            <div class="events-grid">
                <?php foreach ($upcomingEvents as $event): ?>
                    <div class="event-card">
                        <div class="event-date">
                            <?= date('d M Y', strtotime($event['date'])) ?>
                        </div>
                        <h3><?= htmlspecialchars($event['title']) ?></h3>
                        <p class="event-location"><?= htmlspecialchars($event['location']) ?></p>
                        <p class="event-description"><?= htmlspecialchars(substr($event['description'], 0, 100)) ?>...</p>
                        <div class="event-price"><?= number_format($event['price'], 2) ?> €</div>
                        <a href="/events/<?= $event['id'] ?>" class="btn btn-secondary">Détails</a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="view-all">
                <a href="/events">Voir tous les événements</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="features">
    <div class="container">
        <h2>Pourquoi nous choisir ?</h2>
        <div class="features-grid">
            <div class="feature">
                <h3>Réservation facile</h3>
                <p>Réservez vos billets en quelques clics depuis chez vous.</p>
            </div>
            <div class="feature">
                <h3>Paiement sécurisé</h3>
                <p>Vos transactions sont 100% sécurisées avec notre système de paiement.</p>
            </div>
            <div class="feature">
                <h3>Support client</h3>
                <p>Notre équipe est disponible pour répondre à toutes vos questions.</p>
            </div>
        </div>
    </div>
</section>