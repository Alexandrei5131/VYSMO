<?php
    include('database.php');

    session_start();

    if(isset($_SESSION['statpage'])){
        /* Functions */
        function pathTo($pageName) {
            echo "<script>window.location.href = '/pages/$pageName.php'</script>";
        }

        if ($_SESSION['statpage'] == 'invalid' || empty($_SESSION['statpage'])) {
            /* Set Default Invalid */
            $_SESSION['statpage'] = 'invalid';
        }

        if ($_SESSION['statpage'] == 'valid') {
            if($_SESSION['role'] == 'Admin'){
                pathTo('dashboard');
            }
            elseif($_SESSION['role']  == 'Guard'){
                pathTo('scanAccountQR');
            }
            elseif($_SESSION['role']  == 'Visitor' || $_SESSION['role']  == 'Guest'){
                pathTo('userProfile');
            }
        } 
    }

?>

<!DOCTYPE html>
<html>

<head>
<script src="nodevtool.js"></script>
  <title>VYSMO</title>
  <link rel="stylesheet" href="login.css">
  <link href="images/vysmoprintlogo.png" rel="icon">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <!-- Created by:Alcantara,Macario,Manubay, and Pineda -->
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
</head>

<body class="indexBody">

    <div class="wrapper">
        <div id="formContent" >
    
            <!-- Logo -->
            <div class="logo">
                <img src="images/logo.png" id="logo" alt="Logo" />
            </div>
            <div class="logo1">
                <h1 class="logoHead logoHeadForgotPass">NEUST-VYSMO</h1>
                <span>Enter your registered VYSMO email to reset your password.</span>
            </div>

            <!-- Login Form -->
            <div class="form">
                <form method="POST" action="">
                    <input type="text" id="email"  name="email" placeholder="Email" style="text-align: center;" required>
                    <button type="submit" id="reset" class="reset" name="reset">Reset Password</button>
                </form>
            </div>
            <div class="backFpForm">
                <a href="index.php" class="backFp" id="back"><i class="bi bi-arrow-left"></i> Back</a>
            </div>
        </div>
    </div>
</body>

</html>



<?php
//check if registered yung email na ininput
//if 
  date_default_timezone_set("Asia/Manila");

  if(isset($_POST['reset'])){
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    $sql = mysqli_query($connection, "SELECT account_id, role FROM accounts WHERE email ='{$email}'");
    if(mysqli_num_rows($sql) > 0){
        
        $valid_email = filter_var($email, FILTER_VALIDATE_EMAIL);
        $accountID = mysqli_fetch_array($sql);


        $expirationFormat = strtotime("+30 minutes"); // Set expiration to 30 minutes from now
        $expiration = date("Y-m-d H:i:s", $expirationFormat);
        
        $alpha   = str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZ', 5));//get randomly 5 letters, can be repeated
        $numeric = str_shuffle(str_repeat('0123456789', 5));//get randomly 5 numbers, can be repeated
        $combinedAlphaNumeric = substr($alpha, 0, 5) . substr($numeric, 0, 5) . md5($valid_email) ; //pagsamahin
        $key = str_shuffle($combinedAlphaNumeric);
        $addKey = substr(md5(uniqid(rand(), 1)), 6, 6);//generate uniq
        $key = $key.$addKey;

        $sqlInsert = "INSERT INTO account_reset_password (account_id, resetKey, expiration) VALUE ('$accountID[0]', '$key', '$expiration')";
        if(mysqli_query($connection, $sqlInsert)){

            $passwordResetLink = "https://192.168.88.138/vysmo/resetPassword.php?resetKey=".$key."&accountID=".urlencode($accountID[0]);
            $_SESSION['resetLink']= $passwordResetLink;
            $_SESSION['emailReset']= $valid_email;
            $_SESSION['resetRole'] = $accountID[1];
            echo "<script>window.location = 'resetEmail.php';</script>";

        }

    }
    else{
        echo 
            "<script>
              Swal.fire({
                  icon: 'error',
                  title: 'Account does not exist',
                  text: 'The email you input is not registered in VYSMO!'
              });
            </script>";
      }
  }