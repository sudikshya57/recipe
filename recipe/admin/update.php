<?php
session_start();
include("../connection.php");

// Validate and fetch row ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Recipe ID.");
}
$rowId = $_GET['id'];

$nameErr = "";
$hasError = false;

// Default values for recipe fields
$name = "";
$instructions = "";
$ingredients = "";
$image = "";
$category = ""; // Category field

// Fetch existing recipe data to populate the form
$sqlfetch = "SELECT * FROM `recipes` WHERE id=$rowId";
$res = mysqli_query($conn, $sqlfetch);

if (mysqli_num_rows($res) > 0) {
    $row = mysqli_fetch_assoc($res);
    $name = $row['name'];
    $instructions = $row['instructions'];
    $ingredients = $row['ingredients'];
    $image = $row['image'];
    $category = $row['category']; // Fetch category
} else {
    die("Recipe not found.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty($_POST["cat_name"])) {
        $hasError = true;
        $nameErr = "Recipe Name is required.";
    } else {
        $name = trim($_POST["cat_name"]);
    }

    $instructions = trim($_POST["instructions"]);
    $ingredients = trim($_POST["ingredients"]);
    $category = $_POST["category"]; // Capture category
    $imageClause = ""; // For conditional image update

    // Handle image upload (if any)
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        $imageName = basename($_FILES['image']['name']);
        $targetFilePath = $targetDir . uniqid() . "_" . $imageName;
        $imageType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        // Validate image type
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageType, $allowedTypes)) {
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true); // Create directory if it doesn't exist
            }

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
                $imageClause = ", image='$targetFilePath'"; // Prepare image update clause
            } else {
                echo "<p style='color:red;'>Failed to upload the image.</p>";
            }
        } else {
            echo "<p style='color:red;'>Invalid image type. Only JPG, JPEG, PNG, and GIF are allowed.</p>";
        }
    }

    // Update the recipe if no errors
    if (!$hasError) {
        $sqlUpdate = "UPDATE `recipes` 
                      SET name='$name', 
                          instructions='$instructions', 
                          ingredients='$ingredients', 
                          category='$category' 
                          $imageClause 
                      WHERE id=$rowId";
        $result = mysqli_query($conn, $sqlUpdate);

        if ($result) {
            header('Location: view.php'); // Redirect on success
            exit();
        } else {
            echo "<p style='color:red;'>Error updating recipe: " . mysqli_error($conn) . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Recipe</title>
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

        input[type="text"], textarea, select {
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

        p[style="color:red;"] {
            color: #a64b3b;
            font-weight: bold;
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

        input[type="file"] {
            display: none;
            margin-bottom: 10px;
        }

    </style>
</head>
<body>
    <h1>Update Recipe</h1>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="cat_name">Recipe Name:</label>
        <input type="text" id="cat_name" name="cat_name" value="<?php echo htmlspecialchars($name); ?>" required>

        <label for="instructions">Instructions:</label>
        <textarea id="instructions" name="instructions" rows="5" required><?php echo htmlspecialchars($instructions); ?></textarea>

        <label for="ingredients">Ingredients:</label>
        <textarea id="ingredients" name="ingredients" rows="5" required><?php echo htmlspecialchars($ingredients); ?></textarea>

        <label for="category">Category:</label>
    <select id="category" name="category" required>
    <option value="" disabled selected>Select Category</option>
    <option value="Appetizer" <?php echo ($category == 'Appetizer' ? 'selected' : ''); ?>>Appetizer</option>
    <option value="Main Course" <?php echo ($category == 'Main Course' ? 'selected' : ''); ?>>Main Course</option>
    <option value="Dessert" <?php echo ($category == 'Dessert' ? 'selected' : ''); ?>>Dessert</option>
    <option value="Snack" <?php echo ($category == 'Snack' ? 'selected' : ''); ?>>Snack</option>
    <!-- Add more categories as needed -->
</select>

        </div><br><br>

        <label for="image" class="file-label">Upload New Image (if any)</label>
        <input type="file" id="image" name="image" accept="image/*">

        <?php if ($nameErr): ?>
            <p style="color:red;"><?php echo $nameErr; ?></p>
        <?php endif; ?>

        <button type="submit">Update Recipe</button>
    </form>
</body>
</html>
