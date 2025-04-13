<?php
class Review
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
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
}
