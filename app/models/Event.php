<?php
class Event
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Récupère tous les événements à venir
     */
    public function getUpcomingEvents($limit = null)
    {
        // Log de debug
        error_log("Début de getUpcomingEvents avec limite: " . ($limit ? $limit : "aucune"));

        $query = "
            SELECT * FROM events 
            WHERE date >= NOW() 
            ORDER BY date ASC
        ";

        try {
            if ($limit) {
                $query .= " LIMIT " . intval($limit);
                $stmt = $this->db->prepare($query);
                error_log("Exécution de la requête SQL avec limite: " . $query);
                $stmt->execute();
            } else {
                $stmt = $this->db->prepare($query);
                error_log("Exécution de la requête SQL sans limite: " . $query);
                $stmt->execute();
            }

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("Nombre d'événements récupérés: " . count($results));
            return $results;
        } catch (PDOException $e) {
            error_log("Erreur SQL: " . $e->getMessage());
            // En mode développement, on peut retourner un tableau vide
            // En production, il serait préférable de gérer cette erreur différemment
            return [];
        }
    }

    /**
     * Récupère un événement par son ID
     */
    public function getEventById($eventId)
    {
        $stmt = $this->db->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->execute([$eventId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les événements par terme de recherche
     */
    public function searchEvents($searchTerm)
    {
        $searchTerm = "%$searchTerm%";
        $stmt = $this->db->prepare("
            SELECT * FROM events 
            WHERE title LIKE ? OR description LIKE ? OR location LIKE ?
            ORDER BY date ASC
        ");
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Met à jour le nombre de billets disponibles
     */
    public function updateAvailableTickets($eventId, $quantity)
    {
        $stmt = $this->db->prepare("
            UPDATE events
            SET available_tickets = available_tickets - ?
            WHERE id = ? AND available_tickets >= ?
        ");
        return $stmt->execute([$quantity, $eventId, $quantity]);
    }

    /**
     * Vérifie si un événement a suffisamment de billets disponibles
     */
    public function hasAvailableTickets($eventId, $quantity)
    {
        $stmt = $this->db->prepare("
            SELECT available_tickets 
            FROM events
            WHERE id = ?
        ");
        $stmt->execute([$eventId]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        return $event && $event['available_tickets'] >= $quantity;
    }
}
