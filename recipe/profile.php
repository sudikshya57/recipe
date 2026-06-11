<?php
include "connection.php";
session_start();
if (!isset($_SESSION['name'])) {
  header("location: login.php");
  exit();
}

?>
<?php
include "nav-recipe.php";

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
     <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: rgb(250, 234, 196);
        }
        .profile-container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }
        .profile-picture {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            overflow: hidden;
            position: relative;
        }
        .profile-picture img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .upload-button {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            font-size: 12px;
            padding: 5px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .upload-button:hover {
            background-color: rgba(0, 0, 0, 0.9);
        }
        .profile-info h2 {
            margin: 0;
            color: #333;
        }
        .profile-info p {
            margin: 5px 0;
            color: #777;
        }
        .profile-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }
        .profile-actions button {
            flex: 1;
            padding: 10px;
            background-color: #5a3e2b;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .profile-actions button:hover {
            background-color: #6c4a2f;
        }
        .stats {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            padding: 10px;
            background: #f1f1f1;
            border-radius: 5px;
        }
        .stat {
            text-align: center;
        }
        .stat h3 {
            margin: 0;
            color: #333;
        }
        .stat p {
            margin: 5px 0;
            color: #555;
        }
</style>


  </head>
  


  <body>
    
    
<?php
$id = $_SESSION['id'];

$sql = "SELECT date(created_at) as created_at FROM user WHERE id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$created_at = $row['created_at'];





?>


<div class="profile-container">
    <div class="profile-header">

      <div class="profile-info">
       
      
        <h2><?php echo $_SESSION['name']; ?></h2>
        <p>Email: <?php echo $_SESSION['email'];?></p>
        <p>Joined: <?php echo $created_at ?></p>
      </div>
    </div>
    <div class="stats">
        <div class="stat">
            <h3>10</h3>
            <p>Recipes Uploaded</p>
        </div>
        <div class="stat">
            <h3>25</h3>
            <p>Recipes Liked</p>
        </div>
        <div class="stat">
            <h3>5</h3>
            <p>Recipes Saved</p>
        </div>
    </div>

    <div class="profile-actions">
        <a href="user/userupload.php">
             <button>Upload Recipe</button>
    </a>
        <button>View Uploaded Recipes</button>
        <button>Delete Account</button>
    </div>
</div>




</body>
</html>