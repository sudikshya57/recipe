<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="view.php" content="width=device-width, initial-scale=1.0">
    <title>Upload Recipe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5ece2;
            margin: 0;
            padding: 0;
            color: #5a3e2b;
        }

        h1 {
            text-align: center;
            color: #5a3e2b;
            margin-top: 20px;
        }

        form {
            background: #fff7f0;
            max-width: 400px;
            margin: 20px auto;
            padding: 15px;
            border: 1px solid #d6c5b4;
            border-radius: 5px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #5a3e2b;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #d6c5b4;
            border-radius: 3px;
            background-color: #fdfaf7;
            color: #5a3e2b;
        }

        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-color: #fdfaf7;
            padding-right: 30px; /* Space for the dropdown icon */
        }

        .file-label {
            display: inline-block;
            padding: 10px 15px;
            background-color: #8b5e3c;
            color: #ffffff;
            border-radius: 3px;
            cursor: pointer;
            text-align: center;
        }

        .file-label:hover {
            background-color: #6c4a2f;
        }

        button {
            width: 100%;
            background-color: #8b5e3c;
            color: #ffffff;
            border: none;
            padding: 10px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 3px;
        }

        button:hover {
            background-color: #6c4a2f;
        }

        p {
            text-align: center;
            font-size: 14px;
        }

        p[style="color:green;"] {
            color: #6c8a3e;
            font-weight: bold;
        }

        p[style="color:red;"] {
            color: #a64b3b;
            font-weight: bold;
        }

        /* Custom arrow for the select dropdown */
        select::-ms-expand {
            display: none;
        }

        .category-wrapper {
            position: relative;
        }

        .category-wrapper::after {
            content: "▼";
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            color: #5a3e2b;
        }

        /* Hide the file input and style the file label */
        input[type="file"] {
            display: none;
        }

    </style>
</head>

<body>
    <h1>Upload a New Recipe</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="name">Recipe Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="instructions">Instructions:</label><br>
        <textarea id="instructions" name="instructions" rows="5" required></textarea><br><br>

        <label for="ingredients">Ingredients (comma-separated):</label><br>
        <textarea id="ingredients" name="ingredients" rows="3" required></textarea><br><br>

        <label for="category">Category:</label><br>
        <div class="category-wrapper">
            <select id="category" name="category" required>
                <option value="" disabled selected>Select Category</option> <!-- Disabled option -->
                <option value="Appetizer">Appetizer</option>
                <option value="Main Course">Main Course</option>
                <option value="Dessert">Dessert</option>
                <option value="Snack">Snack</option>
                <!-- Add more categories as needed -->
            </select>
        </div><br><br>

        <label for="image" class="file-label">Choose File</label>
        <input type="file" id="image" name="image" accept="image/*" required><br><br>

        <button type="submit">Upload Recipe</button>
    </form>

    <?php
    // Database connection
    $host = "localhost";
    $dbname = "db";
    $username = "root";
    $password = "";
    $port = 3307;

    $conn = new mysqli($host, $username, $password, $dbname, $port);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $conn->real_escape_string($_POST['name']);
        $instructions = $conn->real_escape_string($_POST['instructions']);
        $ingredients = $conn->real_escape_string($_POST['ingredients']);
        $category = $conn->real_escape_string($_POST['category']); // Capture category

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $targetDir = "uploads/";
            $imageName = basename($_FILES['image']['name']);
            $targetFilePath = $targetDir . uniqid() . "_" . $imageName; // Use unique name for file
            $imageType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

            // Validate image type
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($imageType, $allowedTypes)) {
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true); // Create directory if it doesn't exist
                }

                // Move uploaded file
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                    // Insert recipe into database with category and default status 'pending'
                    $stmt = $conn->prepare("INSERT INTO recipes (name, instructions, ingredients, image, category, status) VALUES (?, ?, ?, ?, ?, 'pending')");
                    $stmt->bind_param("sssss", $name, $instructions, $ingredients, $targetFilePath, $category);

                    if ($stmt->execute()) {
                        // Redirect to view.php after successful upload
                        header("Location: view.php");
                        exit(); // Ensure no further code is executed
                    } else {
                        echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
                    }
                    $stmt->close();
                } else {
                    echo "<p style='color:red;'>Failed to upload image.</p>";
                }
            } else {
                echo "<p style='color:red;'>Invalid image type. Only JPG, JPEG, PNG, and GIF are allowed.</p>";
            }
        } else {
            echo "<p style='color:red;'>Please upload an image.</p>";
        }
    }

    $conn->close();
    ?>

</body>
</html>
