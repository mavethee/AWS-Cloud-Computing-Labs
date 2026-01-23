<?php
require_once 'connect.php';
session_start(); // Ważne: uruchamiamy sesję, żeby wiedzieć kim jesteś

// Ustalamy ID użytkownika (na podstawie sesji przeglądarki)
$current_user_id = session_id();

// Prosty "Fake Admin" - jeśli w adresie jest ?admin=1, to jesteś szefem
// W prawdziwym życiu tu byłoby logowanie hasłem
$is_admin = isset($_GET['admin']) && $_GET['admin'] == '1';

// Inicjalizacja zmiennych
$id = ""; $imie = ""; $nazwisko = ""; $miasto = "";
$tryb_edycji = false;

// --- LOGIKA USUWANIA ---
if (isset($_GET['delete'])) {
    $id_del = $_GET['delete'];
    
    if ($is_admin) {
        // Admin usuwa wszystko jak leci
        $stmt = $conn->prepare("DELETE FROM osoby WHERE id = ?");
        $stmt->bind_param("i", $id_del);
    } else {
        // Zwykły user usuwa TYLKO swoje (sprawdzamy owner_id)
        $stmt = $conn->prepare("DELETE FROM osoby WHERE id = ? AND owner_id = ?");
        $stmt->bind_param("is", $id_del, $current_user_id);
    }
    
    $stmt->execute();
    
    // Jeśli nic nie usunięto (bo próbowałeś usunąć wpis admina), to trudno :)
    header("Location: index.php" . ($is_admin ? "?admin=1" : ""));
    exit();
}

// --- LOGIKA EDYCJI (POBIERANIE) ---
if (isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    
    // Pobieramy dane tylko jeśli masz do nich prawo
    if ($is_admin) {
        $stmt = $conn->prepare("SELECT * FROM osoby WHERE id = ?");
        $stmt->bind_param("i", $id_edit);
    } else {
        $stmt = $conn->prepare("SELECT * FROM osoby WHERE id = ? AND owner_id = ?");
        $stmt->bind_param("is", $id_edit, $current_user_id);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row['id'];
        $imie = $row['imie'];
        $nazwisko = $row['nazwisko'];
        $miasto = $row['miasto'];
        $tryb_edycji = true;
    }
}

// --- LOGIKA ZAPISU (DODAWANIE / UPDATE) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $miasto = $_POST['miasto'];
    $id = $_POST['id'];

    if (!empty($id)) {
        // AKTUALIZACJA
        if ($is_admin) {
            $stmt = $conn->prepare("UPDATE osoby SET imie=?, nazwisko=?, miasto=? WHERE id=?");
            $stmt->bind_param("sssi", $imie, $nazwisko, $miasto, $id);
        } else {
            // Aktualizuj tylko SWOJE
            $stmt = $conn->prepare("UPDATE osoby SET imie=?, nazwisko=?, miasto=? WHERE id=? AND owner_id=?");
            $stmt->bind_param("sssis", $imie, $nazwisko, $miasto, $id, $current_user_id);
        }
    } else {
        // DODAWANIE (Przypisujemy Twoje ID sesji jako właściciela)
        $stmt = $conn->prepare("INSERT INTO osoby (imie, nazwisko, miasto, owner_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $imie, $nazwisko, $miasto, $current_user_id);
    }
    
    $stmt->execute();
    header("Location: index.php" . ($is_admin ? "?admin=1" : ""));
    exit();
}

// Pobieranie listy
$lista_osob = $conn->query("SELECT * FROM osoby ORDER BY id DESC");
?>
