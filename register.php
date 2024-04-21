<?php
session_start();

// connect to the database
$servername="localhost";
$username="root";
$password="";
$databasename="pharmacy";

$conn = mysqli_connect($servername, $username, $password, $databasename);

if (mysqli_connect_errno()) {

    die("Connection error " . mysqli_connect_error());

}
// initializing variables
$firstName = "";
$lastName    = "";
$email    = "";
$password    = "";
$password2    = "";
$errors = array(); 

// // initilising variables


if (isset($_POST['register']))
{
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $errors = array(); 

    if (empty($firstName)) { array_push($errors, "First Name is required"); }
    if (empty($lastName)) { array_push($errors, "Last Name is required"); }
    if (empty($email)) { array_push($errors, "Email is required"); }
    if (empty($password)) { array_push($errors, "Password is required"); }
    if ($password != $password2)
    {
        array_push($errors, "The two passwords do not match");
    }
    // first check the database to make sure 
    // a user does not already exist with the same email
    $email_check_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $email_check_query);
    $user = mysqli_fetch_assoc($result);
    
    if ($email_check_query) { // if user exists
        
        if ($user && $user['email'] === $email) {
        array_push($errors, "email already exists");
        }
    }

    // Finally, insert data if there are no errors in the form
    if (count($errors) == 0) 
    {
        $sql_query = "INSERT INTO users (firstName, lastName, email, password, password2)
                      VALUES ('$firstName', '$lastName', '$email', '$password','$password2')";
        mysqli_query($conn, $sql_query);
        $_SESSION['success'] = "Data inserted successfully";
        header('location: index.php');
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="stylesheet.css"> 
</head>
<body>
    <div class="banner">
        <img src="andreabanner2.png" alt="Pharmacy Banner">
    </div>

    <div class="register-container">
        <h2>Register</h2>
        <form method="post" action="register.php" >
            <?php include('errors.php'); ?>
            <div class="form-group">
                <label for="firstname">First Name:</label>
                <input type="text" id="firstName" name="firstName" value="<?php echo $firstName; ?>"required>
            </div>
            <div class="form-group">
                <label for="lastname">Last Name:</label>
                <input type="text" id="lastName" name="lastName" value="<?php echo $lastName; ?>"required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="password2" name="password2" required>
            </div>
            <button name="register" type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
