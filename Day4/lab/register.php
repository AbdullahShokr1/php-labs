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
    $name = clean($_POST['name'] ?? '');
    $email = clean($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $salary = clean($_POST['salary'] ?? '');

    if ($name === '') $errors[] = 'الاسم مطلوب.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'البريد غير صالح.';
    if (strlen($password) < 6) $errors[] = 'كلمة المرور قصيرة.';
    if ($password !== $confirm_password) $errors[] = 'كلمة المرور غير متطابقة.';
    if (!is_numeric(str_replace(',', '.', $salary))) $errors[] = 'المرتب يجب أن يكون رقمياً.';
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "يجب رفع صورة.";
    } else {
        $allowed = ['image/jpeg','image/png','image/gif','image/webp'];
        $file_type = mime_content_type($_FILES['image']['tmp_name']);
        if (!in_array($file_type, $allowed)) {
            $errors[] = "صيغة الصورة غير مدعومة (اختر jpg, png, gif, webp).";
        }
    }

    if (empty($errors)) {
        try {
            
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid("user_", true) . "." . strtolower($ext);
            $uploadDir = __DIR__ . "/image/"; 
            $uploadPath = $uploadDir . $newFileName;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password, salary, image) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$name, $email, $password, $salary, $newFileName]);
                $success = true;
            } else {
                $errors[] = "فشل في رفع الصورة.";
            }
            header("Location: list.php");
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                $errors[] = "البريد الإلكتروني مسجل بالفعل.";
            } else {
                $errors[] = "خطأ: " . $e->getMessage();
            }
        }
    }
}
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>إنشاء حساب</title>
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h3 class="card-title mb-4 text-center">إنشاء حساب</h3>

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
              تم إنشاء الحساب بنجاح.
            </div>
          <?php endif; ?>

          <form id="registerForm" method="post" novalidate enctype="multipart/form-data">
            <div class="mb-3">
              <label for="name" class="form-label">الاسم</label>
              <input type="text" class="form-control" id="name" name="name" required value="">
              <div class="invalid-feedback">يرجى إدخال الاسم.</div>
            </div>

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

            <div class="mb-3">
              <label for="confirm_password" class="form-label">تأكيد كلمة المرور</label>
              <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
              <div class="invalid-feedback">التأكيد يجب أن يطابق كلمة المرور.</div>
            </div>

            <div class="mb-3">
              <label for="salary" class="form-label">المرتب</label>
              <input type="text" class="form-control" id="salary" name="salary" required value="">
              <div class="invalid-feedback">يرجى إدخال مرتب رقمي.</div>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">الصورة الشخصية</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                <div class="invalid-feedback">يرجى رفع صورة.</div>
            </div>


            <div class="d-grid gap-2">
              <button class="btn btn-primary" type="submit">إنشاء حساب</button>
              <a href="login.php" class="btn btn-outline-secondary">الذهاب لتسجيل الدخول</a>
            </div>
          </form>

        </div>
      </div>

    </div>
  </div>
</div>

<!-- Bootstrap + optional JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
(function () {
  'use strict'
  const form = document.getElementById('registerForm');

  form.addEventListener('submit', function (event) {
    // reset custom validity
    const password = document.getElementById('password');
    const confirm = document.getElementById('confirm_password');
    const salary = document.getElementById('salary');

    // salary numeric check (allow comma or dot)
    const salaryVal = salary.value.trim().replace(',', '.');
    if (isNaN(salaryVal) || salaryVal === '') {
      salary.setCustomValidity('invalid');
    } else {
      salary.setCustomValidity('');
    }

    // confirm password match
    if (password.value !== confirm.value) {
      confirm.setCustomValidity('no-match');
    } else {
      confirm.setCustomValidity('');
    }

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
