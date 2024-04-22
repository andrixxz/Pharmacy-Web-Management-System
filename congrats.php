<?php
// start the session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// connects to the database
$servername = "localhost";
$username = "root";
$password = "";
$databasename = "pharmacy";

$conn = mysqli_connect($servername, $username, $password, $databasename);

if (mysqli_connect_errno()) {
    die("Connection error " . mysqli_connect_error());
}

// checks if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // redirecting to the login page if user is not logged in
    header("Location: login.php");
    exit;
}

// delete cart items for the logged-in user as it has been purchased
$sql_delete = "DELETE FROM cart WHERE user_id = '$user_id'";
if (!mysqli_query($conn, $sql_delete)) {
    echo "Error deleting cart items: " . mysqli_error($conn);
    exit;
}

// redirecting to page showing a congrats message for succesful purchase
header("Location: congrats_msg.html");
exit;
?>

