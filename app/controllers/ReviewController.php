<?php
class ReviewController
{
    private $db;
    private $reviewModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->reviewModel = new Review($db);
    }

    /**
     * Affiche le formulaire pour laisser un avis
     */
    public function create($eventId)
    {
        if (!isLoggedIn()) {
            redirect('login');
        }

        // Vérifier si l'événement existe
        $eventStmt = $this->db->prepare("SELECT * FROM events WHERE id = ?");
        $eventStmt->execute([$eventId]);
        $event = $eventStmt->fetch(PDO::FETCH_ASSOC);

        if (!$event) {
            redirect('events');
        }

        // Vérifier si l'utilisateur a déjà posté un avis pour cet événement
        if ($this->reviewModel->hasUserReviewedEvent($_SESSION['user_id'], $eventId)) {
            $_SESSION['error'] = "Vous avez déjà posté un avis pour cet événement.";
            redirect("events/$eventId");
        }

        // Vérifier si l'utilisateur a assisté à l'événement (a une réservation)
        $hasAttended = $this->reviewModel->hasUserAttendedEvent($_SESSION['user_id'], $eventId);
        if (!$hasAttended) {
            $_SESSION['error'] = "Vous devez avoir assisté à l'événement pour pouvoir laisser un avis.";
            redirect("events/$eventId");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rating = intval($_POST['rating'] ?? 0);
            $comment = $_POST['comment'] ?? '';

            $errors = [];
            if ($rating < 1 || $rating > 5) {
                $errors[] = "La note doit être comprise entre 1 et 5.";
            }
            if (empty($comment)) {
                $errors[] = "Le commentaire est requis.";
            }

            if (empty($errors)) {
                if ($this->reviewModel->createReview($_SESSION['user_id'], $eventId, $rating, $comment)) {
                    $_SESSION['success'] = "Votre avis a été publié avec succès.";
                    redirect("events/$eventId");
                } else {
                    $errors[] = "Une erreur est survenue lors de la création de l'avis.";
                }
            }
        }

        // Charger la vue
        $title = "Ajouter un avis - " . htmlspecialchars($event['title']) . " - " . APP_NAME;
        ob_start();
        require_once __DIR__ . '/../views/reviews/create.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layouts/main.php';
    }

    /**
     * Affiche le formulaire pour modifier un avis
     */
    public function edit($reviewId)
    {
        if (!isLoggedIn()) {
            redirect('login');
        }

        $review = $this->reviewModel->getReviewById($reviewId);

        if (!$review || $review['user_id'] != $_SESSION['user_id']) {
            redirect('profile');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $rating = intval($_POST['rating'] ?? 0);
            $comment = $_POST['comment'] ?? '';

            $errors = [];
            if ($rating < 1 || $rating > 5) {
                $errors[] = "La note doit être comprise entre 1 et 5.";
            }
            if (empty($comment)) {
                $errors[] = "Le commentaire est requis.";
            }

            if (empty($errors)) {
                if ($this->reviewModel->updateReview($reviewId, $_SESSION['user_id'], $rating, $comment)) {
                    $_SESSION['success'] = "Votre avis a été mis à jour avec succès.";
                    redirect('profile');
                } else {
                    $errors[] = "Une erreur est survenue lors de la mise à jour de l'avis.";
                }
            }
        }

        // Charger la vue
        $title = "Modifier votre avis - " . htmlspecialchars($review['event_title']) . " - " . APP_NAME;
        ob_start();
        require_once __DIR__ . '/../views/reviews/edit.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layouts/main.php';
    }

    /**
     * Supprime un avis
     */
    public function delete($reviewId)
    {
        if (!isLoggedIn()) {
            redirect('login');
        }

        $review = $this->reviewModel->getReviewById($reviewId);

        if (!$review || $review['user_id'] != $_SESSION['user_id']) {
            redirect('profile');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->reviewModel->deleteReview($reviewId, $_SESSION['user_id'])) {
                $_SESSION['success'] = "Votre avis a été supprimé avec succès.";
            } else {
                $_SESSION['error'] = "Une erreur est survenue lors de la suppression de l'avis.";
            }
            redirect('profile');
        }

        // Charger la vue
        $title = "Supprimer votre avis - " . APP_NAME;
        ob_start();
        require_once __DIR__ . '/../views/reviews/delete.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layouts/main.php';
    }
}
