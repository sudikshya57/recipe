<?php
session_start();
include("../connection.php");

// Display success message for deletion
if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
    echo "<p style='color:green;'>Item has been deleted successfully!</p>";
    echo "<script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.pathname);
            }
          </script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel View</title>
    <link rel="stylesheet" href="../nav-recipe.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fdf6e4;
            color: #5a3e36;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #8b4513;
        }

        .button-container {
            overflow: hidden;
            margin-bottom: 20px;
        }

        .button-container a {
            text-decoration: none;
        }

        .upload-btn {
            float: left;
            background-color: #8b4513;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 14px;
            cursor: pointer;
        }

        .upload-btn:hover {
            background-color: #a0522d;
        }

        .logout-btn {
            float: right;
            background-color: rgba(124, 17, 17, 0.7);
            color: #ffffff;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 14px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: #b02a37;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff5eb;
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #d2b48c;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #a0522d;
            color: #ffffff;
        }

        tr:nth-child(even) {
            background-color: #f8e4d8;
        }

        tr:hover {
            background-color: #f1c7b1;
        }

        img {
            max-width: 100px;
            height: auto;
            border: 2px solid #d2b48c;
            border-radius: 5px;
        }

        button {
            color: #ffffff;
            border: none;
            border-radius: 5px;
            padding: 8px 12px;
            margin: 2px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover {
            opacity: 0.9;
        }

        .btn-update {
            background-color: #4682b4;
        }

        .btn-update:hover {
            background-color: #315f85;
        }

        .btn-delete {
            background-color: #dc3545;
        }

        .btn-delete:hover {
            background-color: #b02a37;
        }

        .btn-approve {
            background-color: #28a745;
        }

        .btn-approve:hover {
            background-color: #218838;
        }

        .actions {
            display: flex;
            gap: 5px;
        }

        @media (max-width: 768px) {
            table {
                font-size: 14px;
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            th, td {
                padding: 8px;
            }

            button {
                font-size: 12px;
                padding: 6px 10px;
            }
        }
    </style>
</head>

<body>
   
    <h1>Admin Panel View</h1>
    <div class="button-container">
        <a href="upload.php">
            <button class="upload-btn">Upload Recipe</button>
        </a>
        <a href="../logout.php">
            <button class="logout-btn">Logout</button>
        </a>
    </div>

    <!-- Pending Recipes Table -->
    <table>
        <h2 style="font-size:30px; text-align:center;">Pending Recipes</h2>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Instructions</th>
                <th>Ingredients</th>
                <th>Image</th>
                <th>Status</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT id, name, instructions, ingredients, image, status, category FROM recipes WHERE status = 'pending'";
            $result = mysqli_query($conn, $sql);

            if (!$result) {
                echo "<tr><td colspan='8'>Error fetching data: " . mysqli_error($conn) . "</td></tr>";
            } elseif (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = htmlspecialchars($row['id']);
                    $name = htmlspecialchars($row['name']);
                    $instructions = htmlspecialchars($row['instructions']);
                    $ingredients = htmlspecialchars($row['ingredients']);
                    $image = !empty($row['image']) ? htmlspecialchars($row['image']) : "default-image.png";
                    $status = htmlspecialchars($row['status']);
                    $category = htmlspecialchars($row['category']);

                    echo "<tr>
                        <td>{$id}</td>
                        <td>{$name}</td>
                        <td>{$instructions}</td>
                        <td>{$ingredients}</td>
                        <td><img src='{$image}' alt='Recipe Image'></td>
                        <td>{$status}</td>
                        <td>{$category}</td>
                        <td class='actions'>
                            <form action='update.php' method='GET'>
                                <input type='hidden' name='id' value='{$id}'>
                                <button type='submit' class='btn-update'>Update</button>
                            </form>
                            <form action='delete.php' method='GET'>
                                <input type='hidden' name='id' value='{$id}'>
                                <button type='submit' class='btn-delete'>Delete</button>
                            </form>
                            <form action='approve.php' method='GET'>
                                <input type='hidden' name='id' value='{$id}'>
                                <button type='submit' class='btn-approve'>Approve</button>
                            </form>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No pending recipes found!</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- All Recipes Table -->
    <table>
        <h2 style="font-size:30px; text-align:center;">All Recipes</h2>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Instructions</th>
                <th>Ingredients</th>
                <th>Image</th>
                <th>Status</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT id, name, instructions, ingredients, image, status, category FROM recipes";
            $result = mysqli_query($conn, $sql);

            if (!$result) {
                echo "<tr><td colspan='8'>Error fetching data: " . mysqli_error($conn) . "</td></tr>";
            } elseif (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = htmlspecialchars($row['id']);
                    $name = htmlspecialchars($row['name']);
                    $instructions = htmlspecialchars($row['instructions']);
                    $ingredients = htmlspecialchars($row['ingredients']);
                    $image = !empty($row['image']) ? htmlspecialchars($row['image']) : "default-image.png";
                    $status = htmlspecialchars($row['status']);
                    $category = htmlspecialchars($row['category']);

                    echo "<tr>
                        <td>{$id}</td>
                        <td>{$name}</td>
                        <td>{$instructions}</td>
                        <td>{$ingredients}</td>
                        <td><img src='{$image}' alt='Recipe Image'></td>
                        <td>{$status}</td>
                        <td>{$category}</td>
                        <td class='actions'>
                            <form action='update.php' method='GET'>
                                <input type='hidden' name='id' value='{$id}'>
                                <button type='submit' class='btn-update'>Update</button>
                            </form>
                            <form action='delete.php' method='GET'>
                                <input type='hidden' name='id' value='{$id}'>
                                <button type='submit' class='btn-delete'>Delete</button>
                            </form>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No recipes found!</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
