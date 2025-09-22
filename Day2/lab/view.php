<?php
$id = $_GET['id'] ?? -1;
$lines = file("db.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

if (!isset($lines[$id])) {
    die("Record not found");
}

$cols = explode(",", $lines[$id]);
$labels = ["First Name", "Last Name", "Address", "Country", "Gender", "Skills", "Username", "Password", "Department"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Record</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2>View Record</h2>
  <table class="table table-bordered">
    <?php foreach ($labels as $i => $label): ?>
      <tr>
        <th><?= $label ?></th>
        <td><?= htmlspecialchars($cols[$i] ?? '') ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
  <a href="list.php" class="btn btn-secondary">← Back</a>
</div>
</body>
</html>
