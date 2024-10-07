<?php
session_start();
include_once("db_connections.php"); // Include your database connection

$error = '';

// Check login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        if ($username == 'root' && $password == 'toor') {
            $_SESSION['user_id'] = 'root';
            header("Location: employees.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    }
}

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
        // Add Employee
        if ($_POST['action'] == 'add') {
            if (isset($_POST['name']) && isset($_POST['position']) && isset($_POST['email']) && isset($_POST['phone'])) {
                $name = mysqli_real_escape_string($conn, $_POST['name']);
                $position = mysqli_real_escape_string($conn, $_POST['position']);
                $email = mysqli_real_escape_string($conn, $_POST['email']);
                $phone = mysqli_real_escape_string($conn, $_POST['phone']);
                
                $sql = "INSERT INTO employees (name, position, email, phone) VALUES ('$name', '$position', '$email', '$phone')";
                mysqli_query($conn, $sql);
            }
        }
        // Delete Employee
        if ($_POST['action'] == 'delete' && isset($_POST['employee_id'])) {
            $employee_id = mysqli_real_escape_string($conn, $_POST['employee_id']);
            $sql = "DELETE FROM employees WHERE employee_id = '$employee_id'";
            mysqli_query($conn, $sql);
        }
    }
    
    // Query to retrieve employee information
    $sql = "SELECT * FROM employees";
    $result = mysqli_query($conn, $sql);

    // Display employee information and actions in HTML
    echo "<!DOCTYPE html>
    <html lang=\"en\">
    <head>
        <meta charset=\"UTF-8\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
        <title>Employee Management</title>
        <link rel=\"stylesheet\" href=\"styles.css\">
        <style>
            body {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
                margin: 0;
                font-family: Arial, sans-serif;
            }
            .main-content {
                flex: 1;
            }
            footer {
                background: #f1f1f1;
                padding: 10px;
                text-align: center;
                position: relative;
                width: 100%;
            }
        </style>
    </head>
    <body>
        <header>
            <div class=\"logo\">
                <img src=\"logo.png\" alt=\"Company Logo\">
            </div>
            <nav>
                <ul>
                    <li><a href=\"index.html\">Home</a></li>
                    <li><a href=\"employees.php\">Employees</a></li>
                    <li><a href=\"orders.php\">Orders</a></li>
                    <li><a href=\"logout.php\" class=\"btn btn-secondary\">Logout</a></li>
                </ul>
            </nav>
        </header>
        <main class=\"main-content\">
            <h1>Employee Management</h1>";

    if (mysqli_num_rows($result) > 0) {
        echo "<table><tr><th>Employee ID</th><th>Name</th><th>Position</th><th>Email</th><th>Phone</th><th>Actions</th></tr>";
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                <td>{$row['employee_id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['position']}</td>
                <td>{$row['email']}</td>
                <td>{$row['phone']}</td>
                <td>
                    <form method='POST' action='employees.php' style='display:inline;'>
                        <input type='hidden' name='employee_id' value='{$row['employee_id']}'>
                        <input type='hidden' name='action' value='delete'>
                        <input type='submit' value='Delete' class='btn btn-danger'>
                    </form>
                </td>
            </tr>";
        }
        echo "</table>";
    } else {
        echo "No employees found.";
    }

    echo "<h2>Add New Employee</h2>
    <form method='POST' action='employees.php'>
        <label for='name'>Name:</label>
        <input type='text' id='name' name='name' required>
        <br>
        <label for='position'>Position:</label>
        <input type='text' id='position' name='position' required>
        <br>
        <label for='email'>Email:</label>
        <input type='email' id='email' name='email' required>
        <br>
        <label for='phone'>Phone:</label>
        <input type='text' id='phone' name='phone' required>
        <br>
        <input type='hidden' name='action' value='add'>
        <input type='submit' value='Add Employee' class='btn'>
    </form>
    </main>
    <footer>
        <p>&copy; 2024 Your Company. All rights reserved.</p>
    </footer>
    </body>
    </html>";
} else {
    // Display login form
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Employee Management</title>
        <link rel="stylesheet" href="styles.css">
        <style>
            body {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
                margin: 0;
                font-family: Arial, sans-serif;
            }
            .main-content {
                flex: 1;
            }
            footer {
                background: #f1f1f1;
                padding: 10px;
                text-align: center;
                position: relative;
                width: 100%;
            }
        </style>
    </head>
    <body>
        <header>
            <div class="logo">
                <img src="logo.png" alt="Company Logo">
            </div>
            <nav>
                <ul>
                    <li><a href="index.html">Home</a></li>
                </ul>
            </nav>
        </header>
        <main class="main-content">
            <div class="form-container">
                <div class="form-header">
                    <button id="login-tab" class="form-tab active">Login</button>
                </div>';
                
    if (!empty($error)) {
        echo "<p style='color:red;'>$error</p>";
    }

    echo '
                <div id="login-form" class="form-content active">
                    <form method="POST" action="employees.php">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" required>
                        <br>
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                        <br>
                        <input type="submit" value="Login" class="btn">
                    </form>
                </div>
            </div>
        </main>
        <footer>
            <p>&copy; 2024 Your Company. All rights reserved.</p>
        </footer>
    </body>
    </html>';
}
mysqli_close($conn);
?>
