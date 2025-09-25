<?php
// Include the header file for the page layout
include 'header.php'; 

// Include the database connection file
include 'database.php'; 

// Check if the form is submitted using the POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form input data
    $firstName = $_POST['firstName']; // Get the first name from the form
    $lastName = $_POST['lastName'];   // Get the last name from the form
    $email = $_POST['email'];         // Get the email address from the form
    $password = $_POST['password'];   // Get the password from the form
    $confirmPassword = $_POST['confirmPassword']; // Get the confirm password from the form
    
    // Check if the password and confirm password match
    if ($password !== $confirmPassword) {
        // Display an error message if passwords do not match
        echo "<p>Passwords do not match.</p>";
    } else {
        // Hash the password using MD5 for security purposes
        $passwordHash = md5($password);
        
        // Prepare an SQL statement to insert the user data into the database
        $stmt = $conn->prepare("INSERT INTO users (member_type, first_name, last_name, email, password_md5Hash) VALUES ('Member', ?, ?, ?, ?)");
        $stmt->bind_param("ssss", $firstName, $lastName, $email, $passwordHash); // Bind the form inputs to the SQL query
        
        // Execute the prepared statement and check for success
        if ($stmt->execute()) {
            // Display a success message if the account is created
            echo "<p>Account created successfully!</p>";
        } else {
            // Display an error message if the query fails
            echo "Error: " . $stmt->error;
        }
        
        // Close the prepared statement
        $stmt->close();
    }
    
    // Close the database connection
    $conn->close();
}
?>

<!-- Display a heading for the page -->
<div class="welcome-text">
    <h1>Create Your Account</h1>
</div>

<!-- Main container for the sign-up form -->
<div class="container main-section">
    <div class="form-container">
        <!-- Sign-up form -->
        <form id="signUpForm" action="signup.php" method="post" class="sign-up-form">
            <!-- First Name input field -->
            <div class="form-group">
                <label for="firstName">First Name:</label>
                <input type="text" id="firstName" name="firstName" required maxlength="20">
            </div>
            
            <!-- Last Name input field -->
            <div class="form-group">
                <label for="lastName">Last Name:</label>
                <input type="text" id="lastName" name="lastName" required maxlength="20">
            </div>
            
            <!-- Email input field with validation for proper email format -->
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required pattern=".+@.+\.(com|net|org)" title="Email must end with .com, .net, or .org">
            </div>
            
            <!-- Password input field -->
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" minlength="8" required>
            </div>
            
            <!-- Confirm Password input field -->
            <div class="form-group">
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>
            </div>
            
            <!-- Submit button to create an account -->
            <button type="submit" class="submit-btn">Create Account</button>
        </form>
        
        <!-- Link to redirect to the login page if the user already has an account -->
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
</div>

<?php
// Include the footer file for the page layout
include 'footer.php'; 
?>
