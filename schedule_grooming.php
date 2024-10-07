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

// Fetch available grooming time slots
$sql = "SELECT * FROM grooming_app WHERE date >= CURDATE()";
$result = $conn->query($sql);

$timeSlots = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $timeSlots[] = $row;
    }
}

// Handle grooming appointment scheduling
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['schedule'])) {
    $timeSlotId = $_POST['time_slot'];
    $contactDetails = $_POST['contact_details'];

    // Insert the appointment into the grooming_app_user table
    $sql = "INSERT INTO grooming_app_user (user_id, grooming_app_id, contact_details) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $userId, $timeSlotId, $contactDetails);

    if ($stmt->execute()) {
        
    } else {
        echo "Error scheduling appointment: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Grooming Appointment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 400px;
            width: 100%;
        }
        .container h1 {
            font-size: 24px;
            font-weight: 600;
            color: #333333;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            font-size: 14px;
            font-weight: 500;
            color: #666666;
            display: block;
            margin-bottom: 5px;
        }
        .form-group input[type="text"],
        .form-group select {
            width: 100%;
            height: 40px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            padding: 0 10px;
            font-size: 14px;
        }
        .form-group input[type="submit"] {
            width: 100%;
            height: 40px;
            background-color: #5c6bc0;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .form-group input[type="submit"]:hover {
            background-color: #3949ab;
        }
        .back-button {
            margin-top: 20px;
            text-align: center;
        }
        .back-button a {
            color: #5c6bc0;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .back-button a:hover {
            color: #3949ab;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Schedule Grooming Appointment</h1>
        <form action="schedule_grooming.php" method="post">
            <div class="form-group">
                <label for="time_slot">Select a Time Slot:</label>
                <select name="time_slot" id="time_slot" required>
                    <option value="">Select a Time Slot</option>
                    <?php foreach ($timeSlots as $slot): ?>
                        <option value="<?php echo $slot['id']; ?>"><?php echo $slot['date'] . ' ' . $slot['time_slot_id']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="contact_details">Contact Details:</label>
                <input type="text" name="contact_details" id="contact_details" required>
            </div>
            <div class="form-group">
                <input type="submit" name="schedule" value="Schedule">
            </div>
        </form>
        <div class="back-button">
            <a href="index1.html">Back</a>
        </div>
    </div>
</body>
</html>
