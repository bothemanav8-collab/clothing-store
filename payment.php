<?php
session_start();

if(!isset($_SESSION['total_amount'])){
    header("Location: cart.php");
    exit();
}

$total = $_SESSION['total_amount'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Secure Payment</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">

</head>
<body>

<div class="payment-wrapper">

    <div class="payment-card">

        <h2>💳 Secure Payment</h2>
        <p class="total">Total Amount: ₹<?php echo $total; ?></p>

        <form method="post" action="process_payment.php" onsubmit="showLoader()">

            <div class="radio-group">
                <label>
                    <input type="radio" name="payment_method" value="UPI" required>
                    UPI
                </label>
            </div>

            <div class="radio-group">
                <label>
                    <input type="radio" name="payment_method" value="Card">
                    Credit / Debit Card
                </label>
            </div>

            <div class="radio-group">
                <label>
                    <input type="radio" name="payment_method" value="COD">
                    Cash On Delivery
                </label>
            </div>

            <button type="submit" class="pay-btn">
                Pay Now
            </button>

        </form>

        <!-- Loader -->
        <div class="loader" id="loader">
            <div class="spinner"></div>
            <p>Processing Payment...</p>
        </div>

    </div>

</div>

<script>
function showLoader(){
    document.getElementById("loader").style.display = "flex";
}
</script>

</body>
</html>
