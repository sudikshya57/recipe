<?php
session_start();
$adminError = "";

if (isset($_SESSION['admin_name'])) {
    header("Location: adminDashboard.php"); // Redirect if already logged in
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Simple admin login logic
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_name'] = $username;
        header("Location: adminDashboard.php"); // Redirect to admin dashboard
        exit();
    } else {
        $adminError = "Invalid admin username or password!";
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="login.css" rel="stylesheet">
  </head>
  <body>
    <?php include "navbar.php"; ?>

    <div id="form" class="container mt-5">
      <h1 class="text-center">Admin Login</h1>
      <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="mb-3">
          <label for="username" class="form-label">Enter Username</label>
          <input type="text" id="username" name="username" class="form-control" required />
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Enter Password</label>
          <input type="password" id="password" name="password" class="form-control" required />
        </div>

        <div>
          <span style="color:red;"><?php echo $adminError; ?></span>
        </div>

        <div class="text-center">
          <button type="submit" id="btn" class="btn btn-danger">Login</button>
        </div>
      </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
