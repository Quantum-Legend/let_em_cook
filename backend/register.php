<?php
 // Include database connection file
include_once "db_connect.php";
session_start();
$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data and sanitize
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'create-password', FILTER_SANITIZE_STRING);


    // Check if username already exists
    $query = "SELECT * FROM user_credentials WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $response["success"] = false;
        $response["message"] = "Username already exists. Please choose a different username.";
    } else {
        // Check if email already exists
        $query = "SELECT * FROM user_credentials WHERE email_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $response["success"] = false;
            $response["message"] = "Email already exists. Please use a different email address.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user data into the database
            $insert_query = "INSERT INTO user_credentials (username, email_id, password) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);
            $insert_result = mysqli_stmt_execute($stmt);

            if ($insert_result) {
                $response["success"] = true;
            } else {
                $response["success"] = false;
                $response["message"] = "Error: Unable to register user.";
            }
        }
    }

    // Close prepared statement and database connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    // Send JSON response
    header("Content-Type: application/json");
    echo json_encode($response);
}
?>
