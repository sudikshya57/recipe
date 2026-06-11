<?php
include "connection.php";
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />
    <link href="signup.css" rel="stylesheet" />
  </head>
  <body>
    <?php include "navbar.php"; ?>

    <?php
    $username = $email = $password = $cpassword = "";
    $dbError = "";
    $enteredName = $enteredEmail = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $enteredName = htmlspecialchars($_POST["name"]);
        $enteredEmail = htmlspecialchars($_POST["email"]);
        $enteredPassword = $_POST["password"];
        $enteredCpassword = $_POST["comfirmpassword"];

        // Check if the email already exists
        $sql = "SELECT * FROM user WHERE email='$enteredEmail'";
        $result = mysqli_query($conn, $sql);
        $count_user = mysqli_num_rows($result);

        if ($count_user == 0) {
            if ($enteredPassword === $enteredCpassword) {
                $username = $enteredName;
                $email = $enteredEmail;
                $password = password_hash($enteredPassword, PASSWORD_DEFAULT);

                $insertSQL = "INSERT INTO user (username, email, password) VALUES ('$username', '$email', '$password')";
                $insertResult = mysqli_query($conn, $insertSQL);

                if ($insertResult) {
                    header("Location: login.php");
                    exit;
                } else {
                    $dbError = "Error: " . $conn->error;
                }
            } else {
                $dbError = "Passwords do not match!";
            }
        } else {
            $dbError = "Email already exists!";
        }
    }
    ?>

    <!-- Signup page starts here -->
    <div class="hero">
      <div id="form">
        <h1>Signup Form</h1>
        <form
          method="POST"
          action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
          onsubmit="return validateForm()"
          enctype="multipart/form-data"
        >
          <span id="nameerr" style="color: red;"></span>
          <input
            id="name"
            name="name"
            type="text"
            placeholder="Name"
            value="<?php echo htmlspecialchars($enteredName); ?>"
          />

          <span id="emailerr" style="color: red;"></span>
          <span style="color: red;"><?php echo $dbError; ?></span>
          <input
            id="email"
            name="email"
            type="email"
            placeholder="Email"
            value="<?php echo htmlspecialchars($enteredEmail); ?>"
          />

          <span id="passworderr" style="color: red;"></span>
          <input
            id="password"
            name="password"
            type="password"
            placeholder="Password"
          />

          <span id="confirmpassworderr" style="color: red;"></span>
          <input
            id="confirmpassword"
            name="comfirmpassword"
            type="password"
            placeholder="Confirm Password"
          />

          <input type="submit" id="btn" value="Signup" name="submit"/>
        </form>
      </div>
    </div>

    <script>
      function validateForm() {
        const name = document.getElementById("name").value.trim();
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value;
        const confirmpassword = document.getElementById("confirmpassword").value;
        let hasError = false;

        // Clear previous error messages
        document.getElementById("nameerr").innerHTML = "";
        document.getElementById("emailerr").innerHTML = "";
        document.getElementById("passworderr").innerHTML = "";
        document.getElementById("confirmpassworderr").innerHTML = "";

        if (name === "") {
          document.getElementById("nameerr").innerHTML = "Name is required!";
          hasError = true;
        }

        if (email === "") {
          document.getElementById("emailerr").innerHTML = "Email is required!";
          hasError = true;
        }

        if (password === "" || password.length <= 6) {
          document.getElementById("passworderr").innerHTML =
            "Password must be more than 6 characters!";
          hasError = true;
        }

        if (confirmpassword === "" || confirmpassword !== password) {
          document.getElementById("confirmpassworderr").innerHTML =
            "Passwords do not match!";
          hasError = true;
        }

        return !hasError;
      }
    </script>
  </body>
</html>
