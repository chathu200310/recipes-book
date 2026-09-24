<?php
session_start();
require_once 'includes/db.php';


$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : 'all';


$query = "SELECT recipes.*, users.username FROM recipes JOIN users ON recipes.user_id = users.id WHERE 1=1";

if (!empty($search)) {
    $query .= " AND (recipes.title LIKE '%" . $conn->real_escape_string($search) . "%' OR recipes.ingredients LIKE '%" . $conn->real_escape_string($search) . "%')";
}

if ($category != 'all') {
    $query .= " AND recipes.category = '" . $conn->real_escape_string($category) . "'";
}

$query .= " ORDER BY recipes.created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Recipes - TastyBytes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">

   
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold text-warning" href="index.php">🍳 TastyBytes</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="recipes.php">Recipes</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="btn btn-warning ms-2" href="dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="btn btn-outline-danger ms-2" href="auth/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="btn btn-outline-warning ms-2" href="auth/login.php">Login</a></li>
                        <li class="nav-item"><a class="btn btn-warning ms-2" href="auth/register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    
    <div class="container my-4">
        <h2 class="text-center fw-bold mb-4">Explore All Recipes</h2>

        
        <form action="recipes.php" method="GET" class="row g-3 justify-content-center mb-5">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by recipe name or ingredient..." value="<?= htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="all" <?= $category == 'all' ? 'selected' : ''; ?>>All Categories</option>
                    <option value="breakfast" <?= $category == 'breakfast' ? 'selected' : ''; ?>>Breakfast</option>
                    <option value="lunch" <?= $category == 'lunch' ? 'selected' : ''; ?>>Lunch</option>
                    <option value="dinner" <?= $category == 'dinner' ? 'selected' : ''; ?>>Dinner</option>
                    <option value="dessert" <?= $category == 'dessert' ? 'selected' : ''; ?>>Dessert</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-warning w-100 fw-bold">Filter</button>
            </div>
        </form>

        
        <div class="row">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($recipe = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <?php 
                                $img = !empty($recipe['image_url']) ? $recipe['image_url'] : 'https://images.unsplash.com/photo-1495521821757-a1efb6729352?w=500';
                            ?>
                            <img src="<?= htmlspecialchars($img); ?>" class="card-img-top" alt="<?= htmlspecialchars($recipe['title']); ?>" style="height: 200px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <div>
                                    <span class="badge bg-warning text-dark mb-2"><?= strtoupper(htmlspecialchars($recipe['category'])); ?></span>
                                    <h5 class="card-title fw-bold"><?= htmlspecialchars($recipe['title']); ?></h5>
                                    <p class="card-text text-muted small">By @<?= htmlspecialchars($recipe['username']); ?></p>
                                </div>
                                <div class="mt-auto">
                                    <h6><strong>Ingredients:</strong></h6>
                                    <p class="card-text small text-truncate"><?= htmlspecialchars($recipe['ingredients']); ?></p>
                                    
                                    
                                    <button class="btn btn-dark btn-sm w-100 mt-2" data-bs-toggle="modal" data-bs-target="#recipeModal<?= $recipe['id']; ?>">View Details</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="modal fade" id="recipeModal<?= $recipe['id']; ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-header-title fw-bold"><?= htmlspecialchars($recipe['title']); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <img src="<?= htmlspecialchars($img); ?>" class="img-fluid rounded mb-3" style="width: 100%; max-height: 250px; object-fit: cover;">
                                    <h6><strong>Category:</strong> <?= ucfirst(htmlspecialchars($recipe['category'])); ?></h6>
                                    <h6><strong>Posted By:</strong> <?= htmlspecialchars($recipe['username']); ?></h6>
                                    <hr>
                                    <h6><strong>Ingredients:</strong></h6>
                                    <p><?= nl2br(htmlspecialchars($recipe['ingredients'])); ?></p>
                                    <hr>
                                    <h6><strong>Instructions:</strong></h6>
                                    <p><?= nl2br(htmlspecialchars($recipe['instructions'])); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center my-5">
                    <p class="text-muted fs-5">No recipes found matching your search criteria.</p>
                    <a href="recipes.php" class="btn btn-outline-dark">Reset Filter</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">&copy; 2026 TastyBytes - Digital Recipe Book. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>