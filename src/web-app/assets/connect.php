<?php
// Raportowanie błędów (pomaga przy debugowaniu)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = "[AWS RDS ENDPOINT LINK]";
$user = "app_user";
$pass = "TajneHasloAplikacji";
$dbname = "projekt";

try {
    $conn = new mysqli($host, $user, $pass, $dbname);
    $conn->set_charset("utf8");
} catch (mysqli_sql_exception $e) {
    die("<h3>Błąd połączenia z bazą danych!</h3>
         Sprawdź czy baza '<b>$dbname</b>' istnieje w phpMyAdmin.<br>
         Błąd: " . $e->getMessage());
}
?>