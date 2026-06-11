<?php
include "connection.php";
session_start();

if (isset($_SESSION['name'])) {
    header("Location: welcome.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
    />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="login.css" rel="stylesheet">
</head>
<body>

<?php include "navbar.php"; ?>

<?php
$enteredEmail = "";
$enteredPassword = "";
$dbError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enteredEmail = htmlspecialchars($_POST['logemail']);
    $enteredPassword = $_POST['logpassword'];

    $sqlQ = "SELECT * FROM user WHERE email='$enteredEmail'";
    $res = mysqli_query($conn, $sqlQ);

    if (mysqli_num_rows($res) == 0) {
        $dbError = "Invalid Email!";
    } else {
        $row = mysqli_fetch_assoc($res); // Fetch user data
        $DBhashpassword = $row['password'];

        if (password_verify($enteredPassword, $DBhashpassword)) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['name'] = $row['username'];
            $_SESSION['email'] = $row['email'];

            // Redirect based on user type
            if ($row['user_type'] === 'admin') {
                header("Location: adminDashboard.php");
            } else {
                header("Location: welcome.php");
            }
            exit;
        } else {
            $dbError = "Invalid Password!";
        }
    }
}
?>

<div id="form" class="container mt-5">
    <h1 class="text-center">User Login</h1>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return loginValidation()">
        <div class="mb-3">
            <label for="logemail" class="form-label">Email</label>
            <input type="text" id="logemail" name="logemail" class="form-control" value="<?php echo htmlspecialchars($enteredEmail); ?>" placeholder="Enter your email">
            <span id="logemailerr" style="color:red;"></span>
        </div>

        <div class="mb-3">
            <label for="logpassword" class="form-label">Password</label>
            <input type="password" id="logpassword" name="logpassword" class="form-control" placeholder="Enter your password">
            <span id="logpassworderr" style="color:red;"></span>
        </div>

        <div>
            <span style="color:red;"><?php echo $dbError; ?></span>
        </div>

        <div class="text-center">
            <button type="submit" id="btn" class="btn btn-primary">Login</button>
            <a href="adminLogin.php" class="btn btn-outline-secondary">Admin Login</a>
        </div>
    </form>
</div>

<script>
    function loginValidation() {
        const logemail = document.getElementById("logemail").value.trim();
        const logpassword = document.getElementById("logpassword").value.trim();
        let hasError = false;

        // Clear previous error messages
        document.getElementById("logemailerr").innerHTML = "";
        document.getElementById("logpassworderr").innerHTML = "";

        if (logemail === "") {
            document.getElementById("logemailerr").innerHTML = "Email is required!";
            hasError = true;
        }

        if (logpassword === "") {
            document.getElementById("logpassworderr").innerHTML = "Password is required!";
            hasError = true;
        }

        return !hasError;
    }
</script>

<script src="script.js"></script>

</body>
</html>
