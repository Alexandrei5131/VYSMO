<?php
    
    include('database.php');

?>
<!DOCTYPE html>
<html>

<head>
    <title>VYSMO</title>
    <link rel="stylesheet" href="login.css">
        
    <script src="nodevtool.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Created by:Alcantara,Macario,Manubay, and Pineda -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
    <!-- Favicon -->
    <link href="images/vysmoprintlogo.png" rel="icon">
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">


    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel=”stylesheet” href=”css/bootstrap-responsive.css”>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="registration/registration.css">
    <link href="pages/css/style.css" rel="stylesheet">    <script src="nodevtool.js"></script>
    <!-- Customized Bootstrap Stylesheet -->
    <link href="pages/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">


    <!-- Template Stylesheet -->
    <link href="pages/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="registration/registration.css">




  <!-- Created by:Alcantara,Macario,Manubay, and Pineda -->

  <style>

    .iconEye{
        margin-left: -20px;
    }
    #password{
        margin-left: -4px;
    }
    .save:hover{
        background-color: lightblue;
    }
    .createNewPass{
    font-size: 20px;
  }
#logoReset{
    width: 100px;
    margin-top: -45px;
}
@media screen and (max-width: 500px) {
    .wrapperReset{
        width: 50%;
    }
}

    </style>

  <script>
    

    $(document).ready(function() {//to validate the password and confirmpass
      $("#newPassword").keyup(validate);
      $("#cfnNewPassword").keyup(validate);
    });

    function validate() {
      var password1 = $("#newPassword").val();
      var password2 = $("#cfnNewPassword").val();

      if(password1 == password2) {
        $("#validate-status").text(""); 
   
      }
      else {

          $("#validate-status").text("Password not match");  
      }

    }

    
  </script>
  
</head>

<body class="indexBody centerReset">
    <?php

        $accountID = urldecode($_GET['accountID']);
        $resetKey = $_GET['resetKey'];
        $currentDateTime = date("Y-m-d H:i:s");

        $checkKey = mysqli_query($connection, "SELECT * FROM account_reset_password  WHERE account_id='$accountID' AND resetKey = '$resetKey' AND expiration >= '$currentDateTime'"); //
        if(mysqli_num_rows($checkKey) > 0){//valid resetpassword
            ?>
            <div class="wrapper wrapperReset">
                <div id="formContent" class="resetPassForm">
                    <!-- Logo -->
                    <div class="logoResetPass">
                        <img src="images/logo.png" id="logoReset" alt="Logo" />
                    </div>
                    <div class="logo1">
                        <h1 class="logoHead logoHeadForgotPass">NEUST-VYSMO</h1>
                        <span class="createNewPass">Create New Password</span>
                    </div>

                    <!-- Login Form -->
                    <div class="form">
                        <form method="POST">
                            <input type="hidden" name="accountID" value="<?php echo $accountID;?>">
                            <input type="password" id="newPassword" class="password" name="password1" placeholder="Password" minlength="8" required><i onclick="newpassword() " id="toggleNewPassword" class="bi bi-eye iconEye"></i>
                           <br> <input type="password" id="cfnNewPassword" class="password" name="password2" placeholder="Confirm Password" minlength="8" required><i onclick="confirmpassword() " id="toggleConfirmPassword" class="bi bi-eye iconEye"></i><br>
                            <p id="validate-status" style="color:red;  margin-top: 5px; overflow: hidden;"></p>
                            <button type="submit" class="save" style="margin-top: 0px;" name="resetPassword">Save New Password</button>
                        </form>
                    </div>
                    <script>
                        function newpassword() {
                            var passwordInput = document.getElementById("newPassword");
                            var iconEye = document.getElementById("toggleNewPassword");

                            if (passwordInput.type === "password") {
                                passwordInput.type = "text";
                                iconEye.classList.remove("bi-eye");
                                iconEye.classList.add("bi-eye-slash");
                            } else {
                                passwordInput.type = "password";
                                iconEye.classList.remove("bi-eye-slash");
                                iconEye.classList.add("bi-eye");
                            }
                        }
                        function confirmpassword() {
                            var passwordInput = document.getElementById("cfnNewPassword");
                            var iconEye = document.getElementById("toggleConfirmPassword");

                            if (passwordInput.type === "password") {
                                passwordInput.type = "text";
                                iconEye.classList.remove("bi-eye");
                                iconEye.classList.add("bi-eye-slash");
                            } else {
                                passwordInput.type = "password";
                                iconEye.classList.remove("bi-eye-slash");
                                iconEye.classList.add("bi-eye");
                            }
                        }
                    </script>

                </div>
            </div>
    <?php
        }
        else{//expired date or invalid url
    ?>  
        <body class="p-0">
            <div class="content1">
                <nav class="navbar fixed-top">
                        <div class="row headerError justify-content-center mx-0 mt-2" id="">
                            <div class="row logoRow justify-content-center">
                                <div class="col-auto p-0 logoQRBorder1 ">
                                    <img src="images/logo.png" alt="" class="logoQR1">
                                </div>
                                <div class="col-auto p-0 vysLogoBorder1 ">
                                    <img src="images/vysmoprintlogo.png" class="vysLogo1" alt="" id="vysLogo1">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col headerTitleMain1">
                                    <div class="text-center">
                                        <span class="headerTitle2 ">Nueva Ecija University of Science and Technology</span>
                                    </div>
                                    <div class="text-center">
                                        <span class="headerTitle2 ">Visitor Monitoring System</span>         
                                    </div>
                                </div>
                            </div> 
                        </div>
                </nav>
            </div>
            <div class="center">
                <div class="container col-12 col-sm-8" >
                    <div class="row">
                        <div class="col">
                            <div class="items">
                            <p class="text-center textErrorDesign">We apologize for any inconvenience, but it seems that the provided link is either invalid or has expired. 
                        Please request a new reset password link to re-login again. Thanks!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        
    <?php

        }
    ?>

<script src="registration/script.js"></script>

</body>

</html>

<?php

    if(isset($_POST['resetPassword'])){
        $pass1 = mysqli_real_escape_string($connection, $_POST['password1']);
        $pass2 = mysqli_real_escape_string($connection, $_POST['password2']);

        if($pass1 == $pass2){

            $sql = mysqli_query($connection, "SELECT * FROM account_reset_password WHERE account_id = '$accountID'");
            if(mysqli_num_rows($sql) > 0){

                

                $sql1 = mysqli_query($connection, "SELECT * FROM account_reset_password WHERE account_id = '$accountID' AND resetKey ='$resetKey' AND expiration >= '$currentDateTime'");
                if(mysqli_num_rows($sql1)){

                    $salt = "45a4748f715f501377bf2c6de1b259b1"; //visitor monitoring system
                    $hashpassword = md5($salt.$pass1);

                    $reset = "UPDATE accounts SET password = '$hashpassword' WHERE account_id = '$accountID';";

                    if(mysqli_query($connection,$reset)){
                        //(OPTIONAL) magemail na nareset yung password, to inform!
                        echo
                        "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Account Password Reset',
                                text: 'You have successfully reset your password!'
                            }).then(function() {
                            window.location = '../vysmo';
                        });
                        </script>";

                    }

                }
                else{
                    //either invalid reset key or expired reset link
                    echo
                        "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Invalid or Expired Link',
                                text: 'Please request a new reset password'
                            }).then(function() {
                            window.location = 'forgotPassword.php';
                        });
                        </script>";
                }

            }
            else{
                //email doesnt exist
            }

        }

    }

?>