<?php
include "config.php";

$search = "";
$result = null;

if(isset($_GET['query']) && !empty($_GET['query'])){

    $search = mysqli_real_escape_string($conn, $_GET['query']);

    $sql = "SELECT * FROM products 
            WHERE name LIKE '%$search%'";

    $result = mysqli_query($conn, $sql);

    if(!$result){
        die("Query Failed: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h2 style="text-align:center; margin-top:140px;">
    Search Results for "<?php echo htmlspecialchars($search); ?>"
</h2>

<div class="search-container">

<?php
if($result && mysqli_num_rows($result) > 0){

    while($row = mysqli_fetch_assoc($result)){
        ?>

        <div class="search-card">
            
           <div class="search-img">
    <img src="images/<?php echo htmlspecialchars($row['image']); ?>" 
         alt="<?php echo htmlspecialchars($row['name']); ?>"
         onerror="this.src='images/default.png';">
</div>


            <h3><?php echo $row['name']; ?></h3>
            <p>₹<?php echo $row['price']; ?></p>
            <a href="product.php?id=<?php echo $row['id']; ?>">View Product</a>
        </div>

        <?php
    }

} else {
    echo "<p style='text-align:center;'>No products found.</p>";
}
?>

</div>

</div>

</body>
</html>
