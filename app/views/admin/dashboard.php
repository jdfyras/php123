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
.stats-card {
    transition: transform 0.2s;
    cursor: pointer;
}
.stats-card:hover {
    transform: translateY(-5px);
}
.border-left-primary { border-left: 4px solid #4e73df !important; }
.border-left-success { border-left: 4px solid #1cc88a !important; }
.border-left-info { border-left: 4px solid #36b9cc !important; }
.border-left-warning { border-left: 4px solid #f6c23e !important; }
.text-xs { font-size: 0.7rem; }
.text-gray-800 { color: #5a5c69 !important; }
.text-gray-300 { color: #dddfeb !important; }
</style>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 dashboard-sidebar">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="/event_management/public/admin/dashboard">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/event_management/public/admin/users">
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
                <h1 class="h2">Dashboard</h1>
            </div>

            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2 stats-card">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        UTILISATEURS</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['total_users']; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2 stats-card">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        ÉVÉNEMENTS À VENIR</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['total_events']; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2 stats-card">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        RÉSERVATIONS</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['total_reservations']; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2 stats-card">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        UTILISATEURS ACTIFS</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['active_users']; ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-check fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Activité Récente</h6>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($stats['recent_activity'])): ?>
                                <div class="list-group">
                                    <?php foreach ($stats['recent_activity'] as $activity): ?>
                                        <div class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-1"><?php echo htmlspecialchars($activity['title']); ?></h5>
                                                <small><?php echo date('d/m/Y H:i', strtotime($activity['date'])); ?></small>
                                            </div>
                                            <p class="mb-1"><?php echo htmlspecialchars($activity['description']); ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p>Aucune activité récente à afficher.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div> 