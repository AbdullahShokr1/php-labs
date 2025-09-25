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


function clean($s){ return htmlspecialchars(trim($s)); }

$errors = [];
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = clean($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'البريد غير صالح.';
    if (strlen($password) < 6) $errors[] = 'كلمة المرور قصيرة.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id, name, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $success = true;
                header("Location: list.php");
            } else {
                $errors[] = "كلمة المرور غير صحيحة.";
            }
        }
    }
}
?>
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>تسجيل دخول</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow-sm">
        <div class="card-body">
          <h3 class="card-title mb-4 text-center">تسجيل دخول</h3>

          <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
              <ul class="mb-0">
                <?php foreach ($errors as $e): ?>
                  <li><?= $e ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php elseif ($success): ?>
            <div class="alert alert-success">
              تم تسجيل الدخول بنجاح .
            </div>
          <?php endif; ?>

          <form id="loginForm" method="post" novalidate>
            <div class="mb-3">
              <label for="email" class="form-label">البريد الإلكتروني</label>
              <input type="email" class="form-control" id="email" name="email" required value="">
              <div class="invalid-feedback">يرجى إدخال بريد إلكتروني صالح.</div>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">كلمة المرور</label>
              <input type="password" class="form-control" id="password" name="password" minlength="6" required>
              <div class="invalid-feedback">كلمة المرور يجب أن تكون 6 أحرف على الأقل.</div>
            </div>

            <div class="d-grid gap-2">
              <button class="btn btn-primary" type="submit">دخول</button>
              <a href="register.php" class="btn btn-outline-secondary">إنشاء حساب</a>
            </div>
          </form>

        </div>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
  'use strict'
  const form = document.getElementById('loginForm');

  form.addEventListener('submit', function (event) {
    if (!form.checkValidity()) {
      event.preventDefault()
      event.stopPropagation()
    }
    form.classList.add('was-validated')
  }, false)
})();
</script>
</body>
</html>
