<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $users = json_decode(file_get_contents('users.json'), true);
  $email = $_POST['email'];
  $password = $_POST['password'];

  foreach ($users as $user) {
    if ($user['email'] === $email && password_verify($password, $user['password'])) {
      $_SESSION['user'] = $email;
      header("Location: upload.php");
      exit;
    }
  }
  $error = "Email ou senha incorretos.";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Login - Sap Archive</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Login</h1>
  <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
  <form method="POST">
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Senha:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Entrar</button>
  </form>
  <p><a href="index.php">Voltar</a></p>
</body>
</html>
