<?php
session_start();
require_once 'classes/HelperMySQLi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $_SESSION['name'] = $name;

    $existingStudent = HelperMySQLi::getStudentByName($name);
    if (!$existingStudent) {
        HelperMySQLi::insertStudent($name);
    }

    header('Location: profile.php');
    exit;
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
<!-- No additional code is needed at the $PLACEHOLDER$ for pushing to GitHub. -->

<!-- To push this project to GitHub, follow these steps: -->

1. Initialize a Git repository in your project folder:
  ```
  git init
  ```

2. Add all files to the staging area:
  ```
  git add .
  ```

3. Commit the changes:
  ```
  git commit -m "Initial commit"
  ```

4. Create a new repository on GitHub (via the GitHub website).

5. Link your local repository to the GitHub repository:
  ```
  git remote add origin https://github.com/your-username/your-repository-name.git
  ```

6. Push the code to GitHub:
  ```
  git push -u origin main
  ```

Replace `your-username` and `your-repository-name` with your GitHub username and the name of your repository.