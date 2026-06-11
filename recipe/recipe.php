<?php
session_start();
include("connection.php");  // Include your database connection here

// Fetch approved recipes from the database
$sql = "SELECT id, name, image, ingredients, instructions, category, rating_total, rating_count FROM recipes WHERE status = 'approved'";
$result = mysqli_query($conn, $sql);

// Check if the query was successful and fetch results
$recipes = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $recipes[] = $row; // Store each approved recipe in the $recipes array
    }
} else {
    echo "Error fetching recipes: " . mysqli_error($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home's Recipe</title>
  <link href="recipe.css" rel="stylesheet">
  <style>
    .star-rating {
      display: flex;
      cursor: pointer;
    }

    .star-rating .star {
      font-size: 30px;
      color: gray;
      margin-right: 5px;
    }

    .star-rating .star.selected {
      color: gold;
    }
  </style>
</head>
<body>
<?php
include "nav-recipe.php";
?>

<section class="hero">
  <div class="hero-section">
    <h2>Where taste meets tradition and creativity.</h2>
    <form action="#" class="search-box">
      <input type="text" class="search-control" id="search-input" placeholder="Search">
      <button type="submit" class="search-btn btn" id="search-btn">Search</button>
    </form>
  </div>
</section>

<section class="recipes">
  <div class="meal-result">
    <h2 class="title">Your Search Results:</h2>

    <div id="meal">
      <!-- Display the approved recipes here -->
      <?php foreach ($recipes as $recipe): ?>
        <div class="meal-item">
          <div class="meal-img">
            <!-- Display the recipe image -->
            <img src="admin/<?= htmlspecialchars($recipe['image']) ?>" alt="food">
          </div>
          <div class="meal-name">
            <h3><?= htmlspecialchars($recipe['name']) ?></h3>
            
            <!-- Star Rating System -->
            <div class="star-rating" data-id="<?= $recipe['id'] ?>">
              <span class="star" data-value="1">&#9733;</span>
              <span class="star" data-value="2">&#9733;</span>
              <span class="star" data-value="3">&#9733;</span>
              <span class="star" data-value="4">&#9733;</span>
              <span class="star" data-value="5">&#9733;</span>
            </div>
            
            <!-- Display the average rating -->
            <p class="average-rating" id="average-rating-<?= $recipe['id'] ?>">
              Average Rating: <?= $recipe['rating_count'] > 0 ? number_format($recipe['rating_total'] / $recipe['rating_count'], 1) : 'No ratings yet' ?>
            </p>
            
            <!-- Show recipe details on the same page -->
            <button class="recipe-btn" data-id="<?= $recipe['id'] ?>">Get Recipe</button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="meal-details" id="meal-details" style="display:none;">
    <button type="button" class="btn recipe-close-btn" id="recipe-close-btn" onclick="closeRecipeDetails()">&times;</button>

    <div class="meal-details-content">
      <h2 class="recipe-title" id="recipe-name">Meals Name Here</h2>
      <p class="recipe-category" id="recipe-category">Category Name</p>
      <div class="recipe-instruct">
        <h3>Instructions:</h3>
        <p id="recipe-instructions">....</p>
      </div>
      <div class="recipe-meal-img">
        <img src="food.jpg" id="recipe-image" alt="">
        <h2 style="text-align:center;">Ingredients:</h2>
        <ul class="ingredients" id="recipe-ingredients" style="text-align:center;">
          <!-- Ingredients will be populated here -->
        </ul>
      </div>
    </div>
  </div>
</section>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const recipeButtons = document.querySelectorAll(".recipe-btn");
    const mealDetails = document.getElementById("meal-details");

    // Handle the "Get Recipe" button click
    recipeButtons.forEach(button => {
      button.addEventListener("click", function() {
        const recipeId = button.getAttribute("data-id");

        // Fetch recipe details by ID
        const recipe = <?php echo json_encode($recipes); ?>.find(r => r.id == recipeId);
        
        if (recipe) {
          document.getElementById('recipe-name').textContent = recipe.name;
          document.getElementById('recipe-category').textContent = recipe.category;
          document.getElementById('recipe-instructions').textContent = recipe.instructions;

          // Populate ingredients
          const ingredientsList = document.getElementById('recipe-ingredients');
          ingredientsList.innerHTML = ''; // Clear previous ingredients
          recipe.ingredients.split(',').forEach(ingredient => {
            const li = document.createElement('li');
            li.textContent = ingredient.trim();
            ingredientsList.appendChild(li);
          });

          // Use relative path for images stored in 'admin/uploads'
          document.getElementById('recipe-image').src = 'admin/' + recipe.image;
          mealDetails.style.display = "block";  // Show the meal details
        }
      });
    });

    // Handle star rating click
    const starRatings = document.querySelectorAll('.star-rating');
    starRatings.forEach(starRating => {
      const stars = starRating.querySelectorAll('.star');

      stars.forEach(star => {
        star.addEventListener('click', function() {
          const rating = star.getAttribute('data-value');

          // Update UI to show selected stars
          stars.forEach(s => s.classList.remove('selected'));
          for (let i = 0; i < rating; i++) {
            stars[i].classList.add('selected');
          }

          // Send the rating to the server using AJAX
          const recipeId = starRating.getAttribute('data-id');
          saveRating(recipeId, rating);
        });
      });
    });

    // Send rating to the server using AJAX
    function saveRating(recipeId, rating) {
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'save_rating.php', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          const response = JSON.parse(xhr.responseText);
          const averageRating = response.average_rating;

          // Update the average rating display
          const averageRatingElement = document.getElementById('average-rating-' + recipeId);
          if (averageRatingElement) {
            averageRatingElement.textContent = 'Average Rating: ' + averageRating.toFixed(1);
          }
        }
      };
      xhr.send('recipe_id=' + recipeId + '&rating=' + rating);
    }

    // Close the recipe details modal
    document.getElementById("recipe-close-btn").addEventListener("click", function() {
      mealDetails.style.display = "none";
    });
  });

  function closeRecipeDetails() {
    document.getElementById("meal-details").style.display = "none";
  }
</script>

</body>
</html> 