<?php
require_once __DIR__ . '/../Database.php';

class HomeController
{
    private $eventModel;
    private $userModel;

    public function __construct()
    {
        $db = Database::getInstance()->getConnection();
        $this->eventModel = new Event($db);
        $this->userModel = new User($db);
    }

    public function index()
    {
        $title = "Accueil - Gestion d'Événements";
        $currentPage = 'home';

        // Get upcoming events
        $events = $this->eventModel->getUpcomingEvents(6); // Limit to 6 events

        // Format event descriptions
        foreach ($events as &$event) {
            if (strlen($event['description']) > 100) {
                $event['description'] = substr($event['description'], 0, 100) . '...';
            }
        }

        // Start output buffering
        ob_start();
        require_once __DIR__ . '/../views/home/index.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function search()
    {
        header('Content-Type: application/json');
        
        $query = $_GET['query'] ?? '';
        $category = $_GET['category'] ?? '';
        $date = $_GET['date'] ?? '';
        
        try {
            $events = $this->eventModel->searchEvents($query, $category, $date);
            echo json_encode(['success' => true, 'events' => $events]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function newsletter()
    {
        header('Content-Type: application/json');
        
        $email = $_POST['email'] ?? '';
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Email invalide']);
            return;
        }
        
        try {
            // Add newsletter subscription logic here
            echo json_encode(['success' => true, 'message' => 'Inscription réussie à la newsletter']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function about()
    {
        requireAuth();
        $currentPage = 'about';
        $title = "À propos - Gestion d'Événements";
        ob_start();
        require_once __DIR__ . '/../views/home/about.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function contact()
    {
        requireAuth();
        $currentPage = 'contact';
        $title = "Contact - Gestion d'Événements";
        ob_start();
        require_once __DIR__ . '/../views/home/contact.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layouts/main.php';
    }
}
