CREATE DATABASE tansiyon_kayit;

USE tansiyon_kayit;

-- Kullanıcılar tablosu
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tansiyon kayıtları tablosu
CREATE TABLE blood_pressure_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    systolic INT NOT NULL,
    diastolic INT NOT NULL,
    pulse INT NOT NULL,
    record_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


-- Varsayılan admin kullanıcısını ekleme
INSERT INTO users (username, password, role) VALUES ('admin', '$2y$10$1SRUSqEJVmP2.6i.oZ185uV01Ufnzs7brnHB/GuP2nPXs8rIWPpMi', 'admin');
