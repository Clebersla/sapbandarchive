<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Enviar Arquivo - Sap Archive</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Enviar Arquivo</h1>
  <p>Logado como: <?= htmlspecialchars($_SESSION['user']) ?> | <a href="logout.php">Sair</a></p>

  <form action="upload_handler.php" method="POST" enctype="multipart/form-data">
    <label>Nome do arquivo:</label><br>
    <input type="text" name="name" required><br><br>

    <label>Tipo (vídeo, demo, imagem, etc):</label><br>
    <input type="text" name="type"><br><br>

    <label>Selecione o arquivo:</label><br>
    <input type="file" name="file" required><br><br>

    <button type="submit">Enviar</button>
  </form>
</body>
</html>
