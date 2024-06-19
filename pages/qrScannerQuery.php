
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VYSMO</title>
    <!-- Favicon -->
    <link href="../images/vysmoprintlogo.png" rel="icon">
    <!--SWEET ALERT 2-->
    <script src="js/nodevtool.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
</head>
<body style="background-color: #cce6ff;">

</body>
</html>

<?php
    session_start();
    include('../database.php');

    $accountID = $_SESSION['accountID'];

    $ScannedBldgQR = $_POST['ScannedBldgQR'];
    $bldgQRData = explode('***', $ScannedBldgQR);

    $destinationID = $bldgQRData[0];
    $keyScan = $bldgQRData[1];
    //echo $keyScan;
    $checkValidBldgQR = mysqli_query($connection,"SELECT destination, typeOfDestination FROM destination_list WHERE destination_id = '$destinationID' AND keyScan = '$keyScan'");
    if(mysqli_num_rows($checkValidBldgQR) > 0){
        
        $destinationAbbrev = mysqli_fetch_array($checkValidBldgQR);

        $checkRequestVisit = mysqli_query($connection, "SELECT * FROM visitation_records WHERE account_id = '$accountID' AND done = 1 AND destination_id = '$destinationID' AND typeOfVisit='Requested Visit'");
        if(mysqli_num_rows($checkRequestVisit) > 0){//merong request

            $updateDoneRecord = "UPDATE visitation_records SET timestampDestination='$currentTime', done = 3 WHERE  done=1 AND destination_id = '$destinationID' AND account_id = '$accountID'";
            if(mysqli_query($connection, $updateDoneRecord)){
                    
                echo
                    "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Welcome to ".$destinationAbbrev[0]." Building',
                            text: 'You have reached your destination',
                            timer: 4000
                        }).then(function() {
                            window.location = 'qrScanner.php';
                        });
                    </script>"; 

            }

        }
        else{
            $urlDestination = base64_encode($destinationID."***".$destinationAbbrev[0]."***".$destinationAbbrev[1]);//type of destination
            echo
                "<script>
                        window.location = 'impromptuVisit.php?encdata=$urlDestination';
                </script>";
        }
    }
    else{
        //invalid
        echo
            "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid',
                    text: 'Please scan the new generated QR-Code',
                    timer: 4000
                }).then(function() {
                    window.location = 'qrScanner.php';
                });
            </script>"; 
    }

?>