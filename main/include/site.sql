-- =========================================
-- Fichier d'initialisation : cv_generator.sql
-- Base de données pour un générateur de CV en ligne
-- Version améliorée : Sécurité, performance et intégrité renforcées
-- =========================================

/* =========================================
   1. CRÉATION DE LA BASE DE DONNÉES
========================================= */
CREATE DATABASE IF NOT EXISTS cv_generator_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE cv_generator_db;

/* =========================================
   2. TABLE UTILISATEURS
   - Index sur email pour des recherches rapides et unicité.
   - Rôle par défaut 'utilisateur'.
========================================= */
CREATE TABLE IF NOT EXISTS utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL, -- Hash bcrypt recommandé (longueur suffisante pour sécurité)
    role ENUM('utilisateur', 'admin') DEFAULT 'utilisateur' NOT NULL,
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email), -- Index pour accélérer les recherches par email
    UNIQUE KEY unique_email (email) -- Unicité renforcée
) ENGINE=InnoDB;

/* =========================================
   3. TABLE CV
   - Index sur utilisateur_id pour les JOIN rapides.
   - Template par défaut 'classique'.
========================================= */
CREATE TABLE IF NOT EXISTS cvs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT NOT NULL,
    titre VARCHAR(255) DEFAULT 'Mon CV' NOT NULL,
    poste VARCHAR(255),
    profil TEXT,
    telephone VARCHAR(30),
    adresse VARCHAR(255),
    photo VARCHAR(255), -- Chemin vers l'image uploadée
    template VARCHAR(50) DEFAULT 'classique' NOT NULL,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE,
    INDEX idx_utilisateur_id (utilisateur_id), -- Index pour les requêtes par utilisateur
    INDEX idx_date_creation (date_creation) -- Index pour trier par date
) ENGINE=InnoDB;

/* =========================================
   4. TABLE EXPÉRIENCES PROFESSIONNELLES
   - Check pour s'assurer que date_fin >= date_debut (si supporté par MySQL 8+).
========================================= */
CREATE TABLE IF NOT EXISTS experiences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cv_id INT NOT NULL,
    poste VARCHAR(255) NOT NULL,
    entreprise VARCHAR(255) NOT NULL,
    date_debut DATE,
    date_fin DATE,
    description TEXT,
    FOREIGN KEY (cv_id) REFERENCES cvs(id) ON DELETE CASCADE,
    INDEX idx_cv_id (cv_id), -- Index pour les JOIN
    CONSTRAINT chk_dates_experience CHECK (date_fin IS NULL OR date_fin >= date_debut) -- Vérification des dates (optionnel, selon version MySQL)
) ENGINE=InnoDB;

/* =========================================
   5. TABLE FORMATIONS
   - Similaire à expériences, avec check sur les dates.
========================================= */
CREATE TABLE IF NOT EXISTS formations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cv_id INT NOT NULL,
    diplome VARCHAR(255) NOT NULL,
    etablissement VARCHAR(255) NOT NULL,
    date_debut DATE,
    date_fin DATE,
    description TEXT,
    FOREIGN KEY (cv_id) REFERENCES cvs(id) ON DELETE CASCADE,
    INDEX idx_cv_id (cv_id),
    CONSTRAINT chk_dates_formation CHECK (date_fin IS NULL OR date_fin >= date_debut)
) ENGINE=InnoDB;

/* =========================================
   6. TABLE COMPÉTENCES
   - Niveau par défaut 'Intermédiaire'.
========================================= */
CREATE TABLE IF NOT EXISTS competences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cv_id INT NOT NULL,
    nom VARCHAR(100) NOT NULL,
    niveau ENUM('Débutant', 'Intermédiaire', 'Avancé', 'Expert') DEFAULT 'Intermédiaire' NOT NULL,
    FOREIGN KEY (cv_id) REFERENCES cvs(id) ON DELETE CASCADE,
    INDEX idx_cv_id (cv_id)
) ENGINE=InnoDB;

/* =========================================
   7. TABLE LANGUES
   - Niveau par défaut 'Conversationnel'.
========================================= */
CREATE TABLE IF NOT EXISTS langues (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cv_id INT NOT NULL,
    langue VARCHAR(100) NOT NULL,
    niveau ENUM('Basique', 'Conversationnel', 'Courant', 'Bilingue', 'Natif') DEFAULT 'Conversationnel' NOT NULL,
    FOREIGN KEY (cv_id) REFERENCES cvs(id) ON DELETE CASCADE,
    INDEX idx_cv_id (cv_id)
) ENGINE=InnoDB;

/* =========================================
   8. TABLE CENTRES D'INTÉRÊT (OPTIONNEL)
   - Pour compléter le CV avec hobbies/loisirs.
========================================= */
CREATE TABLE IF NOT EXISTS centres_interet (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cv_id INT NOT NULL,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    FOREIGN KEY (cv_id) REFERENCES cvs(id) ON DELETE CASCADE,
    INDEX idx_cv_id (cv_id)
) ENGINE=InnoDB;

/* =========================================
   9. TABLE LOGS D'ACTIVITÉ (OPTIONNEL)
   - Pour tracer les actions des utilisateurs (ex. : création/modification de CV).
========================================= */
CREATE TABLE IF NOT EXISTS logs_activite (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT,
    action VARCHAR(255) NOT NULL, -- Ex. : 'CV créé', 'Connexion'
    details TEXT,
    date_action TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE SET NULL,
    INDEX idx_utilisateur_id (utilisateur_id),
    INDEX idx_date_action (date_action)
) ENGINE=InnoDB;

/* =========================================
   10. TRIGGERS POUR MAINTENANCE AUTOMATIQUE
   - Mise à jour automatique des timestamps et logs.
========================================= */
-- Trigger pour loguer la création d'un CV
DELIMITER //
CREATE TRIGGER trg_log_cv_creation AFTER INSERT ON cvs
FOR EACH ROW
BEGIN
    INSERT INTO logs_activite (utilisateur_id, action, details) 
    VALUES (NEW.utilisateur_id, 'CV créé', CONCAT('Titre : ', NEW.titre));
END;
//
DELIMITER ;

-- Trigger pour loguer la modification d'un CV
DELIMITER //
CREATE TRIGGER trg_log_cv_modification AFTER UPDATE ON cvs
FOR EACH ROW
BEGIN
    INSERT INTO logs_activite (utilisateur_id, action, details) 
    VALUES (NEW.utilisateur_id, 'CV modifié', CONCAT('Titre : ', NEW.titre));
END;
//
DELIMITER ;

/* =========================================
   11. DONNÉES DE TEST (OPTIONNEL)
   - Utilisateur exemple avec hash bcrypt réel pour "password123".
   - Remplace le hash par un généré dynamiquement en PHP si nécessaire.
========================================= */
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role)
VALUES (
    'Doe',
    'John',
    'john.doe@email.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- Hash bcrypt pour "password123"
    'utilisateur'
);

-- Exemple de CV pour l'utilisateur test
INSERT INTO cvs (utilisateur_id, titre, poste, profil, telephone, adresse)
VALUES (
    1, -- ID de l'utilisateur inséré
    'Mon CV Développeur',
    'Développeur Web',
    'Passionné par le développement full-stack.',
    '+33 6 12 34 56 78',
    '123 Rue Exemple, Paris, France'
);

-- Exemples d'expériences, formations, etc. pour le CV
INSERT INTO experiences (cv_id, poste, entreprise, date_debut, date_fin, description)
VALUES (1, 'Développeur Junior', 'TechCorp', '2020-01-01', '2022-12-31', 'Développement d\'applications web.');

INSERT INTO formations (cv_id, diplome, etablissement, date_debut, date_fin, description)
VALUES (1, 'Master Informatique', 'Université Exemple', '2018-09-01', '2020-06-30', 'Spécialisation en développement.');

INSERT INTO competences (cv_id, nom, niveau)
VALUES (1, 'PHP', 'Avancé'), (1, 'JavaScript', 'Expert');

INSERT INTO langues (cv_id, langue, niveau)
VALUES (1, 'Français', 'Natif'), (1, 'Anglais', 'Courant');

INSERT INTO centres_interet (cv_id, nom, description)
VALUES (1, 'Voyages', 'Exploration de nouvelles cultures.');

-- Fin du script
