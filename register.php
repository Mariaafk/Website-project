<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password

    // Check if username or email already exists
    $checkUserQuery = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($checkUserQuery);
    $stmt->bind_param('ss', $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // User already exists
        echo "Username or Email already taken.";
    } else {
        // Insert new user into database
        $insertQuery = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param('sss', $username, $email, $password);
        if ($stmt->execute()) {
            // Registration successful, redirect to login page
            header("Location: login.html");
            exit(); // Stop further execution
        } else {
            // Registration failed
            echo "Error: " . $stmt->error;
        }
    }
}
?>
