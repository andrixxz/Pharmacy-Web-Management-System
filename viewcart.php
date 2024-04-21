<?php
// Start the session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
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

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // Redirect to the login page if user is not logged in
    header("Location: login.php");
    exit;
}

// Check if product details are sent from the form (for adding items to the cart)
if (isset($_POST["pname"]) && isset($_POST["price"])) {
    // Get product details from the form
    $pname = $_POST["pname"];
    $price = $_POST["price"];

    // Insert product information along with user ID into the "cart" table
    $sql = "INSERT INTO cart (user_id, cname, cprice) VALUES ('$user_id', '$pname', '$price')";
    if (mysqli_query($conn, $sql)) {
        echo "Product added to cart successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Handle form submission to remove items from the cart
if (isset($_POST["remove_item"])) {
    $cart_item_id = $_POST["cart_item_id"];
    
    // Delete the item from the database
    $sql_delete = "DELETE FROM cart WHERE id = '$cart_item_id'";
    if (!mysqli_query($conn, $sql_delete)) {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}

// Fetch products from the cart table for the logged-in user
$sql = "SELECT * FROM cart WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cart</title>
    <link rel="stylesheet" href="viewcartstyle.css">
</head>
<body>
<div class="banner">
    <img src="andreabanner2.png" alt="Pharmacy Banner" class="banner-image">
    <div class="view-cart">
        <a href="index.html">Go back to Products</a>
    </div>
</div>
<div class="container">
    <h2>Shopping Cart</h2>
    <div class="cart-items">
        <?php
        $total_price = 0;
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                // Output the cart item
                ?>
                <div class="cart-item">
                    <div class="item-details">
                        <!-- Display product name -->
                        <h3><?php echo $row["cname"]; ?></h3>
                        <!-- Display product price -->
                        <p>Price: $<?php echo $row["cprice"]; ?></p>
                        <!-- Form to remove item from cart -->
                        <form method="post" action="">
                            <input type="hidden" name="cart_item_id" value="<?php echo $row["id"]; ?>">
                            <button type="submit" name="remove_item">Remove</button>
                        </form>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>Your cart is empty</p>";
        }
        ?>
    </div>
</div>
</body>
</html>
