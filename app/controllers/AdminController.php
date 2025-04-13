<?php
class AdminController
{
    private $userModel;
    private $eventModel;
    private $reviewModel;
    private $reservationModel;

    public function __construct()
    {
        $db = Database::getInstance()->getConnection();
        $this->userModel = new User($db);
        $this->eventModel = new Event($db);
        $this->reviewModel = new Review($db);
        $this->reservationModel = new Reservation($db);
    }

    public function dashboard()
    {
        requireAdmin();
        $currentPage = 'admin/dashboard';
        $title = "Admin Dashboard - Gestion d'Événements";

        // Get statistics
        $stats = [
            'total_users' => $this->userModel->getTotalUsers(),
            'total_events' => $this->eventModel->getTotalEvents(),
            'total_reservations' => $this->reservationModel->getTotalReservations(),
            'total_reviews' => $this->reviewModel->getTotalReviews(),
            'active_users' => $this->userModel->getUsersByStatus('active'),
            'pending_users' => $this->userModel->getUsersByStatus('pending'),
            'recent_activity' => $this->getRecentActivity()
        ];

        ob_start();
        require_once __DIR__ . '/../views/admin/dashboard.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    private function getRecentActivity($limit = 10)
    {
        $recentReservations = $this->reservationModel->getRecentReservations($limit);
        $recentEvents = $this->eventModel->getUpcomingEvents($limit);
        
        $activities = [];
        
        // Format reservations
        foreach ($recentReservations as $reservation) {
            if (!empty($reservation['created_at'])) {
                $activities[] = [
                    'type' => 'reservation',
                    'title' => "New reservation for {$reservation['event_title']}",
                    'description' => "{$reservation['firstname']} {$reservation['lastname']} reserved {$reservation['quantity']} tickets",
                    'date' => $reservation['created_at']
                ];
            }
        }
        
        // Format events
        foreach ($recentEvents as $event) {
            if (!empty($event['date'])) {
                $activities[] = [
                    'type' => 'event',
                    'title' => "Upcoming event: {$event['title']}",
                    'description' => "Event scheduled for " . date('F j, Y', strtotime($event['date'])),
                    'date' => $event['date']
                ];
            }
        }
        
        // Sort activities by date, most recent first
        usort($activities, function($a, $b) {
            $dateA = !empty($a['date']) ? strtotime($a['date']) : 0;
            $dateB = !empty($b['date']) ? strtotime($b['date']) : 0;
            return $dateB - $dateA;
        });
        
        // Return limited number of activities
        return array_slice($activities, 0, $limit);
    }

    public function users()
    {
        requireAdmin();
        $currentPage = 'admin/users';
        $title = "Gestion des Utilisateurs - Administration";
        
        $users = $this->userModel->getAllUsers();
        
        ob_start();
        require_once __DIR__ . '/../views/admin/users.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function getUser($id)
    {
        requireAdmin();
        header('Content-Type: application/json');
        
        $user = $this->userModel->getUserById($id);
        if ($user) {
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
        }
    }

    public function updateUser()
    {
        requireAdmin();
        header('Content-Type: application/json');
        
        $data = $_POST;
        $userId = $data['id'];
        
        try {
            $success = $this->userModel->updateUser(
                $userId,
                $data['firstname'],
                $data['lastname'],
                $data['email'],
                $data['role'],
                $data['status']
            );
            
            if ($success) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update user']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteUser($id)
    {
        requireAdmin();
        header('Content-Type: application/json');
        
        try {
            $success = $this->userModel->deleteAccount($id);
            if ($success) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete user']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function updateUserStatus($id)
    {
        // Implementation for updating user status
    }

    public function updateUserRole($id)
    {
        // Implementation for updating user role
    }

    public function events()
    {
        $events = $this->eventModel->getAllEvents();
        require_once __DIR__ . '/../views/admin/events.php';
    }

    public function reviews()
    {
        $reviews = $this->reviewModel->getAll();
        require_once __DIR__ . '/../views/admin/reviews.php';
    }

    public function reservations()
    {
        $reservations = $this->reservationModel->getAll();
        require_once __DIR__ . '/../views/admin/reservations.php';
    }

    public function addUser()
    {
        requireAdmin();
        header('Content-Type: application/json');
        
        $data = $_POST;
        
        try {
            $success = $this->userModel->register(
                $data['firstname'],
                $data['lastname'],
                $data['email'],
                $data['password']
            );
            
            if ($success) {
                // Update role if different from default
                if ($data['role'] !== 'user') {
                    $userId = $this->userModel->getUserByEmail($data['email'])['id'];
                    $this->userModel->updateUserRole($userId, $data['role']);
                }
                
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to create user']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
} 