<?php
// Start the session to manage user authentication and session variables
session_start();

// Set session timeout limit to 2 hours (in seconds)
$session_timeout = 2 * 60 * 60; // 2 hours

// Check if the session has expired
if (isset($_SESSION['login_time'])) {
    // Calculate the time since the user logged in
    $time_since_login = time() - $_SESSION['login_time'];
    if ($time_since_login > $session_timeout) {
        // If session has expired, log out the user
        session_unset(); // Clear session variables
        session_destroy(); // Destroy the session
        header("Location: login.php?message=session_expired"); // Redirect to the login page
        exit();
    }
}

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['user_id']) || $_SESSION['member_type'] != 'Admin') {
    // If the user is not logged in or not an admin, restrict access and redirect to the login page
    header("Location: login.php?message=admin_access_required");
    exit();
}

// Include the header for consistent page structure and navigation
include 'header.php'; 

// Include the database connection to allow interaction with the database
include 'database.php'; 
?>

<!-- Admin Dashboard Title -->
<h1 class="page-title">Admin Dashboard</h1>

<!-- Dashboard Container for Admin Options -->
<div class="dashboard-container">
    <!-- Option to navigate to the Edit Book Details page -->
    <div class="dashboard-item">
        <a href="editbooksdetails.php">
            <button>Edit Book Details</button>
        </a>
    </div>

    <!-- Option to navigate to the Return or Delete Books page -->
    <div class="dashboard-item">
        <a href="return-or-deletebooks.php">
            <button>Return or Delete Books</button>
        </a>
    </div>
</div>

<!-- Section for Upcoming Events -->
<div class="dashboard-upcoming-events">
    <h3>Upcoming Events</h3>
    <!-- Static list of events for admin reference -->
    <ul>
        <li>Library System Maintenance - September 30, 2024</li>
        <li>Book Fair - October 15, 2024</li>
        <li>Staff Training Session - October 22, 2024</li>
    </ul>
</div>

<?php
// Include the footer for consistent page layout and closing elements
include 'footer.php'; 
?>
