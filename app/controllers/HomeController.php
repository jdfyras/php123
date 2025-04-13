<?php
class HomeController
{
    private $db;
    private $eventModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->eventModel = new Event($db);
        error_log("HomeController initialisé");
    }

    public function index()
    {
        error_log("Début de la méthode index du HomeController");

        // Récupérer les événements à venir
        $upcomingEvents = $this->eventModel->getUpcomingEvents(6);
        error_log("Événements récupérés: " . print_r($upcomingEvents, true));

        // Si aucun événement n'est disponible, on crée un événement de test
        if (empty($upcomingEvents)) {
            error_log("Aucun événement trouvé - Vérification si la table events existe");
            try {
                $stmt = $this->db->query("SHOW TABLES LIKE 'events'");
                if ($stmt->rowCount() == 0) {
                    error_log("La table events n'existe pas - Les tables de la base de données ne sont pas créées");
                } else {
                    error_log("La table events existe mais est vide");
                }
            } catch (PDOException $e) {
                error_log("Erreur lors de la vérification des tables: " . $e->getMessage());
            }
        }

        // Charger la vue home
        $title = "Accueil - Gestion d'Événements";
        error_log("Chargement de la vue home/index.php");
        ob_start();
        require_once __DIR__ . '/../views/home/index.php';
        $content = ob_get_clean();
        error_log("Vue home/index.php chargée");

        error_log("Chargement du layout main.php");
        require_once __DIR__ . '/../views/layouts/main.php';
        error_log("Layout main.php chargé");
    }
}
