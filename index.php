<?php
session_start();
require_once 'classes/HelperMySQLi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Získanie mena z formulára  
  $name = trim($_POST['name']);
  // Uložíme meno do session
  $_SESSION['name'] = $name;

  if ($name !== '') { // Kontrola, či meno nie je prázdne
    $existingStudent = HelperMySQLi::getStudentByName($name);
    if (!$existingStudent) {
        HelperMySQLi::insertStudent($name);
    }
    // Presmerovanie na profilovú stránku
    header('Location: profile.php');
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="sk">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Prihlásenie</title>
  <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body class="index-page">
  <div class="login-container">
    <div class="login-card">
      <h1>Prihlásenie</h1>
      <form action="index.php" method="POST">
        <label for="name">Tvoje meno:</label>
        <input type="text" id="name" name="name" placeholder="Zadaj svoje meno" required />
        <button type="submit">Prihlásiť sa</button>
      </form>
    </div>
  </div>
</body>
</html>
