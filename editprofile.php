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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve edited details from the form
    $new_user_name = $_POST["new_user_name"];
    $new_user_last_name = $_POST["new_user_last_name"];
    $new_user_email = $_POST["new_user_email"];

    // Update user details in the database
    $update_sql = "UPDATE users SET firstName = '$new_user_name', lastName = '$new_user_last_name', email = '$new_user_email' WHERE id = $user_id";
    if (mysqli_query($conn, $update_sql)) {
        // Redirect to profile page or display success message
        header("Location: profile.php");
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
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
            min-height: 950px;
        }
        .profile-container {
            max-width: 800px;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
            margin-bottom: 150px;
            text-align: center;
        }
        .profile-container h1 {
            margin-bottom: 30px;
        }
        .detail {
            margin-bottom: 30px; /* Increase margin-bottom for more space between lines */
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .label {
            font-weight: bold;
            flex: 1;
            white-space: nowrap; /* Prevent label from wrapping */
            margin-right: 10px; /* Add right margin for spacing */
        }

        .value {
            flex: 3;
            text-align: left;
            white-space: nowrap; /* Prevent value from wrapping */
        }

        input[type="text"],
        input[type="email"] {
            width: 100%; /* Set input width to 100% */
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }
        button {
            width: 70%; /* Set button width to 100% */
            padding: 10px;
            border: none;
            border-radius: 3px;
            background-color: #6aa06f; /* Button background color */
            color: #fff; /* Button text color */
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #9ec0a1; /* Button hover background color */
        }

        .cancel-button {
            display: inline-block;
            width: auto;
            padding: 10px 20px;
            background-color: rgb(188, 216, 189);
            color: #fff;
            text-decoration: none;
            border-radius: 3px;
            margin-top: 10px; /* Adjust margin-top as needed for spacing */
        }

        .cancel-button:hover {
            background-color: #aaa;
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
            <h1>Edit Profile</h1>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="new_user_name">First Name:</label>
                    <br>
                    <br>
                    <input type="text" id="new_user_name" name="new_user_name" value="<?php echo $user_name; ?>">
                    <br> <!-- Add line break -->
                    <br>
                </div>
                <div class="form-group">
                    <label for="new_user_last_name">Last Name:</label>
                    <br>
                    <br>
                    <input type="text" id="new_user_last_name" name="new_user_last_name" value="<?php echo $user_last_name; ?>">
                    <br>
                    <br><!-- Add line break -->
                </div>
                <div class="form-group">
                    <label for="new_user_email">Email:</label>
                    <br>
                    <br>
                    <input type="email" id="new_user_email" name="new_user_email" value="<?php echo $user_email; ?>">
                    <br> <!-- Add line break -->
                    <br>
                </div>
                <br>
                <button type="submit">Update Profile</button>
                <a href="profile.php" class="cancel-button">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>

