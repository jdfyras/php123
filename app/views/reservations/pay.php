<div class="form-container">
    <h1>Paiement</h1>

    <div class="reservation-summary">
        <h2>Récapitulatif de votre réservation</h2>
        <div class="summary-details">
            <p><strong>Événement:</strong> <?= htmlspecialchars($reservation['event_title']) ?></p>
            <p><strong>Date:</strong> <?= date('d/m/Y H:i', strtotime($reservation['event_date'])) ?></p>
            <p><strong>Nombre de billets:</strong> <?= $reservation['quantity'] ?></p>
            <p><strong>Prix unitaire:</strong> <?= number_format($reservation['price'], 2) ?> €</p>
            <p><strong>Prix total:</strong> <span class="total-price"><?= number_format($reservation['total_price'], 2) ?> €</span></p>
        </div>
    </div>

    <div class="payment-section">
        <h2>Informations de paiement</h2>
        <form action="/reservations/<?= $reservation['id'] ?>/pay" method="post" class="payment-form validate-form">
            <!-- Protection CSRF -->
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

            <div class="form-group">
                <label for="card_number">Numéro de carte</label>
                <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" required pattern="[0-9\s]{13,19}">
                <small>16 chiffres sans espaces</small>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="expiry_date">Date d'expiration</label>
                    <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/AA" required pattern="(0[1-9]|1[0-2])\/[0-9]{2}">
                </div>

                <div class="form-group">
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" name="cvv" placeholder="123" required pattern="[0-9]{3,4}">
                </div>
            </div>

            <div class="form-group">
                <label for="card_holder">Nom du titulaire</label>
                <input type="text" id="card_holder" name="card_holder" placeholder="Prénom Nom" required>
            </div>

            <div class="payment-actions">
                <button type="submit" class="btn btn-primary">Payer <?= number_format($reservation['total_price'], 2) ?> €</button>
                <a href="/profile" class="btn btn-secondary">Annuler</a>
            </div>

            <div class="payment-note">
                <p><small>Note: Ceci est une simulation de paiement. Aucune vraie transaction ne sera effectuée.</small></p>
            </div>
        </form>
    </div>
</div>

<style>
    .reservation-summary {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
    }

    .summary-details {
        margin-top: 1rem;
    }

    .summary-details p {
        margin-bottom: 0.5rem;
    }

    .total-price {
        font-weight: bold;
        color: var(--primary-color);
        font-size: 1.1em;
    }

    .payment-section {
        padding: 1.5rem;
        background-color: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
    }

    .form-row {
        display: flex;
        gap: 1rem;
    }

    .form-row .form-group {
        flex: 1;
    }

    .payment-actions {
        margin-top: 1.5rem;
    }

    .payment-note {
        margin-top: 1rem;
        color: #6c757d;
        text-align: center;
    }

    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
            gap: 0;
        }
    }
</style>