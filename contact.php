<?php
include "config.php";

$msg = "";
$error = "";

if(isset($_POST['send'])){

    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if(!empty($name) && !empty($email) && !empty($subject) && !empty($message)){

        $stmt = mysqli_prepare($conn,
            "INSERT INTO contact_messages (name,email,subject,message) VALUES (?,?,?,?)"
        );

        mysqli_stmt_bind_param($stmt,"ssss",$name,$email,$subject,$message);

        if(mysqli_stmt_execute($stmt)){
            $msg = "success";   // Only flag
        }else{
            $error = "Something went wrong. Please try again.";
        }

        mysqli_stmt_close($stmt);
    }else{
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Contact Us | Manav Fashion</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>
<body class="contact-body">

<div class="contact-container">

    <div class="contact-left">
        <h1>Get In Touch</h1>
        <p>Have questions about our latest collections or orders?  
        Our Manav Fashion support team is here to help you.</p>

        <div class="contact-info">
            <p>📍 Akola, Maharashtra</p>
            <p>📞 +91 8975779434</p>
            <p>✉ bothemanav8gmail.com</p>
        </div>
    </div>

    <div class="contact-right">

        <h2>Send Us a Message</h2>

        <?php if($error!=""){ ?>
            <div class="contact-error"><?php echo $error; ?></div>
        <?php } ?>

        <form method="post" class="contact-form">

            <input type="text" name="name" placeholder="Your Name" required>

            <input type="email" name="email" placeholder="Your Email" required>

            <select name="subject" required>
                <option value="">Select Subject</option>
                <option value="Order Query">Order Query</option>
                <option value="Return / Exchange">Return / Exchange</option>
                <option value="Size Issue">Size Issue</option>
                <option value="Payment Problem">Payment Problem</option>
                <option value="Bulk Order">Bulk Order</option>
                <option value="Collaboration">Collaboration</option>
                <option value="Other">Other</option>
            </select>

            <textarea name="message" rows="5" placeholder="Your Message" required></textarea>

            <button type="submit" name="send">Send Message</button>

        </form>

    </div>

</div>
<a href="index.php" class="contact-back-btn">← Back</a>

<!-- SUCCESS POPUP -->
<?php if($msg=="success"){ ?>
<div class="success-popup" id="successPopup">
    <div class="success-box">

        <div class="success-tick">
            <svg viewBox="0 0 52 52">
                <circle class="tick-circle" cx="26" cy="26" r="25"/>
                <path class="tick-check" fill="none" d="M14 27l7 7 16-16"/>
            </svg>
        </div>

        <h3>Message Sent Successfully!</h3>
        <p>Our fashion team will contact you within 24 hours.</p>

        <button onclick="closePopup()">Close</button>

    </div>
</div>
<?php } ?>

<script>
function closePopup(){
    document.getElementById("successPopup").style.display="none";
}

setTimeout(function(){
    let popup = document.getElementById("successPopup");
    if(popup){
        popup.style.display="none";
    }
},4000);
</script>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/navbar.css">

</body>
</html>
