<?php
session_start();
require_once "config.php";   // 🔥 THIS WAS MISSING

error_reporting(E_ALL);
ini_set('display_errors', 1);

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

if(!isset($_GET['order_id'])){
    die("Order not found.");
}

$user_id  = $_SESSION['user_id'];
$order_id = intval($_GET['order_id']);

/* Fetch Order */
$result = mysqli_query($conn,
"SELECT * FROM orders 
 WHERE id='$order_id' 
 AND user_id='$user_id'");

if(mysqli_num_rows($result) == 0){
    die("Invalid Order.");
}

$order = mysqli_fetch_assoc($result);

/* ===== AUTO STATUS LOGIC ===== */

$created = strtotime($order['created_at']);
$now = time();
$diffHours = ($now - $created) / 3600;

if($diffHours >= 72){
    $newStatus = "Delivered";
}
elseif($diffHours >= 48){
    $newStatus = "Shipped";
}
elseif($diffHours >= 24){
    $newStatus = "Packed";
}
else{
    $newStatus = "Placed";
}

/* Update If Changed */
if($order['status'] != $newStatus){
    mysqli_query($conn,
    "UPDATE orders SET status='$newStatus' WHERE id='$order_id'");
}

$status = $newStatus;

/* Steps */
$steps = ["Placed","Packed","Shipped","Delivered"];
$currentStep = array_search($status, $steps);
if($currentStep === false){
    $currentStep = 0;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Track Order</title>
<style>
body{
    background:#000;
    font-family:Arial;
}

.tracking-container{
    max-width:650px;
    margin:80px auto;
    padding:40px;
    border-radius:20px;
    background:linear-gradient(135deg,#071f1b,#0f3a33);
    color:white;
}

/* Progress */
.progress-bar{
    width:100%;
    height:6px;
    background:#222;
    border-radius:10px;
    margin:40px 0;
    overflow:hidden;
}

.progress-active{
    height:100%;
    width:0%;
    background:linear-gradient(90deg,#00ffcc,#00ffaa);
    border-radius:10px;
    transition:width 1.5s ease-in-out;
    box-shadow:0 0 15px #00ffcc;
}

/* Timeline */
.timeline{
    display:flex;
    justify-content:space-between;
    margin-top:30px;
}

.timeline-step{
    text-align:center;
    flex:1;
}

.circle{
    width:25px;
    height:25px;
    border-radius:50%;
    background:#333;
    margin:0 auto 10px;
}

.active .circle{
    background:#00ffcc;
    box-shadow:0 0 15px #00ffcc;
}

.timeline-step span{
    font-size:13px;
    opacity:0.6;
}

.active span{
    opacity:1;
    color:#00ffcc;
    font-weight:bold;
}

.back-btn{
    display:inline-block;
    margin-top:40px;
    padding:10px 20px;
    background:#00ffcc;
    color:black;
    border-radius:30px;
    text-decoration:none;
}
</style>
</head>
<body>

<div class="tracking-container">

<h2>🚚 Order Tracking</h2>

<p><strong>Order ID:</strong> #<?php echo $order['id']; ?></p>
<p><strong>Total:</strong> ₹<?php echo $order['total']; ?></p>
<p><strong>Current Status:</strong> <?php echo $status; ?></p>

<div class="progress-bar">
    <div class="progress-active" id="progressActive"></div>
</div>

<div class="timeline">
<?php
foreach($steps as $index => $step){
    $activeClass = ($index <= $currentStep) ? "active" : "";
?>
    <div class="timeline-step <?php echo $activeClass; ?>">
        <div class="circle"></div>
        <span>
            <?php echo $step; ?><br>
            <?php echo date("M d", strtotime($order['created_at']." +$index day")); ?>
        </span>
    </div>
<?php } ?>
</div>

<a href="orders.php" class="back-btn">← Back to Orders</a>

</div>

<script>
document.addEventListener("DOMContentLoaded", function(){
    let currentStep = <?php echo $currentStep; ?>;
    let totalSteps = 3; 
    let percentage = (currentStep / totalSteps) * 100;
    document.getElementById("progressActive").style.width = percentage + "%";
});
</script>

</body>
</html>
