<?php
session_start();
include "config.php";

$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Cart</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>
<body class="premium-body">

<div class="premium-cart-wrapper">

<h1 class="premium-cart-title">🛒 My Cart</h1>

<?php
if(isset($_SESSION['cart']) && is_array($_SESSION['cart']) && count($_SESSION['cart']) > 0){

    echo "<div class='premium-cart-grid'>";

    foreach($_SESSION['cart'] as $item){

        // New structure expected
        if(is_array($item)){
            $id = intval($item['id']);
            $size = $item['size'];
            $quantity = $item['quantity'];
        } else {
            // Old fallback
            $id = intval($item);
            $size = 'M';
            $quantity = 1;
        }

        $result = mysqli_query($conn,"SELECT * FROM products WHERE id=$id");

        if($result && mysqli_num_rows($result) > 0){

            $row = mysqli_fetch_assoc($result);

            echo "<div class='premium-cart-card'>";

                echo "<div class='cart-img'>";
                echo "<img src='images/".$row['image']."' alt=''>";
                echo "</div>";

                echo "<div class='cart-info'>";
                echo "<h3>".$row['name']."</h3>";
                echo "<p><strong>Size:</strong> ".$size."</p>";
                echo "<p><strong>Quantity:</strong> ".$quantity."</p>";
                echo "<p class='premium-price'>₹".$row['price']."</p>";
                echo "</div>";

            echo "</div>";

            $total += ($row['price'] * $quantity);
        }
    }

    echo "</div>";

    echo "<div class='premium-summary'>";
    echo "<h2>Total: ₹".$total."</h2>";
    echo "<a href='checkout.php' class='premium-btn'>Proceed To Checkout</a>";
    echo "</div>";

} else {

    echo "
    <div class='premium-empty'>
        <h3>Cart is Empty</h3>
        <p>Add some luxury to your wardrobe.</p>
        <a href='index.php' class='premium-btn'>Start Shopping</a>
    </div>
    ";
}
?>

</div>

</body>
</html>