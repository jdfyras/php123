<div class="form-container">
    <h1>Modifier mon avis pour "<?= htmlspecialchars($review['event_title']) ?>"</h1>

    <form action="/reviews/edit/<?= $review['id'] ?>" method="post" class="review-form validate-form">
        <!-- Protection CSRF -->
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

        <div class="form-group">
            <label>Votre note</label>
            <div class="rating-select">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <label>
                        <input type="radio" name="rating" value="<?= $i ?>" <?= (isset($_POST['rating']) ? $_POST['rating'] == $i : $review['rating'] == $i) ? 'checked' : '' ?> required>
                        <span class="star">★</span>
                    </label>
                <?php endfor; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="comment">Votre commentaire</label>
            <textarea id="comment" name="comment" rows="6" required><?= htmlspecialchars($_POST['comment'] ?? $review['comment']) ?></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Mettre à jour mon avis</button>
            <a href="/profile" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<style>
    .rating-select {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }

    .rating-select label {
        cursor: pointer;
        font-size: 2rem;
        padding: 0 0.1em;
        color: #ddd;
    }

    .rating-select input[type="radio"] {
        display: none;
    }

    .rating-select label:hover,
    .rating-select label:hover~label,
    .rating-select input[type="radio"]:checked~label {
        color: #f39c12;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ratingInputs = document.querySelectorAll('.rating-select input');
        ratingInputs.forEach(input => {
            input.addEventListener('change', function() {
                // Reset all stars
                document.querySelectorAll('.rating-select label').forEach(label => {
                    label.classList.remove('selected');
                });

                // Mark selected stars
                let currentValue = this.value;
                let labels = document.querySelectorAll('.rating-select label');
                for (let i = labels.length - 1; i >= labels.length - currentValue; i--) {
                    labels[i].classList.add('selected');
                }
            });
        });

        // Trigger change event on checked input to initialize stars
        const checkedInput = document.querySelector('.rating-select input:checked');
        if (checkedInput) {
            checkedInput.dispatchEvent(new Event('change'));
        }
    });
</script>