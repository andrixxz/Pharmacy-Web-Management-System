<?php
// Start the session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if user is not logged in
    header("Location: login.php");
    exit;
}

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$databasename = "pharmacy";

$conn = mysqli_connect($servername, $username, $password, $databasename);

if (mysqli_connect_errno()) {
    die("Connection error " . mysqli_connect_error());
}
// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Assuming you have columns named 'firstName', 'lastName', and 'email' in your users table
    $row = $result->fetch_assoc();
    $user_name = $row["firstName"];
    $user_last_name = $row["lastName"];
    $user_email = $row["email"];
} else {
    echo "User not found";
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        /* Add CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #e2ece6;
            margin: 0;
            padding: 0;
        }

        .banner {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000; /* Adjust the z-index as needed to ensure the banner appears above other content */
            text-align: center;
            background-color: white; /* Add this line to set the background color */
        }

        .banner img {
            width: 100%;
            max-width: 1000px; /* Adjust the maximum width as needed */
            height: auto;
        }

        .main-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .profile-container {
            max-width: 800px;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 200px;
            margin-bottom: 100px;
            text-align: center;
            border: 7px solid #506b55;
        }

        .profile-container h1 {
            color: #333333;
            text-align: center;
            margin-bottom: 50px;
        }

        .profile-info {
            margin-top: 10px;
        }

        .detail {
            margin-bottom: 20px;
            padding: 10px; /* Add padding for the boxes */
            border: 1px solid #ccc; /* Add border for the boxes */
            border-radius: 5px; /* Add border radius for rounded corners */
        }

        .label {
            font-weight: bold;
            white-space: nowrap; /* Prevent label from wrapping */
        }

        .value {
            margin-left: 10px;
            white-space: nowrap; /* Prevent value from wrapping */
        }

        .edit-button {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            background-color: #6aa06f;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .edit-button:hover {
            background-color: #9ec0a1;
        }
        .back-to-products-link {
            display: inline-block;
            padding: 5px 10px;
            background-color: #6aa06f;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            margin-bottom: 10px;
        }

        .back-to-products-link:hover {
            background-color: #9ec0a1;
        }
    </style>

</head>
<body>
    <div class="banner">
        <img src="andreabanner2.png" alt="Pharmacy Banner">
        <div class="view-cart">
            <a href="index.html" class="back-to-products-link">Go back to Products</a>
        </div>
    </div>
    <div class="main-container">
        <div class="profile-container">
            <h1>User Profile</h1>
            <div class="profile-info">
                <span class="label">Name:</span>
                <br>
                <br>
                <div class="detail">
                    <span class="value"><?php echo $user_name; ?></span>
                </div>
                <span class="label">Last Name:</span>
                <br>
                <br>
                <div class="detail">
                    <span class="value"><?php echo $user_last_name; ?></span>
                </div>
                    <span class="label">Email:</span>
                    <br>
                    <br>
                <div class="detail">
                    <span class="value"><?php echo $user_email; ?></span>
                </div>
            </div>
            <a href="editprofile.php" class="edit-button">Edit Details</a>
        </div>
    </div>
</body>
</html>

