<?php
include("connection.php"); // Include your database connection here

if (isset($_POST['recipe_id']) && isset($_POST['rating'])) {
    $recipe_id = $_POST['recipe_id'];
    $rating = (int)$_POST['rating'];

    // Get the current rating total and count from the database
    $sql = "SELECT rating_total, rating_count FROM recipes WHERE id = $recipe_id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $rating_total = $row['rating_total'];
        $rating_count = $row['rating_count'];

        // Update the total rating and count
        $rating_total += $rating;
        $rating_count += 1;

        // Update the database with the new rating values
        $update_sql = "UPDATE recipes SET rating_total = $rating_total, rating_count = $rating_count WHERE id = $recipe_id";
        if (mysqli_query($conn, $update_sql)) {
            // Calculate the new average rating
            $average_rating = $rating_total / $rating_count;

            // Respond with the new average rating
            echo json_encode(['average_rating' => $average_rating]);
        } else {
            echo "Error updating rating: " . mysqli_error($conn);
        }
    } else {
        echo "Recipe not found.";
    }
} else {
    echo "Invalid request.";
}
?>
