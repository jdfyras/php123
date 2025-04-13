<?php
// Ne pas définir le titre ici, il est déjà défini dans le contrôleur
// $title = "Mon Profil - Gestion d'Événements";
// Ne pas démarrer la capture du contenu ici, c'est fait dans le contrôleur
// ob_start();
?>

<div class="profile-container">
    <h1>Mon profil</h1>

    <div class="profile-section">
        <h2>Informations personnelles</h2>
        <div class="profile-info">
            <p><strong>Nom :</strong> <?= htmlspecialchars($user['lastname']) ?></p>
            <p><strong>Prénom :</strong> <?= htmlspecialchars($user['firstname']) ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Compte créé le :</strong> <?= date('d/m/Y', strtotime($user['created_at'])) ?></p>
        </div>
        <div class="profile-actions">
            <a href="/profile/update" class="btn btn-secondary">Modifier mes informations</a>
            <a href="/profile/change-password" class="btn btn-secondary">Changer mon mot de passe</a>
        </div>
    </div>

    <div class="profile-section">
        <h2>Mes réservations</h2>
        <?php
        // Vérifier que $db existe
        if (isset($db)) {
            // Récupérer les réservations de l'utilisateur
            $reservationsStmt = $db->prepare("
                SELECT r.*, e.title as event_title, e.date as event_date 
                FROM reservations r
                JOIN events e ON r.event_id = e.id
                WHERE r.user_id = ?
                ORDER BY r.created_at DESC
            ");
            $reservationsStmt->execute([$user['id']]);
            $reservations = $reservationsStmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "<div class='alert alert-warning'>Impossible de charger les réservations.</div>";
            $reservations = [];
        }
        ?>

        <?php if (empty($reservations)): ?>
            <p>Vous n'avez pas encore effectué de réservation.</p>
        <?php else: ?>
            <div class="reservations-list">
                <?php foreach ($reservations as $reservation): ?>
                    <div class="reservation-card">
                        <div class="reservation-header">
                            <h3><?= htmlspecialchars($reservation['event_title']) ?></h3>
                            <span class="reservation-status status-<?= $reservation['status'] ?>"><?= ucfirst($reservation['status']) ?></span>
                        </div>
                        <div class="reservation-details">
                            <p><strong>Date de l'événement :</strong> <?= date('d/m/Y H:i', strtotime($reservation['event_date'])) ?></p>
                            <p><strong>Quantité :</strong> <?= $reservation['quantity'] ?> billet(s)</p>
                            <p><strong>Prix total :</strong> <?= number_format($reservation['total_price'], 2) ?> €</p>
                            <p><strong>Réservé le :</strong> <?= date('d/m/Y', strtotime($reservation['created_at'])) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="profile-section">
        <h2>Mes avis</h2>
        <?php
        // Vérifier que $db existe
        if (isset($db)) {
            // Récupérer les avis de l'utilisateur
            $reviewsStmt = $db->prepare("
                SELECT r.*, e.title as event_title
                FROM reviews r
                JOIN events e ON r.event_id = e.id
                WHERE r.user_id = ?
                ORDER BY r.created_at DESC
            ");
            $reviewsStmt->execute([$user['id']]);
            $reviews = $reviewsStmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "<div class='alert alert-warning'>Impossible de charger les avis.</div>";
            $reviews = [];
        }
        ?>

        <?php if (empty($reviews)): ?>
            <p>Vous n'avez pas encore posté d'avis.</p>
        <?php else: ?>
            <div class="reviews-list">
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <h3><?= htmlspecialchars($review['event_title']) ?></h3>
                            <div class="review-rating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="star<?= $i <= $review['rating'] ? ' filled' : '' ?>">★</span>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="review-content">
                            <p><?= htmlspecialchars($review['comment']) ?></p>
                            <p class="review-date">Posté le <?= date('d/m/Y', strtotime($review['created_at'])) ?></p>
                        </div>
                        <div class="review-actions">
                            <a href="/reviews/edit/<?= $review['id'] ?>" class="btn btn-sm btn-secondary">Modifier</a>
                            <a href="/reviews/delete/<?= $review['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet avis ?')">Supprimer</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="profile-section danger-zone">
        <h2>Zone danger</h2>
        <p>Attention, ces actions sont irréversibles.</p>
        <div class="danger-actions">
            <a href="/profile/deactivate" class="btn btn-warning">Désactiver mon compte</a>
            <a href="/profile/delete" class="btn btn-danger">Supprimer mon compte</a>
        </div>
    </div>
</div>

<?php
// Ne pas terminer la capture du contenu ici, c'est fait dans le contrôleur
// $content = ob_get_clean();
?>