<?php
$files = json_decode(file_get_contents('files.json'), true) ?? [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Arquivo da Banda Sap</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Arquivo da Banda Sap</h1>
  <p>Uploads de vídeos, demos, e arquivos raros da banda.</p>
  <a href="upload.php">Enviar novo arquivo</a> | 
  <a href="credits.html">Créditos</a>

  <hr>

  <?php if (count($files) > 0): ?>
    <ul>
      <?php foreach ($files as $file): ?>
        <li>
          <a href="<?= htmlspecialchars($file['path']) ?>" download>
            <?= htmlspecialchars($file['name']) ?>
          </a> – <?= htmlspecialchars($file['type']) ?>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p>Nenhum arquivo ainda.</p>
  <?php endif; ?>
</body>
</html>
