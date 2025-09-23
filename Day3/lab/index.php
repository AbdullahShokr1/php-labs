<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bootstrap Form Example</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .error { color: red; font-size: 0.9em; }
  </style>
</head>
<body class="bg-light">

<div class="container mt-5">
  <a class="btn btn-success" href="list.php">Show Data</a>
  <h2 class="mb-4">Registration Form</h2>
  <form id="myForm" class="border p-4 bg-white rounded" action="register.php" method="POST">
    <div class="mb-3">
      <label for="firstName" class="form-label">First Name</label>
      <input type="text" id="firstName" name="firstName" class="form-control" required>
    </div>
    
    <div class="mb-3">
      <label for="lastName" class="form-label">Last Name</label>
      <input type="text" id="lastName" name="lastName" class="form-control" required>
    </div>
    
    <div class="mb-3">
      <label for="address" class="form-label">Address</label>
      <textarea id="address" class="form-control" rows="3" required name="address"></textarea>
    </div>
    
    
    <div class="mb-3">
      <label for="country" class="form-label">Country</label>
      <select id="country" class="form-select" required name="country">
        <option value="">-- Select Country --</option>
        <option>EGY</option>
        <option>USA</option>
        <option>UK</option>
        <option>India</option>
        <option>Canada</option>
      </select>
    </div>
    
    
    <div class="mb-3">
      <label class="form-label">Gender</label><br>
      <div class="form-check form-check-inline">
        <input type="radio" name="gender" id="male" class="form-check-input" value="Male" required>
        <label class="form-check-label" for="male">Male</label>
      </div>
      <div class="form-check form-check-inline">
        <input type="radio" name="gender" id="female" class="form-check-input" value="Female">
        <label class="form-check-label" for="female">Female</label>
      </div>
    </div>
    
    
    <div class="mb-3">
      <label class="form-label">Skills</label><br>
      <div class="form-check form-check-inline">
        <input type="checkbox" id="html" class="form-check-input" value="1" name="skills[]">
        <label class="form-check-label" for="html">HTML</label>
      </div>
      <div class="form-check form-check-inline">
        <input type="checkbox" id="css" class="form-check-input" value="2" name="skills[]" >
        <label class="form-check-label" for="css">CSS</label>
      </div>
      <div class="form-check form-check-inline">
        <input type="checkbox" id="js" class="form-check-input" value="3" name="skills[]">
        <label class="form-check-label" for="js">JavaScript</label>
      </div>
    </div>
    
    
    <div class="mb-3">
      <label for="username" class="form-label">Username</label>
      <input type="text" id="username" name="username" class="form-control" required>
    </div>
    
    
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <input type="password" id="password" name="password" class="form-control" required>
    </div>
    
    
    <div class="mb-3">
      <label for="department" class="form-label">Department</label>
      <input type="text" id="department"  name="department" class="form-control" value="IT Department" readonly>
    </div>
    
    
    <div class="mb-3">
      <label class="form-label">Verification Code</label>
      <div class="d-flex align-items-center">
        <input type="text" id="generatedCode" name="realCode" class="form-control me-2" readonly>
      </div>
    </div>
    
    
    <div class="mb-3">
      <label for="confirmCode" class="form-label">Enter Code</label>
      <input type="text" id="confirmCode" name="confirmCode" class="form-control" required>
    </div>
    
    
    <div class="mb-3">
      <button type="submit" class="btn btn-primary">Submit</button>
      <button type="reset" class="btn btn-warning">Reset</button>
    </div>
    
    <p id="errorMsg" class="error"></p>
  </form>
</div>

<script>
  function generateCode() {
    const code = Math.floor(1000 + Math.random() * 9000);
    return code;
  }
  document.getElementById('generatedCode').value = generateCode();
</script>

</body>
</html>
