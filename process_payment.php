<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])){
    header("Location: cart.php");
    exit();
}

if(!isset($_SESSION['total_amount'])){
    header("Location: cart.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);
$total   = floatval($_SESSION['total_amount']);
$method  = $_POST['payment_method'] ?? "COD";

/* Insert Order */
$stmt = mysqli_prepare($conn,
"INSERT INTO orders (user_id,total,payment_method,payment_status,status)
 VALUES (?,?,?,?,?)");

$status_payment = "Paid";
$status_order = "Placed";

mysqli_stmt_bind_param($stmt,"idsss",
    $user_id,
    $total,
    $method,
    $status_payment,
    $status_order
);

mysqli_stmt_execute($stmt);
$order_id = mysqli_insert_id($conn);

/* Insert Order Items */
foreach($_SESSION['cart'] as $item){

    if(!isset($item['id'], $item['size'], $item['quantity'])){
        continue;
    }

    $product_id = intval($item['id']);
    $size = $item['size'];
    $quantity = intval($item['quantity']);

    $result = mysqli_query($conn,"SELECT price FROM products WHERE id=$product_id");

    if($result && mysqli_num_rows($result) > 0){

        $row = mysqli_fetch_assoc($result);
        $price = floatval($row['price']);

        $stmt2 = mysqli_prepare($conn,
        "INSERT INTO order_items (order_id, product_id, size, quantity, price)
         VALUES (?,?,?,?,?)");

        mysqli_stmt_bind_param($stmt2,"iisid",
            $order_id,
            $product_id,
            $size,
            $quantity,
            $price
        );

        mysqli_stmt_execute($stmt2);
    }
}

/* Clear cart */
unset($_SESSION['cart']);
unset($_SESSION['total_amount']);

header("Location: success.php?order_id=".$order_id);
exit();
?>