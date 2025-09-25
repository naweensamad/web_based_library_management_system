<?php
// Check if a session is not already started
// This ensures that session_start() is called only once in the application to avoid errors.
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start a new session or resume the existing session
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags for character encoding and responsive design -->
    <meta charset="UTF-8"> <!-- Defines the character set to UTF-8 for proper text rendering -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Makes the page responsive to all devices -->
    
    <!-- Page title displayed in the browser tab -->
    <title>Australian University Library</title>
    
    <!-- Linking Bootstrap CSS for styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Linking custom CSS for additional styling -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <!-- Library brand name displayed in the navigation bar -->
            <a class="navbar-brand" href="#">Australian University Library</a>
            
            <!-- Mobile menu toggle button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span> <!-- Icon displayed for mobile navigation -->
            </button>
            
            <!-- Collapsible navbar links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto"> <!-- Align navigation links to the right -->
                    <li class="nav-item">
                        <!-- Link to the Home page -->
                        <a class="nav-link" href="index.html">Home</a>
                    </li>
                    <li class="nav-item">
                        <!-- Link to the Browse Books page -->
                        <a class="nav-link" href="browse&borrowbooks.php">Browse Books</a>
                    </li>

                    <!-- Check if the user is logged in -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <!-- Link to My Account page for logged-in users -->
                            <a class="nav-link" href="myaccount.php">My Account</a>
                        </li>

                        <!-- Check if the logged-in user is an Admin -->
                        <?php if ($_SESSION['member_type'] === 'Admin'): ?>
                            <li class="nav-item">
                                <!-- Link to the Admin Dashboard for Admin users -->
                                <a class="nav-link" href="admindashboard.php">Admin Dashboard</a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item">
                            <!-- Logout link for logged-in users -->
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <!-- Links for users who are not logged in -->
                        <li class="nav-item">
                            <!-- Link to the Sign-Up page -->
                            <a class="nav-link" href="signup.php">Sign Up</a>
                        </li>
                        <li class="nav-item">
                            <!-- Link to the Login page -->
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</body>
</html>
