# Système de Gestion d'Événements

Un système de gestion d'événements moderne développé en PHP, permettant aux utilisateurs de créer, gérer et réserver des événements. Le système comprend une interface d'administration complète et une expérience utilisateur intuitive.

## Fonctionnalités

### Pour les Utilisateurs
- 👥 Inscription et authentification des utilisateurs
- 🎫 Réservation de billets pour les événements
- 🔍 Recherche d'événements par nom, catégorie et date
- 📅 Consultation des événements à venir
- ⭐ Système d'avis et de notation
- 📧 Inscription à la newsletter

### Pour les Administrateurs
- 📊 Tableau de bord administratif
- 👥 Gestion complète des utilisateurs
- 🎭 Gestion des événements
- 🎟️ Gestion des réservations
- 📝 Gestion des avis
- 📊 Statistiques et rapports

### Caractéristiques Techniques
- 🔒 Authentification sécurisée
- 💳 Système de paiement sécurisé
- 📱 Design responsive
- 🎨 Interface moderne et intuitive
- 🔍 Recherche avancée
- 📊 Statistiques en temps réel

## Prérequis

- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Serveur Web (Apache recommandé)
- Composer (gestionnaire de dépendances PHP)

## Installation

1. Clonez le dépôt :
```bash
git clone https://github.com/votre-username/event-management.git
```

2. Installez les dépendances :
```bash
composer install
```

3. Configurez votre base de données :
   - Créez une base de données MySQL
   - Copiez le fichier `.env.example` vers `.env`
   - Modifiez les informations de connexion dans `.env`

4. Importez la structure de la base de données :
```bash
mysql -u votre_utilisateur -p votre_base_de_donnees < app/config/database.sql
```

5. Configurez votre serveur web :
   - Pointez le DocumentRoot vers le dossier `public/`
   - Activez le module mod_rewrite d'Apache

## Structure du Projet

```
event_management/
├── app/
│   ├── config/         # Configuration de l'application
│   ├── controllers/    # Contrôleurs
│   ├── models/         # Modèles
│   └── views/          # Vues
├── public/
│   ├── assets/        # Ressources statiques (CSS, JS, images)
│   └── index.php      # Point d'entrée de l'application
└── vendor/            # Dépendances
```

## Routes Principales

- `/` - Page d'accueil
- `/events` - Liste des événements
- `/events/details/{id}` - Détails d'un événement
- `/user/register` - Inscription
- `/user/login` - Connexion
- `/admin/dashboard` - Tableau de bord administrateur
- `/admin/users` - Gestion des utilisateurs
- `/admin/events` - Gestion des événements

## Comptes par Défaut

### Administrateur
- Email: admin@example.com
- Mot de passe: password123

### Utilisateur Test
- Email: user@example.com
- Mot de passe: password123

## Sécurité

- Protection contre les injections SQL (requêtes préparées)
- Protection CSRF
- Hachage sécurisé des mots de passe
- Validation des entrées utilisateur
- Sessions sécurisées

## Contribution

1. Fork le projet
2. Créez votre branche de fonctionnalité (`git checkout -b feature/AmazingFeature`)
3. Committez vos changements (`git commit -m 'Add some AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrez une Pull Request

## Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## Support

Pour toute question ou problème :
- Ouvrez une issue sur GitHub
- Contactez l'équipe de support à support@example.com

## Remerciements

- Bootstrap pour le framework CSS
- Font Awesome pour les icônes
- jQuery pour les fonctionnalités JavaScript
- La communauté PHP pour son soutien continu 