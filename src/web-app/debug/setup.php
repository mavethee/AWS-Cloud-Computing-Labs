<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// =======================================================
// KONFIGURACJA
// =======================================================
$host = "[AWS RDS ENDPOINT TUTAJ]"; 
$user = "";
$pass = "";
// =======================================================

try {
    // 1. Połączenie z serwerem
    $conn = new mysqli($host, $user, $pass);
    echo "Połączono z RDS...<br>";

    // 2. Skrypt SQL (Tworzy bazę, usera aplikacji i tabele)
    $sql = "
        CREATE DATABASE IF NOT EXISTS projekt;
        USE projekt;

        -- Tworzymy usera, którego brakuje Twojej aplikacji
        CREATE USER IF NOT EXISTS 'app_user'@'%' IDENTIFIED BY 'TajneHasloAplikacji'; 
        GRANT SELECT, INSERT, UPDATE, DELETE ON projekt.* TO 'app_user'@'%';
        FLUSH PRIVILEGES;

        -- Tworzymy tabelę
        DROP TABLE IF EXISTS osoby;
        CREATE TABLE osoby (
            id INT AUTO_INCREMENT PRIMARY KEY,
            imie VARCHAR(50) NOT NULL,
            nazwisko VARCHAR(50) NOT NULL,
            miasto VARCHAR(50) NOT NULL,
            owner_id VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );

        -- Dane startowe
        INSERT INTO osoby (imie, nazwisko, miasto, owner_id) VALUES 
        ('Jan', 'Kowalski', 'Warszawa', 'admin'),
        ('Anna', 'Nowak', 'Gdańsk', 'admin');
    ";

    // 3. Wykonanie wielu zapytań naraz
    if ($conn->multi_query($sql)) {
        do {
            // Musimy 'przewinąć' wyniki, żeby nie blokować połączenia
            if ($result = $conn->store_result()) { 
                $result->free(); 
            }
        } while ($conn->next_result());
        
        echo "<h1 style='color:green; border: 2px solid green; padding: 20px;'>SUKCES! Baza gotowa.</h1>";
        echo "<h3>Użytkownik 'app_user' został utworzony.</h3>";
        echo "<p>Teraz wejdź na <a href='index.php'>index.php</a> - powinno działać!</p>";
    }

} catch (Exception $e) {
    echo "<h1 style='color:red'>Błąd krytyczny:</h1>";
    echo "<strong>" . $e->getMessage() . "</strong><br><br>";
    echo "Sprawdź czy:<br>";
    echo "1. Wpisałeś dobry <b>\$host</b> (Endpoint RDS) na górze tego pliku?<br>";
    echo "2. Hasło roota w YAML to na pewno 'DBPassw0rd!'?<br>";
}
?>