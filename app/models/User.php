<?php
class User
{
    private $db;
    private $id;
    private $firstname;
    private $lastname;
    private $email;
    private $password_hash;
    private $role;
    private $status;
    private $created_at;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function register($firstname, $lastname, $email, $password)
    {
        // Vérifier si l'email existe déjà
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            return false;
        }

        // Hasher le mot de passe
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        // Générer un code de vérification
        $verification_code = rand(100000, 999999);

        // Insérer le nouvel utilisateur
        $stmt = $this->db->prepare("
            INSERT INTO users (firstname, lastname, email, password_hash, role, status, created_at, is_verified, verification_code)
            VALUES (?, ?, ?, ?, 'user', 'actif', NOW(), 0, ?)
        ");

        return $stmt->execute([$firstname, $lastname, $email, $password_hash, $verification_code]);
    }

    public function login($email, $password)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM users 
            WHERE email = ? AND status = 'actif' AND is_verified = 1
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            $this->id = $user['id'];
            $this->firstname = $user['firstname'];
            $this->lastname = $user['lastname'];
            $this->email = $user['email'];
            $this->role = $user['role'];
            return $user;
        }
        return false;
    }

    public function updateProfile($id, $firstname, $lastname, $email)
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET firstname = ?, lastname = ?, email = ?
            WHERE id = ?
        ");
        return $stmt->execute([$firstname, $lastname, $email, $id]);
    }

    public function changePassword($id, $new_password)
    {
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("
            UPDATE users 
            SET password_hash = ?
            WHERE id = ?
        ");
        return $stmt->execute([$password_hash, $id]);
    }

    public function deactivateAccount($id)
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET status = 'désactivé'
            WHERE id = ?
        ");
        return $stmt->execute([$id]);
    }

    public function deleteAccount($id)
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET status = 'supprimé'
            WHERE id = ?
        ");
        return $stmt->execute([$id]);
    }

    public function getUserById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get all users
     */
    public function getAllUsers()
    {
        $stmt = $this->db->prepare("
            SELECT * FROM users 
            ORDER BY created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get users by status
     */
    public function getUsersByStatus($status)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM users 
            WHERE status = ?
        ");
        $stmt->execute([$status]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    /**
     * Get total number of users
     */
    public function getTotalUsers()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM users");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    /**
     * Update user details
     */
    public function updateUser($userId, $firstname, $lastname, $email, $role, $status)
    {
        // Check if email is already used by another user
        $stmt = $this->db->prepare("
            SELECT id FROM users 
            WHERE email = ? AND id != ?
        ");
        $stmt->execute([$email, $userId]);
        if ($stmt->rowCount() > 0) {
            return false;
        }

        $stmt = $this->db->prepare("
            UPDATE users 
            SET firstname = ?, lastname = ?, email = ?, role = ?, status = ?
            WHERE id = ?
        ");
        return $stmt->execute([$firstname, $lastname, $email, $role, $status, $userId]);
    }

    /**
     * Update user status
     */
    public function updateUserStatus($userId, $status)
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET status = ?
            WHERE id = ?
        ");
        return $stmt->execute([$status, $userId]);
    }

    /**
     * Update user role
     */
    public function updateUserRole($userId, $role)
    {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET role = ?
            WHERE id = ?
        ");
        return $stmt->execute([$role, $userId]);
    }

    public function verifyUser($email) {
        $stmt = $this->db->prepare("UPDATE users SET is_verified = 1, verification_code = NULL WHERE email = ?");
        return $stmt->execute([$email]);
    }

    public function setVerificationCode($email, $code) {
        $stmt = $this->db->prepare("UPDATE users SET verification_code = ? WHERE email = ?");
        return $stmt->execute([$code, $email]);
    }

    public function checkVerificationCode($email, $code) {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = ? AND verification_code = ?");
        $stmt->execute([$email, $code]);
        return $stmt->rowCount() > 0;
    }

    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }
    public function getFirstname()
    {
        return $this->firstname;
    }
    public function getLastname()
    {
        return $this->lastname;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getRole()
    {
        return $this->role;
    }
}
