<?php

// Start the session to track user information
session_start();

// Include the header file for consistent page layout
include 'header.php'; 

// Include the database connection file
include 'database.php'; 

// Define the session timeout limit (2 hours in seconds)
$session_timeout = 2 * 60 * 60; // 2 hours

// Check if the user's session has expired
if (isset($_SESSION['login_time'])) {
    $time_since_login = time() - $_SESSION['login_time']; // Calculate time since login
    if ($time_since_login > $session_timeout) {
        // If the session has expired, log out the user and redirect to the login page with a message
        session_unset();
        session_destroy();
        header("Location: login.php?message=session_expired");
        exit();
    }
}

// Initialize a variable to store success or error messages
$message = '';  

// Check if the form has been submitted using the POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize the book ID from the form
    $bookId = mysqli_real_escape_string($conn, $_POST['bookID']);
    
    // Check if the "Return Book" button was clicked
    if (isset($_POST['return'])) {
        // SQL query to update the book status to 'Available' and clear the member ID
        $sql = "UPDATE book_status SET member_id = NULL, status = 'Available', applied_date = NOW() WHERE book_id = '$bookId'";
        if ($conn->query($sql) === TRUE) {
            // Redirect to the same page with a success message
            header("Location: return-or-deletebooks.php?message=return_success");
            exit();
        }
    } 
    // Check if the "Delete Book" button was clicked
    elseif (isset($_POST['delete'])) {
        // SQL query to update the book status to 'Deleted'
        $sql = "UPDATE book_status SET status = 'Deleted' WHERE book_id = '$bookId'";
        if ($conn->query($sql) === TRUE) {
            // Redirect to the same page with a success message
            header("Location: return-or-deletebooks.php?message=delete_success");
            exit();
        }
    }
    
    // Close the database connection
    $conn->close();
}

// Retrieve the message from the URL query string, if present
if (isset($_GET['message'])) {
    if ($_GET['message'] == 'return_success') {
        $message = "Book returned successfully!"; // Success message for returning a book
    } elseif ($_GET['message'] == 'delete_success') {
        $message = "Book deleted successfully!"; // Success message for deleting a book
    }
}
?>

<!-- Main section for returning or deleting books -->
<div class="return-delete-container">
    <h1 class="page-title">Return, Delete Books</h1>
    
    <?php if (!empty($message)): ?>
        <!-- Display success or error message -->
        <div class="alert alert-success" role="alert" style="margin-bottom: 20px;">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <!-- Form for returning or deleting books -->
    <form class="return-delete-form" action="return-or-deletebooks.php" method="post">
        <!-- Dropdown to select a book -->
        <div class="form-group">
            <label for="bookID">Select Book:</label>
            <select id="bookID" name="bookID" required>
                <option value="">Select a book</option>
                <?php
                // SQL query to fetch all books from the database
                $sql = "SELECT book_id, book_title FROM books";
                $result = $conn->query($sql);

                // Populate the dropdown with books from the database
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row['book_id'] . '">' . $row['book_title'] . '</option>';
                    }
                } else {
                    echo '<option value="">No books available</option>'; // Message if no books are found
                }
                ?>
            </select>
        </div>

        <!-- Input field for entering the author name -->
        <div class="form-group">
            <label for="author">Author:</label>
            <input type="text" id="author" name="author" placeholder="Enter author name" required>
        </div>

        <!-- Input field for entering the publisher name -->
        <div class="form-group">
            <label for="publisher">Publisher:</label>
            <input type="text" id="publisher" name="publisher" placeholder="Enter publisher name" required>
        </div>

        <!-- Dropdown to select the category of the book -->
        <div class="form-group">
            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="Fiction">Fiction</option>
                <option value="Nonfiction">Nonfiction</option>
                <option value="Reference">Reference</option>
            </select>
        </div>

        <!-- Buttons to return or delete the selected book -->
        <button type="submit" name="return" class="return-btn">Return Book</button>
        <button type="submit" name="delete" class="delete-btn">Delete Book</button>
    </form>
</div>

<?php
// Include the footer file for consistent page layout
include 'footer.php'; 
?>
