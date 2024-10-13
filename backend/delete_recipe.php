<?php
// Include database connection file
include_once "db_connect.php";

// Start session to ensure user is logged in
session_start();

// Check if user is logged in and the recipe ID is provided
if (isset($_POST['recipe_id']) && isset($_SESSION['user_id'])) {
    $recipe_id = $_POST['recipe_id'];
    $user_id = $_SESSION['user_id'];

    // Optional: Verify that the user owns the recipe they are trying to delete
    $query = "DELETE FROM recipes WHERE id = ? AND created_by = (SELECT username FROM user_credentials WHERE user_id = ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $recipe_id, $user_id);

    // Execute the query
    if (mysqli_stmt_execute($stmt)) {
        // Success, redirect back to browse_recipes.php
        header("Location: ../frontend/browse_recipes.php?message=Recipe Deleted");
    } else {
        // Failure, send error message
        echo "Error: Could not delete recipe.";
    }

    // Close the statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    // Redirect to login if not logged in
    header("Location: ../frontend/login.php");
    exit();
}
?>
