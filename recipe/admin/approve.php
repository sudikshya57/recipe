
<?php

include "../connection.php";

session_start();
$id = $_GET['id'];
$sql =  "UPDATE recipes SET status='approved' WHERE id=$id";
$result = mysqli_query($conn, $sql);
if ($result) {
    header("Location: view.php");
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}


?>