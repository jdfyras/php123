<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Réinitialiser le mot de passe</h2>
                    <?php if (!empty($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                    <?php endif; ?>
                    <form method="post" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback">
                                Veuillez entrer une adresse email valide.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Captcha</label>
                            <div class="mb-2">
                                <img src="data:image/png;base64,<?= CaptchaHelper::generateCaptcha() ?>" alt="Captcha" class="mb-2">
                            </div>
                            <input type="text" name="captcha" class="form-control" required placeholder="Entrez le code ci-dessus">
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Envoyer le lien de réinitialisation</button>
                            <a href="<?= url('login') ?>" class="btn btn-link">Retour à la connexion</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script> 