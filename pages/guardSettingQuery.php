<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VYSMO</title>
    <!--SWEET ALERT 2-->
    <!-- Favicon -->
    <link href="../images/vysmoprintlogo.png" rel="icon">
    
    <script src="js/nodevtool.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
</head>
<body style="background-color: #cce6ff;">

</body>
</html>


<?php
    include('../database.php');
    
    if(isset($_POST['activateQR'])){
        $accountID = $_POST['accountID'];

        $activateQR = "UPDATE accounts SET quick_login = 0 WHERE account_id = '$accountID'";
        if(mysqli_query($connection, $activateQR)){

            $activity = "ACTIVATION - QR CODE LOG-IN";
            $insertLogs = "INSERT INTO guard_logs (account_id, date_logs, activity, time_logs) VALUE ('$accountID', '$dateToday', '$activity', '$currentTime')";
            if(mysqli_query($connection, $insertLogs)){
                echo 
                    "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'QR Code Activated Successfully',
                            timer: 3000
                        }).then(function() {
                            window.location = 'guardSetting.php';
                        });
                    </script>";
            }
        }
        else{
            echo "<script>alert('activate failed')</script>";
        }
    }
    elseif(isset($_POST['deactivateQR'])){
        $accountID = $_POST['accountID'];

        $activateQR = "UPDATE accounts SET quick_login = 1 WHERE account_id = '$accountID'";
        if(mysqli_query($connection, $activateQR)){
            $activity = "DEACTIVATION - QR CODE LOG-IN";
            $insertLogs = "INSERT INTO guard_logs (account_id, date_logs, activity, time_logs) VALUE ('$accountID', '$dateToday', '$activity', '$currentTime')";
            if(mysqli_query($connection, $insertLogs)){
                echo 
                    "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'QR Code Deactivated Successfully',
                            timer: 3000
                        }).then(function() {
                            window.location = 'guardSetting.php';
                        });
                    </script>";
            }
        }
        else{
            echo "<script>alert('deactivate failed')</script>";
        }
    }
?>