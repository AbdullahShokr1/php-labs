<?php
session_start();

$host = "localhost";
$user = "root";  
$pass = "";   
$dbname = "php_tanta";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
} catch (PDOException $e) {
    echo($e->getMessage());
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->query("SELECT id, name, email, salary, image, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>قائمة المستخدمين</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>قائمة المستخدمين</h2>
    <div>
      <a href="register.php" class="btn btn-secondary btn-sm">انشاء حساب جديد</a>
      <a href="logout.php" class="btn btn-danger btn-sm">تسجيل خروج</a>
    </div>
  </div>

  <div class="row">
    <?php if ($users): ?>
      <?php foreach ($users as $user): ?>
        <div class="col-md-4 mb-3">
          <div class="card h-100 shadow-sm">
            <?php if (!empty($user['image'])): ?>
              <img src="image/<?= htmlspecialchars($user['image']) ?>" class="card-img-top" alt="صورة المستخدم" style="height:200px; object-fit:cover;">
            <?php else: ?>
              <img src="https://via.placeholder.com/200x200?text=No+Image" class="card-img-top" alt="No image">
            <?php endif; ?>
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($user['name']) ?></h5>
              <p class="card-text mb-1"><strong>البريد:</strong> <?= htmlspecialchars($user['email']) ?></p>
              <p class="card-text mb-1"><strong>المرتب:</strong> <?= htmlspecialchars($user['salary']) ?></p>
              <p class="card-text"><small class="text-muted">مسجل منذ: <?= htmlspecialchars($user['created_at']) ?></small></p>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a href="edit.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-primary">عرض / تعديل</a>
                <a href="delete.php?id=<?= $user['id'] ?>" 
                class="btn btn-sm btn-danger"
                onclick="return confirm('هل أنت متأكد أنك تريد حذف هذا المستخدم؟');">
                حذف
                </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12">
        <div class="alert alert-info">لا يوجد مستخدمين مسجلين حالياً.</div>
      </div>
    <?php endif; ?>
  </div>
</div>
</body>
</html>
