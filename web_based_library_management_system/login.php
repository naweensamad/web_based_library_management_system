<?php
// Start session at the top of the page to track user login state
session_start();

// Include the header to maintain consistent page layout
include 'header.php'; 

// Include database connection file to establish a connection with the database
include 'database.php'; 

// Check if the form was submitted using the POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form input data from the user
    $email = $_POST['email'];  // Retrieve the email address entered in the form
    $password = $_POST['password'];  // Retrieve the password entered in the form
    
    // Hash the password using MD5 (not recommended for production due to weak security)
    $passwordHash = md5($password);
    
    // Use prepared statements to securely check if the user exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password_md5Hash = ?");
    $stmt->bind_param("ss", $email, $passwordHash); // Bind the email and hashed password as parameters
    $stmt->execute();
    $result = $stmt->get_result(); // Get the result of the executed query
    
    // Check if exactly one matching user record was found
    if ($result->num_rows == 1) {
        // Fetch user data from the database
        $row = $result->fetch_assoc();
        // Store user details in session variables for later use
        $_SESSION['user_id'] = $row['member_id'];  // Store user ID
        $_SESSION['first_name'] = $row['first_name'];  // Store user first name
        $_SESSION['member_type'] = $row['member_type'];  // Store user type (e.g., Member or Admin)
        $_SESSION['login_time'] = time();  // Record the login time for session management
        
        // Redirect the user to the 'browse and borrow books' page after successful login
        header("Location: browse&borrowbooks.php");
        exit(); // Exit script execution after redirection
    } else {
        // Display an error message for invalid login credentials
        echo "Invalid login credentials.";
    }
    
    // Close the prepared statement and database connection to free resources
    $stmt->close();
    $conn->close();
}
?>

<!-- Welcome Text Section -->
<div class="welcome-text">
    <h1>Login to Your Account</h1> <!-- Display login heading -->
</div>

<!-- Login Form Section -->
<div class="container main-section">
    <div class="form-container">
        <form id="loginForm" action="login.php" method="post" class="login-form">
            <!-- Email Input Field -->
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required 
                       pattern=".+@.+\.(com|net|org)" 
                       title="Email must end with .com, .net, or .org">
            </div>
            
            <!-- Password Input Field -->
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <!-- Submit Button -->
            <button type="submit" class="submit-btn">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign up here</a>.</p> <!-- Link to the signup page -->
    </div>
</div>

<?php
// Include the footer to maintain consistent page layout
include 'footer.php'; 
?>
