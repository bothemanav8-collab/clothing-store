<?php
include "config.php";
error_reporting(E_ALL);
ini_set('display_errors',1);

$settings_query = mysqli_query($conn,"SELECT * FROM site_settings WHERE id=1");
$settings = mysqli_fetch_assoc($settings_query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Manav Fashion</title>
<link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
<head>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/navbar.css">
</head>


<style>



/* ================= FOOTER ================= */

.modern-footer{
    margin-top:120px;
    padding:40px;
    text-align:center;
    background:#000;
    border-top:1px solid rgba(0,255,200,0.2);
}

/* Animations */

@keyframes fadeUp{
    from{opacity:0; transform:translateY(40px);}
    to{opacity:1; transform:translateY(0);}
}

</style>
</head>

<body>

<?php include "navbar.php"; ?>

<!-- HERO -->
<section class="hero"
style="background:url('images/<?php echo $settings['hero_image']; ?>') center/cover no-repeat;">

<div class="hero-content">
<h1><?php echo $settings['hero_title']; ?></h1>
<p><?php echo $settings['hero_subtitle']; ?></p>

<a href="<?php echo $settings['hero_button_link']; ?>" 
class="hero-btn" id="heroBtn">
<?php echo $settings['hero_button_text']; ?>
</a>

</div>
</section>

<!-- PRODUCTS -->
<section id="products">
<h2 class="section-title">Featured Collection</h2>

<div class="product-grid">

<?php
$result = mysqli_query($conn,"SELECT * FROM products LIMIT 12");
while($row = mysqli_fetch_assoc($result)){
?>

<div class="product-card">
<img src="images/<?php echo $row['image']; ?>">
<div class="product-info">
<h3><?php echo $row['name']; ?></h3>
<p class="price">₹<?php echo $row['price']; ?></p>
<a href="product.php?id=<?php echo $row['id']; ?>" class="btn-outline">
View
</a>
</div>
</div>

<?php } ?>

</div>
</section>

<footer class="modern-footer">
© 2026 Manav Fashion • Luxury. Style. Identity.
</footer>

<script>

/* Reveal Animation */

const cards = document.querySelectorAll(".product-card");

const observer = new IntersectionObserver(entries=>{
entries.forEach(entry=>{
if(entry.isIntersecting){
entry.target.classList.add("show");
}
});
},{threshold:0.2});

cards.forEach(card=>observer.observe(card));

</script>
<script src="js/script.js"></script>

</body>
</html>
