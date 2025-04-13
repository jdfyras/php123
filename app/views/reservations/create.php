<div class="form-container">
    <h1>Réserver - <?= htmlspecialchars($event['title']) ?></h1>

    <div class="event-info">
        <p><strong>Date:</strong> <?= date('d/m/Y H:i', strtotime($event['date'])) ?></p>
        <p><strong>Lieu:</strong> <?= htmlspecialchars($event['location']) ?></p>
        <p><strong>Prix unitaire:</strong> <?= number_format($event['price'], 2) ?> €</p>
        <p><strong>Billets disponibles:</strong> <?= number_format($event['available_tickets']) ?></p>
    </div>

    <form action="/events/<?= $event['id'] ?>/book" method="post" class="booking-form validate-form">
        <!-- Protection CSRF -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

        <div class="form-group">
            <label for="quantity">Nombre de billets</label>
            <input type="number" id="quantity" name="quantity" min="1" max="<?= $event['available_tickets'] ?>" value="<?= $_POST['quantity'] ?? 1 ?>" required>
            <small>Maximum <?= $event['available_tickets'] ?> billet(s)</small>
        </div>

        <div class="price-calculation">
            <p>Prix total: <span id="total-price"><?= number_format($event['price'], 2) ?></span> €</p>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Continuer vers le paiement</button>
            <a href="/events/<?= $event['id'] ?>" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('quantity');
        const totalPriceElement = document.getElementById('total-price');
        const unitPrice = <?= $event['price'] ?>;

        function updateTotal() {
            const quantity = parseInt(quantityInput.value);
            const total = (quantity * unitPrice).toFixed(2);
            totalPriceElement.textContent = total;
        }

        quantityInput.addEventListener('change', updateTotal);
        quantityInput.addEventListener('input', updateTotal);

        // Initialiser
        updateTotal();
    });
</script>

<style>
    .event-info {
        margin: 1.5rem 0;
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: var(--border-radius);
    }

    .event-info p {
        margin-bottom: 0.5rem;
    }

    .price-calculation {
        margin: 1.5rem 0;
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: var(--border-radius);
        font-weight: bold;
    }
</style>