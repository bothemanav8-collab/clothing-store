<?php
include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$msg="";
$error="";

/* ================= FETCH USER DATA ================= */

$stmt = mysqli_prepare($conn,
"SELECT name,email,phone,profile_photo FROM users WHERE id=?");

mysqli_stmt_bind_param($stmt,"i",$user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt,$name,$email,$phone,$photo);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);


/* ================= UPDATE PROFILE ================= */

if(isset($_POST['update'])){

    $new_name  = trim($_POST['name']);
    $new_email = trim($_POST['email']);
    $new_phone = trim($_POST['phone']);

    if(!empty($new_name) && !empty($new_email)){

        if(isset($_FILES['photo']) && $_FILES['photo']['name']!=""){

            $allowed = ["jpg","jpeg","png","webp"];
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));

            if(in_array($ext,$allowed)){

                if(!is_dir("uploads")){
                    mkdir("uploads",0777,true);
                }

                $fileTmp  = $_FILES['photo']['tmp_name'];
                $fileName = time()."_".basename($_FILES['photo']['name']);
                $target   = "uploads/".$fileName;

                if(move_uploaded_file($fileTmp,$target)){

                    $stmt = mysqli_prepare($conn,
                    "UPDATE users SET name=?, email=?, phone=?, profile_photo=? WHERE id=?");

                    mysqli_stmt_bind_param($stmt,"ssssi",
                    $new_name,$new_email,$new_phone,$fileName,$user_id);

                } else {
                    $error = "Image upload failed.";
                }

            } else {
                $error = "Only JPG, PNG, WEBP allowed.";
            }

        } else {

            $stmt = mysqli_prepare($conn,
            "UPDATE users SET name=?, email=?, phone=? WHERE id=?");

            mysqli_stmt_bind_param($stmt,"sssi",
            $new_name,$new_email,$new_phone,$user_id);
        }

        if(empty($error) && mysqli_stmt_execute($stmt)){
            $msg="Profile Updated Successfully!";
            $name  = $new_name;
            $email = $new_email;
            $phone = $new_phone;
            if(isset($fileName)){
                $photo = $fileName;
            }
        } else if(empty($error)){
            $error="Update Failed.";
        }

        mysqli_stmt_close($stmt);

    }else{
        $error="Name & Email required.";
    }
}


/* ================= FETCH FEEDBACK STATS ================= */

$avg_rating = 0;
$total_reviews = 0;

$stmt = mysqli_prepare($conn,
"SELECT AVG(rating), COUNT(*) FROM feedback WHERE user_id=?");

mysqli_stmt_bind_param($stmt,"i",$user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt,$avg_rating,$total_reviews);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

$avg_rating = round($avg_rating,1);


/* ================= FETCH USER REVIEWS ================= */

$reviews = [];

$stmt = mysqli_prepare($conn,
"SELECT rating,message,created_at FROM feedback WHERE user_id=? ORDER BY id DESC");

mysqli_stmt_bind_param($stmt,"i",$user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt,$rating,$message,$created);

while(mysqli_stmt_fetch($stmt)){
    $reviews[] = [
        "rating"=>$rating,
        "message"=>$message,
        "created"=>$created
    ];
}

mysqli_stmt_close($stmt);

?>

<!DOCTYPE html>
<html>
<head>
<title>My Profile | Manav Fashion</title>
<link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>

<body class="profile-body">

<a href="index.php" class="contact-back-btn">← Back</a>

<div class="profile-container">

<h1>👤 My Profile</h1>

<?php if($msg!=""){ ?>
<div class="contact-success"><?php echo $msg; ?></div>
<?php } ?>

<?php if($error!=""){ ?>
<div class="contact-error"><?php echo $error; ?></div>
<?php } ?>


<!-- ================= PROFILE FORM ================= -->

<form method="post" enctype="multipart/form-data" class="profile-form">

<div class="profile-photo-wrapper">
<img src="<?php 
if(!empty($photo) && file_exists("uploads/".$photo)){
    echo "uploads/".$photo;
} else {
    echo "images/default.png";
}
?>" id="profilePreview">

<label for="photoInput" class="photo-overlay">
Change Photo
</label>
</div>

<input type="file" name="photo" id="photoInput" hidden>

<label>Name</label>
<input type="text" name="name"
value="<?php echo htmlspecialchars($name); ?>" required>

<label>Email</label>
<input type="email" name="email"
value="<?php echo htmlspecialchars($email); ?>" required>

<label>Mobile Number</label>
<input type="text" name="phone"
value="<?php echo htmlspecialchars($phone); ?>">

<button type="submit" name="update">Update Profile</button>

</form>

<hr style="margin:40px 0; opacity:0.2;">

<!-- ================= FEEDBACK STATS ================= -->
<div class="feedback-action">
    <a href="feedback.php" class="give-feedback-btn">
        ⭐ Give Feedback
    </a>
</div>

<div class="review-stats">
<h3>⭐ Average Rating: <?php echo $avg_rating ? $avg_rating : 0; ?></h3>
<p>Total Reviews: <?php echo $total_reviews; ?></p>
</div>

<!-- ================= MY REVIEWS ================= -->

<div class="my-reviews">
<h2>📝 My Reviews</h2>

<?php if(count($reviews) > 0){ ?>

<?php foreach($reviews as $row){ ?>

<div class="review-card">
<div class="review-stars">
<?php echo str_repeat("⭐",$row['rating']); ?>
</div>

<p><?php echo htmlspecialchars($row['message']); ?></p>
<small><?php echo $row['created']; ?></small>
</div>

<?php } ?>

<?php } else { ?>
<p>No reviews yet.</p>
<?php } ?>

</div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function(){

const photoInput = document.getElementById("photoInput");
const previewImg = document.getElementById("profilePreview");

if(photoInput && previewImg){
photoInput.addEventListener("change", function(){
const file = this.files[0];
if(file){
const reader = new FileReader();
reader.onload = function(e){
previewImg.src = e.target.result;
}
reader.readAsDataURL(file);
}
});
}

});
</script>

</body>
</html>
