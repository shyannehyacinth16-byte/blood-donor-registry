-- Blood Donor Registry schema + sample data
CREATE DATABASE IF NOT EXISTS blood_donor_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE blood_donor_db;

DROP TABLE IF EXISTS donors;
CREATE TABLE donors (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  blood_type ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  phone VARCHAR(20) NOT NULL,
  email VARCHAR(120) DEFAULT NULL,
  city VARCHAR(80) NOT NULL,
  last_donation_date DATE DEFAULT NULL,
  available TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_blood_type (blood_type),
  INDEX idx_city (city)
) ENGINE=InnoDB;

INSERT INTO donors (full_name, blood_type, phone, email, city, last_donation_date, available) VALUES
('Amara Johnson',  'O-',  '+1 473 555 0101', 'amara@example.com',  'St. George''s', '2026-05-14', 1),
('Daniel Phillip', 'A+',  '+1 473 555 0102', 'daniel@example.com', 'Grenville',     '2026-08-02', 0),
('Keisha Roberts', 'B+',  '+1 473 555 0103', 'keisha@example.com', 'Victoria',      NULL,         1),
('Marcus Bain',    'AB+', '+1 473 555 0104', NULL,                 'Sauteurs',      '2026-03-21', 1),
('Tanya Charles',  'O+',  '+1 473 555 0105', 'tanya@example.com',  'Gouyave',       '2026-06-30', 1);

-- Staff accounts (passwords are bcrypt hashes, never plain text)
DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Default demo account: admin / ChangeMe123!   (change it after deploying)
INSERT INTO users (username, password_hash) VALUES
('admin', '$2b$10$ckHt5AnGlkorndC0a5VX7e2kQgF8Q.7Xi.wLJaM1tWCRx.KGmIJme');
