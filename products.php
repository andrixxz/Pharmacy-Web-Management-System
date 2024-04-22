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



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Display</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Products</h2>
        <div class="products">
            <?php
                // Database connection
                //$con = mysqli_connect("localhost","root","","Product_details");

                // Fetch products from database
                $query = "SELECT * FROM product ORDER BY id ASC";
                $result = mysqli_query($con, $query);
                if(mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
            ?>
            <div class="product">
                <img src="<?php echo $row["image"]; ?>" alt="<?php echo $row["pname"]; ?>">
                <h3><?php echo $row["pname"]; ?></h3>
                <p>Price: $<?php echo $row["price"]; ?></p>
                <form method="post" action="viewcart.php?action=add&id=<?php echo $row["id"]; ?>">
                    <input type="number" name="quantity" value="1" min="1">
                    <input type="submit" name="add" value="Add to Cart">
                    <input type="hidden" name="hidden_name" value="<?php echo $row["pname"]; ?>">
                    <input type="hidden" name="hidden_price" value="<?php echo $row["price"]; ?>">
                </form>
            </div>
            <?php
                    }
                }
            ?>
        </div>
    </div>
</body>
</html>