<?php
// Start the session (if not already started)
session_start();

// Include your database connection script
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    echo "Error: User is not logged in.";
    exit();
}

// Fetch breed options from the database
$sql = "SELECT breed FROM eligible_pets";
$result = $conn->query($sql);

if (!$result) {
    die("Error retrieving breeds: " . $conn->error);
}

// Generate breed options dynamically
$breedOptions = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Escape special characters to prevent HTML injection
        $breed = htmlspecialchars($row['breed']);
        $breedOptions .= "<option value='" . $breed . "'>" . $breed . "</option>";
    }
} else {
    $breedOptions = "<option value=''>No breeds found</option>";
}

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $petName = $_POST['pet-name'];
        $petAge = $_POST['pet-age'];
        $petBreed = $_POST['pet-breed'];
        
        // Check if the breed exists in the database
        $sql = "SELECT * FROM eligible_pets WHERE breed = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $petBreed);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Breed exists, insert the pet information into the new table
            $sql = "INSERT INTO user_pets (user_id, pet_name, pet_age, pet_breed) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isis", $userId, $petName, $petAge, $petBreed);

            // Execute the prepared statement
            if ($stmt->execute()) {
                echo "Pet information saved successfully."; // Optional success message
            } else {
                echo "Error: " . $conn->error; // Display error message if execution fails
            }

            // Close the prepared statement
            $stmt->close();
        } else {
            echo "Error: The specified breed does not exist in the database."; // Breed does not exist
        }
    }
} else {
    echo "Error: User is not logged in."; // User ID is not set
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Information</title>
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
            padding-top: 100px; /* Adjusted padding to push content lower */
        }
        h1 {
            font-size: 48px; /* Increased font size */
            font-weight: 500;
            line-height: 1.2;
            text-align: center;
            color: #5c6bc0; /* Changed text color to blue */
            margin-bottom: 30px; /* Added margin to create space below the title */
        }
        form {
            width: 400px;
            background-color: rgba(120, 158, 248, 0.13);
            margin: 0 auto;
            border-radius: 10px;
            padding: 50px 35px;
            box-shadow: 0 0 40px rgba(8,7,16,0.6);
            position: relative; /* Added position relative */
        }
        label {
            display: block;
            margin-top: 30px;
            font-size: 16px;
            font-weight: 500;
        }
        input {
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
        select {
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
        input[type="submit"] {
            margin-top: 20px;
            width: 100%;
            background-color: snow;
            color: #080710;
            padding: 15px 0;
            font-size: 18px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="button"] { /* Adjusted button style */
            margin-top: 10px;
            width: 150px;
            background-color: #5c6bc0; /* Blue color */
            color: white; /* White text color */
            padding: 10px 0; /* Adjusted padding */
            font-size: 14px;
            font-weight: 500;
            border: none; /* Removed border */
            border-radius: 3px;
            cursor: pointer;
            position: absolute; /* Positioned absolutely */
            bottom: -70px; /* Positioned below the form */
            left: calc(50% - 75px); /* Centered horizontally */
        }
        input[type="button"]:hover { /* Hover effect */
            background-color: #3949ab; /* Darker blue color */
        }
        ::placeholder {
            color: #81bddf;
        }
        select, input[type="submit"] {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Enter Pet Information</h1>
    <form id="pet-form" action="pets.php" method="post">
        <label for="pet-name">Name:</label>
        <input type="text" id="pet-name" name="pet-name" required>
        
        <label for="pet-age">Age:</label>
        <input type="number" id="pet-age" name="pet-age" required>
        
        <label for="pet-breed">Breed:</label>
        <select id="pet-breed-select" name="pet-breed" required>
            <option value="">Select Breed</option> <!-- Default value -->
            <?php echo $breedOptions; ?>
        </select>
        
        <input type="submit" value="Save">
        
        <!-- Back button -->
        <input type="button" value="Back" onclick="window.location.href='index1.html'">
    </form>
</body>
</html>
