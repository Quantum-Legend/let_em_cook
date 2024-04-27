<?php
    // Database configuration file
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "let_em_cook";

    $conn = mysqli_connect($servername, $username, $password, $database);

    if(!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>