<?php
session_start();
include_once("db_connections.php"); // Include your database connection

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        $sql = "SELECT user_id, password FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['user_id'];
                header("Location: customers.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Invalid username.";
        }
    } elseif (isset($_POST['register'])) {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
        if (mysqli_query($conn, $sql)) {
            $success = "Registration successful. You can now log in.";
        } else {
            $error = "Registration failed. Username might already be taken.";
        }
    }
}

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT products.product_id, products.name, products.description, products.price, purchases.quantity, purchases.purchase_date 
            FROM purchases 
            JOIN products ON purchases.product_id = products.product_id 
            WHERE purchases.user_id = '$user_id'";
    $result = mysqli_query($conn, $sql);

    echo "<!DOCTYPE html>
    <html lang=\"en\">
    <head>
        <meta charset=\"UTF-8\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
        <title>Milk Products Shop Management System</title>
        <link rel=\"stylesheet\" href=\"styles.css\">
    </head>
    <body>
        <header>
            <div class=\"logo\">
                <img src=\"logo.png\" alt=\"Milk Products Shop Logo\">
            </div>
            <nav>
                <ul>
                    <li><a href=\"index.html\">Home</a></li>
                    <li><a href=\"products.php\">Products</a></li>
                    <li><a href=\"sales.php\">Sales</a></li>
                    <li><a href=\"customers.php\">Customers</a></li>
                    <li><a href=\"suppliers.php\">Suppliers</a></li>
                    <li><a href=\"employees.php\">Employees</a></li>
                    <li><a href=\"inventory.php\">Inventory</a></li>
                    <li><a href=\"orders.php\">Orders</a></li>
                    <li><a href=\"contact.html\">Contact Us</a></li>
                </ul>
            </nav>
        </header>
        <main class=\"main-content\">
            <h1>Your Purchased Products</h1>";
    if (mysqli_num_rows($result) > 0) {
        echo "<table><tr><th>ProductID</th><th>Name</th><th>Description</th><th>Price</th><th>Quantity</th><th>Purchase Date</th></tr>";
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>" . $row["product_id"]. "</td><td>" . $row["name"]. "</td><td>" . $row["description"]. "</td><td>" . $row["price"]. "</td><td>" . $row["quantity"]. "</td><td>" . $row["purchase_date"]. "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "You have not purchased any products.";
    }

    echo '<a href="logout.php" class="btn btn-secondary">Logout</a>
    </main>
    <footer>
        <p>&copy; 2024 Milk Products Shop. All rights reserved.</p>
    </footer>
    </body>
    </html>';
} else {
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Milk Products Shop Management System</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <header>
            <div class="logo">
                <img src="logo.png" alt="Milk Products Shop Logo">
            </div>
            <nav>
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="sales.php">Sales</a></li>
                    <li><a href="customers.php">Customers</a></li>
                    <li><a href="suppliers.php">Suppliers</a></li>
                    <li><a href="employees.php">Employees</a></li>
                    <li><a href="inventory.php">Inventory</a></li>
                    <li><a href="orders.php">Orders</a></li>
                    <li><a href="contact.html">Contact Us</a></li>
                </ul>
            </nav>
        </header>
        <main class="main-content">
            <div class="form-container">
                <div class="form-header">
                    <button id="login-tab" class="form-tab active">Login</button>
                    <button id="signup-tab" class="form-tab">Signup</button>
                </div>';
                
    if (!empty($error)) {
        echo "<p style='color:red;'>$error</p>";
    }
    if (!empty($success)) {
        echo "<p style='color:green;'>$success</p>";
    }

    echo '
    <div id="login-form" class="form-content active">
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <input type="submit" name="login" value="Login" class="btn">
    </form>
    <p>Not a member? <a href="#" id="switch-to-signup" class="form-link">Signup now</a></p>
</div>
                <div id="signup-form" class="form-content">
                <form method="POST">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                    <br>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <br>
                    <input type="submit" name="register" value="Signup" class="btn">
                </form>
                <p>Already a member? <a href="#" id="switch-to-login" class="form-link">Login now</a></p>
            </div>
            
                        </div>
                        </main>
                        <footer>
                        <p>&copy; 2024 Milk Products Shop. All rights reserved.</p>
                        </footer>
                        <script>
                        document.getElementById("login-tab").addEventListener("click", function() {
                            document.getElementById("login-form").classList.add("active");
                            document.getElementById("signup-form").classList.remove("active");
                            document.getElementById("login-tab").classList.add("active");
                            document.getElementById("signup-tab").classList.remove("active");
                        });
                        
                        document.getElementById("signup-tab").addEventListener("click", function() {
                            document.getElementById("signup-form").classList.add("active");
                            document.getElementById("login-form").classList.remove("active");
                            document.getElementById("signup-tab").classList.add("active");
                            document.getElementById("login-tab").classList.remove("active");
                        });
                        
                        document.getElementById("switch-to-signup").addEventListener("click", function(e) {
                            e.preventDefault();
                            document.getElementById("signup-tab").click();
                        });
                        
                        document.getElementById("switch-to-login").addEventListener("click", function(e) {
                            e.preventDefault();
                            document.getElementById("login-tab").click();
                        });
                        </script>
                        </body>
                        </html>';
                        }
                        mysqli_close($conn);
                        ?>
                        