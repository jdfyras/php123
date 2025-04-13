<div class="events-container">
    <div class="events-header">
        <h1>Tous les événements</h1>
        <form action="/events" method="get" class="search-form">
            <input type="text" name="search" placeholder="Rechercher un événement..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </form>
    </div>

    <?php if (empty($events)): ?>
        <div class="empty-state">
            <p>Aucun événement trouvé.</p>
            <?php if (isset($_GET['search'])): ?>
                <p><a href="/events">Voir tous les événements</a></p>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="events-grid">
            <?php foreach ($events as $event): ?>
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
    <?php endif; ?>
</div>

<style>
    .events-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .search-form {
        display: flex;
        gap: 0.5rem;
    }

    .search-form input {
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: var(--border-radius);
        min-width: 250px;
    }

    .empty-state {
        text-align: center;
        padding: 2rem;
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
    }

    @media (max-width: 768px) {
        .events-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .search-form {
            width: 100%;
        }

        .search-form input {
            flex: 1;
        }
    }
</style>