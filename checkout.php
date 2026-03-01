<?php
session_start();
include "config.php";

/* LOGIN CHECK */
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

/* CART CHECK */
if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])){
    die("Your Cart is Empty");
}

/* CALCULATE TOTAL */
$total = 0;
$products = [];

foreach($_SESSION['cart'] as $item){

    if(!isset($item['id'], $item['quantity'], $item['size'])){
        continue;
    }

    $id = intval($item['id']);
    $quantity = intval($item['quantity']);

    $result = mysqli_query($conn,"SELECT * FROM products WHERE id=$id");

    if($result && mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);

        // Attach size & quantity to product
        $row['size'] = $item['size'];
        $row['quantity'] = $quantity;

        $products[] = $row;

        $total += ($row['price'] * $quantity);
    }
}

$_SESSION['total_amount'] = $total;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout | Manav Fashion</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>
<body>

<div class="checkout-container">
<div class="checkout-wrapper">

    <h2>🛒 Checkout Summary</h2>

    <?php foreach($products as $product){ ?>

        <div class="product-row">

            <div class="product-left">
                <img src="images/<?php echo $product['image']; ?>">
                <div class="product-name">
                    <?php echo $product['name']; ?>
                    <p>Size: <?php echo $product['size']; ?></p>
                    <p>Qty: <?php echo $product['quantity']; ?></p>
                </div>
            </div>

            <div class="product-price">
                ₹<?php echo $product['price']; ?> × <?php echo $product['quantity']; ?>
            </div>

        </div>

    <?php } ?>

    <div class="summary">
        <h3>Total: ₹<?php echo $total; ?></h3>

        <a href="payment.php" class="checkout-btn">
            Proceed To Payment →
        </a>
    </div>

</div>
</div>

</body>
</html>