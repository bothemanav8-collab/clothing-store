<?php
include "config.php";

$message = "";

/* ✅ Default Safe Redirect */
$redirect = "index.php";

/* ✅ Allow only internal pages */
$allowed_pages = ["index.php","cart.php","checkout.php","orders.php"];

if(isset($_GET['redirect']) && in_array($_GET['redirect'],$allowed_pages)){
    $redirect = $_GET['redirect'];
}

if(isset($_POST['login'])){

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass  = $_POST['password'];

    if(isset($_POST['redirect']) && in_array($_POST['redirect'],$allowed_pages)){
        $redirect = $_POST['redirect'];
    }

    $stmt = mysqli_prepare($conn,"SELECT id,password FROM users WHERE email=?");
    mysqli_stmt_bind_param($stmt,"s",$email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if(mysqli_stmt_num_rows($stmt) > 0){

        mysqli_stmt_bind_result($stmt,$id,$hashed_pass);
        mysqli_stmt_fetch($stmt);

        if(password_verify($pass,$hashed_pass)){

            $_SESSION['user_id'] = $id;

            header("Location: ".$redirect);
            exit();

        } else {
            $message = "Invalid Password!";
        }

    } else {
        $message = "Email Not Found!";
    }

    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login | Manav Fashion</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>
<body class="auth-pro-body">

<div class="auth-pro-container">

    <div class="auth-left">
        <h1>MANAV FASHION</h1>
        <p>Luxury. Style. Identity.</p>
    </div>

    <div class="auth-right">
        <div class="auth-pro-box">

            <h2>Welcome Back 👋</h2>

            <?php if($message != ""){ ?>
                <div class="auth-error"><?php echo $message; ?></div>
            <?php } ?>

            <form method="post">

                <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">

                <div class="input-group">
                    <input type="email" name="email" required>
                    <label>Email Address</label>
                </div>

                <div class="input-group password-group">
                    <input type="password" name="password" id="password" required>
                    <label>Password</label>
                    <span class="toggle-pass" onclick="togglePassword()">👁</span>
                </div>

                <button class="auth-pro-btn" name="login">
                    Login
                </button>

            </form>

            <div class="auth-links">
                <a href="#">Forgot Password?</a>
                <span>|</span>
                <a href="register.php">Create Account</a>
            </div>

        </div>
    </div>
</div>

<script>
function togglePassword(){
    const pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
