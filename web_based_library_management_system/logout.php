<?php
// Start session
session_start();

// Destroy all session data to log the user out
session_unset();  // Unset all session variables to clear user data
session_destroy();  // Destroy the session completely to ensure logout

// Optionally, add a delay for automatic redirection to the login page
header("refresh:5;url=login.php"); // Redirect the user to the login page after 5 seconds

// Include the header for consistent page layout
include 'header.php'; 
?>

<!-- Logout Message Section -->
<div class="container main-section">
    <div class="logout-message">
        <!-- Display logout confirmation message -->
        <h1>You have successfully logged out</h1>
        <p>Thank you for using the Australian University Library System.</p>
        <!-- Provide options for the user to log back in or return to the home page -->
        <p>
            <a href="login.php">Log back in</a> 
            or 
            <a href="index.html">Return to the Home Page</a>.
        </p>
    </div>
</div>

<?php
// Include the footer for consistent page layout
include 'footer.php'; 
?>
