<?php
// Start the session to track user login state and activity
session_start();

// Check if the user is logged in, if not, redirect them to the login page with an appropriate message
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?message=login_required");
    exit();
}

// Set session timeout limit (e.g., 2 hours in seconds)
$session_timeout = 2 * 60 * 60; // 2 hours

// Check if the session has expired
if (isset($_SESSION['login_time'])) {
    $time_since_login = time() - $_SESSION['login_time']; // Calculate time since the session started
    if ($time_since_login > $session_timeout) {
        // If the session has expired, log out the user, destroy session data, and redirect to login page
        session_unset();
        session_destroy();
        header("Location: login.php?message=session_expired");
        exit();
    }
}

// Include the header of the webpage (typically contains navigation and branding)
include 'header.php'; 

// Include the database connection file to interact with the database
include 'database.php'; 

// Display success message if a book was successfully borrowed
if (isset($_GET['message']) && $_GET['message'] == 'success') {
    echo '<p class="alert alert-success">Book borrowed successfully!</p>';
}

// Display a warning message if the book is already on loan
if (isset($_GET['message']) && $_GET['message'] == 'already_onloan') {
    echo '<p class="alert alert-warning">This book is already on loan by another user.</p>';
}

// Display a warning message if the user tries to borrow books without logging in
if (isset($_GET['message']) && $_GET['message'] == 'login_required') {
    echo '<p class="alert alert-warning">You must log in to borrow books.</p>';
}

// Query to fetch book details along with their statuses, excluding books marked as "Deleted"
$sql = "
    SELECT books.book_id, books.book_title, books.author, books.publisher, books.language, books.category, books.cover_image, 
           book_status.status 
    FROM books 
    LEFT JOIN book_status ON books.book_id = book_status.book_id
    WHERE book_status.status != 'Deleted'
";

// Execute the query and store the result
$result = $conn->query($sql);
?>

<!-- Books Grid Section -->
<div class="container books-section py-3">
    <div class="row">
        <?php
        // Check if any books were retrieved from the database
        if ($result->num_rows > 0) {
            // Loop through each book and display its details in a grid format
            while ($row = $result->fetch_assoc()) {
                echo '<div class="col-12 col-md-6 col-lg-4 mb-4">';
                echo '<div class="card">';
                echo '<img src="' . $row['cover_image'] . '" alt="Book Cover" class="card-img-top">'; // Display book cover image
                echo '<div class="card-body text-center">';
                echo '<h5 class="card-title">' . $row['book_title'] . '</h5>'; // Display book title
                echo '<p class="card-text">Author: ' . $row['author'] . '</p>'; // Display author name
                echo '<p class="card-text">Publisher: ' . $row['publisher'] . '</p>'; // Display publisher name
                echo '<p class="card-text">Language: ' . $row['language'] . '</p>'; // Display book language
                echo '<p class="card-text">Category: ' . $row['category'] . '</p>'; // Display book category

                // Check if the book is "Onloan" and disable the borrow button if true
                if ($row['status'] == 'Onloan') {
                    echo '<button class="btn btn-secondary" disabled>On Loan</button>'; // Disabled button for books on loan
                } else {
                    // Display a form with a button to borrow the book
                    echo '<form action="borrow.php" method="POST">';
                    echo '<input type="hidden" name="book_id" value="' . $row['book_id'] . '">'; // Include book ID as a hidden input
                    echo '<button type="submit" class="btn btn-dark">Borrow</button>'; // Borrow button
                    echo '</form>';
                }

                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            // Display a message if no books are found in the database
            echo "<p>No books found.</p>";
        }
        // Close the database connection
        $conn->close();
        ?>
    </div>
</div>

<?php
// Include the footer of the webpage (typically contains additional links and copyright information)
include 'footer.php'; 
?>
