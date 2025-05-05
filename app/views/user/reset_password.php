<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Nouveau mot de passe</h2>
                    <?php if (!empty($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                    <?php if (!empty($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                    <?php endif; ?>
                    <form method="post" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" 
                                required minlength="8" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$">
                            <div class="invalid-feedback">
                                Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.
                            </div>
                            <div class="form-text">
                                Le mot de passe doit contenir :
                                <ul class="mb-0">
                                    <li>Au moins 8 caractères</li>
                                    <li>Une lettre majuscule</li>
                                    <li>Une lettre minuscule</li>
                                    <li>Un chiffre</li>
                                    <li>Un caractère spécial (@$!%*?&)</li>
                                </ul>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            <div class="invalid-feedback">
                                Les mots de passe ne correspondent pas.
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Réinitialiser le mot de passe</button>
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
            const password = form.querySelector('#new_password').value;
            const confirmPassword = form.querySelector('#confirm_password').value;
            
            // Check password match
            if (password !== confirmPassword) {
                form.querySelector('#confirm_password').setCustomValidity('Les mots de passe ne correspondent pas');
            } else {
                form.querySelector('#confirm_password').setCustomValidity('');
            }

            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            
            form.classList.add('was-validated')
        }, false)
    })

    // Real-time password confirmation validation
    document.querySelector('#confirm_password').addEventListener('input', function(e) {
        const password = document.querySelector('#new_password').value;
        if (this.value !== password) {
            this.setCustomValidity('Les mots de passe ne correspondent pas');
        } else {
            this.setCustomValidity('');
        }
    });

    // Real-time password strength validation
    document.querySelector('#new_password').addEventListener('input', function(e) {
        const confirmPassword = document.querySelector('#confirm_password');
        if (confirmPassword.value) {
            if (this.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Les mots de passe ne correspondent pas');
            } else {
                confirmPassword.setCustomValidity('');
            }
        }
    });
})()
</script> 