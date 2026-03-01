<?php
session_start();
include "config.php";

if(!isset($_GET['id'])){
    die("Product Not Found");
}

$id = intval($_GET['id']);
$result = mysqli_query($conn,"SELECT * FROM products WHERE id=$id");

if(mysqli_num_rows($result) == 0){
    die("Invalid Product");
}

$product = mysqli_fetch_assoc($result);


/* ================= ADD TO CART ================= */
if(isset($_POST['add'])){

    $size = $_POST['size'] ?? '';
    $qty  = intval($_POST['qty'] ?? 1);

    if($size == ''){
        die("Please select size.");
    }

    if(!isset($_SESSION['cart'])){
        $_SESSION['cart'] = [];
    }

    $found = false;

    foreach($_SESSION['cart'] as &$item){
        if(is_array($item) && $item['id'] == $product['id'] && $item['size'] == $size){
            $item['quantity'] += $qty;
            $found = true;
            break;
        }
    }

    if(!$found){
        $_SESSION['cart'][] = [
            'id' => $product['id'],
            'size' => $size,
            'quantity' => $qty
        ];
    }

    header("Location: cart.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $product['name']; ?></title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="advanced-product">

    <div class="adv-img">
        <img src="images/<?php echo $product['image']; ?>" alt="">
    </div>

    <div class="adv-details">
        <h1><?php echo $product['name']; ?></h1>
        <p class="adv-price">₹<?php echo $product['price']; ?></p>
        <p class="adv-desc"><?php echo $product['description']; ?></p>

        <form method="post">

            <label>Select Size</label>
            <select name="size" class="adv-select" required>
                <option value="" disabled selected>Select Size</option>
                <option value="S">S</option>
                <option value="M">M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
            </select>

            <label>Quantity</label>
            <input type="number" name="qty" value="1" min="1" class="adv-qty">

            <button name="add" class="adv-btn">
                Add To Cart
            </button>

        </form>

    </div>

</div>

<script src="js/script.js"></script>
</body>
</html>