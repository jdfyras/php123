<?php
class Event
{
    private $db;

    public function __construct($db)
    {
        if (!$db instanceof PDO) {
            throw new Exception("Invalid database connection");
        }
        $this->db = $db;
    }

    /**
     * Get total number of events
     */
    public function getTotalEvents()
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM events");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'];
        } catch (PDOException $e) {
            error_log("Error in getTotalEvents: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Récupère tous les événements à venir
     */
    public function getUpcomingEvents($limit = 6)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT * FROM events 
                WHERE date > NOW() 
                ORDER BY date ASC 
                LIMIT :limit
            ");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error in getUpcomingEvents: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère un événement par son ID
     */
    public function getEventById($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM events WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error in getEventById: " . $e->getMessage());
            return null;
        }
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

    public function createEvent($data)
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO events (title, description, date, location, price, available_tickets)
                VALUES (:title, :description, :date, :location, :price, :available_tickets)
            ");
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Error in createEvent: " . $e->getMessage());
            return false;
        }
    }

    public function updateEvent($id, $data)
    {
        try {
            $sql = "UPDATE events SET ";
            $updates = [];
            foreach ($data as $key => $value) {
                $updates[] = "$key = :$key";
            }
            $sql .= implode(', ', $updates);
            $sql .= " WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $data['id'] = $id;
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Error in updateEvent: " . $e->getMessage());
            return false;
        }
    }

    public function deleteEvent($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM events WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error in deleteEvent: " . $e->getMessage());
            return false;
        }
    }
}
