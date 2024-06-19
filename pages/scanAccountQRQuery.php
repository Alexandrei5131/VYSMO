<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Favicon -->
    <link href="../images/vysmoprintlogo.png" rel="icon">
    
    <script src="js/nodevtool.js"></script>
    <!--SWEET ALERT 2-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
</head>
<body style="background-color: #cce6ff;">

</body>
</html>

<?php

    session_start();
    include('../database.php');


    if(isset($_POST['timeInApproveVisit'])){

        $guardAccountID = $_SESSION['accountID'];
        $visitorAccountID = $_POST['visitorAccountID'];

        date_default_timezone_set("Asia/Manila");
        
        $timeIn = date('h:i A', time());

        $totalRequestVisit = $_POST['totalRequestVisit'];

        
            for($i=0; $i < $totalRequestVisit; $i++){
                $visitationID = $_POST['visitationID'.$i];
                $numberOfVisitor = $_POST['numberOfVisitor'.$i];
                
                $insertTimeInUniv = "INSERT INTO visit_timein_university (visitation_id, visitor_account_id, dateVisit, timestamp, guard_account_id) VALUE ('$visitationID', '$visitorAccountID', '$dateToday', '$timeIn', '$guardAccountID')";
                if(mysqli_query($connection, $insertTimeInUniv)){
                    
                    if(isset($_POST['selectVisit'.$i])){//approved
                        //timein na ni guard
                        $updateDoneVisitationRecord = mysqli_query($connection,"UPDATE visitation_records SET numberOfVisitor = '$numberOfVisitor', done = 1 WHERE visitation_id = '$visitationID'");
                        
                    }
                    else{//reject
                        
                        $updateDoneRejectVisit = "UPDATE visitation_records SET done = 2 WHERE visitation_id = '$visitationID'";
                        if(mysqli_query($connection,$updateDoneRejectVisit)){
                            
                            $rejectReason = $_POST['rejectReason'.$i];
                            $updateRejectReason = mysqli_query($connection, "UPDATE visit_requested SET rejectReason = '$rejectReason' WHERE visitation_id = '$visitationID'");

                        }
                            
                    }
                    
                    echo
                        "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Confirmed Successfully',
                                timer: 4000
                            }).then(function() {
                                window.location = 'scanAccountQR.php';
                            });
                        </script>"; 

                }
            }
        }

    


    /*
    echo "Visitor Email: " . $visitorEmail . "<br>";
    echo "Date Today Time: " . $dateToday . "<br>";
    echo "Time In: " . $timeIn . "<br>";
    echo "Account Email: " . $accountEmail . "<br>";
    */
    
    /*
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Iterate through the $_POST array
        foreach ($_POST as $key => $value) {
            // Echo the key (field name) and its corresponding value
            echo "Field: " . htmlspecialchars($key) . "<br>";
            echo "Value: " . htmlspecialchars($value) . "<br>";
            echo "<hr>"; // Add a horizontal line to separate fields
        }
    } else {
        echo "No POST data submitted.";
    }
    */
?>