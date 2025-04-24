<?php
// Zapnutie zobrazovania všetkých chýb pre debugovanie
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Spustenie session
session_start();

// Načítanie potrebných tried
require_once 'classes/HelperMySQLi.php';
require_once 'classes/Arrival.php';
require_once 'classes/Student.php';

// Získanie mena používateľa zo session
$name = $_SESSION['name'] ?? '';

// Kontrola, či je používateľ "all" alebo konkrétny študent
if ($name === 'all') {
    // Získať všetky príchody
    $arrivals = HelperMySQLi::getAllArrivals();
} else {
    // Získať príchody pre konkrétneho študenta
    $student = new Student($name);
    $arrivals = HelperMySQLi::getStudentArrivals($name);
}

// Zoskupenie príchodov podľa dátumu
$grouped = [];
if (!empty($arrivals)) {
    foreach ($arrivals as $arrival) {
        $date = date('Y-m-d', strtotime($arrival['time']));
        $grouped[$date][] = $arrival;
    }
}

// Odhlásenie používateľa
if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

// Pridanie nového príchodu
if (isset($_POST['add_arrival'])) {
    if ($name !== 'all') {
        $arrival = new Arrival($name);
        $arrival->logArrival($name);
    }
    header('Location: profile.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Profil</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="profile-page">
<header id="header">
    <!-- Zobrazenie počtu príchodov -->
    <div class="left">Počet príchodov: <?= count($arrivals ?? []) ?></div>
    <div class="right">
        <div class="profile-wrapper">
            <!-- Ikona profilu a meno používateľa -->
            <img src="icon-profile.svg" alt="Profil" class="profile-icon">
            <span>
                <?= htmlspecialchars($name ?? '') ?>
            </span>
        </div>
    </div>
</header>

<div class="container">
    <div class="main-content">
        <!-- Kontrola, či sú príchody prázdne -->
        <?php if (empty($arrivals)): ?>
            <p class="no-arrivals">Žiadne príchody.</p>
        <?php else: ?>
            <!-- Zobrazenie príchodov zoskupených podľa dátumu -->
            <?php foreach ($grouped as $date => $dailyArrivals): ?>
                <div class="date-block">
                    <div class="date-heading"><?= htmlspecialchars($date) ?></div>
                    <div class="date-group">
                        <?php foreach ($dailyArrivals as $index => $arrival): ?>
                            <div class="arrival">
                                <!-- Čas príchodu -->
                                <span class="time"><?= $index + 1 ?>. <?= date('H:i:s', strtotime($arrival['time'])) ?></span>
                                <!-- Meno študenta, ak je používateľ "all" -->
                                <span class="name">
                                    <?= ($name === 'all' && isset($arrival['name'])) ? htmlspecialchars($arrival['name']) : '' ?>
                                </span>
                                <!-- Poznámka o meškaní -->
                                <span class="note"><?= !empty($arrival['late']) ? 'Meškanie' : '' ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="sidebar">
        <div class="button-block">
            <form method="POST">
                <!-- Tlačidlo na pridanie príchodu, ak používateľ nie je "all" -->
                <?php if ($name !== 'all'): ?>
                    <button type="submit" name="add_arrival">Zaznač príchod</button>
                <?php endif; ?>
                <!-- Tlačidlo na odhlásenie -->
                <button type="submit" name="logout">Odhlásiť sa</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Pridanie triedy "scrolled" na header pri scrollovaní
    window.onscroll = function () {
        let header = document.getElementById('header');
        header.classList.toggle('scrolled', window.scrollY > 0);
    };
</script>
</body>
</html>
