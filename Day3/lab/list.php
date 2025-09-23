<?php

try {
    $pdo = new PDO("mysql:host=localhost;dbname=tanta_test;charset=utf8mb4", "root", '');
    $stmt = $pdo->query("
    SELECT u.*, GROUP_CONCAT(s.name SEPARATOR ', ') AS skills
    FROM users u
    LEFT JOIN user_skills us ON u.id = us.user_id
    LEFT JOIN skills s ON us.skill_id = s.id
    GROUP BY u.id
    ");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo $e->getMessage();
}
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
    <?php if ($users): ?>
        <?php foreach ($users as $index => $user): ?>
        <tr>
          <td><?= $index + 1 ?></td>
          <td><?= htmlspecialchars($user['firstName']) ?></td>
          <td><?= htmlspecialchars($user['lastName']) ?></td>
          <td><?= htmlspecialchars($user['address']) ?></td>
          <td><?= htmlspecialchars($user['country']) ?></td>
          <td><?= htmlspecialchars($user['gender']) ?></td>
          <td><?= htmlspecialchars($user['skills']) ?></td>
          <td><?= htmlspecialchars($user['username']) ?></td>
          <td><?= htmlspecialchars($user['password']) ?></td>
          <td><?= htmlspecialchars($user['department']) ?></td>
          <td>
            <a href="view.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-info">View</a>
            <a href="delete.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this entry?')">Delete</a>
          </td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
          <td colspan="11" class="text-center text-muted">No records found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
  </table>
  <a class="btn btn-primary" href='index.php'>Registe new data</a>
</div>

</body>
</html>
