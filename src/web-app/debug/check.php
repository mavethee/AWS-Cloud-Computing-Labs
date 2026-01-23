<?php
// Ten skrypt sprawdzi, co naprawdę widzi PHP
$dbname = ""; // Tu wpisz dokładnie to samo, co masz w index.php
$conn = new mysqli("", "", "", $dbname);

if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

echo "<h1>Połączono z bazą: $dbname</h1>";

// Zapytanie do MySQL: "Pokaż mi wszystkie tabele, jakie tu masz"
$result = $conn->query("SHOW TABLES");

echo "<h3>Lista tabel w tej bazie:</h3>";
if ($result->num_rows > 0) {
    echo "<ul>";
    while($row = $result->fetch_array()) {
        // Wypisujemy dokładną nazwę tabeli (ważna wielkość liter na Macu!)
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color:red; font-weight:bold;'>Baza jest PUSTA! PHP nie widzi tu żadnych tabel.</p>";
    echo "<p>Prawdopodobnie stworzyłeś tabelę 'osoby' w innej bazie danych w phpMyAdmin.</p>";
}
?>
