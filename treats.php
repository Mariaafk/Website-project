<?php
session_start();

// Include your database connection script
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "Error: User is not logged in.";
    exit();
}

$userId = $_SESSION['user_id'];

// Handle the treat purchase
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['treats'])) {
    $purchases = $_POST['treats'];

    foreach ($purchases as $purchase) {
        $treatId = $purchase['treat_id'];
        $quantity = $purchase['quantity'];

        if ($quantity > 0) {
            // Fetch the treat price from the database
            $sql = "SELECT price FROM treats WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $treatId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $pricePerTreat = $row['price'];
                $totalCost = $pricePerTreat * $quantity;

                // Fetch the current credit amount
                $sql = "SELECT credit_amount FROM credit WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $currentCredit = $row['credit_amount'];

                    if ($currentCredit >= $totalCost) {
                        // Deduct the total cost from the user's credit
                        $sql = "UPDATE credit SET credit_amount = credit_amount - ? WHERE user_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("di", $totalCost, $userId);
                        if ($stmt->execute()) {
                            // Insert the purchase into the treats_user table
                            $sql = "INSERT INTO treats_user (user_id, treat_id, quantity) VALUES (?, ?, ?)";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("iii", $userId, $treatId, $quantity);
                            if ($stmt->execute()) {
                                // Purchase recorded successfully
                            } else {
                                echo "Error recording purchase: " . $conn->error;
                            }
                        } else {
                            echo "Error updating credit: " . $conn->error;
                        }
                    } else {
                        echo "Error: Insufficient credit.";
                    }
                } else {
                    echo "Error: No credit record found for the user.";
                }
            } else {
                echo "Error: Treat not found.";
            }
        }
    }
}

// Fetch the treats from the database
$sql = "SELECT * FROM treats";
$result = $conn->query($sql);

$treats = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $treats[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treats Shop</title>
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
        .shop-container {
            width: 600px;
            background-color: rgba(120, 158, 248, 0.13);
            margin: 0 auto;
            border-radius: 10px;
            padding: 50px 35px;
            box-shadow: 0 0 40px rgba(8,7,16,0.6);
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #5c6bc0;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #5c6bc0;
            color: white;
        }
        input[type="number"], input[type="submit"], input[type="button"] {
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
        input[type="number"] {
            width: 80px;
        }
        input[type="submit"]:hover, input[type="button"]:hover {
            background-color: #3949ab;
        }
        label {
            display: block;
            margin-top: 30px;
            font-size: 16px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <h1>Treats Shop</h1>
    <div class="shop-container">
        <form action="treats.php" method="post">
            <table>
                <tr>
                    <th>Treat</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
                <?php foreach ($treats as $treat): ?>
                <tr>
                    <td><?php echo htmlspecialchars($treat['name']); ?></td>
                    <td>$<?php echo number_format($treat['price'], 2); ?></td>
                    <td>
                        <input type="number" name="treats[<?php echo $treat['id']; ?>][quantity]" min="0" value="0" required>
                        <input type="hidden" name="treats[<?php echo $treat['id']; ?>][treat_id]" value="<?php echo $treat['id']; ?>">
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <input type="submit" value="Buy">
        </form>
        <!-- Back button -->
        <input type="button" value="Back to Shop" onclick="window.location.href='shop.php'">
    </div>
</body>
</html>
