
<?php
require('database.php');
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
      <link rel="stylesheet" href="login.css">
      
  <script src="nodevtool.js"></script>
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

      <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

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

      </style>
  </head>

  <body class="indexBody center">
      <?php
        
        //onchange txtfield cam saka lang magttrigger
        if(isset($_POST['qrcontents'])){

          $qrcontents = $_POST['qrcontents'];
          
          $getAccountQRDB = mysqli_query($connection, "SELECT qrName, keyQR, ivQR FROM account_qr WHERE encryptedQrContent = '$qrcontents'");
          if(mysqli_num_rows($getAccountQRDB) > 0){

            while($row = mysqli_fetch_array($getAccountQRDB)){
                $qrInfo = $row;   
            }
            $qrName = $qrInfo[0];
            $key = base64_decode($qrInfo[1]);
            $iv = base64_decode($qrInfo[2]);

            $decryptedQR = openssl_decrypt(base64_decode($qrcontents), 'aes-128-cbc', $key, 0, $iv);

            $qrArray = explode("***", $decryptedQR); //magiging laman ng qrcode in array (email, ) index 0-3

            $accountID = $qrArray[0];

            if (isset($qrArray[1])) { // if yung iniscan na qrcode does not have qrname, so that we wont have error
                $email = $qrArray[1];
            } else {
                $email = null;
            }

            $checkAccount = mysqli_query($connection, "SELECT * FROM accounts WHERE account_id = '{$accountID}' AND email = '{$email}' AND role = 'Guard'");
            if(mysqli_num_rows($checkAccount) > 0){

              $checkQuickLogin = mysqli_query($connection, "SELECT * FROM accounts WHERE account_id = '$accountID' AND quick_login = 0");
              if(mysqli_num_rows($checkQuickLogin) > 0){

                $_SESSION['accountID'] = $accountID;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = 'Guard';
                $_SESSION['accountQR'] = $qrName;
                $_SESSION['statpage'] = 'valid';

                $activity = "QR LOGGED IN";
                $insertLogs = "INSERT INTO guard_logs (account_id, date_logs, activity, time_logs) VALUE ('$accountID', '$dateToday', '$activity', '$currentTime')";
                if(mysqli_query($connection, $insertLogs)){
                  echo "<script>window.location = 'pages/scanAccountQR.php'</script>";
                }

              }
              else{
                echo
                "<script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Quick Login Denied',
                        text: 'This QR Code is deactivated'
                    })
                </script>";
              }
              

            }
            else{
              echo 
                "<script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'This is not a Guard Account QR Code'
                    })
                </script>";
            }
            
          }
          else{
            echo 
              "<script>
                  Swal.fire({
                      icon: 'warning',
                      title: 'Invalid QR Code'
                  })
              </script>";
          }

        }
      ?>
    <div class="wrapper">
      <div id="formContent" >
        <!-- Logo -->
        <div class="logo">
          <img src="images/logo.png" id="logo" alt="Logo" />
        </div>
        <div class="logo1">
          <h1 class="logoHead">NEUST<br>Visitor Monitoring System</h1>
          <h2>Scan Guard Account QR</h2>
        </div>
        <!-- Login Form -->
        <div class="form">
          <div class="col loginQr1 mb-3">
            <div class="row justify-content-center">
              <video id="QRCode"></video>
            </div>
          </div>
          <form action="" method="POST">
            <input type="hidden" id="qrcontents" name="qrcontents">
          </form>
        </div>
        <div class="backQRForm">
          <a href="index.php" class="backQR" id="back"><i class="bi bi-arrow-left"></i> Back to Log In</a>
        </div>
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

<script>//camera scan

    let scanner = new Instascan.Scanner({ video: document.getElementById('QRCode')});
    Instascan.Camera.getCameras().then(function(cameras){
        if(cameras.length > 0 ){
            scanner.start(cameras[0]);
        } else{
            alert('No cameras found');
        }

    }).catch(function(e) {
        console.error(e);
    });

    scanner.addListener('scan',function(qrContents){
        let qrcontents = document.getElementById('qrcontents').value=qrContents;
        document.forms[0].submit(); 
        

    });
   

</script>
