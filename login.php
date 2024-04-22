<?php
// start the session
session_start();

// connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$databasename = "pharmacy";

$conn = mysqli_connect($servername, $username, $password, $databasename);

// check if the connection was successful
if (mysqli_connect_errno()) {
    die("Connection error " . mysqli_connect_error());
}

// initializing variables
$email    = "";
$password = "";
$errors = array();

// processing the login form submission
if (isset($_POST['login'])) {
    // gets email and password from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // validating form fields
    if (empty($email)) {
        array_push($errors, "Email is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    // if no errors, we attempt to log in
    if (count($errors) == 0) {
        // selecting from database table
        $sql_query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
        $results = mysqli_query($conn, $sql_query);
        if (mysqli_num_rows($results) == 1) {
            // fetches user ID from the database
            $user_row = mysqli_fetch_assoc($results);
            $user_id = $user_row['id'];

            // store the user email and ID in the session
            $_SESSION['email'] = $email;
            $_SESSION['user_id'] = $user_id;

            $_SESSION['success'] = "You are now logged in";
            header('location: index.html');
        } else {
            array_push($errors, "Wrong email/password combination");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
<div class="banner">
    <img src="andreabanner2.png" alt="Pharmacy Banner">
</div>
<div class="login-container">
    <h2>Login</h2>
    <form action="login.php" method="post">
        <!-- Include error messages -->
        <?php include('errors.php'); ?>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button name="login" type="submit">Login</button>
    </form>
    <!-- Link to register page -->
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</div>
</body>
</html>
