<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/forms.css">
    <link rel="stylesheet" href="css/user_profile.css">
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
                <a href="../backend/logout.php" class="button">Logout</a>
                <a href="user_profile.php" class="button">User</a>
            </div>
        </div>
    </header>
    <main>
        <section id="user-info">
            <h2>User Information</h2>
            <?php
            session_start();
            include_once "../backend/db_connect.php"; // Include your database connection script

            // Check if user is logged in
            if(isset($_SESSION["user_id"])) {
                $user_id = $_SESSION["user_id"];
                
                // Query to fetch user information based on user_id
                $query = "SELECT * FROM user_credentials WHERE user_id = $user_id";
                $result = mysqli_query($conn, $query);

                if(mysqli_num_rows($result) > 0) {
                    $user_info = mysqli_fetch_assoc($result);
                    echo '<form>';
                    echo '<div>';
                    echo '<label for="username">Username:</label>';
                    echo '<input type="text" id="username" name="username" value="' . $user_info['username'] . '" readonly>';
                    echo '</div>';
                    echo '<div>';
                    echo '<label for="email">Email:</label>';
                    echo '<input type="email" id="email" name="email" value="' . $user_info['email_id'] . '" readonly>';
                    echo '</div>';
                    echo '</form>';
                } else {
                    // Handle if user information is not found
                    echo "User information not found.";
                }
            } else {
                // Redirect to login page if user is not logged in
                header("location: login.php");
                exit;
            }

            mysqli_close($conn);
            ?>
            <!-- Add more user information fields as needed -->
        </section>
        <section id="edit-profile">
            <h2>Edit Profile</h2>
            <form id="edit-profile-form" method="post" action="update_profile.php">
                <label for="new-username">New Username:</label>
                <input type="text" id="new-username" name="new-username" placeholder="Enter new username"><br>
                <label for="new-email">New Email:</label>
                <input type="email" id="new-email" name="new-email" placeholder="Enter new email address"><br>
                <!-- Add more fields for editing profile information -->
                <input type="submit" value="Save Changes">
            </form>
        </section>
        <section id="profile-actions">
            <h2>Profile Actions</h2>
            <ul>
                <li><a href="user_recipes.php">View My Recipes</a></li>
                <!-- Add more profile-related actions as needed -->
            </ul>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Recipe Sharing Platform</p>
    </footer>
</body>
</html>
