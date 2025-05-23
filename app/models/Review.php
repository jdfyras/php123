<?php
class Review
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
     * Get total number of reviews
     */
    public function getTotalReviews()
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM reviews");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'];
        } catch (PDOException $e) {
            error_log("Error in getTotalReviews: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Récupère tous les avis pour un événement donné
     */
    public function getReviewsByEventId($eventId)
    {
        $stmt = $this->db->prepare("
            SELECT r.*, u.firstname, u.lastname 
            FROM reviews r
            JOIN users u ON r.user_id = u.id
            WHERE r.event_id = ?
            ORDER BY r.created_at DESC
        ");
        $stmt->execute([$eventId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère tous les avis d'un utilisateur
     */
    public function getReviewsByUserId($userId)
    {
        $stmt = $this->db->prepare("
            SELECT r.*, e.title as event_title
            FROM reviews r
            JOIN events e ON r.event_id = e.id
            WHERE r.user_id = ?
            ORDER BY r.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un avis par son ID
     */
    public function getReviewById($reviewId)
    {
        $stmt = $this->db->prepare("
            SELECT r.*, e.title as event_title
            FROM reviews r
            JOIN events e ON r.event_id = e.id
            WHERE r.id = ?
        ");
        $stmt->execute([$reviewId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Vérifie si un utilisateur a déjà posté un avis pour un événement
     */
    public function hasUserReviewedEvent($userId, $eventId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM reviews
            WHERE user_id = ? AND event_id = ?
        ");
        $stmt->execute([$userId, $eventId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    /**
     * Crée un nouvel avis
     */
    public function createReview($userId, $eventId, $rating, $comment)
    {
        // Vérifier que la note est valide
        if ($rating < 1 || $rating > 5) {
            return false;
        }

        // Vérifier que l'utilisateur n'a pas déjà posté un avis pour cet événement
        if ($this->hasUserReviewedEvent($userId, $eventId)) {
            return false;
        }

        $stmt = $this->db->prepare("
            INSERT INTO reviews (user_id, event_id, rating, comment, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ");
        return $stmt->execute([$userId, $eventId, $rating, $comment]);
    }

    /**
     * Met à jour un avis existant
     */
    public function updateReview($reviewId, $userId, $rating, $comment)
    {
        // Vérifier que la note est valide
        if ($rating < 1 || $rating > 5) {
            return false;
        }

        // Vérifier que l'avis appartient bien à l'utilisateur
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM reviews
            WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([$reviewId, $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] === 0) {
            return false;
        }

        $stmt = $this->db->prepare("
            UPDATE reviews
            SET rating = ?, comment = ?
            WHERE id = ? AND user_id = ?
        ");
        return $stmt->execute([$rating, $comment, $reviewId, $userId]);
    }

    /**
     * Supprime un avis
     */
    public function deleteReview($reviewId, $userId)
    {
        // Vérifier que l'avis appartient bien à l'utilisateur
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM reviews
            WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([$reviewId, $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] === 0) {
            return false;
        }

        $stmt = $this->db->prepare("
            DELETE FROM reviews
            WHERE id = ? AND user_id = ?
        ");
        return $stmt->execute([$reviewId, $userId]);
    }

    /**
     * Calcule la note moyenne d'un événement
     */
    public function getAverageRating($eventId)
    {
        $stmt = $this->db->prepare("
            SELECT AVG(rating) as average
            FROM reviews
            WHERE event_id = ?
        ");
        $stmt->execute([$eventId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['average'] ? round($result['average'], 1) : 0;
    }

    public function getAll()
    {
        try {
            $stmt = $this->db->query("
                SELECT r.*, u.firstname, u.lastname, e.title as event_title 
                FROM reviews r 
                JOIN users u ON r.user_id = u.id 
                JOIN events e ON r.event_id = e.id 
                ORDER BY r.created_at DESC
            ");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error in Review::getAll: " . $e->getMessage());
            return [];
        }
    }

    public function getById($id)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT r.*, u.firstname, u.lastname, e.title as event_title 
                FROM reviews r 
                JOIN users u ON r.user_id = u.id 
                JOIN events e ON r.event_id = e.id 
                WHERE r.id = :id
            ");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Error in Review::getById: " . $e->getMessage());
            return null;
        }
    }

    public function create($data)
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO reviews (user_id, event_id, rating, comment, created_at)
                VALUES (:user_id, :event_id, :rating, :comment, NOW())
            ");
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Error in Review::create: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data)
    {
        try {
            $stmt = $this->db->prepare("
                UPDATE reviews 
                SET rating = :rating, comment = :comment 
                WHERE id = :id
            ");
            $data['id'] = $id;
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Error in Review::update: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM reviews WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error in Review::delete: " . $e->getMessage());
            return false;
        }
    }

    public function getRecentReviews($limit = 5)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT r.*, u.firstname, u.lastname, e.title as event_title 
                FROM reviews r 
                JOIN users u ON r.user_id = u.id 
                JOIN events e ON r.event_id = e.id 
                ORDER BY r.created_at DESC 
                LIMIT :limit
            ");
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error in Review::getRecentReviews: " . $e->getMessage());
            return [];
        }
    }
}
