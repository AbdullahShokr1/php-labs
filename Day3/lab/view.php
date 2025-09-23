<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=tanta_test;charset=utf8mb4", "root", '');
} catch (PDOException $e) {
    echo $e->getMessage();
}

$id = $_GET['id'] ?? -1;
if (!is_numeric($id)) {
    die("Invalid ID");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = !empty($_POST['password']) ? $_POST['password'] : null;

    $sql = "UPDATE users 
            SET firstName=?, lastName=?, address=?, country=?, gender=?, username=?, " .
          ($password ? "password=?, " : "") . " department=? 
            WHERE id=?";

    $params = [
        $_POST['firstName'],
        $_POST['lastName'],
        $_POST['address'],
        $_POST['country'],
        $_POST['gender'],
        $_POST['username'],
    ];

    if ($password) $params[] = $password;
    $params[] = $_POST['department'];
    $params[] = $id;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $pdo->prepare("DELETE FROM user_skills WHERE user_id=?")->execute([$id]);
    if (!empty($_POST['skills'])) {
        $stmtSkill = $pdo->prepare("INSERT INTO user_skills (user_id, skill_id) VALUES (?, ?)");
        foreach ($_POST['skills'] as $skillId) {
            $stmtSkill->execute([$id, $skillId]);
        }
    }

    $success = "Record updated successfully.";
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    echo"Record not found.";
}
$skills = $pdo->query("SELECT * FROM skills")->fetchAll(PDO::FETCH_ASSOC);
$stmt = $pdo->prepare("SELECT skill_id FROM user_skills WHERE user_id=?");
$stmt->execute([$id]);
$userSkills = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View / Update Record</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2>View / Update Record</h2>

  <?php if (!empty($success)): ?>
      <div class="alert alert-success"><?= $success ?></div>
  <?php endif; ?>

  <form method="post" class="border p-4 bg-white rounded">
    <div class="mb-3">
      <label for="firstName" class="form-label">First Name</label>
      <input type="text" id="firstName" name="firstName" value="<?= htmlspecialchars($user['firstName']) ?>" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="lastName" class="form-label">Last Name</label>
      <input type="text" id="lastName" name="lastName" value="<?= htmlspecialchars($user['lastName']) ?>" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="address" class="form-label">Address</label>
      <textarea id="address" name="address" class="form-control" rows="3" required><?= htmlspecialchars($user['address']) ?></textarea>
    </div>

    <div class="mb-3">
      <label for="country" class="form-label">Country</label>
      <select id="country" class="form-select" required name="country">
        <option value="">-- Select Country --</option>
        <?php foreach (["EGY","USA","UK","India","Canada"] as $c): ?>
          <option value="<?= $c ?>" <?= $user['country']==$c ? "selected" : "" ?>><?= $c ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Gender</label><br>
      <div class="form-check form-check-inline">
        <input type="radio" name="gender" id="male" class="form-check-input" value="male" <?= $user['gender']=="male" ? "checked" : "" ?>>
        <label class="form-check-label" for="male">Male</label>
      </div>
      <div class="form-check form-check-inline">
        <input type="radio" name="gender" id="female" class="form-check-input" value="female" <?= $user['gender']=="female" ? "checked" : "" ?>>
        <label class="form-check-label" for="female">Female</label>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Skills</label><br>
      <?php foreach ($skills as $skill): ?>
        <div class="form-check form-check-inline">
          <input type="checkbox" id="skill<?= $skill['id'] ?>" class="form-check-input" name="skills[]" value="<?= $skill['id'] ?>"
            <?= in_array($skill['id'], $userSkills) ? "checked" : "" ?>>
          <label class="form-check-label" for="skill<?= $skill['id'] ?>"><?= htmlspecialchars($skill['name']) ?></label>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="mb-3">
      <label for="username" class="form-label">Username</label>
      <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">Password (leave empty to keep current)</label>
      <input type="password" id="password" name="password" class="form-control">
    </div>

    <div class="mb-3">
      <label for="department" class="form-label">Department</label>
      <input type="text" id="department" name="department" value="<?= htmlspecialchars($user['department']) ?>" class="form-control" readonly>
    </div>

    <button type="submit" class="btn btn-success">Update</button>
    <a href="list.php" class="btn btn-secondary">← Back</a>
  </form>
</div>
</body>
</html>
