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

        // Insérer le nouvel utilisateur
        $stmt = $this->db->prepare("
            INSERT INTO users (firstname, lastname, email, password_hash, role, status, created_at)
            VALUES (?, ?, ?, ?, 'user', 'actif', NOW())
        ");

        return $stmt->execute([$firstname, $lastname, $email, $password_hash]);
    }

    public function login($email, $password)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM users 
            WHERE email = ? AND status = 'actif'
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            $this->id = $user['id'];
            $this->firstname = $user['firstname'];
            $this->lastname = $user['lastname'];
            $this->email = $user['email'];
            $this->role = $user['role'];
            return true;
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
