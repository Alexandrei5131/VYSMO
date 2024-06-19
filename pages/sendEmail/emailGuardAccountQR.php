

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VYSMO</title>
    
    <!-- Favicon -->
    <link href="../../images/vysmoprintlogo.png" rel="icon">
    
    <script src="../../nodevtool.js"></script>
    
    <!--SWEET ALERT 2-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
</head>
<body style="background-color: #cce6ff;">

</body>
</html>


<?php

    use PHPMailer\PHPMailer\PHPMailer; 
    use PHPMailer\PHPMailer\SMTP; 
    use PHPMailer\PHPMailer\Exception;

    $email = $_GET['email'];
    $qrName = $_GET['qrName'];
    if(isset($_GET['newQR']) && $_GET['newQR'] == 'yes'){
    }
    else{
        $generatedpassword = $_GET['password'];
    }


    // Include library files 
    require '../../PHPMailer/Exception.php'; 
    require '../../PHPMailer/PHPMailer.php'; 
    require '../../PHPMailer/SMTP.php'; 
    
    // Create an instance; Pass `true` to enable exceptions 
    $mail = new PHPMailer;
    
    // Server settings 
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;    //Enable verbose debug output 
    $mail->isSMTP();                            // Set mailer to use SMTP 
    $mail->Host = 'smtp.gmail.com';           // Specify main and backup SMTP servers 
    $mail->SMTPAuth = true;                     // Enable SMTP authentication 
    $mail->Username = 'vysmoph@gmail.com';       // SMTP username 
    $mail->Password = 'jdqcbfeccahjryui';         // SMTP password 
    $mail->SMTPSecure = 'ssl';                  // Enable TLS encryption, `ssl` also accepted 
    $mail->Port = 465;                          // TCP port to connect to 
    
    $mail->isHTML(true);
    // Sender info 
    $mail->setFrom('vysmoph@gmail.com', 'VYSMO'); 
    //$mail->addReplyTo('reply@gmail.com', 'SenderName');

    $recipient = $email;
    $subj = "NEUST VYSMO GUARD ACCOUNT QR";
    if(isset($_GET['newQR']) && $_GET['newQR'] == 'yes'){
        $msg = "<p style='font-size: 13.28px'>
                <b>Good Day Guard!</b>
                This will be your New Account QR Code to access your account in NEUST-VYSMO. 
                Please print and laminate this QR Code for quick login.
            </p><br>";
    }
    else{
        $msg = "<p style='font-size: 13.28px'>
                <b>Good Day Guard!</b>
                This will be your permanent QR Code to access your account in NEUST-VYSMO. 
                Please print and laminate this QR Code for quick login.
            </p><br>
            <p>
                <h3> Account Credentials: </h3> <h4> Email: <i>$email</i> <br> Password:<i> $generatedpassword </i> </h4>
            </p>";
    }
    

    $ts = $subj ;

    //echo $subj;
    //echo '<script>alert("'.$ts.'")</script>';

    // Add a recipient 
    $mail->addAddress($recipient); 
    
    // Set email format to HTML 
    $mail->isHTML(true); 
    
    // Add an attachment by specifying the file path and name
    $attachmentDir = '../guardQR/';
    $mail->addAttachment($attachmentDir . $qrName);
    
    // Mail subject 
    $mail->Subject = $subj; 
    
    // Mail body content 
    $mail->Body = $msg; 

    // Send email 
    if($mail->send()) { 

        if(isset($_GET['newQR']) && $_GET['newQR'] == 'yes'){
            echo 
                "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Guard Account QR Sent!',
                        text: 'New Guard Account QR Code have been sent to the email',
                        timer: 5000
                    }).then(function() {
                        window.location = '../addGuard.php';
                    });
                </script>";
        }
        else{
            echo 
                "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Guard Account Created!',
                        text: 'Guard Account QR Code have been sent to the email',
                        timer: 5000
                    }).then(function() {
                        window.location = '../addGuard.php';
                    });
                </script>";
        }
        
    }


?>