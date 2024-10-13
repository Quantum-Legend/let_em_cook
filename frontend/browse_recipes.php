<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Recipes</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/browse_recipes.css">
</head>
<body>
<header>
    <img src="./images/bg.jpg" class="background-image"/>
    <div class="overlay">
        <div class="heading-container">
            <h1>Let 'Em Cook!</h1>
            <h3>Browse Recipes</h3>
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

    // Query to fetch recipe data from the database
    $query = "SELECT * FROM recipes";
    $result = mysqli_query($conn, $query);

    // Check if there are any recipes
    if (mysqli_num_rows($result) > 0) {
        // Loop through each recipe and display them
        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['id']; // Assuming 'recipe_id' is the primary key
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
            // Format ingredients and instructions
            echo "<li><strong>Ingredients<br/></strong><span><div class='content'>$ingredients</div></span></li>";
            echo "<li><strong>Instructions<br/></strong><span><div class='content'>$instructions_html</div></span></li>";
            echo "</ul>";

            // Add a delete button
            echo "<form action='../backend/delete_recipe.php' method='POST'>";
            echo "<input type='hidden' name='recipe_id' value='$id'>";
            echo "<input type='submit' value='Delete' class='delete-button'>";
            echo "</form>";

            echo "</article>";
        }
    } else {
        // Display a message if no recipes are found
        echo "<p>No recipes found.</p>";
    }

    // Close the database connection
    mysqli_close($conn);
    ?>
    </section>
</main>

<footer>
    <p>&copy; 2024 Recipe Sharing Platform</p>
</footer>
<script>
document.querySelectorAll('.delete-button').forEach(button => {
    button.addEventListener('click', function(event) {
        if (!confirm('Are you sure you want to delete this recipe?')) {
            event.preventDefault();
        }
    });
});
</script>

</body>
</html>
