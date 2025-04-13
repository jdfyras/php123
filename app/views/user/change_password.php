<?php
// Ne pas définir le titre ici, il est déjà défini dans le contrôleur
// $title = "Changer mon mot de passe - Gestion d'Événements";
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1 class="h4 mb-0">Changer mon mot de passe</h1>
                </div>
                <div class="card-body">
                    <form action="<?= url('profile/change-password') ?>" method="post" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mot de passe actuel</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                            <div class="invalid-feedback">
                                Le mot de passe actuel est requis
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" 
                                required minlength="8">
                            <div class="invalid-feedback">
                                Le nouveau mot de passe doit contenir au moins 8 caractères
                            </div>
                            <div class="form-text">
                                Le mot de passe doit contenir au moins 8 caractères
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmer le nouveau mot de passe</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            <div class="invalid-feedback">
                                Les mots de passe ne correspondent pas
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Changer le mot de passe</button>
                            <a href="<?= url('profile') ?>" class="btn btn-secondary">Annuler</a>
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

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                // Check if passwords match
                const newPassword = form.querySelector('#new_password').value;
                const confirmPassword = form.querySelector('#confirm_password').value;
                
                if (newPassword !== confirmPassword) {
                    event.preventDefault();
                    form.querySelector('#confirm_password').setCustomValidity('Les mots de passe ne correspondent pas');
                } else {
                    form.querySelector('#confirm_password').setCustomValidity('');
                }

                form.classList.add('was-validated')
            }, false)
        })
})()
</script>