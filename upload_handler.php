<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $targetDir = "downloads/";
  $fileName = basename($_FILES["file"]["name"]);
  $targetFile = $targetDir . $fileName;

  if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
    $files = json_decode(file_get_contents('files.json'), true) ?? [];
    $files[] = [
      'name' => $_POST['name'],
      'type' => $_POST['type'],
      'path' => $targetFile,
      'date' => date('Y-m-d H:i:s'),
      'uploaded_by' => $_SESSION['user']
    ];
    file_put_contents('files.json', json_encode($files, JSON_PRETTY_PRINT));
    echo "✅ Arquivo enviado com sucesso! <a href='index.php'>Voltar</a>";
  } else {
    echo "❌ Erro ao enviar o arquivo.";
  }
}
?>
