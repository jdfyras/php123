<?php
class UserController
{
    private $userModel;
    private $db;

    public function __construct($db)
    {
        if (!$db instanceof PDO) {
            throw new Exception("Invalid database connection");
        }
        $this->db = $db;
        $this->userModel = new User($db);
    }

    public function register()
    {
        // If user is already logged in, redirect to home
        if (isLoggedIn()) {
            redirect('');
        }

        $currentPage = 'register';
        $title = "Inscription - Gestion d'Événements";
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstname = $_POST['firstname'] ?? '';
            $lastname = $_POST['lastname'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if ($password !== $confirm_password) {
                $_SESSION['error'] = "Les mots de passe ne correspondent pas";
            } else {
                $result = $this->userModel->register($firstname, $lastname, $email, $password);
                if ($result) {
                    // Get the code from the database
                    $user = $this->userModel->getUserByEmail($email);
                    $verification_code = $user['verification_code'];
                    $_SESSION['verification_email'] = $email;
                    require_once __DIR__ . '/../helpers/MailHelper.php';
                    MailHelper::sendMail($email, 'Votre code de vérification', "Votre code de vérification est : <b>$verification_code</b>");
                    redirect('verify_code');
                } else {
                    $_SESSION['error'] = "L'email est déjà utilisé";
                }
            }
        }

        ob_start();
        require_once __DIR__ . '/../views/user/register.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function login()
    {
        // If user is already logged in, redirect to home
        if (isLoggedIn()) {
            redirect('');
        }

        $currentPage = 'login';
        $title = "Connexion - Gestion d'Événements";
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $captcha = $_POST['captcha'] ?? '';

            // Validate captcha first
            require_once __DIR__ . '/../helpers/CaptchaHelper.php';
            if (!CaptchaHelper::validateCaptcha($captcha)) {
                $_SESSION['error'] = "Code de vérification incorrect";
            } else {
                $user = $this->userModel->login($email, $password);
                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['user_firstname'] = $user['firstname'];
                    $_SESSION['user_lastname'] = $user['lastname'];
                    $_SESSION['success'] = "Connexion réussie !";
                    
                    if ($user['role'] === 'admin') {
                        redirect('admin/dashboard');
                    } else {
                        redirect('');
                    }
                } else {
                    $_SESSION['error'] = "Email ou mot de passe incorrect";
                }
            }
        }

        ob_start();
        require_once __DIR__ . '/../helpers/CaptchaHelper.php';
        require_once __DIR__ . '/../views/user/login.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function logout()
    {
        session_destroy();
        redirect('login');
    }

    public function profile()
    {
        requireAuth();
        $currentPage = 'profile';
        $title = "Mon Profil - Gestion d'Événements";
        
        $userId = getCurrentUserId();
        $user = $this->userModel->getUserById($userId);

        ob_start();
        require_once __DIR__ . '/../views/user/profile.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function updateProfile()
    {
        requireAuth();
        $currentPage = 'update-profile';
        $title = "Modifier mon profil - Gestion d'Événements";
        
        $userId = getCurrentUserId();
        $user = $this->userModel->getUserById($userId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstname = $_POST['firstname'] ?? '';
            $lastname = $_POST['lastname'] ?? '';
            $email = $_POST['email'] ?? '';

            $result = $this->userModel->updateProfile($userId, $firstname, $lastname, $email);
            if ($result) {
                $_SESSION['user_firstname'] = $firstname;
                $_SESSION['user_lastname'] = $lastname;
                $_SESSION['user_email'] = $email;
                $_SESSION['success'] = "Profil mis à jour avec succès";
                redirect('profile');
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour du profil";
            }
        }

        ob_start();
        require_once __DIR__ . '/../views/user/update_profile.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function changePassword()
    {
        requireAuth();
        $currentPage = 'change-password';
        $title = "Changer mon mot de passe - Gestion d'Événements";
        
        $userId = getCurrentUserId();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Verify current password
            $user = $this->userModel->getUserById($userId);
            if (!password_verify($current_password, $user['password_hash'])) {
                $_SESSION['error'] = "Le mot de passe actuel est incorrect";
            } elseif ($new_password !== $confirm_password) {
                $_SESSION['error'] = "Les nouveaux mots de passe ne correspondent pas";
            } elseif (strlen($new_password) < 8) {
                $_SESSION['error'] = "Le nouveau mot de passe doit contenir au moins 8 caractères";
            } else {
                $result = $this->userModel->changePassword($userId, $new_password);
                if ($result) {
                    $_SESSION['success'] = "Mot de passe modifié avec succès";
                    redirect('profile');
                } else {
                    $_SESSION['error'] = "Erreur lors de la modification du mot de passe";
                }
            }
        }

        ob_start();
        require_once __DIR__ . '/../views/user/change_password.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function deactivateAccount()
    {
        if (!isLoggedIn()) {
            redirect('login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->userModel->deactivateAccount($_SESSION['user_id'])) {
                session_destroy();
                redirect('');
            }
        }

        // Charger la vue
        $title = "Désactiver mon compte - " . APP_NAME;
        ob_start();
        require_once __DIR__ . '/../views/user/deactivate_account.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function deleteAccount()
    {
        if (!isLoggedIn()) {
            redirect('login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->userModel->deleteAccount($_SESSION['user_id'])) {
                session_destroy();
                redirect('');
            }
        }

        // Charger la vue
        $title = "Supprimer mon compte - " . APP_NAME;
        ob_start();
        require_once __DIR__ . '/../views/user/delete_account.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layouts/main.php';
    }

    // Add a new method for code verification (skeleton)
    public function verify_code()
    {
        $currentPage = 'verify_code';
        $title = "Vérification du code - Gestion d'Événements";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input_code = $_POST['verification_code'] ?? '';
            if (isset($_SESSION['verification_email'])) {
                $email = $_SESSION['verification_email'];
                $user = $this->userModel->getUserByEmail($email);
                if ($user && $this->userModel->checkVerificationCode($email, $input_code)) {
                    $this->userModel->verifyUser($email);
                    unset($_SESSION['verification_email']);
                    $_SESSION['success'] = "Votre inscription est vérifiée ! Vous pouvez maintenant vous connecter.";
                    redirect('login');
                } else {
                    $_SESSION['error'] = "Code de vérification incorrect. Veuillez réessayer.";
                }
            } else {
                $_SESSION['error'] = "Session expirée ou invalide. Veuillez vous réinscrire.";
                redirect('register');
            }
        }
        ob_start();
        require_once __DIR__ . '/../views/user/verify_code.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    // Password reset request form
    public function requestResetPassword()
    {
        $currentPage = 'request-reset-password';
        $title = "Réinitialiser le mot de passe - Gestion d'Événements";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $captcha = $_POST['captcha'] ?? '';

            // Validate captcha first
            require_once __DIR__ . '/../helpers/CaptchaHelper.php';
            if (!CaptchaHelper::validateCaptcha($captcha)) {
                $_SESSION['error'] = "Code de vérification incorrect";
            } else {
                $user = $this->userModel->getUserByEmail($email);
                if ($user) {
                    $token = bin2hex(random_bytes(32));
                    $this->userModel->setPasswordResetToken($email, $token);
                    require_once __DIR__ . '/../helpers/MailHelper.php';
                    $resetLink = url('reset-password?token=' . $token . '&email=' . urlencode($email));
                    MailHelper::sendMail($email, 'Réinitialisation du mot de passe', "Cliquez sur ce lien pour réinitialiser votre mot de passe : <a href='$resetLink'>$resetLink</a>");
                    $_SESSION['success'] = "Un email de réinitialisation a été envoyé.";
                } else {
                    $_SESSION['error'] = "Aucun utilisateur trouvé avec cet email.";
                }
            }
        }
        ob_start();
        require_once __DIR__ . '/../helpers/CaptchaHelper.php';
        require_once __DIR__ . '/../views/user/request_reset_password.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layouts/main.php';
    }

    // Password reset form
    public function resetPassword()
    {
        $currentPage = 'reset-password';
        $title = "Nouveau mot de passe - Gestion d'Événements";
        $token = $_GET['token'] ?? '';
        $email = $_GET['email'] ?? '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            if ($new_password !== $confirm_password) {
                $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
            } elseif (strlen($new_password) < 8) {
                $_SESSION['error'] = "Le mot de passe doit contenir au moins 8 caractères.";
            } else {
                if ($this->userModel->verifyPasswordResetToken($email, $token)) {
                    $this->userModel->changePasswordByEmail($email, $new_password);
                    $this->userModel->clearPasswordResetToken($email);
                    $_SESSION['success'] = "Mot de passe réinitialisé avec succès.";
                    redirect('login');
                } else {
                    $_SESSION['error'] = "Lien de réinitialisation invalide ou expiré.";
                }
            }
        }
        ob_start();
        require_once __DIR__ . '/../views/user/reset_password.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../views/layouts/main.php';
    }
}
