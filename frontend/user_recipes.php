<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Recipes</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/browse_recipes.css">
</head>
<body>
<header>
    <img src="./images/bg.jpg" class="background-image"/>
    <div class="overlay">
        <div class="heading-container">
            <h1>Let 'Em Cook!</h1>
            <h3>User Recipes</h3>
        </div>
        <div class="header-buttons">
            <a href="create_recipe.html" class="button">Create Recipe</a>
            <a href="../backend/logout.php" class="button">Logout</a>
            <a href="user_profile.php" class="button">User</a>
        </div>
    </div>
</header>

<main>
    <section id="recipe-list">
    <?php
// Include database connection file
include_once "../backend/db_connect.php";

// Check if user is logged in
session_start();
if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];

    $username_query = "SELECT username FROM user_credentials WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $username_query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $username);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Query to fetch recipes created by the user
    $query = "SELECT * FROM recipes WHERE created_by = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username); // Assuming username is a string
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if there are any recipes
    if (mysqli_num_rows($result) > 0) {
        // Loop through each recipe and display them
        while ($row = mysqli_fetch_assoc($result)) {
            $title = $row['title'];
            $description = $row['description'];
            $ingredients = $row['ingredients'];
            $instructions = $row['instructions'];
            $instructions_html = nl2br($instructions);
            $image_name = $row['image_name'];
            $image_path = "./images/" . $image_name; // Path to the image directory

            // Output HTML for each recipe item
            echo "<article class='recipe-item'>";
            echo "<h2>$title</h2>";
            echo "<p>$description</p>";
            echo "<img src='$image_path' alt='$title' class='recipe-image'>"; // Display the image
            echo "<ul>";
            // You may need to format ingredients and instructions appropriately
            echo "<li><strong>Ingredients<br/></strong><span><div class='content'>$ingredients</div></span></li>";
            echo "<li><strong>Instructions<br/></strong><span><div class='content'>$instructions_html</div></span></li>";

            echo "</ul>";
            echo "</article>";
        }
    } else {
        // Display a message if no recipes are found
        echo "<p>No recipes found.</p>";
    }
} else {
    // Redirect to login page if user is not logged in
    header("location: login.php");
    exit;
}

// Close the database connection
mysqli_close($conn);
?>

    </section>
</main>
<footer>
    <p>&copy; 2024 Recipe Sharing Platform</p>
</footer>
</body>
</html>
