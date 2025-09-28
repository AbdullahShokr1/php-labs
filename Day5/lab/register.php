<?php
require_once "DB.php";

$firstName  = htmlspecialchars($_POST['firstName']);
$lastName   = htmlspecialchars($_POST['lastName']);
$address    = htmlspecialchars($_POST['address']);
$country    = htmlspecialchars($_POST['country']);
$gender     = htmlspecialchars($_POST['gender']);
$skills     = isset($_POST['skills']) ? $_POST['skills'] : [];
$username   = htmlspecialchars($_POST['username']);
$password   = htmlspecialchars($_POST['password']);
$department = htmlspecialchars($_POST['department']);
$confirmCode= htmlspecialchars($_POST['confirmCode']);
$realCode   = htmlspecialchars($_POST['realCode']);

$errors = [];
if (strlen($password) < 6) {
    $errors[] = "Password must be at least 6 characters.";
}
if ($confirmCode !== $realCode) {
    $errors[] = "Verification code does not match.";
}
if (
    empty($firstName) || empty($lastName) || empty($address) || empty($country) ||
    empty($gender) || empty($skills) || empty($username) || empty($password) || empty($department)
) {
    $errors[] = "You must complete the entire form.";
}

if (!empty($errors)) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <title>Validation Errors</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    </head>
    <body class="bg-light">

      <div class="container mt-5">
        <div class="alert alert-danger shadow-sm">
          <h4><i class="bi bi-exclamation-triangle-fill"></i> Validation Errors</h4>
          <ul class="mb-0">
            <?php foreach ($errors as $e) { ?>
              <li><?= $e; ?></li>
            <?php } ?>
          </ul>
          <hr>
          <a href="form.php" class="btn btn-outline-dark btn-sm">← Go Back</a>
        </div>
      </div>

    </body>
    </html>
    <?php
    exit;
}

try {
    $db = DB::getInstance("php_tanta", "localhost", "root", "");
    $conn = $db->get_connection();
    $conn->beginTransaction();
    $db->insert("users", [
        "firstName"  => $firstName,
        "lastName"   => $lastName,
        "address"    => $address,
        "country"    => $country,
        "gender"     => $gender,
        "username"   => $username,
        "password"   => $password,
        "department" => $department
    ]);
    $userId = $conn->lastInsertId();
    $stmtSkill = $conn->prepare("INSERT INTO user_skills (user_id, skill_id) VALUES (?, ?)");
    foreach ($skills as $skillId) {
        $stmtSkill->execute([$userId, $skillId]);
    }
    $conn->commit();
    echo "<div class='alert alert-success'>User registered successfully.</div>";
    echo "<a href='list.php'>Show the Data</a>";
} catch (PDOException $e) {
    $conn->rollBack();
    echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
}
