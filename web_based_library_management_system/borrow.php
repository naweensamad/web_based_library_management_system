<?php
// Start the session to manage user login state and session variables
session_start(); 

// Set session timeout limit to 2 hours (specified in seconds)
$session_timeout = 2 * 60 * 60; // 2 hours

// Check if the session has expired
if (isset($_SESSION['login_time'])) {
    // Calculate the time since the user logged in
    $time_since_login = time() - $_SESSION['login_time'];
    if ($time_since_login > $session_timeout) {
        // If session has expired, log the user out by clearing session data and redirecting to the login page
        session_unset();
        session_destroy();
        header("Location: login.php?message=session_expired");
        exit();
    }
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect to the login page with an appropriate message
    header("Location: login.php?message=login_required");
    exit();
}

// Check if the user is a member and not an admin
if ($_SESSION['member_type'] !== 'Member') {
    // If the user is not a member, restrict their access to borrowing books and redirect them
    header("Location: browse&borrowbooks.php?message=admin_cannot_borrow");
    exit();
}

// Include the database connection file to interact with the database
include 'database.php'; 

// Check if the 'book_id' is received from the form submission
if (isset($_POST['book_id'])) {
    // Retrieve the book ID from the form data and the user ID from the session
    $bookId = $_POST['book_id'];
    $userId = $_SESSION['user_id'];

    // Query to check if the book is already borrowed
    $checkSql = "SELECT * FROM book_status WHERE book_id = '$bookId' AND status = 'Onloan'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        // If the book is already on loan, redirect to the browse and borrow books page with a message
        header("Location: browse&borrowbooks.php?message=already_onloan");
        exit();
    } else {
        // Update the book status in the database to mark it as borrowed
        $sql = "UPDATE book_status SET member_id = '$userId', status = 'Onloan', applied_date = NOW() WHERE book_id = '$bookId'";
        
        if ($conn->query($sql) === TRUE) {
            // If the update is successful, redirect to the browse and borrow books page with a success message
            header("Location: browse&borrowbooks.php?message=success");
            exit();
        } else {
            // If the update fails, display an error message
            echo "Error updating record: " . $conn->error;
        }
    }
    
    // Close the database connection
    $conn->close();
} else {
    // If the book ID is missing from the form submission, display an error message
    echo "Book ID is missing.";
}
?>
