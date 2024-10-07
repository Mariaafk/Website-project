<?php
// Start the session (if not already started)
session_start();

// Include your database connection script
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "Error: User is not logged in.";
    exit();
}

$userId = $_SESSION['user_id'];

// Handle the credit addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['credit'])) {
    $creditToAdd = $_POST['credit'];

    // Check if the user already has a credit entry
    $sql = "SELECT * FROM credit WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update the existing credit
        $sql = "UPDATE credit SET credit_amount = credit_amount + ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("di", $creditToAdd, $userId);
    } else {
        // Insert a new credit entry
        $sql = "INSERT INTO credit (user_id, credit_amount) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("id", $userId, $creditToAdd);
    }

    if ($stmt->execute()) {
        echo "Credit updated successfully.";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
}

// Fetch the current credit amount
$creditAmount = 0.00;
$sql = "SELECT credit_amount FROM credit WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $creditAmount = $row['credit_amount'];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Credit</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <style>
        *, *:before, *:after {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        body {
            background-color: hwb(0 98% 2%);
            padding-top: 100px;
        }
        h1 {
            font-size: 48px;
            font-weight: 500;
            line-height: 1.2;
            text-align: center;
            color: #5c6bc0;
            margin-bottom: 30px;
        }
        .credit-container {
            width: 400px;
            background-color: rgba(120, 158, 248, 0.13);
            margin: 0 auto;
            border-radius: 10px;
            padding: 50px 35px;
            box-shadow: 0 0 40px rgba(8,7,16,0.6);
            text-align: center;
        }
        .credit-container input[type="button"], .credit-container input[type="submit"] {
            margin: 10px;
            width: 150px;
            background-color: #5c6bc0;
            color: white;
            padding: 10px 0;
            font-size: 14px;
            font-weight: 500;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .credit-container input[type="button"]:hover, .credit-container input[type="submit"]:hover {
            background-color: #3949ab;
        }
        .credit-container input[type="number"] {
            display: block;
            height: 50px;
            width: 100%;
            background-color: rgba(125, 129, 247, 0.07);
            border-radius: 3px;
            padding: 0 10px;
            margin-top: 8px;
            font-size: 14px;
            font-weight: 300;
        }
        .credit-container label {
            display: block;
            margin-top: 30px;
            font-size: 16px;
            font-weight: 500;
        }
        .credit-amount {
            font-size: 24px;
            font-weight: 500;
            margin-top: 20px;
        }
    </style>
    <script>
        function showCredit() {
            document.getElementById('credit-amount').style.display = 'block';
        }

        function addCredit() {
            document.getElementById('add-credit-form').style.display = 'block';
        }
    </script>
</head>
<body>
    <h1>User Credit</h1>
    <div class="credit-container">
        <input type="button" value="Show Credit" onclick="showCredit()">
        <input type="button" value="Add Credit" onclick="addCredit()">
        <div id="credit-amount" class="credit-amount" style="display:none;">
            Credit: $<?php echo number_format($creditAmount, 2); ?>
        </div>
        <form id="add-credit-form" action="credit.php" method="post" style="display:none;">
            <label for="credit">Add Credit:</label>
            <input type="number" id="credit" name="credit" step="0.01" required>
            <input type="submit" value="Add Credit">
        </form>
        <!-- Back button -->
        <input type="button" value="Back to Shop" onclick="window.location.href='shop.php'">
    </div>
</body>
</html>
