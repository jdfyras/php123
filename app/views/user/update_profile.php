<?php
// Ne pas définir le titre ici, il est déjà défini dans le contrôleur
// $title = "Modifier mon profil - Gestion d'Événements";
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1 class="h4 mb-0">Modifier mon profil</h1>
                </div>
                <div class="card-body">
                    <form action="<?= url('profile/update') ?>" method="post" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="firstname" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="firstname" name="firstname" 
                                value="<?= htmlspecialchars($user['firstname']) ?>" required>
                            <div class="invalid-feedback">
                                Le prénom est requis
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="lastname" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="lastname" name="lastname" 
                                value="<?= htmlspecialchars($user['lastname']) ?>" required>
                            <div class="invalid-feedback">
                                Le nom est requis
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                value="<?= htmlspecialchars($user['email']) ?>" required>
                            <div class="invalid-feedback">
                                L'email est requis et doit être valide
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                            <a href="<?= url('profile') ?>" class="btn btn-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Example starter JavaScript for disabling form submissions if there are invalid fields
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

                form.classList.add('was-validated')
            }, false)
        })
})()
</script>