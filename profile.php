<?php
session_start();
require_once 'classes/HelperMySQLi.php';
require_once 'classes/Student.php';
require_once 'classes/Arrival.php';

if (!isset($_SESSION['name'])) {
    echo "Nie ste prihlásený.";
    exit;
}

$studentName = $_SESSION['name'];

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

if (isset($_POST['add_arrival']) && $studentName !== 'all') {
    $arrival = new Arrival($studentName);
    $arrival->logArrival();
    header("Location: profile.php");
    exit;
}

$arrivals = $studentName === 'all'
    ? HelperMySQLi::getAllArrivals()
    : HelperMySQLi::getArrivalsByStudent($studentName);

usort($arrivals, fn($a, $b) => strtotime($a['time']) <=> strtotime($b['time']));
$grouped = [];
foreach ($arrivals as $arrival) {
    $date = date('Y-m-d', strtotime($arrival['time']));
    $grouped[$date][] = $arrival;
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Profil</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body class="profile-page">
<header id="header">
    <div class="left">Počet príchodov: <?= count($arrivals) ?></div>
    <div class="right">
        <div class="profile-wrapper">
            <img src="icon-profile.svg" alt="Profil" class="profile-icon">
            <span><?= htmlspecialchars($studentName) ?></span>
        </div>
    </div>
</header>

<div class="container">
    <div class="main-content">
        <?php if (empty($arrivals)): ?>
            <p>Žiadne príchody.</p>
        <?php else: ?>
            <?php foreach ($grouped as $date => $entries): ?>
                <div class="date-block">
                    <div class="date-heading"><?= htmlspecialchars($date) ?></div>
                    <div class="date-group">
                        <?php foreach ($entries as $index => $entry): ?>
                            <div class="arrival">
                                <span class="time"><?= $index + 1 ?>. <?= date('H:i:s', strtotime($entry['time'])) ?></span>
                                <span class="name"><?= $studentName === 'all' ? htmlspecialchars($entry['name']) : '' ?></span>
                                <span class="note"><?= $entry['late'] ? 'Meškanie' : '' ?></span>
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
            <?php if ($studentName !== 'all'): ?>
                <button type="submit" name="add_arrival">Zaznač príchod</button>
            <?php endif; ?>
            <button type="submit" name="logout">Odhlásiť sa</button>
            </form>
        </div>
    </div>

<script>
        // Sticky header behavior on scroll
        window.onscroll = function() {
            let header = document.getElementById('header');
            if (window.scrollY > 0) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }
    </script>
</body>
</html>
