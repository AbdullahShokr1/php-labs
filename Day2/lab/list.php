<?php

$lines = file("db.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Data List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <h2 class="mb-4">Saved Data</h2>
  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Address</th>
        <th>Country</th>
        <th>Gender</th>
        <th>Skills</th>
        <th>Username</th>
        <th>Password</th>
        <th>Department</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($lines as $index => $line): ?>
        <?php
        $cols = explode(",", $line);
        ?>
        <tr>
          <td><?= $index + 1 ?></td>
          <?php foreach ($cols as $col): ?>
            <td><?= htmlspecialchars($col) ?></td>
          <?php endforeach; ?>
          <td>
            <a href="view.php?id=<?= $index ?>" class="btn btn-sm btn-info">View</a>
            <a href="delete.php?id=<?= $index ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this entry?')">Delete</a>
          </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <a class="btn btn-primary" href='index.php'>Registe new data</a>
</div>

</body>
</html>
