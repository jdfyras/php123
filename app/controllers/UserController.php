<?php
class UserController
{
    private $userModel;
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
        $this->userModel = new User($db);
    }

    public function register()
    {
        error_log("Début de la méthode register du UserController");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("Traitement du formulaire d'inscription (POST)");
            $firstname = $_POST['firstname'] ?? '';
            $lastname = $_POST['lastname'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Validation des données
            $errors = [];
            if (empty($firstname)) $errors[] = "Le prénom est requis";
            if (empty($lastname)) $errors[] = "Le nom est requis";
            if (empty($email)) $errors[] = "L'email est requis";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "L'email n'est pas valide";
            if (empty($password)) $errors[] = "Le mot de passe est requis";
            if (strlen($password) < 8) $errors[] = "Le mot de passe doit contenir au moins 8 caractères";
            if ($password !== $confirm_password) $errors[] = "Les mots de passe ne correspondent pas";

            if (empty($errors)) {
                error_log("Tentative d'inscription pour {$email}");
                if ($this->userModel->register($firstname, $lastname, $email, $password)) {
                    error_log("Inscription réussie pour {$email}, redirection vers /login");
                    header('Location: /login');
                    exit;
                } else {
                    error_log("Échec d'inscription: email {$email} déjà utilisé");
                    $errors[] = "Cette adresse email est déjà utilisée";
                }
            } else {
                error_log("Erreurs de validation: " . implode(", ", $errors));
            }
        } else {
            error_log("Affichage du formulaire d'inscription (GET)");
        }

        // Charger la vue
        $title = "Inscription - Gestion d'Événements";
        error_log("Chargement de la vue register.php");

        // S'assurer que le buffer est vide et prêt à être utilisé
        if (ob_get_level()) {
            ob_end_clean();
        }

        ob_start();
        require_once __DIR__ . '/../views/user/register.php';
        $content = ob_get_clean();

        // Vérifier que le contenu a bien été chargé
        if (empty($content)) {
            error_log("ERREUR: Le contenu de la vue register.php est vide");
            $content = '<div class="alert alert-danger">Erreur lors du chargement du formulaire d\'inscription</div>';
        } else {
            error_log("Vue register.php chargée avec succès (" . strlen($content) . " caractères)");
        }

        error_log("Chargement du layout main.php");
        require_once __DIR__ . '/../views/layouts/main.php';
        error_log("Layout main.php chargé");
    }

    public function login()
    {
        error_log("Début de la méthode login du UserController");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("Traitement du formulaire de connexion (POST)");
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Débogage des informations reçues
            error_log("Email soumis: " . $email);
            error_log("Mot de passe soumis: [CACHÉ POUR SÉCURITÉ]");

            // Vérifier si l'utilisateur existe dans la base de données
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                error_log("Utilisateur trouvé dans la base de données: " . $user['email'] . " (ID: " . $user['id'] . ", Rôle: " . $user['role'] . ", Statut: " . $user['status'] . ")");

                // Vérifier si le mot de passe est correct
                $password_matches = password_verify($password, $user['password_hash']);
                error_log("Vérification du mot de passe: " . ($password_matches ? "Réussie" : "Échouée"));
            } else {
                error_log("Aucun utilisateur trouvé avec l'email: " . $email);
            }

            if ($this->userModel->login($email, $password)) {
                $_SESSION['user_id'] = $this->userModel->getId();
                $_SESSION['user_role'] = $this->userModel->getRole();
                error_log("Connexion réussie pour {$email}, redirection vers /profile");
                header('Location: /profile');
                exit;
            } else {
                error_log("Échec de connexion pour {$email}");
                $error = "Email ou mot de passe incorrect";
            }
        } else {
            error_log("Affichage du formulaire de connexion (GET)");
        }

        // Charger la vue
        $title = "Connexion - Gestion d'Événements";
        error_log("Chargement de la vue login.php");

        // S'assurer que le buffer est vide et prêt à être utilisé
        if (ob_get_level()) {
            ob_end_clean();
        }

        ob_start();
        require_once __DIR__ . '/../views/user/login.php';
        $content = ob_get_clean();

        // Vérifier que le contenu a bien été chargé
        if (empty($content)) {
            error_log("ERREUR: Le contenu de la vue login.php est vide");
            $content = '<div class="alert alert-danger">Erreur lors du chargement du formulaire de connexion</div>';
        } else {
            error_log("Vue login.php chargée avec succès (" . strlen($content) . " caractères)");
        }

        error_log("Chargement du layout main.php");
        require_once __DIR__ . '/../views/layouts/main.php';
        error_log("Layout main.php chargé");
    }

    public function profile()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $user = $this->userModel->getUserById($_SESSION['user_id']);

        // Charger la vue
        $title = "Mon Profil - Gestion d'Événements";

        // S'assurer que le buffer est vide et prêt à être utilisé
        if (ob_get_level()) {
            ob_end_clean();
        }

        // Passer l'objet de base de données à la vue
        $db = $this->db;

        ob_start();
        require_once __DIR__ . '/../views/user/profile.php';
        $content = ob_get_clean();

        // Vérifier que le contenu a bien été chargé
        if (empty($content)) {
            error_log("ERREUR: Le contenu de la vue profile.php est vide");
            $content = '<div class="alert alert-danger">Erreur lors du chargement du profil</div>';
        }

        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function updateProfile()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstname = $_POST['firstname'] ?? '';
            $lastname = $_POST['lastname'] ?? '';
            $email = $_POST['email'] ?? '';

            $errors = [];
            if (empty($firstname)) $errors[] = "Le prénom est requis";
            if (empty($lastname)) $errors[] = "Le nom est requis";
            if (empty($email)) $errors[] = "L'email est requis";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "L'email n'est pas valide";

            if (empty($errors)) {
                if ($this->userModel->updateProfile($_SESSION['user_id'], $firstname, $lastname, $email)) {
                    header('Location: /profile');
                    exit;
                } else {
                    $errors[] = "Erreur lors de la mise à jour du profil";
                }
            }
        }

        $user = $this->userModel->getUserById($_SESSION['user_id']);

        // Charger la vue
        $title = "Modifier mon profil - Gestion d'Événements";
        ob_start();
        require_once __DIR__ . '/../views/user/update_profile.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function changePassword()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current_password = $_POST['current_password'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            $errors = [];
            if (empty($current_password)) $errors[] = "Le mot de passe actuel est requis";
            if (empty($new_password)) $errors[] = "Le nouveau mot de passe est requis";
            if (strlen($new_password) < 8) $errors[] = "Le nouveau mot de passe doit contenir au moins 8 caractères";
            if ($new_password !== $confirm_password) $errors[] = "Les nouveaux mots de passe ne correspondent pas";

            if (empty($errors)) {
                $user = $this->userModel->getUserById($_SESSION['user_id']);
                if (password_verify($current_password, $user['password_hash'])) {
                    if ($this->userModel->changePassword($_SESSION['user_id'], $new_password)) {
                        header('Location: /profile');
                        exit;
                    } else {
                        $errors[] = "Erreur lors du changement de mot de passe";
                    }
                } else {
                    $errors[] = "Le mot de passe actuel est incorrect";
                }
            }
        }

        // Charger la vue
        $title = "Changer de mot de passe - Gestion d'Événements";
        ob_start();
        require_once __DIR__ . '/../views/user/change_password.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function deactivateAccount()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->userModel->deactivateAccount($_SESSION['user_id'])) {
                session_destroy();
                header('Location: /');
                exit;
            }
        }

        // Charger la vue
        $title = "Désactiver mon compte - Gestion d'Événements";
        ob_start();
        require_once __DIR__ . '/../views/user/deactivate_account.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function deleteAccount()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->userModel->deleteAccount($_SESSION['user_id'])) {
                session_destroy();
                header('Location: /');
                exit;
            }
        }

        // Charger la vue
        $title = "Supprimer mon compte - Gestion d'Événements";
        ob_start();
        require_once __DIR__ . '/../views/user/delete_account.php';
        $content = ob_get_clean();

        require_once __DIR__ . '/../views/layouts/main.php';
    }

    public function logout()
    {
        session_destroy();
        header('Location: /');
        exit;
    }
}
