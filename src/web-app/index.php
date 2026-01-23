<?php require_once './assets/logic.php'; ?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Baza osób</title>
    <link rel="stylesheet" href="./assets/styles.css">
</head>
<body>

    <h1>Panel administracyjny</h1>

    <div class="editor-box">
        <h3><?php echo $tryb_edycji ? "Edytuj dane" : "Dodaj osobę"; ?></h3>
        
        <form method="post" action="index.php">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="text" name="imie" placeholder="Imię" value="<?php echo $imie; ?>" required><br>
            <input type="text" name="nazwisko" placeholder="Nazwisko" value="<?php echo $nazwisko; ?>" required><br>
            <input type="text" name="miasto" placeholder="Miasto" value="<?php echo $miasto; ?>" required><br>
            <input type="submit" value="<?php echo $tryb_edycji ? "Zapisz zmiany" : "Dodaj do bazy"; ?>">
            
            <?php if($tryb_edycji): ?>
                <a href="index.php" class="cancel">Anuluj</a>
            <?php endif; ?>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Imię</th>
                <th>Nazwisko</th>
                <th>Miasto</th>
                <th>Działanie</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($lista_osob->num_rows > 0):
                while($row = $lista_osob->fetch_assoc()): 
                    // Sprawdzamy czy to Twój wpis
                    $is_mine = ($row['owner_id'] == session_id());
                    // Sprawdzamy czy jesteś w trybie admina
                    $admin_mode = (isset($_GET['admin']) && $_GET['admin'] == '1');
            ?>
                    <tr style="<?php echo ($is_mine ? "background-color: #e8f5e9;" : ""); ?>">
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['imie']; ?></td>
                        <td><?php echo $row['nazwisko']; ?></td>
                        <td><?php echo $row['miasto']; ?></td>
                        <td>
                            <?php if ($is_mine || $admin_mode): ?>
                                <a href="index.php?edit=<?php echo $row['id']; ?><?php echo $admin_mode ? '&admin=1' : ''; ?>" class="btn-edit">Edytuj</a>
                                <a href="index.php?delete=<?php echo $row['id']; ?><?php echo $admin_mode ? '&admin=1' : ''; ?>" class="btn-del" onclick="return confirm('Na pewno usunąć?')">Usuń</a>
                            <?php else: ?>
                                <span style="color:#ccc; font-size:0.8em;">(Tylko odczyt)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile;
            else: ?>
                <tr><td colspan="5" style="text-align:center">Brak danych w bazie</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>