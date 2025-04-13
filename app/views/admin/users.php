<style>
.dashboard-sidebar {
    min-height: calc(100vh - 56px);
    background-color: #f8f9fa;
    border-right: 1px solid #dee2e6;
    padding-top: 1rem;
}
.dashboard-sidebar .nav-link {
    color: #333;
    padding: 0.5rem 1rem;
    border-radius: 0.25rem;
    margin: 0.2rem 0;
}
.dashboard-sidebar .nav-link:hover {
    background-color: #e9ecef;
}
.dashboard-sidebar .nav-link.active {
    color: #fff;
    background-color: #007bff;
}
.dashboard-sidebar .nav-link i {
    margin-right: 0.5rem;
    width: 1.25rem;
    text-align: center;
}
.table td, .table th {
    vertical-align: middle;
}
.badge {
    font-size: 0.875em;
    padding: 0.5em 0.75em;
}
.btn-action {
    padding: 0.25rem 0.5rem;
    margin: 0 0.25rem;
}
.btn-action i {
    font-size: 0.875rem;
}
</style>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 dashboard-sidebar">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="/event_management/public/admin/dashboard">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="/event_management/public/admin/users">
                        <i class="fas fa-users"></i> Utilisateurs
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/event_management/public/admin/events">
                        <i class="fas fa-calendar-alt"></i> Événements
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/event_management/public/admin/reservations">
                        <i class="fas fa-ticket-alt"></i> Réservations
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/event_management/public/admin/reviews">
                        <i class="fas fa-star"></i> Avis
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main content -->
        <main class="col-md-10 ms-sm-auto px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Gestion des Utilisateurs</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-plus me-1"></i> Ajouter un Utilisateur
                    </button>
                </div>
            </div>

            <!-- Users Table -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="usersTable">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Email</th>
                                    <th>Rôle</th>
                                    <th>Statut</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                                    <td><?php echo htmlspecialchars($user['lastname']); ?></td>
                                    <td><?php echo htmlspecialchars($user['firstname']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $user['role'] === 'admin' ? 'bg-danger' : ($user['role'] === 'organizer' ? 'bg-info' : 'bg-primary'); ?>">
                                            <?php echo htmlspecialchars(ucfirst($user['role'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $user['status'] === 'active' ? 'bg-success' : 'bg-warning'; ?>">
                                            <?php echo htmlspecialchars(ucfirst($user['status'])); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary btn-action" onclick="editUser(<?php echo $user['id']; ?>)" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger btn-action" onclick="deleteUser(<?php echo $user['id']; ?>)" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un Utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addUserForm">
                    <div class="mb-3">
                        <label for="addFirstname" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="addFirstname" name="firstname" required>
                    </div>
                    <div class="mb-3">
                        <label for="addLastname" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="addLastname" name="lastname" required>
                    </div>
                    <div class="mb-3">
                        <label for="addEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="addEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="addPassword" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="addPassword" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="addRole" class="form-label">Rôle</label>
                        <select class="form-select" id="addRole" name="role" required>
                            <option value="user">Utilisateur</option>
                            <option value="organizer">Organisateur</option>
                            <option value="admin">Administrateur</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="addUser()">Ajouter</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier l'Utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="hidden" id="editUserId" name="id">
                    <div class="mb-3">
                        <label for="editFirstname" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="editFirstname" name="firstname" required>
                    </div>
                    <div class="mb-3">
                        <label for="editLastname" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="editLastname" name="lastname" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="editRole" class="form-label">Rôle</label>
                        <select class="form-select" id="editRole" name="role" required>
                            <option value="user">Utilisateur</option>
                            <option value="organizer">Organisateur</option>
                            <option value="admin">Administrateur</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editStatus" class="form-label">Statut</label>
                        <select class="form-select" id="editStatus" name="status" required>
                            <option value="active">Actif</option>
                            <option value="inactive">Inactif</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="saveUserChanges()">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<script>
function addUser() {
    const form = document.getElementById('addUserForm');
    const formData = new FormData(form);
    
    fetch('/event_management/public/admin/users/add', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            window.location.reload();
        } else {
            alert('Error adding user: ' + result.message);
        }
    });
}

function editUser(userId) {
    fetch(`/event_management/public/admin/users/${userId}`)
        .then(response => response.json())
        .then(user => {
            document.getElementById('editUserId').value = user.id;
            document.getElementById('editFirstname').value = user.firstname;
            document.getElementById('editLastname').value = user.lastname;
            document.getElementById('editEmail').value = user.email;
            document.getElementById('editRole').value = user.role;
            document.getElementById('editStatus').value = user.status;
            
            new bootstrap.Modal(document.getElementById('editUserModal')).show();
        });
}

function saveUserChanges() {
    const form = document.getElementById('editUserForm');
    const formData = new FormData(form);
    
    fetch('/event_management/public/admin/users/update', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            window.location.reload();
        } else {
            alert('Error updating user: ' + result.message);
        }
    });
}

function deleteUser(userId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
        fetch(`/event_management/public/admin/users/delete/${userId}`, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                window.location.reload();
            } else {
                alert('Error deleting user: ' + result.message);
            }
        });
    }
}
</script> 