<?php
include "config.php";

$message = "";

if(isset($_POST['register'])){

    $name  = mysqli_real_escape_string($conn,$_POST['name']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

    if(mysqli_num_rows($check) > 0){
        $message = "Email Already Registered!";
    }else{
        mysqli_query($conn,"INSERT INTO users(name,email,password) VALUES('$name','$email','$pass')");
        $message = "Registered Successfully! You can login now.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register | Manav Fashion</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="auth-pro-body">

<div class="auth-pro-container">

    <!-- LEFT SIDE -->
    <div class="auth-left">
        <h1>Join Manav Fashion</h1>
        <p>Create your premium fashion account</p>
    </div>

    <!-- RIGHT SIDE -->
    <div class="auth-right">

        <div class="auth-pro-box">

            <h2>Create Account ✨</h2>

            <?php 
            if($message != ""){
                echo "<div class='auth-error'>$message</div>";
            }
            ?>

            <form method="post">

                <div class="input-group">
                    <input type="text" name="name" required>
                    <label>Full Name</label>
                </div>

                <div class="input-group">
                    <input type="email" name="email" required>
                    <label>Email Address</label>
                </div>

                <div class="input-group password-group">
                    <input type="password" name="password" id="regpass" required>
                    <label>Password</label>
                    <span class="toggle-pass" onclick="toggleRegPassword()">👁</span>
                </div>

                <button class="auth-pro-btn" name="register">
                    Register
                </button>

            </form>

            <div class="auth-links">
                Already have an account? 
                <a href="login.php">Login</a>
            </div>

        </div>

    </div>

</div>

<script src="js/script.js"></script>

<script>
function toggleRegPassword(){
    const pass = document.getElementById("regpass");
    pass.type = pass.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
