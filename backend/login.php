<?php
// Include database connection file
include_once "db_connect.php";
session_start();
$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $username_or_email = filter_input(INPUT_POST, 'username_or_email', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // Prepare and execute the SQL query
    $query = "SELECT * FROM user_credentials WHERE username = ? OR email_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $username_or_email, $username_or_email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check whether username or email found in the database
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row["password"];

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Authentication successful
            $user_id = $row["user_id"];
            $_SESSION["loggedin"] = true;
            $_SESSION["user_id"] = $user_id;
            $response["success"] = true;
        } else {
            // Authentication failed
            $response["success"] = false;
            $response["message"] = "Invalid Password!";
        }
    } else {
        $response["success"] = false;
        $response["message"] = "Username/Email ID not found";
    }

    // Close prepared statement and database connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    // Send JSON response
    header("Content-Type: application/json");
    echo json_encode($response);
}
?>
