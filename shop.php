<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Shop</title>
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
        .shop-buttons {
            text-align: center;
            margin-bottom: 30px;
        }
        .shop-buttons input {
            margin: 10px;
            width: 120px;
            background-color: #5c6bc0; /* Blue color */
            color: white; /* White text color */
            padding: 10px 0; /* Adjusted padding */
            font-size: 14px;
            font-weight: 500;
            border: none; /* Removed border */
            border-radius: 3px;
            cursor: pointer;
        }
        .shop-buttons input:hover { /* Hover effect */
            background-color: #3949ab; /* Darker blue color */
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
        input[type="text"], input[type="number"], select {
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
        input[type="submit"], .back-button {
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
        .back-button {
            margin-top: 10px;
            background-color: #5c6bc0; /* Blue color */
            color: white; /* White text color */
            padding: 10px 0; /* Adjusted padding */
            font-size: 14px;
            font-weight: 500;
            border: none; /* Removed border */
            border-radius: 3px;
            cursor: pointer;
        }
        .back-button:hover { /* Hover effect */
            background-color: #3949ab; /* Darker blue color */
        }
        ::placeholder {
            color: #81bddf;
        }
        select, input[type="submit"], .back-button {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Pet Shop</h1>
    <div class="shop-buttons">
        <input type="button" value="Credit" onclick="window.location.href='credit.php'">
        <input type="button" value="Treats" onclick="window.location.href='treats.php'">
        <input type="button" value="Food" onclick="window.location.href='food.php'">
        <input type="button" value="Brushes" onclick="window.location.href='brush.php'">
    </div>
    <input type="button" class="back-button" value="Back" onclick="window.location.href='index1.html'">
    
   
    </body>
 </html>     