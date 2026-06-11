<?php
include("../connection.php");

$rowId = $_GET['id'];
$sql = "DELETE FROM recipes WHERE id=$rowId";

$result = mysqli_query($conn, $sql);

if ($result) {
    // Redirect to view.php with a query parameter indicating the item was deleted
    header('Location: view.php?deleted=1'); 
    exit();
} else {
    echo "Error deleting record: " . mysqli_error($conn);
}
?>
