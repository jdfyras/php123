-- Utiliser la base de données
USE event_management;

-- Désactiver la vérification des clés étrangères
SET FOREIGN_KEY_CHECKS = 0;

-- Supprimer les données existantes dans l'ordre inverse des dépendances
DELETE FROM reviews;
DELETE FROM reservations;
DELETE FROM events;
DELETE FROM users;

-- Réactiver la vérification des clés étrangères
SET FOREIGN_KEY_CHECKS = 1;

-- Insert admin user (password: Admin123!)
INSERT INTO users (firstname, lastname, email, password_hash, role, status, is_verified) VALUES
('Admin', 'System', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'actif', 1);

-- Insert sample users (password: Password123!)
INSERT INTO users (firstname, lastname, email, password_hash, role, status, is_verified) VALUES
('Jean', 'Dupont', 'jean@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'actif', 1),
('Marie', 'Martin', 'marie@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'actif', 1),
('Pierre', 'Bernard', 'pierre@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'actif', 1);

-- Insert sample events
INSERT INTO events (title, description, date, location, capacity, price, category, status, created_by) VALUES
('Concert de Jazz', 'Une soirée exceptionnelle avec les meilleurs artistes de jazz.', DATE_ADD(NOW(), INTERVAL 1 MONTH), 'Salle Pleyel, Paris', 200, 45.00, 'Concert', 'upcoming', 1),
('Festival de Théâtre', 'Trois jours de représentations théâtrales.', DATE_ADD(NOW(), INTERVAL 2 MONTH), 'Théâtre de la Ville, Paris', 150, 30.00, 'Théâtre', 'upcoming', 1),
('Exposition d''Art Moderne', 'Découvrez les œuvres d''artistes contemporains.', DATE_ADD(NOW(), INTERVAL 15 DAY), 'Galerie d''Art Modern, Paris', 100, 15.00, 'Exposition', 'upcoming', 1),
('Conférence Tech', 'Les dernières innovations technologiques.', DATE_ADD(NOW(), INTERVAL 3 WEEK), 'Centre de Conférences, Paris', 300, 25.00, 'Conférence', 'upcoming', 1);

-- Insert sample reservations
INSERT INTO reservations (user_id, event_id, status, number_of_tickets, total_price, payment_status) VALUES
(2, 1, 'confirmed', 2, 90.00, 'completed'),
(3, 1, 'confirmed', 1, 45.00, 'completed'),
(2, 2, 'confirmed', 3, 90.00, 'completed'),
(4, 3, 'pending', 2, 30.00, 'pending');

-- Insert sample reviews
INSERT INTO reviews (user_id, event_id, rating, comment) VALUES
(2, 1, 5, 'Excellent concert ! Une ambiance extraordinaire.'),
(3, 1, 4, 'Très bonne soirée, musiciens talentueux.'),
(2, 2, 5, 'Superbe mise en scène et excellents acteurs.'),
(4, 2, 4, 'Une belle découverte théâtrale.');

-- Insert sample notifications
INSERT INTO notifications (user_id, title, message, type) VALUES
(2, 'Confirmation de réservation', 'Votre réservation pour le Concert de Jazz a été confirmée.', 'success'),
(3, 'Rappel événement', 'Le Concert de Jazz aura lieu demain !', 'info'),
(2, 'Nouveau commentaire', 'Quelqu''un a commenté un événement que vous suivez.', 'info'),
(4, 'Paiement en attente', 'N''oubliez pas de finaliser votre paiement pour l''Exposition d''Art Moderne.', 'warning');

-- Vérifier l'insertion des utilisateurs
SELECT 'Users inserted:', COUNT(*) as user_count FROM users;

-- Vérifier l'insertion des événements
SELECT 'Events inserted:', COUNT(*) as event_count FROM events;

-- Vérifier l'insertion des réservations
SELECT 'Reservations inserted:', COUNT(*) as reservation_count FROM reservations;

-- Vérifier l'insertion des avis
SELECT 'Reviews inserted:', COUNT(*) as review_count FROM reviews; 