

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VYSMO</title>
    <!--SWEET ALERT 2-->
    <!-- Favicon -->
    <link href="../../images/vysmoprintlogo.png" rel="icon">
    
    <script src="../../nodevtool.js"></script>
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
    $role = $_GET['role'];
    $qrName = $_GET['qrName'];
    if($role == 'Guest'){
        
        $destination = $_GET['destination'];
        $destinationLink = $_GET['destinationLink'];
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

    // Sender info 
    $mail->setFrom('vysmoph@gmail.com', 'VYSMO'); 
    //$mail->addReplyTo('reply@gmail.com', 'SenderName');

    $recipient = $email;

    

    if($role == 'Guest'){
        $subj = "NEUST VYSMO GUEST ACCOUNT QR";
        $msg = "<p style='font-size: 13.28px'>
                    <b>Good Day Guest Visitor!</b> Your <b>VISITATION REQUEST</b> has been received. Your destination is '<i>$destination</i>'. 
                    We've attached your Account Visitor QR Code that will be scanned when you enter the University. 
                    The guard will scan the Account Visitor QR Code to verify your information and visitation details. <br><br>
                    We are pleased to remind you that your Visitation Request is valid only 5 days after your chosen date. 
                    We are expecting you to visit the University within the given time. Thank you!<br><br>

                    <b>Map Guide Link: $destinationLink</b><br><br>

                    <b>Reminder</b>: To organize your next visit, log in to your account and complete your personal information.
                </p><br>
                <p>
                    <h3> Account Credentials: </h3> <h4> Email: <i>$email</i> <br> Password:<i> $generatedpassword </i> </h4>
                </p>";
                
    }
    elseif($role == 'Visitor'){
        $subj = "NEUST VYSMO VISITOR ACCOUNT QR";
        $msg = "<b>Good Day Visitor!</b> 
        We've attached your Account Visitor QR Code that will be scanned when you enter the University. 
        The guard scans the Account Visitor QR Code to verify your information and visitation details.  
        Please log in to your account and submit a visitation request. Thank you.";

    }
    

    // Add a recipient s
    $mail->addAddress($recipient); 
                            
    // Set email format to HTML 
    $mail->isHTML(true); 
    
    // Add an attachment by specifying the file path and name
    $attachmentDir = '../visitorQR/';
    $mail->addAttachment($attachmentDir . $qrName);
    
    // Mail subject
    $mail->Subject = $subj;
    
    // Mail body content 
    $mail->Body = $msg;

     // Send email 
     if($mail->send()) { 

        if($role == 'Guest'){
            echo 
                "<script>
                    Swal.fire({
                        icon: 'info',
                        title: 'Visitation Request Received',
                        text: 'Please check your email for the Account QR Code and Credentials!'
                    }).then(function() {
                        window.location = '../../';
                    });
                </script>";
        }
        elseif($role == 'Visitor'){
            echo 
                "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Account Created Success',
                        text: 'Please check your email for the Account QR Code!'
                    }).then(function() {
                        window.location = '../../';
                    });
                </script>";
        }

        
    }



?>