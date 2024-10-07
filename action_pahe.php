<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the form
    $username = $_POST["uname"];
    $password = $_POST["psw"];

    // Validate the credentials (you can replace this with your own logic)
    if ($username === "your_username" && $password === "your_password") {
        // Successful login
        echo "Welcome, $username!";
    } else {
        // Invalid credentials
        echo "Invalid username or password. Please try again.";
    }
}
?>
