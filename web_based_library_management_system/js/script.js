// Function to prevent numbers from being entered into name input fields
function preventNumbersInNameField(inputField) {
    // Check if the input field exists
    if (inputField) {
        // Add an 'input' event listener to the input field
        inputField.addEventListener('input', function () {
            // Check if the current value contains any numbers
            if (/[0-9]/.test(this.value)) {
                // Display an alert to inform the user
                alert('Only alphabets are allowed in the ' + this.name);
                // Remove all numeric characters from the input value
                this.value = this.value.replace(/[0-9]/g, ''); 
            }
        });
    }
}

// Function to handle form submission for both sign-up and login forms
function handleFormSubmission(form, isSignUp = false) {
    // Check if the form element exists
    if (form) {
        // Add a 'submit' event listener to the form
        form.addEventListener('submit', function (event) {
            // If the form is a sign-up form, validate password and confirm password
            if (isSignUp) {
                const password = document.getElementById('password').value; // Get the password value
                const confirmPassword = document.getElementById('confirmPassword').value; // Get the confirm password value
                if (password !== confirmPassword) {
                    // Prevent form submission if passwords do not match
                    event.preventDefault();
                    alert('Passwords do not match. Please try again.');
                }
            }
            // Log the form submission type to the console
            console.log('Form submitted: ' + (isSignUp ? 'Sign-Up' : 'Login'));
        });
    }
}

// Get the sign-up form element by its ID
const signUpForm = document.getElementById('signUpForm');
// Get the login form element by its ID
const loginForm = document.getElementById('loginForm');

// If the sign-up form exists, add specific validations
if (signUpForm) {
    const firstNameInput = document.getElementById('firstName'); // Get the first name input field
    const lastNameInput = document.getElementById('lastName'); // Get the last name input field
    
    // Prevent numbers from being entered in the first and last name fields
    preventNumbersInNameField(firstNameInput);
    preventNumbersInNameField(lastNameInput);
    
    // Handle the form submission for the sign-up form
    handleFormSubmission(signUpForm, true);
}

// Handle the form submission for the login form (if it exists)
handleFormSubmission(loginForm);
