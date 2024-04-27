<?php
    session_start();
    $response = array();

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $username_or_email = $_POST["username_or_email"];
        $password =$_POST["password"];

        include_once "db_connect.php";

        $username_or_email = mysqli_real_escape_string($conn, $username_or_email);
        $password = mysqli_real_escape_string($conn, $password);

        $query = "SELECT * FROM user_credentials WHERE username = '$username_or_email' OR email_id = '$username_or_email'";
        $result = mysqli_query($conn, $query);

        // Check whether username or email found in the database
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $hashed_password = $row["password"];

            if(password_verify($password, $hashed_password)) {
                // Authentication successful
                $_SESSION["loggedin"] = true;
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

        header("Content-Type: application/json");
        echo json_encode($response);
    }
    