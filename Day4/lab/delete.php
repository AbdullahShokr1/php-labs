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
    header("Location: list.php");
    exit;
}

$user_id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT image FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $uploadDir = __DIR__ . "/image/";
    if (!empty($user['image']) && file_exists($uploadDir . $user['image'])) {
        unlink($uploadDir . $user['image']);
    }

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
}

header("Location: list.php");
exit;
