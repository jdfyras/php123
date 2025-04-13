<style>
.hero-section {
    background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('/event_management/public/assets/images/hero-bg.jpg');
    background-size: cover;
    background-position: center;
    color: white;
    padding: 100px 0;
    margin-bottom: 3rem;
}

.hero-content {
    max-width: 800px;
    margin: 0 auto;
    text-align: center;
}

.hero-title {
    font-size: 3rem;
    font-weight: bold;
    margin-bottom: 1rem;
}

.hero-subtitle {
    font-size: 1.25rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.event-card {
    border: none;
    border-radius: 10px;
    overflow: hidden;
    transition: transform 0.3s;
    margin-bottom: 2rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.event-card:hover {
    transform: translateY(-5px);
}

.event-image {
    height: 200px;
    object-fit: cover;
}

.event-date {
    color: #6c757d;
    font-size: 0.9rem;
}

.event-price {
    font-weight: bold;
    color: #28a745;
}

.event-location {
    color: #6c757d;
    font-size: 0.9rem;
}

.features-section {
    background-color: #f8f9fa;
    padding: 4rem 0;
    margin: 3rem 0;
}

.feature-item {
    text-align: center;
    padding: 2rem;
}

.feature-icon {
    font-size: 2.5rem;
    color: #007bff;
    margin-bottom: 1rem;
}

.search-section {
    background-color: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin-bottom: 3rem;
}
</style>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <h1 class="hero-title">Découvrez des événements extraordinaires</h1>
        <p class="hero-subtitle">Réservez vos billets en ligne pour des événements inoubliables</p>
        <a href="#upcoming-events" class="btn btn-primary btn-lg">Voir nos événements</a>
    </div>
</section>

<!-- Search Section -->
<div class="container">
    <div class="search-section">
        <form class="row g-3 align-items-center">
            <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Rechercher un événement...">
            </div>
            <div class="col-md-3">
                <select class="form-select">
                    <option value="">Toutes les catégories</option>
                    <option value="concert">Concerts</option>
                    <option value="theatre">Théâtre</option>
                    <option value="sport">Sport</option>
                    <option value="exposition">Expositions</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Rechercher</button>
            </div>
        </form>
    </div>
</div>

<!-- Upcoming Events Section -->
<section id="upcoming-events" class="container">
    <h2 class="text-center mb-4">Événements à venir</h2>
    <div class="row">
        <?php foreach ($events as $event): ?>
        <div class="col-md-4">
            <div class="card event-card">
                <img src="<?php echo !empty($event['image']) ? htmlspecialchars($event['image']) : '/event_management/public/assets/images/event-placeholder.jpg'; ?>" 
                     class="card-img-top event-image" alt="<?php echo htmlspecialchars($event['title']); ?>">
                <div class="card-body">
                    <div class="event-date mb-2">
                        <i class="far fa-calendar-alt me-2"></i>
                        <?php echo date('d/m/Y H:i', strtotime($event['date'])); ?>
                    </div>
                    <h5 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars(substr($event['description'], 0, 100)) . '...'; ?></p>
                    <div class="event-location mb-3">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        <?php echo htmlspecialchars($event['location']); ?>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="event-price"><?php echo number_format($event['price'], 2); ?> €</span>
                        <a href="/event_management/public/events/details/<?php echo $event['id']; ?>" class="btn btn-outline-primary">Détails</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <h3>Réservation facile</h3>
                    <p>Réservez vos billets en quelques clics depuis chez vous.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Paiement sécurisé</h3>
                    <p>Vos transactions sont 100% sécurisées avec notre système de paiement.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>Support client</h3>
                    <p>Notre équipe est disponible pour répondre à toutes vos questions.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h3>Restez informé</h3>
            <p class="mb-4">Inscrivez-vous à notre newsletter pour ne manquer aucun événement</p>
            <form class="row g-3 justify-content-center">
                <div class="col-md-8">
                    <input type="email" class="form-control" placeholder="Votre adresse email">
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-primary">S'inscrire</button>
                </div>
            </form>
        </div>
    </div>
</section>