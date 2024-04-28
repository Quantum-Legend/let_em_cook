<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Redirect to login page if not logged in
    header("location: login.php");
    exit;
}

// Include database connection file
include_once "db_connect.php";  

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $title = $_POST["title"];
    $description = $_POST["description"];
    $ingredients = $_POST["ingredients"];
    $instructions = $_POST["instructions"];
    $user_id = $_SESSION["user_id"]; // Get user_id from session

    // Get the username from the user_credentials table using user_id
    $username_query = "SELECT username FROM user_credentials WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $username_query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $username);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Process image upload
    $image_name = $_FILES["image"]["name"];
    $image_tmp_name = $_FILES["image"]["tmp_name"];

    // Insert recipe data into the database
    $insert_query = "INSERT INTO recipes (title, description, ingredients, instructions, created_by, image_name) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($stmt, "ssssss", $title, $description, $ingredients, $instructions, $username, $image_name);
    
    // Execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        // Get the ID of the last inserted recipe
        $recipe_id = mysqli_insert_id($conn);
        
        // Set the image name format as recipe<ID>.jpg
        $image_name = "recipe{$recipe_id}.jpg";
        
        // Move uploaded image to destination folder
        $image_destination = "../frontend/images/{$image_name}";
        move_uploaded_file($image_tmp_name, $image_destination);
        
        // Update the image name in the database
        $update_query = "UPDATE recipes SET image_name = ? WHERE id = ?";
        $stmt_update = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt_update, "si", $image_name, $recipe_id);
        mysqli_stmt_execute($stmt_update);
        mysqli_stmt_close($stmt_update);
        
        // Redirect to browse recipes page
        header("location: ../frontend/browse_recipes.php");
        exit;
    } else {
        // Error handling
        echo "Error: " . mysqli_error($conn);
    }

    // Close statements
    mysqli_stmt_close($stmt);
}

// Close connection
mysqli_close($conn);
?>
