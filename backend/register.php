<?php
// Include database connection file
include_once "db_connect.php";
$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["create-password"];

    
    // Perform validation
    $errors = array(); // Array to store validation errors
    
    // Check if username already exists
    $query = "SELECT * FROM user_credentials WHERE username = '$username'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $errors[] = "Username already exists. Please choose a different username.";
    }
    
    // Check if email already exists
    $query = "SELECT * FROM user_credentials WHERE email_id = '$email'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $errors[] = "Email already exists. Please use a different email address.";
    }
    
    // If there are validation errors, display them and exit
    if (!empty($errors)) {
        foreach ($errors as $error) {
            $response["success"] = false;
            $response["message"] = $error;
        }
    }
    
    // If validation passes, insert user data into the database
    // You need to hash the password before inserting it into the database for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $insert_query = "INSERT INTO user_credentials (username, email_id, password) VALUES ('$username', '$email', '$hashed_password')";
    $insert_result = mysqli_query($conn, $insert_query);
    
    
    if ($insert_result) {
        // Redirect user to login page after successful registration
        $response["success"] = true;
    } else {
        $response["success"] = false;
        $response["message"] ="Error" . mysqli_error($conn);
    }

    header("Content-Type: application/json");
    echo json_encode($response);
}
?>
