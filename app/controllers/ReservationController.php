<?php
class ReservationController
{
    private $db;
    private $eventModel;
    private $reservationModel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->eventModel = new Event($db);
        $this->reservationModel = new Reservation($db);
    }

    /**
     * Affiche le formulaire de réservation
     */
    public function create($id)
    {
        if (!isLoggedIn()) {
            $_SESSION['redirect_after_login'] = "events/$id/book";
            redirect('login');
        }

        // Récupérer l'événement
        $event = $this->eventModel->getEventById($id);

        if (!$event) {
            redirect('events');
        }

        // Si l'événement est déjà passé
        if (strtotime($event['date']) < time()) {
            $_SESSION['error'] = "Cet événement est déjà passé.";
            redirect("events/$id");
        }

        // Si l'événement est complet
        if ($event['available_tickets'] <= 0) {
            $_SESSION['error'] = "Cet événement est complet.";
            redirect("events/$id");
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
                $reservationId = $this->reservationModel->createReservation([
                    'user_id' => $_SESSION['user_id'],
                    'event_id' => $id,
                    'quantity' => $quantity,
                    'total_price' => $totalPrice,
                    'status' => 'en attente'
                ]);

                if ($reservationId) {
                    redirect("reservations/$reservationId/pay");
                } else {
                    $errors[] = "Une erreur est survenue lors de la création de la réservation.";
                }
            }
        }

        // Charger la vue
        $title = "Réserver - " . htmlspecialchars($event['title']) . " - " . APP_NAME;
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
        if (!isLoggedIn()) {
            redirect('login');
        }

        // Récupérer la réservation
        $reservation = $this->reservationModel->getReservationById($id);

        if (!$reservation || $reservation['user_id'] != $_SESSION['user_id']) {
            redirect('profile');
        }

        // Si la réservation est déjà payée
        if ($reservation['status'] === 'payé') {
            $_SESSION['success'] = "Cette réservation a déjà été payée.";
            redirect('profile');
        }

        // Si la réservation est annulée
        if ($reservation['status'] === 'annulé') {
            $_SESSION['error'] = "Cette réservation a été annulée.";
            redirect('profile');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Simuler le paiement (en réalité, vous utiliseriez un système de paiement comme Stripe)
            $success = true;

            if ($success) {
                if ($this->reservationModel->updateStatus($id, 'payé')) {
                    // Mettre à jour le nombre de billets disponibles
                    $this->eventModel->updateAvailableTickets($reservation['event_id'], -$reservation['quantity']);

                    $_SESSION['success'] = "Votre paiement a été effectué avec succès.";
                    redirect('profile');
                } else {
                    $error = "Une erreur est survenue lors de la mise à jour de la réservation.";
                }
            } else {
                $error = "Une erreur est survenue lors du paiement.";
            }
        }

        // Charger la vue
        $title = "Paiement - " . htmlspecialchars($reservation['event_title']) . " - " . APP_NAME;
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
        if (!isLoggedIn()) {
            redirect('login');
        }

        // Récupérer la réservation
        $reservation = $this->reservationModel->getReservationById($id);

        if (!$reservation || $reservation['user_id'] != $_SESSION['user_id']) {
            redirect('profile');
        }

        // Si la réservation est déjà annulée
        if ($reservation['status'] === 'annulé') {
            $_SESSION['error'] = "Cette réservation a déjà été annulée.";
            redirect('profile');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->reservationModel->updateStatus($id, 'annulé')) {
                // Si la réservation était payée, remettre les billets en disponibilité
                if ($reservation['status'] === 'payé') {
                    $this->eventModel->updateAvailableTickets($reservation['event_id'], $reservation['quantity']);
                }

                $_SESSION['success'] = "Votre réservation a été annulée avec succès.";
                redirect('profile');
            } else {
                $error = "Une erreur est survenue lors de l'annulation de la réservation.";
            }
        }

        // Charger la vue
        $title = "Annuler la réservation - " . htmlspecialchars($reservation['event_title']) . " - " . APP_NAME;
        ob_start();
        require_once __DIR__ . '/../views/reservations/cancel.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layouts/main.php';
    }
}
