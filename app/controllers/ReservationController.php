<?php
class ReservationController
{
    private $db;
    private $eventModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->eventModel = new Event($db);
    }

    /**
     * Affiche le formulaire de réservation
     */
    public function create($id)
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_after_login'] = "/events/$id/book";
            header('Location: /login');
            exit;
        }

        // Récupérer l'événement
        $event = $this->eventModel->getEventById($id);

        if (!$event) {
            header('Location: /events');
            exit;
        }

        // Si l'événement est déjà passé
        if (strtotime($event['date']) < time()) {
            $_SESSION['error'] = "Cet événement est déjà passé.";
            header('Location: /events/' . $id);
            exit;
        }

        // Si l'événement est complet
        if ($event['available_tickets'] <= 0) {
            $_SESSION['error'] = "Cet événement est complet.";
            header('Location: /events/' . $id);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $quantity = intval($_POST['quantity'] ?? 1);

            // Validation
            $errors = [];
            if ($quantity <= 0) {
                $errors[] = "La quantité doit être supérieure à 0.";
            }
            if ($quantity > $event['available_tickets']) {
                $errors[] = "Il ne reste que {$event['available_tickets']} billet(s) disponible(s).";
            }

            if (empty($errors)) {
                // Calculer le prix total
                $totalPrice = $event['price'] * $quantity;

                // Créer la réservation
                $stmt = $this->db->prepare("
                    INSERT INTO reservations (user_id, event_id, quantity, total_price, status, created_at)
                    VALUES (?, ?, ?, ?, 'en attente', NOW())
                ");

                if ($stmt->execute([$_SESSION['user_id'], $id, $quantity, $totalPrice])) {
                    $reservationId = $this->db->lastInsertId();

                    // Rediriger vers la page de paiement
                    header('Location: /reservations/' . $reservationId . '/pay');
                    exit;
                } else {
                    $errors[] = "Une erreur est survenue lors de la création de la réservation.";
                }
            }
        }

        // Charger la vue
        $title = "Réserver - " . htmlspecialchars($event['title']);
        ob_start();
        require_once __DIR__ . '/../views/reservations/create.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layouts/main.php';
    }

    /**
     * Affiche la page de paiement
     */
    public function pay($id)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        // Récupérer la réservation
        $stmt = $this->db->prepare("
            SELECT r.*, e.title as event_title, e.date as event_date, e.price
            FROM reservations r
            JOIN events e ON r.event_id = e.id
            WHERE r.id = ? AND r.user_id = ?
        ");
        $stmt->execute([$id, $_SESSION['user_id']]);
        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$reservation) {
            header('Location: /profile');
            exit;
        }

        // Si la réservation est déjà payée
        if ($reservation['status'] === 'payé') {
            $_SESSION['success'] = "Cette réservation a déjà été payée.";
            header('Location: /profile');
            exit;
        }

        // Si la réservation est annulée
        if ($reservation['status'] === 'annulé') {
            $_SESSION['error'] = "Cette réservation a été annulée.";
            header('Location: /profile');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Simuler le paiement (en réalité, vous utiliseriez un système de paiement comme Stripe)
            $success = true;

            if ($success) {
                // Mettre à jour le statut de la réservation
                $stmt = $this->db->prepare("
                    UPDATE reservations
                    SET status = 'payé'
                    WHERE id = ?
                ");

                if ($stmt->execute([$id])) {
                    // Mettre à jour le nombre de billets disponibles
                    $this->eventModel->updateAvailableTickets($reservation['event_id'], $reservation['quantity']);

                    $_SESSION['success'] = "Votre paiement a été effectué avec succès.";
                    header('Location: /profile');
                    exit;
                } else {
                    $error = "Une erreur est survenue lors de la mise à jour de la réservation.";
                }
            } else {
                $error = "Une erreur est survenue lors du paiement.";
            }
        }

        // Charger la vue
        $title = "Paiement - " . htmlspecialchars($reservation['event_title']);
        ob_start();
        require_once __DIR__ . '/../views/reservations/pay.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layouts/main.php';
    }

    /**
     * Annule une réservation
     */
    public function cancel($id)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        // Récupérer la réservation
        $stmt = $this->db->prepare("
            SELECT r.*, e.title as event_title
            FROM reservations r
            JOIN events e ON r.event_id = e.id
            WHERE r.id = ? AND r.user_id = ?
        ");
        $stmt->execute([$id, $_SESSION['user_id']]);
        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$reservation) {
            header('Location: /profile');
            exit;
        }

        // Si la réservation est déjà annulée
        if ($reservation['status'] === 'annulé') {
            $_SESSION['error'] = "Cette réservation a déjà été annulée.";
            header('Location: /profile');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Mettre à jour le statut de la réservation
            $stmt = $this->db->prepare("
                UPDATE reservations
                SET status = 'annulé'
                WHERE id = ?
            ");

            if ($stmt->execute([$id])) {
                // Si la réservation était payée, remettre les billets en disponibilité
                if ($reservation['status'] === 'payé') {
                    $stmt = $this->db->prepare("
                        UPDATE events
                        SET available_tickets = available_tickets + ?
                        WHERE id = ?
                    ");
                    $stmt->execute([$reservation['quantity'], $reservation['event_id']]);
                }

                $_SESSION['success'] = "Votre réservation a été annulée avec succès.";
                header('Location: /profile');
                exit;
            } else {
                $error = "Une erreur est survenue lors de l'annulation de la réservation.";
            }
        }

        // Charger la vue
        $title = "Annuler la réservation - " . htmlspecialchars($reservation['event_title']);
        ob_start();
        require_once __DIR__ . '/../views/reservations/cancel.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layouts/main.php';
    }
}
