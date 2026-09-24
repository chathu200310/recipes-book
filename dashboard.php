<?php
session_start();
require_once 'includes/db.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_recipe'])) {
    $title = trim($_POST['title']);
    $category = trim($_POST['category']);
    $ingredients = trim($_POST['ingredients']);
    $instructions = trim($_POST['instructions']);
    $image_url = trim($_POST['image_url']);

    if (!empty($title) && !empty($category) && !empty($ingredients) && !empty($instructions)) {
        $stmt = $conn->prepare("INSERT INTO recipes (user_id, title, category, ingredients, instructions, image_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $user_id, $title, $category, $ingredients, $instructions, $image_url);
        
        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Recipe added successfully!</div>";
        } else {
            $message = "<div class='alert alert-danger'>Failed to add recipe.</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>Please fill in all required fields.</div>";
    }
}


$query = "SELECT * FROM recipes WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_recipes = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - TastyBytes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand text-warning fw-bold" href="index.php">🍳 TastyBytes</a>
            <div class="ms-auto d-flex align-items-center">
                <span class="text-white me-3">Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</span>
                <a href="auth/logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <?= $message ?>
        <div class="row">
           
            <div class="col-md-5 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Add New Recipe</h4>
                        <form action="dashboard.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Recipe Title</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select" required>
                                    <option value="breakfast">Breakfast</option>
                                    <option value="lunch">Lunch</option>
                                    <option value="dinner">Dinner</option>
                                    <option value="dessert">Dessert</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Image URL</label>
                                <input type="url" name="image_url" class="form-control" placeholder="https://example.com/image.jpg">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ingredients</label>
                                <textarea name="ingredients" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Instructions</label>
                                <textarea name="instructions" class="form-control" rows="3" required></textarea>
                            </div>
                            <button type="submit" name="add_recipe" class="btn btn-warning w-100 fw-bold">Save Recipe</button>
                        </form>
                    </div>
                </div>
            </div>

            
            <div class="col-md-7">
                <h4 class="mb-3">My Recipes</h4>
                <div class="row">
                    <?php if ($user_recipes->num_rows > 0): ?>
                        <?php while ($row = $user_recipes->fetch_assoc()): ?>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <span class="badge bg-warning text-dark mb-2"><?= strtoupper($row['category']); ?></span>
                                        <h5><?= htmlspecialchars($row['title']); ?></h5>
                                        <p class="small text-muted mb-1"><strong>Ingredients:</strong> <?= htmlspecialchars($row['ingredients']); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-muted">You haven't added any recipes yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</body>
</html>