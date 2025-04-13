<?php
class Reservation
{
    private $db;

    public function __construct($db)
    {
        if (!$db instanceof PDO) {
            throw new Exception("Invalid database connection");
        }
        $this->db = $db;
    }

    public function getAll()
    {
        try {
            $stmt = $this->db->query("
                SELECT r.*, u.firstname, u.lastname, e.title as event_title 
                FROM reservations r 
                JOIN users u ON r.user_id = u.id 
                JOIN events e ON r.event_id = e.id 
                ORDER BY r.created_at DESC
            ");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error in Reservation::getAll: " . $e->getMessage());
            return [];
        }
    }

    public function getTotalReservations()
    {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) as total FROM reservations");
            $result = $stmt->fetch();
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error in Reservation::getTotalReservations: " . $e->getMessage());
            return 0;
        }
    }

    public function getById($id)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT r.*, u.firstname, u.lastname, e.title as event_title 
                FROM reservations r 
                JOIN users u ON r.user_id = u.id 
                JOIN events e ON r.event_id = e.id 
                WHERE r.id = :id
            ");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error in Reservation::getById: " . $e->getMessage());
            return null;
        }
    }

    public function create($data)
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO reservations (user_id, event_id, quantity, total_price, status, created_at)
                VALUES (:user_id, :event_id, :quantity, :total_price, :status, NOW())
            ");
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Error in Reservation::create: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data)
    {
        try {
            $stmt = $this->db->prepare("
                UPDATE reservations 
                SET status = :status 
                WHERE id = :id
            ");
            $data['id'] = $id;
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Error in Reservation::update: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM reservations WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error in Reservation::delete: " . $e->getMessage());
            return false;
        }
    }

    public function getRecentReservations($limit = 10)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT r.*, u.firstname, u.lastname, e.title as event_title 
                FROM reservations r 
                JOIN users u ON r.user_id = u.id 
                JOIN events e ON r.event_id = e.id 
                ORDER BY r.created_at DESC
                LIMIT :limit
            ");
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error in Reservation::getRecentReservations: " . $e->getMessage());
            return [];
        }
    }
} 