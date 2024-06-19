<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VYSMO</title>

    <!-- Favicon -->
    <link href="images/vysmoprintlogo.png" rel="icon">
    <script src="nodevtool.js"></script>
    
    <!--SWEET ALERT 2-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
</head>
<body style="background-color: #cce6ff;">

</body>
</html>

<?php
    session_start();
    use PHPMailer\PHPMailer\PHPMailer; 
    use PHPMailer\PHPMailer\SMTP; 
    use PHPMailer\PHPMailer\Exception;

    $passwordResetLink = $_SESSION['resetLink'];
    $email = $_SESSION['emailReset'];
    $role = $_SESSION['resetRole'];
    // Include library files
    require 'PHPMailer/Exception.php'; 
    require 'PHPMailer/PHPMailer.php'; 
    require 'PHPMailer/SMTP.php'; 
    
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

    $subj = "NEUST VYSMO PASSWORD RESET";
    $msg = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Password Reset</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
                <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #cce6ff;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                    }

                    .headerTitle{
                        font-size: 16px;
                    }
                    .container {
                        max-width: 600px;
                        padding: 30px;
                        background-color: #ffffff;
                        box-shadow: 0 30px 60px 0 rgba(0,0,0,0.3);
                    }
                    .header {
                        text-align: center;
                        margin-bottom: 30px;
                    }
                    .logo {
                        width: 150px;
                    }
                    .message {
                        font-size: 18px;
                        color: #333333;
                        margin-bottom: 20px;
                        text-align: justify;
                    }
                    .reset-button {
                        display: inline-block;
                        padding: 15px 30px;
                        background-color: lightsalmon;
                        color: white;
                        border: none;
                        border-radius: 5px;
                        font-size: 20px;
                        font-weight: bold;
                        text-decoration: none;
                        transition: background-color 0.3s;
                    }
                    .reset-button:hover {
                        background-color: orange;
                    }
                    .footer {
                        font-size: 14px;
                        color: #777777;
                        margin-top: 30px;
                    }
                    .logoQR,.vysLogo{
                    width: 80%;
                    }
                    @media(min-width:501px){
                    .vertical-center {
                                margin: 0;
                                position: absolute;
                                top: 50%;
                                left: 50%;
                                -ms-transform: translate(-50%, -50%);
                                transform: translate(-50%, -50%);
                            }
                        }
                        
                        @media(max-width:500px){
                            .headerTitle{
                                font-size: small;
                            }
                            .container{
                                margin-top: 1%;
                            }
                            h1{
                                font-size: 26px;
                            }
                        }
                </style>
            </head>
            <body class="vertical-center">
                <div>
                    <div class="container rounded">
                        <div class="row justify-content-center mt-3">
                            <div class="header">
                                <center><h1><i>NEUST-VYSMO</i></h1></center>
                                <div class="text-center message">
                                    <p>Dear '.$role.',</p>
                                    <p>We received a request to reset your password for your VYSMO account. To proceed with the password reset, please click the button below within the next 30 minutes:</p>
                                </div>
                            </div>
                            <div class="col-auto">
                                <center><a href="'.$passwordResetLink.'" style="color: white;" class="reset-button">Reset Password</a></center>
                            </div>
                            <div class="text-center footer">
                                <p>If you did not request a password reset, you can safely ignore this email, and your password will remain unchanged.</p>
                                <center><p>Thank you for using VYSMO!</p></center>
                            </div>
                        </div> 
                    </div>        
                </div>

            </body>
            </html>
    ';

    // Add a recipient 
    $mail->addAddress($recipient); 
    
    // Set email format to HTML 
    $mail->isHTML(true); 
    
    // Mail subject 
    $mail->Subject = $subj; 
    
    // Mail body content 
    $mail->Body = $msg; 

    // Send email 
    if($mail->send()) {
        echo 
        "<script>
            Swal.fire({
                icon: 'success',
                title: 'Email Sent',
                text: 'Please check your email to reset your password!'
            }).then(function() {
                window.location = 'forgotPassword.php';
            });
        </script>";
    }
    else{
        echo 
        "<script>
            Swal.fire({
                icon: 'error ',
                title: 'Email Sent Failed'
            });
        </script>";
    }
    
        
    session_destroy();

?>