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

    // Check if the product already exists in the cart for the user
    $check_sql = "SELECT * FROM cart WHERE user_id = '$user_id' AND cname = '$pname'";
    $check_result = mysqli_query($conn, $check_sql);
    if (mysqli_num_rows($check_result) > 0) {
        // Product already exists in the cart, update the quantity
        $existing_row = mysqli_fetch_assoc($check_result);
        $quantity = $existing_row['cquantity'] + 1;
        $update_sql = "UPDATE cart SET cquantity = '$quantity' WHERE user_id = '$user_id' AND cname = '$pname'";
        mysqli_query($conn, $update_sql);
    } else {
        // Product does not exist in the cart, insert a new row
        $quantity = 1; // Default quantity is 1
        $insert_sql = "INSERT INTO cart (user_id, cname, cprice, cquantity) VALUES ('$user_id', '$pname', '$price', '$quantity')";
        mysqli_query($conn, $insert_sql);
    }
}

// Handle form submission to remove items from the cart
if (isset($_POST["remove_item"])) {
    $cart_item_id = $_POST["cart_item_id"];
    
    // Check the quantity of the item
    $quantity_sql = "SELECT cquantity FROM cart WHERE id = '$cart_item_id'";
    $quantity_result = mysqli_query($conn, $quantity_sql);
    $row = mysqli_fetch_assoc($quantity_result);
    $quantity = $row['cquantity'];
    
    // If quantity is greater than 1, decrement it
    if ($quantity > 1) {
        $update_sql = "UPDATE cart SET cquantity = cquantity - 1 WHERE id = '$cart_item_id'";
        mysqli_query($conn, $update_sql);
    } else {
        // If quantity is 1, delete the item
        $sql_delete = "DELETE FROM cart WHERE id = '$cart_item_id'";
        if (!mysqli_query($conn, $sql_delete)) {
            echo "Error deleting record: " . mysqli_error($conn);
        }
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
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
<div class="banner">
    <img src="andreabanner2.png" alt="Pharmacy Banner" class="banner-image">
    <div class="navlinks">
            <a href="index.html" class="back-to-products-link">Go back to Products</a>
        </div>
</div>

<div class="container">
<h2>🛒 Shopping Cart</h2>
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
                        <p>Price: €<?php echo $row["cprice"]; ?></p>
                        <!-- Display product quantity -->
                        <p>Quantity: <?php echo $row["cquantity"]; ?></p>
                        <!-- Form to remove item from cart -->
                        <form method="post" action="">
                            <input type="hidden" name="cart_item_id" value="<?php echo $row["id"]; ?>">
                            <button type="submit" name="remove_item">Remove</button>
                        </form>
                    </div>
                </div>
                <?php
                // Calculate total price
                $total_price += $row["cprice"] * $row["cquantity"];
            }
        } else {
            echo "<p>Your cart is empty</p>";
        }
        ?>
    </div>
    <div class="cart-details-container">
    <div class="cart-details">
        <h3>Cart Details:</h3>
        <p>Total Price: €<?php echo $total_price; ?></p>
    </div>
</div>
</div>
</body>
</html>
