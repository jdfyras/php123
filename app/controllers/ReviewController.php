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
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        // Vérifier si l'événement existe
        $eventStmt = $this->db->prepare("SELECT * FROM events WHERE id = ?");
        $eventStmt->execute([$eventId]);
        $event = $eventStmt->fetch(PDO::FETCH_ASSOC);

        if (!$event) {
            header('Location: /events');
            exit;
        }

        // Vérifier si l'utilisateur a déjà posté un avis pour cet événement
        if ($this->reviewModel->hasUserReviewedEvent($_SESSION['user_id'], $eventId)) {
            $_SESSION['error'] = "Vous avez déjà posté un avis pour cet événement.";
            header('Location: /events/' . $eventId);
            exit;
        }

        // Vérifier si l'utilisateur a assisté à l'événement (a une réservation)
        $reservationStmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM reservations
            WHERE user_id = ? AND event_id = ? AND status = 'payé' AND event_id IN (SELECT id FROM events WHERE date < NOW())
        ");
        $reservationStmt->execute([$_SESSION['user_id'], $eventId]);
        $result = $reservationStmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] === 0) {
            $_SESSION['error'] = "Vous devez avoir assisté à l'événement pour pouvoir laisser un avis.";
            header('Location: /events/' . $eventId);
            exit;
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
                    header('Location: /events/' . $eventId);
                    exit;
                } else {
                    $errors[] = "Une erreur est survenue lors de la création de l'avis.";
                }
            }
        }

        // Charger la vue
        $title = "Ajouter un avis - " . htmlspecialchars($event['title']);
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
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $review = $this->reviewModel->getReviewById($reviewId);

        if (!$review) {
            header('Location: /profile');
            exit;
        }

        // Vérifier que l'avis appartient bien à l'utilisateur
        if ($review['user_id'] != $_SESSION['user_id']) {
            header('Location: /profile');
            exit;
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
                    header('Location: /profile');
                    exit;
                } else {
                    $errors[] = "Une erreur est survenue lors de la mise à jour de l'avis.";
                }
            }
        }

        // Charger la vue
        $title = "Modifier votre avis - " . htmlspecialchars($review['event_title']);
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
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $review = $this->reviewModel->getReviewById($reviewId);

        if (!$review) {
            header('Location: /profile');
            exit;
        }

        // Vérifier que l'avis appartient bien à l'utilisateur
        if ($review['user_id'] != $_SESSION['user_id']) {
            header('Location: /profile');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->reviewModel->deleteReview($reviewId, $_SESSION['user_id'])) {
                $_SESSION['success'] = "Votre avis a été supprimé avec succès.";
            } else {
                $_SESSION['error'] = "Une erreur est survenue lors de la suppression de l'avis.";
            }
            header('Location: /profile');
            exit;
        }

        // Charger la vue
        $title = "Supprimer votre avis";
        ob_start();
        require_once __DIR__ . '/../views/reviews/delete.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layouts/main.php';
    }
}
