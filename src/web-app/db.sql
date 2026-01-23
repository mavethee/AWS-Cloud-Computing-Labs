USE projekt;

-- 1. Tworzymy użytkownika dla PHP (żeby nie używać roota w pliku connect.php)
CREATE USER IF NOT EXISTS 'app_user'@'%' IDENTIFIED BY 'TajneHasloAplikacji'; 
GRANT SELECT, INSERT, UPDATE, DELETE ON projekt.* TO 'app_user'@'%';
FLUSH PRIVILEGES;

-- 2. Tworzymy tabelę z kolumną owner_id
DROP TABLE IF EXISTS osoby;
CREATE TABLE osoby (
    id INT AUTO_INCREMENT PRIMARY KEY,
    imie VARCHAR(50) NOT NULL,
    nazwisko VARCHAR(50) NOT NULL,
    miasto VARCHAR(50) NOT NULL,
    owner_id VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Dane testowe (owner_id='admin' symuluje administratora)
INSERT INTO osoby (imie, nazwisko, miasto, owner_id) VALUES 
('Jan', 'Kowalski', 'Warszawa', 'admin'),
('Anna', 'Nowak', 'Gdańsk', 'admin');
