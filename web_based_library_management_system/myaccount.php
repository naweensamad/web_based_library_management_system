<?php
// Start the session to track user information
session_start();

// Set session timeout limit (2 hours in seconds)
$session_timeout = 2 * 60 * 60; // 2 hours

// Check if the session has expired
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

// Check if the user is logged in, if not, redirect them to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the header file for consistent page layout
include 'header.php'; 

// Include the database connection file
include 'database.php'; 

// Fetch the user's personal details from the database using their session user ID
$user_id = $_SESSION['user_id'];
$sql = "SELECT first_name, last_name, email FROM users WHERE member_id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    // If user data is found, store it in the $user array
    $user = $result->fetch_assoc();
} else {
    // Display an error message if user data cannot be fetched
    echo "Error fetching user data.";
    exit();
}

// Fetch the list of books currently borrowed by the user
$sql_borrowed = "
    SELECT books.book_title, books.author, book_status.applied_date, 
           DATE_ADD(book_status.applied_date, INTERVAL 21 DAY) AS return_due_date, 
           IF(book_status.status = 'Available', 'Yes', 'No') AS returned
    FROM book_status
    JOIN books ON books.book_id = book_status.book_id
    WHERE book_status.member_id = '$user_id' AND book_status.status = 'Onloan'
";
$borrowed_books = $conn->query($sql_borrowed); // Execute the query to get borrowed books
?>

<!-- Page Title -->
<h1 class="account-title">My Account</h1>

<!-- Main Account Section -->
<div class="account-container">

    <!-- Personal Information Section -->
    <div class="card personal-info-card">
        <h2>Personal Information</h2>
        <form class="account-form">
            <!-- Display the user's first name as read-only -->
            <div class="form-group">
                <label for="firstName">First Name:</label>
                <input type="text" id="firstName" name="firstName" value="<?php echo $user['first_name']; ?>" readonly>
            </div>
            <!-- Display the user's last name as read-only -->
            <div class="form-group">
                <label for="lastName">Last Name:</label>
                <input type="text" id="lastName" name="lastName" value="<?php echo $user['last_name']; ?>" readonly>
            </div>
            <!-- Display the user's email as read-only -->
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" readonly>
            </div>
        </form>
    </div>

    <!-- Borrowed Books Section -->
    <div class="card borrowed-books-card">
        <h2>My Borrowed Books</h2>
        <table class="borrowed-books-table">
            <thead>
                <tr>
                    <th>Book Title</th>
                    <th>Author</th>
                    <th>Borrowed Date</th>
                    <th>Return Due Date</th>
                    <th>Returned</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($borrowed_books->num_rows > 0): ?>
                    <!-- Loop through each borrowed book and display its details in a table row -->
                    <?php while ($book = $borrowed_books->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $book['book_title']; ?></td>
                            <td><?php echo $book['author']; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($book['applied_date'])); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($book['return_due_date'])); ?></td>
                            <td><?php echo $book['returned']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <!-- Display a message if no borrowed books are found -->
                    <tr>
                        <td colspan="5">No borrowed books found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
// Include the footer file for consistent page layout
include 'footer.php'; 

// Close the database connection
$conn->close();
?>
