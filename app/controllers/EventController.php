<?php
class EventController
{
    private $db;
    private $eventModel;
    private $reviewModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->eventModel = new Event($db);
        $this->reviewModel = new Review($db);
    }

    /**
     * Affiche la liste des événements
     */
    public function index()
    {
        // Récupérer tous les événements à venir
        $events = $this->eventModel->getUpcomingEvents();

        // Recherche d'événements
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $searchTerm = $_GET['search'];
            $events = $this->eventModel->searchEvents($searchTerm);
        }

        // Charger la vue
        $title = "Événements - " . APP_NAME;
        ob_start();
        require_once __DIR__ . '/../views/events/index.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layouts/main.php';
    }

    /**
     * Affiche le détail d'un événement
     */
    public function show($id)
    {
        // Récupérer l'événement
        $event = $this->eventModel->getEventById($id);

        if (!$event) {
            redirect('events');
        }

        // Récupérer les avis pour cet événement
        $reviews = $this->reviewModel->getReviewsByEventId($id);
        $averageRating = $this->reviewModel->getAverageRating($id);

        // Vérifier si l'utilisateur connecté a déjà posté un avis
        $userHasReviewed = false;
        if (isset($_SESSION['user_id'])) {
            $userHasReviewed = $this->reviewModel->hasUserReviewedEvent($_SESSION['user_id'], $id);
        }

        // Charger la vue
        $title = htmlspecialchars($event['title']) . " - " . APP_NAME;
        ob_start();
        require_once __DIR__ . '/../views/events/show.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layouts/main.php';
    }
}
