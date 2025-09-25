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

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "المعرف غير صالح.";
    exit;
}

$user_id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "المستخدم غير موجود.";
    exit;
}

$errors = [];
$success = false;

function clean($s){ return htmlspecialchars(trim($s)); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean($_POST['name'] ?? '');
    $email = clean($_POST['email'] ?? '');
    $salary = clean($_POST['salary'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '') $errors[] = 'الاسم مطلوب.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'البريد غير صالح.';
    if (!is_numeric(str_replace(',', '.', $salary))) $errors[] = 'المرتب يجب أن يكون رقمياً.';

    $imageName = $user['image']; 

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['image/jpeg','image/png','image/gif','image/webp'];
        $file_type = mime_content_type($_FILES['image']['tmp_name']);
        if (in_array($file_type, $allowed)) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid("user_", true) . "." . strtolower($ext);
            $uploadDir = __DIR__ . "/image/";
            $uploadPath = $uploadDir . $newFileName;

            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                if (!empty($user['image']) && file_exists($uploadDir . $user['image'])) {
                    unlink($uploadDir . $user['image']);
                }
                $imageName = $newFileName;
            } else {
                $errors[] = "فشل في رفع الصورة.";
            }
        } else {
            $errors[] = "صيغة الصورة غير مدعومة.";
        }
    }

    if (empty($errors)) {
        try {
            if ($password !== '') {
                $stmt = $pdo->prepare("UPDATE users SET name=?, email=?, password=?, salary=?, image=? WHERE id=?");
                $stmt->execute([$name, $email, $password, $salary, $imageName, $user_id]);
            } else {
                $stmt = $pdo->prepare("UPDATE users SET name=?, email=?, salary=?, image=? WHERE id=?");
                $stmt->execute([$name, $email, $salary, $imageName, $user_id]);
            }

            $success = true;
            $_SESSION['user_name'] = $name;
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                $errors[] = "البريد الإلكتروني مستخدم من قبل.";
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
  <meta charset="UTF-8">
  <title>تعديل الملف الشخصي</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body">
          <h3 class="card-title mb-4 text-center">تعديل الملف الشخصي</h3>

          <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
              <ul class="mb-0">
                <?php foreach ($errors as $e): ?>
                  <li><?= $e ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php elseif ($success): ?>
            <div class="alert alert-success">تم تحديث البيانات بنجاح.</div>
          <?php endif; ?>

          <form method="post" enctype="multipart/form-data">
            <div class="mb-3 text-center">
              <?php if (!empty($user['image'])): ?>
                <img src="image/<?= htmlspecialchars($user['image']) ?>" class="rounded-circle" width="120" height="120" alt="صورة المستخدم">
              <?php else: ?>
                <img src="https://via.placeholder.com/120" class="rounded-circle" alt="No image">
              <?php endif; ?>
            </div>

            <div class="mb-3">
              <label class="form-label">الاسم</label>
              <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>

            <div class="mb-3">
              <label class="form-label">البريد الإلكتروني</label>
              <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>

            <div class="mb-3">
              <label class="form-label">المرتب</label>
              <input type="text" name="salary" class="form-control" value="<?= htmlspecialchars($user['salary']) ?>" required>
            </div>

            <div class="mb-3">
              <label class="form-label">كلمة المرور (اتركها فارغة لو مش عايز تغيرها)</label>
              <input type="password" name="password" class="form-control">
            </div>

            <div class="mb-3">
              <label class="form-label">الصورة الشخصية</label>
              <input type="file" name="image" class="form-control" accept="image/*">
            </div>

            <div class="d-grid">
              <button class="btn btn-primary">تحديث</button>
              <a href="list.php" class="btn btn-secondary mt-2">رجوع</a>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
