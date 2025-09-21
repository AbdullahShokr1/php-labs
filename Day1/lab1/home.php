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
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons (optional for warning icon) -->
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
          <li><?php echo $e; ?></li>
        <?php } ?>
      </ul>
      <hr>
      <a href="form.php" class="btn btn-outline-dark btn-sm">← Go Back</a>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
    exit;
}
?>
<?php
// Greeting
$greeting = $gender === "Male" ? "Hi Mr. $lastName" : "Hi Ms. $lastName";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Welcome</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow-lg">
    <div class="card-header bg-primary text-white">
      <h3><?php echo $greeting; ?></h3>
    </div>
    <div class="card-body">
      <h5 class="card-title">Your Submitted Information</h5>
      <table class="table table-bordered mt-3">
        <tr><th>First Name</th><td><?php echo $firstName; ?></td></tr>
        <tr><th>Last Name</th><td><?php echo $lastName; ?></td></tr>
        <tr><th>Address</th><td><?php echo $address; ?></td></tr>
        <tr><th>Country</th><td><?php echo $country; ?></td></tr>
        <tr><th>Gender</th><td><?php echo $gender; ?></td></tr>
        <tr><th>Skills</th><td><?= implode(", ", $skills);?></td></tr>
        <tr><th>Username</th><td><?php echo $username; ?></td></tr>
        <tr><th>Department</th><td><?php echo $department; ?></td></tr>
      </table>
    </div>
    <div class="card-footer text-end">
      <a href="form.php" class="btn btn-success">Back to Form</a>
    </div>
  </div>
</div>

</body>
</html>
