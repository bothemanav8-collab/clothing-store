<?php

require_once "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* Fetch Orders */
$stmt = mysqli_prepare($conn,
"SELECT id,total,status,created_at 
 FROM orders 
 WHERE user_id=? 
 ORDER BY id DESC");

mysqli_stmt_bind_param($stmt,"i",$user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
<title>My Orders</title>
<link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">

<style>

.orders-container{
    max-width:900px;
    margin:80px auto;
    padding:40px;
    border-radius:20px;
    background:linear-gradient(135deg,#071f1b,#0f3a33);
    color:white;
}

.orders-title{
    text-align:center;
    margin-bottom:40px;
}

.order-card{
    background:#111;
    padding:25px;
    border-radius:15px;
    margin-bottom:20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    transition:0.3s;
}

.order-card:hover{
    transform:translateY(-5px);
    box-shadow:0 0 20px rgba(0,255,204,0.3);
}

.order-info{
    line-height:1.8;
}

.status-badge{
    padding:6px 14px;
    border-radius:20px;
    font-size:12px;
    font-weight:bold;
    text-transform:uppercase;
}

/* Status Colors */
.Placed{background:#444;}
.Packed{background:#007bff;}
.Shipped{background:#ffaa00;}
.Delivered{background:#00cc66;}
.Cancelled{background:#ff4444;}

.order-actions{
    text-align:right;
}

.order-btn{
    display:inline-block;
    padding:8px 16px;
    background:#00ffcc;
    color:black;
    border-radius:20px;
    text-decoration:none;
    font-weight:bold;
    margin-top:10px;
}

.order-btn-outline{
    display:inline-block;
    padding:8px 16px;
    border:1px solid #ff4444;
    color:#ff4444;
    border-radius:20px;
    text-decoration:none;
    margin-top:10px;
}

.no-orders{
    text-align:center;
    padding:40px;
    opacity:0.6;
}

</style>
</head>

<body class="orders-body">

<?php include "navbar.php"; ?>

<div class="orders-container">

<h1 class="orders-title">✨ My Order History</h1>

<?php if(mysqli_num_rows($result) > 0){ ?>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<div class="order-card">

    <div class="order-info">
        <strong>Order #<?php echo $row['id']; ?></strong><br>
        Total: ₹<?php echo $row['total']; ?><br>
        Date: <?php echo date("d M Y", strtotime($row['created_at'])); ?>
    </div>

    <div class="order-actions">

        <span class="status-badge <?php echo $row['status']; ?>">
            <?php echo $row['status']; ?>
        </span>
        <br>

        <a href="tracking.php?order_id=<?php echo $row['id']; ?>" 
           class="order-btn">
           🚚 Track
        </a>

        <?php if($row['status'] == "Placed"){ ?>
            <a href="cancel.php?order_id=<?php echo $row['id']; ?>" 
               class="order-btn-outline">
               ❌ Cancel
            </a>
        <?php } ?>

    </div>

</div>

<?php } ?>

<?php } else { ?>

<div class="no-orders">
    No Orders Found 🛍️
</div>

<?php } ?>

</div>

</body>
</html>
