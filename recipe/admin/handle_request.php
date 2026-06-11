    <?php
    // Database connection
    $conn = new mysqli("localhost", "root", "", "db", 3307);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Process form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = intval($_POST['id']);
        $action = $_POST['action'];

        if ($action === 'approve') {
            // Approve the recipe
            $stmt = $conn->prepare("UPDATE recipes SET status='approved' WHERE id=?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo "Recipe approved successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }
        } elseif ($action === 'delete') {
            // Delete the recipe
            $stmt = $conn->prepare("DELETE FROM recipes WHERE id=?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo "Recipe deleted successfully!";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }

    // Close the database connection
    $conn->close();

    // Redirect back to the admin panel
    header("Location: admin_panel.php");
    exit();
    ?>
