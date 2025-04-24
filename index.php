<?php
// Zapnutie zobrazovania všetkých chýb pre debugovanie
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Spustenie session pre ukladanie údajov medzi požiadavkami
session_start();

// Načítanie potrebných tried
require_once 'classes/HelperMySQLi.php';
require_once 'classes/Student.php';

// Kontrola, či bola odoslaná POST požiadavka
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Získanie a orezanie mena z formulára
    $name = trim($_POST['name']);

    // Zabránime uloženiu používateľa s menom "all"
    if (strtolower($name) !== 'all') {
        try {
            // Získanie alebo vytvorenie študenta podľa mena
            $studentData = Student::getOrCreateStudent($name);
            // Uloženie mena študenta do session
            $_SESSION['name'] = $studentData['name'];
        } catch (Exception $e) {
            // Spracovanie chyby a zobrazenie chybovej správy
            echo "Chyba pri spracovaní študenta: " . $e->getMessage();
            exit;
        }
    } else {
        // Ak je meno "all", uložíme ho priamo do session
        $_SESSION['name'] = 'all';
    }

    // Presmerovanie na stránku profilu
    header("Location: profile.php");
    exit;
}
?>

<!-- HTML pre prihlasovanie -->
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Prihlásenie</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="index-page">
    <div class="login-container">
        <div class="login-card">
            <h1>Prihlás sa</h1>
            <form method="POST">
                <label for="name">Meno študenta:</label>
                <!-- Pole pre zadanie mena študenta -->
                <input type="text" id="name" name="name" required>
                <!-- Tlačidlo na odoslanie formulára -->
                <button type="submit">Prihlásiť sa</button>
            </form>
        </div>
    </div>
</body>
</html>
