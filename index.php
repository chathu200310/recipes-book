<?php
session_start();
require_once 'includes/db.php';


$latest_recipes = $conn->query("SELECT recipes.*, users.username FROM recipes JOIN users ON recipes.user_id = users.id ORDER BY recipes.created_at DESC LIMIT 3");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TastyBytes - Digital Recipe Book</title>
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-warning fs-3" href="index.php">🍳 TastyBytes</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link active fw-bold" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="recipes.php">Recipes</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="btn btn-warning ms-2 fw-bold" href="dashboard.php"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
                        <li class="nav-item"><a class="btn btn-outline-danger ms-2" href="auth/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="btn btn-outline-warning ms-2" href="auth/login.php">Login</a></li>
                        <li class="nav-item"><a class="btn btn-warning ms-2 fw-bold" href="auth/register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

   
    <div id="heroCarousel" class="carousel slide carousel-fade shadow-sm" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active" style="height: 480px;">
                <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=1200" class="d-block w-100 h-100" style="object-fit: cover; filter: brightness(0.6);" alt="Delicious Food">
                <div class="carousel-caption d-none d-md-block text-start mb-5">
                    <h1 class="display-4 fw-bold text-warning">Discover Delicious Recipes</h1>
                    <p class="fs-5">Explore thousands of mouth-watering dishes curated by passionate home chefs.</p>
                    <a href="recipes.php" class="btn btn-warning btn-lg fw-bold">Browse Recipes</a>
                </div>
            </div>
            <div class="carousel-item" style="height: 480px;">
                <img src="https://images.unsplash.com/photo-1498837167922-ddd27525d352?w=1200" class="d-block w-100 h-100" style="object-fit: cover; filter: brightness(0.6);" alt="Healthy Meals">
                <div class="carousel-caption d-none d-md-block text-start mb-5">
                    <h1 class="display-4 fw-bold text-warning">Share Your Culinary Creations</h1>
                    <p class="fs-5">Join our community and upload your own family recipes for the world to see.</p>
                    <a href="auth/register.php" class="btn btn-warning btn-lg fw-bold">Join Us Today</a>
                </div>
            </div>
            <div class="carousel-item" style="height: 480px;">
                <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1200" class="d-block w-100 h-100" style="object-fit: cover; filter: brightness(0.6);" alt="Cooking Fun">
                <div class="carousel-caption d-none d-md-block text-start mb-5">
                    <h1 class="display-4 fw-bold text-warning">Cook Like a Pro</h1>
                    <p class="fs-5">Step-by-step instructions and ingredient lists make cooking effortless.</p>
                    <a href="recipes.php" class="btn btn-warning btn-lg fw-bold">Explore Features</a>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

   
    <div class="bg-warning py-4 text-dark shadow-sm">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-2 mb-md-0">
                    <i class="fa-solid fa-utensils fa-2x mb-2"></i>
                    <h5 class="fw-bold mb-0">Easy to Follow Recipes</h5>
                </div>
                <div class="col-md-4 mb-2 mb-md-0">
                    <i class="fa-solid fa-users fa-2x mb-2"></i>
                    <h5 class="fw-bold mb-0">Active Community</h5>
                </div>
                <div class="col-md-4">
                    <i class="fa-solid fa-mobile-screen-button fa-2x mb-2"></i>
                    <h5 class="fw-bold mb-0">100% Mobile Responsive</h5>
                </div>
            </div>
        </div>
    </div>

    
    <div class="container my-5">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Explore Categories</h2>
            <p class="text-muted">Filter our interactive sample recipes by category</p>
           
            <div class="btn-group" role="group">
                <button class="btn btn-outline-dark filter-btn active" data-category="all">All</button>
                <button class="btn btn-outline-dark filter-btn" data-category="breakfast">Breakfast</button>
                <button class="btn btn-outline-dark filter-btn" data-category="lunch">Lunch</button>
                <button class="btn btn-outline-dark filter-btn" data-category="dessert">Dessert</button>
            </div>
        </div>

       
        <div class="row g-4" id="recipe-list">
          
        </div>
    </div>

   
    <div class="bg-light py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold mb-0">Recently Added Recipes</h3>
                <a href="recipes.php" class="btn btn-outline-dark btn-sm">View All <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="row g-4">
                <?php if ($latest_recipes && $latest_recipes->num_rows > 0): ?>
                    <?php while ($r = $latest_recipes->fetch_assoc()): ?>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <?php $img = !empty($r['image_url']) ? $r['image_url'] : 'https://images.unsplash.com/photo-1495521821757-a1efb6729352?w=500'; ?>
                                <img src="<?= htmlspecialchars($img); ?>" class="card-img-top" alt="<?= htmlspecialchars($r['title']); ?>" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <span class="badge bg-warning text-dark mb-2"><?= strtoupper(htmlspecialchars($r['category'])); ?></span>
                                    <h5 class="card-title fw-bold"><?= htmlspecialchars($r['title']); ?></h5>
                                    <p class="card-text text-muted small">By @<?= htmlspecialchars($r['username']); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-4">
                        <p class="text-muted">No recipes added to the database yet. Be the first to add one!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

   
    <div class="container my-5 text-center">
        <div class="p-5 bg-dark text-white rounded-3 shadow">
            <h2 class="fw-bold text-warning mb-3">Have a Great Recipe in Mind?</h2>
            <p class="col-md-8 mx-auto text-light mb-4">Sign up today to save your favorite dishes, post your unique food creations, and interact with other food enthusiasts.</p>
            <a href="auth/register.php" class="btn btn-warning btn-lg fw-bold px-4 me-2">Create Account</a>
            <a href="contact.php" class="btn btn-outline-light btn-lg px-4">Contact Us</a>
        </div>
    </div>

  
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-1 fw-bold text-warning">🍳 TastyBytes - Digital Recipe Book</p>
            <p class="mb-0 small text-muted">&copy; 2026 Rajarata University of Sri Lanka - Faculty of Technology. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>