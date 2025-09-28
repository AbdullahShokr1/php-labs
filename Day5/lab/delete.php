<?php
require_once "db.php";
require_once "DBHelper.php";

class App {
    use DBHelper;
}

$app = new App();
$db  = $app->initDB();

$id = $_GET['id'] ?? -1;

if (!is_numeric($id)) {
    die("Invalid ID");
}

$deleted = $db->delete("users", ["id" => $id]);

if ($deleted) {
    header("Location: list.php");
    exit;
} else {
    echo "Delete failed.";
}
