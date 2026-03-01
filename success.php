<?php
include "config.php";

if(!isset($_GET['order_id'])){
    die("Invalid Order");
}

$order_id = intval($_GET['order_id']);

$result = mysqli_query($conn,"SELECT * FROM orders WHERE id=$order_id");
$order = mysqli_fetch_assoc($result);

if(!$order){
    die("Order Not Found");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Success</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>
<body class="success-body">

<div class="success-wrapper">

    <div class="success-card">

        <div class="success-icon">✔</div>

        <h1>Order Placed Successfully</h1>

        <p class="success-order-id">
            Order ID: <strong>#<?php echo $order['id']; ?></strong>
        </p>

        <p class="success-total">
            Total Paid: ₹<?php echo $order['total']; ?>
        </p>

        <div class="success-buttons">
            <a href="orders.php" class="success-btn">
                📦 View My Orders
            </a>

            <a href="index.php" class="success-btn-outline">
                🏠 Back To Home
            </a>
        </div>

    </div>

</div>

</body>
</html>
