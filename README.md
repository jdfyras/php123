# Module Utilisateur - Système de Gestion d'Événements

Ce module fait partie d'un système de gestion d'événements et permet aux utilisateurs de s'inscrire, se connecter, gérer leur profil, réserver des tickets et laisser des avis sur les événements.

## Fonctionnalités

- Inscription et connexion des utilisateurs
- Gestion du profil utilisateur
- Réservation et paiement de tickets
- Gestion des avis sur les événements
- Désactivation/suppression de compte

## Prérequis

- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Apache avec mod_rewrite activé
- Composer (pour les dépendances futures)

## Installation

1. Clonez le dépôt :
```bash
git clone [URL_DU_REPO]
```

2. Créez la base de données :
```bash
mysql -u root -p < app/config/database.sql
```

3. Configurez la base de données :
   - Modifiez le fichier `app/config/database.php` avec vos paramètres de connexion

4. Configurez le serveur web :
   - Assurez-vous que le document root pointe vers le dossier `public`
   - Activez le module mod_rewrite d'Apache

## Structure du Projet

```
app/
├── config/         # Fichiers de configuration
├── controllers/    # Contrôleurs
├── models/         # Modèles
└── views/          # Vues
public/
├── css/            # Fichiers CSS
├── js/             # Fichiers JavaScript
└── images/         # Images
```

## Sécurité

- Utilisation de `password_hash()` et `password_verify()` pour le stockage sécurisé des mots de passe
- Protection contre les injections SQL via PDO et les requêtes préparées
- Protection CSRF sur les formulaires sensibles
- Validation des données côté serveur et client
- Assainissement des entrées utilisateur

## Utilisation

1. Inscription :
   - Accédez à `/register`
   - Remplissez le formulaire avec vos informations

2. Connexion :
   - Accédez à `/login`
   - Entrez vos identifiants

3. Gestion du profil :
   - Accédez à `/profile`
   - Modifiez vos informations personnelles
   - Changez votre mot de passe
   - Désactivez ou supprimez votre compte

## Contribution

Les contributions sont les bienvenues ! N'hésitez pas à :
1. Fork le projet
2. Créer une branche pour votre fonctionnalité
3. Commiter vos changements
4. Pousser vers la branche
5. Ouvrir une Pull Request

## Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails. 