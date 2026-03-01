<?php
include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = "";
$error = "";

/* Fetch user name & email */
$stmt = mysqli_prepare($conn,"SELECT name,email FROM users WHERE id=?");
mysqli_stmt_bind_param($stmt,"i",$user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt,$name,$email);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);


/* Submit Feedback */
if(isset($_POST['submit'])){

    $rating  = intval($_POST['rating']);
    $message = trim($_POST['message']);

    if($rating > 0 && !empty($message)){

        $stmt = mysqli_prepare($conn,
        "INSERT INTO feedback (user_id,name,email,rating,message) VALUES (?,?,?,?,?)");

        mysqli_stmt_bind_param($stmt,"issis",
        $user_id,$name,$email,$rating,$message);

        if(mysqli_stmt_execute($stmt)){
            $msg = "success";
        } else {
            $error = "Database error. Please try again.";
        }

        mysqli_stmt_close($stmt);

    } else {
        $error = "Please select rating and write feedback.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Feedback | Manav Fashion</title>
<link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>

<body class="profile-body">

<a href="profile.php" class="contact-back-btn">← Back</a>

<div class="profile-container">

<h1>⭐ Give Feedback</h1>

<?php if($error!=""){ ?>
<div class="contact-error"><?php echo $error; ?></div>
<?php } ?>

<form method="post" class="profile-form">

<!-- ⭐ CLICKABLE STAR SYSTEM -->
<div class="star-rating">
    <input type="hidden" name="rating" id="ratingValue">

    <span class="star" data-value="1">★</span>
    <span class="star" data-value="2">★</span>
    <span class="star" data-value="3">★</span>
    <span class="star" data-value="4">★</span>
    <span class="star" data-value="5">★</span>
</div>

<textarea name="message" rows="4"
placeholder="Write your feedback..." required></textarea>

<button type="submit" name="submit">Submit Feedback</button>

</form>

</div>


<?php if($msg=="success"){ ?>
<div class="success-popup" id="successPopup">
    <div class="success-box">

        <div class="success-tick">
            <svg viewBox="0 0 52 52">
                <circle class="tick-circle" cx="26" cy="26" r="25"/>
                <path class="tick-check" fill="none"
                d="M14 27l7 7 16-16"/>
            </svg>
        </div>

        <h3>Feedback Submitted!</h3>
        <p>Thank you for your valuable review 💎</p>

        <button onclick="closePopup()">Go To Profile</button>
    </div>
</div>
<?php } ?>


<script>
document.addEventListener("DOMContentLoaded", function(){

    const stars = document.querySelectorAll(".star");
    const ratingInput = document.getElementById("ratingValue");

    if(stars.length && ratingInput){

        stars.forEach((star, index) => {

            /* Click */
            star.addEventListener("click", function(){

                const value = this.getAttribute("data-value");
                ratingInput.value = value;

                stars.forEach(s => s.classList.remove("active"));

                for(let i=0; i<value; i++){
                    stars[i].classList.add("active");
                }
            });

            /* Hover effect */
            star.addEventListener("mouseenter", function(){
                stars.forEach(s => s.classList.remove("hover"));

                for(let i=0; i<=index; i++){
                    stars[i].classList.add("hover");
                }
            });

            star.addEventListener("mouseleave", function(){
                stars.forEach(s => s.classList.remove("hover"));
            });

        });
    }

});


function closePopup(){
    window.location.href="profile.php";
}
</script>

</body>
</html>
