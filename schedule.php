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

// Handle scheduling
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['time_slot'])) {
    $selectedTime = $_POST['time_slot'];
    $petName = $_POST['pet_name'];
    $contactDetails = $_POST['contact_details'];

    // Check if the selected time slot is available
    $sql = "SELECT * FROM available_times WHERE time_slot = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedTime);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Time slot is available, save the appointment
        $row = $result->fetch_assoc();
        $timeSlotId = $row['id'];

        $sql = "INSERT INTO schedule_usr (user_id, time_slot_id, pet_name, contact_details) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $userId, $timeSlotId, $petName, $contactDetails);

        if ($stmt->execute()) {
            echo "Appointment scheduled successfully.";
        } else {
            echo "Error scheduling appointment: " . $conn->error;
        }
    } else {
        echo "Error: Selected time slot is not available.";
    }
}

// Fetch available time slots
$sql = "SELECT time_slot FROM available_times";
$result = $conn->query($sql);

$availableTimeSlots = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $availableTimeSlots[] = $row['time_slot'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule</title>
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
        .schedule-container {
            width: 400px;
            background-color: rgba(120, 158, 248, 0.13);
            margin: 0 auto;
            border-radius: 10px;
            padding: 50px 35px;
            box-shadow: 0 0 40px rgba(8,7,16,0.6);
            text-align: center;
        }
        .schedule-container select, .schedule-container input[type="text"] {
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
        .schedule-container input[type="submit"] {
            margin-top: 20px;
            width: 100%;
            background-color: #5c6bc0;
            color: white;
            padding: 15px 0;
            font-size: 18px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
        }
        .schedule-container input[type="submit"]:hover {
            background-color: #3949ab;
        }
        .back-button-container {
    display: inline-block;
    background-color: #5c6bc0;
    padding: 10px 20px;
    border-radius: 5px;
    margin-top: 20px; /* Adjust margin-top to move the button lower */
}

.back-button-container input[type="button"] {
    border: none;
    background: none;
    color: white;
    font-size: 16px;
    cursor: pointer;
}

.back-button-container input[type="button"]:hover {
    background-color: #3949ab;
}


    </style>
</head>
<body>
    <h1>Schedule</h1>
    <div class="schedule-container">
        <form action="schedule.php" method="post">
            <label for="time_slot">Select a time slot:</label>
            <select name="time_slot" id="time_slot">
                <?php foreach ($availableTimeSlots as $timeSlot): ?>
                    <option value="<?php echo $timeSlot; ?>"><?php echo $timeSlot; ?></option>
                <?php endforeach; ?>
            </select>
            <label for="pet_name">Pet Name:</label>
            <input type="text" name="pet_name" id="pet_name" required>
            <label for="contact_details">Contact Details:</label>
            <input type="text" name="contact_details" id="contact_details" required>
            <input type="submit" value="Schedule">
        </form>
        <!-- Back button -->
        <div class="back-button-container">
    <input type="button" value="Back" onclick="window.location.href='index1.html'">
</div>

    </div>
</body>
</html>
