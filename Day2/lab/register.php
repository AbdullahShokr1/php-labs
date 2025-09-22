<?php

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
if (empty($firstName) || empty($lastName) || empty($address) || empty($country) || empty($address) || empty($gender) || empty($skills) || empty($username) || empty($password) || empty($department)) {
    $errors[] = "should complete the form page";
}
?>
<?php
if (!empty($errors)) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Validation Errors</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

  <div class="container mt-5">
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
      <h4 class="alert-heading">
        <i class="bi bi-exclamation-triangle-fill"></i> Validation Errors
      </h4>
      <ul class="mb-0">
        <?php foreach ($errors as $e) { ?>
          <li><?= $e; ?></li>
        <?php } ?>
      </ul>
      <hr>
      <a href="form.php" class="btn btn-outline-dark btn-sm">← Go Back</a>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
    exit;
}
?>
<?php
$data = $_POST;

foreach ($data as $key => $value) {
    if (is_array($value)) {
        $data[$key] = implode("|", $value);
    } else {
        $data[$key] = str_replace(["\r", "\n"], " ", $value);
    }
}
unset($data['confirmCode'], $data['realCode']);
if (isset($data['skills']) && is_array($data['skills'])) {
    $data['skills'] = implode("|", $data['skills']);
}
$result = implode(",", $data) . PHP_EOL;

$result1 = file_put_contents("db.txt", $result, FILE_APPEND);
echo "<a href='list.php'>show the Data</a>"
?>
