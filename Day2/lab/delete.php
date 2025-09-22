<?php
$id = $_GET['id'] ?? -1;
$lines = file("db.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

if (isset($lines[$id])) {
    unset($lines[$id]);
    file_put_contents("db.txt", implode(PHP_EOL, $lines) . PHP_EOL);
}

header("Location: list.php");
exit;
