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
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .banner {
            background-color: white;
            text-align: center;
            padding: 20px;
        }

        .banner img {
            max-width: 100%;
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
            margin-top: 10px;
            margin-bottom: 300px;
            text-align: center;
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
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .label {
            font-weight: bold;
            flex: 1;
            white-space: nowrap; /* Prevent label from wrapping */
        }

        .value {
            margin-left: 10px;
            flex: 3;
            text-align: left;
            white-space: nowrap; /* Prevent value from wrapping */
        }
    </style>

</head>
<body>
    <div class="banner">
        <img src="andreabanner2.png" alt="Pharmacy Banner" class="banner-image">
        <div class="view-cart">
            <a href="index.html">Go back to Products</a>
        </div>
    </div>
    <div class="main-container">
        <div class="profile-container">
            <h1>User Profile</h1>
            <div class="profile-info">
                <div class="detail">
                    <span class="label">Name:</span>
                    <span class="value"><?php echo $user_name; ?></span>
                </div>
                <div class="detail">
                    <span class="label">Last Name:</span>
                    <span class="value"><?php echo $user_last_name; ?></span>
                </div>
                <div class="detail">
                    <span class="label">Email:</span>
                    <span class="value"><?php echo $user_email; ?></span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
