<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home's Recipe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
   
    <style>
      /* Styling for the hero section */
      .hero-container {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        padding: 0;
      }

      .hero-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }

      .hero-text {
        position: absolute;
        color: rgba(37, 28, 90, 0.5);
        font-size: 3rem;
        font-weight: bold;
        font-family: 'Arial', sans-serif;
        text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
        text-align: center;
      }
    </style>
  </head>
  <body style="overflow:hidden;">
    <?php 
      include "navbar.php";
    ?>
    <div class="hero-container">
      <img src="https://images.pexels.com/photos/735869/pexels-photo-735869.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Recipe Background">
      <div class="hero-text">Welcome to our Home's Recipe website</div>
    </div>
    
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#"></a>
          </li>
        </ul>
        <form class="d-flex">
          <button class="btn btn-outline-success mx-2" type="submit">Signup</button>
          <button class="btn btn-outline-primary mx-2" type="submit">Login</button>
          
        </form>
      </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>
