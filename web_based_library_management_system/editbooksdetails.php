<?php
// Start the session at the very top of the page
session_start();

// Set session timeout limit (2 hours in seconds)
$session_timeout = 2 * 60 * 60; // 2 hours

// Check if the session has expired
if (isset($_SESSION['login_time'])) {
    $time_since_login = time() - $_SESSION['login_time'];
    if ($time_since_login > $session_timeout) {
        // If session has expired, log out the user
        session_unset(); // Unset all session variables
        session_destroy(); // Destroy the session
        header("Location: login.php?message=session_expired"); // Redirect to login page with a message
        exit();
    }
}

// Include the header file for consistent page layout and navigation
include 'header.php'; 

// Include the database connection file to interact with the database
include 'database.php'; 

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['member_type'] !== 'Admin') {
    // If the user is not logged in or not an admin, redirect to login page with an error message
    header("Location: login.php?message=admin_required");
    exit();
}

// If the form is submitted (via POST method), process the update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the input data from the form and escape it to prevent SQL injection
    $bookTitle = mysqli_real_escape_string($conn, $_POST['bookTitle']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $publisher = mysqli_real_escape_string($conn, $_POST['publisher']);
    $language = mysqli_real_escape_string($conn, $_POST['language']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    
    // SQL query to update the book details in the database
    $sql = "UPDATE books SET author = '$author', publisher = '$publisher', language = '$language', category = '$category' WHERE book_title = '$bookTitle'";
    
    // Execute the query and provide feedback to the user
    if ($conn->query($sql) === TRUE) {
        echo "Book details updated successfully!";
    } else {
        // Display error message if the query fails
        echo "Error: " . $conn->error;
    }
    
    // Close the database connection
    $conn->close();
}
?>

<!-- Edit Book Details Form Section -->
<div class="edit-book-container">
    <h1 class="page-title">Edit Book Details</h1> <!-- Title of the page -->
    <form class="edit-book-form" action="editbooksdetails.php" method="post"> <!-- Form to edit book details -->
        <div class="form-group">
            <label for="bookTitle">Book Title:</label> <!-- Input for the book title -->
            <input type="text" id="bookTitle" name="bookTitle" placeholder="Enter book title" required>
        </div>

        <div class="form-group">
            <label for="author">Author:</label> <!-- Input for the author's name -->
            <input type="text" id="author" name="author" placeholder="Enter author name" required>
        </div>

        <div class="form-group">
            <label for="publisher">Publisher:</label> <!-- Input for the publisher's name -->
            <input type="text" id="publisher" name="publisher" placeholder="Enter publisher name" required>
        </div>

        <div class="form-group">
            <label for="language">Language:</label> <!-- Input for the book's language -->
            <input type="text" id="language" name="language" placeholder="Enter book language" required>
        </div>

        <div class="form-group">
            <label for="category">Category:</label> <!-- Dropdown for the book category -->
            <select id="category" name="category" required>
                <option value="Fiction">Fiction</option>
                <option value="Nonfiction">Nonfiction</option>
                <option value="Reference">Reference</option>
            </select>
        </div>

        <button type="submit" class="submit-btn">Save Changes</button> <!-- Submit button -->
    </form>
</div>

<?php
// Include the footer file for consistent layout and design
include 'footer.php'; 
?>
