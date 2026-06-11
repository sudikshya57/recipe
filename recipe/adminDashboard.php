<?php
session_start();
if (!isset($_SESSION['admin_name'])) {
  header("Location: adminLogin.php"); // Redirect to login if not authenticated
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    <div class="container mt-5">
      <h1>Welcome, <?php echo $_SESSION['admin_name']; ?>!</h1>
      <p>This is the admin dashboard.</p>
      <!-- <a href="adminLogout.php" class="btn btn-danger">Logout</a> -->
      <a href="admin/view.php" class="btn btn-danger">Continue</a>
    </div>
  </body>
</html>
