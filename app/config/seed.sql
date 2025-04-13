-- Utiliser la base de données
USE event_management;

-- Désactiver la vérification des clés étrangères
SET FOREIGN_KEY_CHECKS = 0;

-- Supprimer les données existantes
TRUNCATE TABLE reviews;
TRUNCATE TABLE reservations;
TRUNCATE TABLE events;
TRUNCATE TABLE users;

-- Réactiver la vérification des clés étrangères
SET FOREIGN_KEY_CHECKS = 1;

-- Insérer des utilisateurs (mot de passe "password123" hashé)
INSERT INTO users (firstname, lastname, email, password_hash, role, status, created_at)
VALUES 
('John', 'Doe', 'john@example.com', '$2y$10$T8ADVDUEdQmpM3AuYkZuwe/vTXlmnxL1OmQeVWnZgEpLDnWYVmb3W', 'user', 'actif', NOW()),
('Jane', 'Smith', 'jane@example.com', '$2y$10$T8ADVDUEdQmpM3AuYkZuwe/vTXlmnxL1OmQeVWnZgEpLDnWYVmb3W', 'organizer', 'actif', NOW()),
('Admin', 'User', 'admin@example.com', '$2y$10$T8ADVDUEdQmpM3AuYkZuwe/vTXlmnxL1OmQeVWnZgEpLDnWYVmb3W', 'admin', 'actif', NOW());

-- Insérer des événements
INSERT INTO events (title, description, date, location, price, available_tickets, created_at)
VALUES 
('Concert de Jazz', 'Un magnifique concert de jazz avec les meilleurs artistes de la scène locale. Une soirée à ne pas manquer!', '2025-06-15 20:00:00', 'Salle Pleyel, Paris', 35.50, 150, NOW()),
('Festival de Musique', 'Festival de musique en plein air avec plusieurs scènes et des artistes internationaux', '2025-07-20 14:00:00', 'Parc des Expositions, Lyon', 65.00, 500, NOW()),
('Conférence Tech', 'Découvrez les dernières innovations technologiques présentées par les experts du domaine', '2025-05-10 09:00:00', 'Centre de Congrès, Marseille', 25.00, 200, NOW()),
('Théâtre : Roméo et Juliette', 'Représentation de la célèbre pièce de Shakespeare par une troupe renommée', '2025-06-05 19:30:00', 'Théâtre National, Nice', 45.00, 100, NOW()),
('Exposition d\'Art Moderne', 'Découvrez les œuvres des plus grands artistes contemporains', '2025-05-01 10:00:00', 'Galerie d\'Art, Bordeaux', 15.00, 300, NOW()),
('Match de Football', 'Match de championnat opposant les deux meilleures équipes de la saison', '2025-05-25 21:00:00', 'Stade Municipal, Lille', 30.00, 800, NOW());

-- Insérer des réservations
INSERT INTO reservations (user_id, event_id, quantity, total_price, status, created_at)
VALUES 
(1, 1, 2, 71.00, 'payé', NOW()),
(1, 3, 1, 25.00, 'payé', NOW()),
(2, 2, 3, 195.00, 'payé', NOW()),
(2, 4, 2, 90.00, 'en attente', NOW()),
(3, 5, 4, 60.00, 'payé', NOW()),
(3, 6, 2, 60.00, 'annulé', NOW());

-- Insérer des avis
INSERT INTO reviews (user_id, event_id, rating, comment, created_at)
VALUES 
(1, 1, 5, 'Concert exceptionnel ! Les musiciens étaient talentueux et l\'ambiance était incroyable.', NOW()),
(1, 3, 4, 'Conférence très intéressante avec des intervenants de qualité. Un petit bémol sur l\'organisation.', NOW()),
(2, 2, 5, 'Festival incroyable ! La programmation était variée et la qualité sonore impeccable.', NOW()),
(4, 5, 3, 'Exposition intéressante mais un peu courte pour le prix demandé.', NOW()); 