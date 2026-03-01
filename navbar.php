<?php
include "config.php";
?>

<div class="navbar" id="navbar">

    <div class="logo">Manav Fashion</div>

    <div class="menu-toggle" id="menuToggle">☰</div>

    <div class="nav-links" id="navLinks">

<a href="index.php">Home</a>
<a href="#products">Shop</a>
<a href="contact.php">Contact</a>

<form action="search.php" method="GET" class="search-wrapper">
    <input type="text" name="query" id="search-box" placeholder="Search products..." required>
</form>

<a href="cart.php" class="cart-icon">

            🛒
            <span class="cart-count">
                <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
            </span>
        </a>

        <button id="themeToggle" class="theme-btn">🌙</button>

        <?php if(empty($_SESSION['user_id'])){ ?>
            <a href="login.php">Login</a>
        <?php } else {
            $uid = $_SESSION['user_id'];
            $user = mysqli_fetch_assoc(mysqli_query($conn,"SELECT profile_photo FROM users WHERE id='$uid'"));
        ?>
        <div class="profile-menu" id="profileMenu">
            <img src="<?php
                if(!empty($user['profile_photo']) && file_exists("uploads/".$user['profile_photo'])){
                    echo "uploads/".$user['profile_photo'];
                } else {
                    echo "images/default.png";
                }
            ?>">
            <div class="dropdown" id="dropdownMenu">
                <a href="profile.php">👤 Profile</a>
                <a href="orders.php">📦 Orders</a>
                <a href="logout.php">🚪 Logout</a>
            </div>
        </div>
        <?php } ?>

    </div>

</div>
