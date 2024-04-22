<?php
// starts the session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// checks if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // if not, redirecting to the login page 
    header("Location: login.php");
    exit;
}

// connecting to the database
$servername = "localhost";
$username = "root";
$password = "";
$databasename = "pharmacy";

$conn = mysqli_connect($servername, $username, $password, $databasename);

// checks if the connection was successful
if (mysqli_connect_errno()) {
    die("Connection error " . mysqli_connect_error());
}
// fetches user details from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);

// if user found
if ($result->num_rows > 0) {
    // fetches first row from result and stores it in array
    $row = $result->fetch_assoc();
    // extracts the value and assigns it to variable
    $user_name = $row["firstName"];
    $user_last_name = $row["lastName"];
    $user_email = $row["email"];
} else {
    echo "User not found";
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // retrieves the edited details from the form
    $new_user_name = $_POST["new_user_name"];
    $new_user_last_name = $_POST["new_user_last_name"];
    $new_user_email = $_POST["new_user_email"];

    // update the user details in the database
    $update_sql = "UPDATE users SET firstName = '$new_user_name', lastName = '$new_user_last_name', email = '$new_user_email' WHERE id = $user_id";
    if (mysqli_query($conn, $update_sql)) {
        // redirect to profile page 
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
    <title>User Edit Profile</title>
    <style>
        //* was having problems with xampp and the stylesheet wasnt working with this page*/
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
            z-index: 1000; /* banner appears above other content */
            text-align: center;
            background-color: white; 
        }

        .banner img {
            width: 100%;
            max-width: 1000px; 
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
            margin-top: 170px;
            margin-bottom: 200px;
            text-align: center;
            border: 7px solid #506b55;
        }
        .profile-container h1 {
            margin-bottom: 30px;
            color: #333333;
        }
        .detail {
            margin-bottom: 30px; 
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .label {
            font-weight: bold;
            flex: 1;
            white-space: nowrap; 
            margin-right: 10px; 
        }
        form label {
            font-weight: bold;
        }

        .value {
            flex: 3;
            text-align: left;
            white-space: nowrap; 
        }

        input[type="text"],
        input[type="email"] {
            width: 100%; 
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
            
        }
        button {
            width: 70%; 
            padding: 10px;
            border: none;
            border-radius: 3px;
            background-color: #6aa06f; 
            color: #fff; 
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #9ec0a1; 
        }

        .cancel-button {
            display: inline-block;
            width: auto;
            padding: 10px 20px;
            background-color: #6aa06f;
            color: #fff;
            text-decoration: none;
            border-radius: 3px;
            margin-top: 10px; 
            font-size: 14px;
            
        }

        .cancel-button:hover {
            background-color: #aaa;
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
        <img src="andreabanner2.png" alt="Pharmacy Banner" class="banner-image">
        <div class="view-cart">
            <a href="index.html" class="back-to-products-link">Go back to Products</a>
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
                    <br> 
                    <br>
                </div>
                <div class="form-group">
                    <label for="new_user_last_name">Last Name:</label>
                    <br>
                    <br>
                    <input type="text" id="new_user_last_name" name="new_user_last_name" value="<?php echo $user_last_name; ?>">
                    <br>
                    <br>
                </div>
                <div class="form-group">
                    <label for="new_user_email">Email:</label>
                    <br>
                    <br>
                    <input type="email" id="new_user_email" name="new_user_email" value="<?php echo $user_email; ?>">
                    <br> 
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

