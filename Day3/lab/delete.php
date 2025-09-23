<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=tanta_test;charset=utf8mb4", "root", '');
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$id = $_GET['id'] ?? -1;

if (!is_numeric($id)) {
    die("Invalid ID");
}

$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$id]);

header("Location: list.php");
exit;
