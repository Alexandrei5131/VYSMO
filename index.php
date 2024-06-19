<?php
    include('database.php');

    session_start();

    if(isset($_SESSION['statpage'])){
        /* Functions */
        function pathTo($pageName) {
            echo "<script>window.location.href = '/vysmo/pages/$pageName.php'</script>";
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
    <title>VYSMO</title>
    <script src="nodevtool.js"></script>
    <link rel="stylesheet" href="login.css">
    <!-- Favicon -->
    <link href="images/vysmoprintlogo.png" rel="icon">
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel=”stylesheet” href=”css/bootstrap-responsive.css”>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Created by:Alcantara,Macario,Manubay, and Pineda -->
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
  <style>
    .iconEye{
        margin-left: -20px;
    }
    #password{
        margin-left: -4px;
    }
         </style>
</head>

<body class="indexBody center loginBody">

    <div class="wrapper">
        <div id="formContent" class="">
    
            <!-- Logo -->
            <div class="logo">
                <img src="images/logo.png" id="logo" alt="Logo" />
            </div>
            <div class="logo1">
                <h1 class="logoHead">NEUST<br>Visitor Monitoring System</h1>
            </div>

            <!-- Login Form -->
            <div class="form">
                <form method="POST">
                    <input type="email" id="email" name="email" placeholder="Email" required>

                    <div class="password-container">
                        <input type="password" id="password" name="password" placeholder="Password" minlength="8" required>
                        <i onclick="showHidePass()" class="bi bi-eye iconEye" id="togglePassword"></i>
                    </div>

                    <input type="submit" class="login" name="login" value="Log In">
                </form>
            </div>

            <script>
                function showHidePass() {
                    var passwordInput = document.getElementById("password");
                    var iconEye = document.getElementById("togglePassword");

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


            <!-- Footer -->
            <div id="formFooter">
                <a class="fpLabel click" href="registration/">Register</a>
                |
                <a class="fpLabel click" href="forgotPassword.php">Forgot Password?</a>

            </div>
            <a class="useQR" href="qrLogin.php"><i class="bi bi-box-arrow-in-right"></i><i class="bi bi-qr-code-scan qrbutton"></i></a>

        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="registration/script.js"></script>

</body>

</html>

<?php

    if(isset($_POST['login'])){

        $email = mysqli_real_escape_string($connection,$_POST['email']);

        $checkEmail = mysqli_query($connection, "SELECT * FROM accounts WHERE email='$email'");
        if(mysqli_num_rows($checkEmail) > 0){

            $password = mysqli_real_escape_string($connection,$_POST['password']);

            $salt = "45a4748f715f501377bf2c6de1b259b1"; //visitor monitoring system
            $hash = md5($salt.$password);
            $checkRole = mysqli_query($connection, "SELECT role FROM accounts WHERE email='$email' AND password='$hash'");
            if(mysqli_num_rows($checkRole) > 0){

                $role = mysqli_fetch_array($checkRole);

                if($role[0] == 'Admin'){
                    $_SESSION['role'] = $role[0];
                    $_SESSION['statpage'] = 'valid';

                    echo 
                        "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Login Successfully',
                                timer: 3000
                            }).then(function() {
                                window.location = 'pages/dashboard.php';
                            });
                        </script>";

                }
                elseif($role[0] == 'Guard' || $role[0] == 'Guest' || $role[0] == 'Visitor'){

                    $checkAccount = mysqli_query($connection, "SELECT accounts.account_id, accounts.role, account_qr.qrName FROM accounts INNER JOIN account_qr ON accounts.account_id = account_qr.account_id
                                        WHERE accounts.email='$email' AND accounts.password='$hash'");//

                    if(mysqli_num_rows($checkAccount) > 0){
                        
                        while($row = mysqli_fetch_array($checkAccount)){
                            $infoAccount = $row;   
                        }
                    
                        $accountID = $infoAccount[0];
                        $role = $infoAccount[1];
                        $accountQR = $infoAccount[2];

                        $_SESSION['accountID'] = $accountID;
                        $_SESSION['email'] = $email;
                        $_SESSION['role'] = $role;
                        $_SESSION['accountQR'] = $accountQR;
                        $_SESSION['statpage'] = 'valid';
                        
                        

                        if($role == 'Guest' or $role == 'Visitor'){
                            echo "<script>
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Login Successfully',
                                        timer: 3000
                                    }).then(function() {
                                        window.location = 'pages/userProfile.php';
                                    });
                                </script>";
                        }
                        elseif($role == 'Guard'){
                            $activity = "LOGGED IN";
                            $insertLogs = "INSERT INTO guard_logs (account_id, date_logs, activity, time_logs) VALUE ('$accountID', '$dateToday', '$activity', '$currentTime')";
                            if(mysqli_query($connection, $insertLogs)){
                                echo "<script>
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Login Successfully',
                                        timer: 3000
                                    }).then(function() {
                                        window.location = 'pages/scanAccountQR.php';
                                    });
                                </script>";
                            }
    
                        }

                    }

                }


            }
            else{
                //wrong credentials
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Wrong Credentials',
                            text: 'Please enter your Correct Account Credentials!',
                            timer: 3000
                        })
                    </script>";
            }

        }
        else{
            echo 
            "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Account does not exist!',
                    timer: 3000
                })
            </script>";
        }
        

    }

?>