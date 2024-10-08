<?php
// starts the session if it hasn't been started already
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
    header("Location: login.php");
    exit;
}

// checks if product details are sent from the form 
if (isset($_POST["pname"]) && isset($_POST["price"])) {
    // gets product details from the form
    $pname = $_POST["pname"];
    $price = $_POST["price"];

    // check if the product already exists in the cart for the user
    $check_sql = "SELECT * FROM cart WHERE user_id = '$user_id' AND cname = '$pname'";
    $check_result = mysqli_query($conn, $check_sql);
    if (mysqli_num_rows($check_result) > 0) {
        // product already exists in the cart so update the quantity
        $existing_row = mysqli_fetch_assoc($check_result);
        $quantity = $existing_row['cquantity'] + 1;
        $update_sql = "UPDATE cart SET cquantity = '$quantity' WHERE user_id = '$user_id' AND cname = '$pname'";
        mysqli_query($conn, $update_sql);
    } else {
        // product does not exist in the cart, insert a new row
        $quantity = 1; // the default quantity is 1
        $insert_sql = "INSERT INTO cart (user_id, cname, cprice, cquantity) VALUES ('$user_id', '$pname', '$price', '$quantity')";
        mysqli_query($conn, $insert_sql);
    }
}

// form submission to remove items from the cart
if (isset($_POST["remove_item"])) {
    // retrieves cart id
    $cart_item_id = $_POST["cart_item_id"];
    
    // checks the quantity of the item
    $quantity_sql = "SELECT cquantity FROM cart WHERE id = '$cart_item_id'";
    $quantity_result = mysqli_query($conn, $quantity_sql);
    $row = mysqli_fetch_assoc($quantity_result);
    $quantity = $row['cquantity'];
    
    // if the quantity is greater than 1, decrement it
    if ($quantity > 1) {
        $update_sql = "UPDATE cart SET cquantity = cquantity - 1 WHERE id = '$cart_item_id'";
        mysqli_query($conn, $update_sql);
    } else {
        // if quantity is 1, delete the product
        $sql_delete = "DELETE FROM cart WHERE id = '$cart_item_id'";
        if (!mysqli_query($conn, $sql_delete)) {
            echo "Error deleting record: " . mysqli_error($conn);
        }
    }
}

// fetches products from the cart table for the logged-in user
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
                // outputing the cart item
                ?>
                <div class="cart-item">
                    <div class="item-details">
                        <!-- displaying the product name -->
                        <h3><?php echo $row["cname"]; ?></h3>
                        <!-- displaying the product price -->
                        <p>Price: €<?php echo $row["cprice"]; ?></p>
                        <!-- displaying the product quantity -->
                        <p>Quantity: <?php echo $row["cquantity"]; ?></p>
                        <!-- used to remove item from cart -->
                        <form method="post" action="">
                            <input type="hidden" name="cart_item_id" value="<?php echo $row["id"]; ?>">
                            <button type="submit" name="remove_item">Remove</button>
                        </form>
                    </div>
                </div>
                <?php
                // calculateing the total price
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
    <form action="congrats.php" method="post">
            <button type="submit" name="pay">Pay</button>
        </form>
</div>
</body>
</html>
