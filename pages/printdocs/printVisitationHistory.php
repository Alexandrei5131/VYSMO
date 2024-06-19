<?php

    include('../../database.php');

    session_start();
    if(isset($_SESSION['statpage'])){
        if ($_SESSION['statpage'] == 'invalid' || empty($_SESSION['statpage'])) {
            /* Set status to invalid */
            $_SESSION['statpage'] = 'invalid';
    
            unset($_SESSION['accountID']);
            unset($_SESSION['email']);
            unset($_SESSION['role']);
            unset($_SESSION['accountQR']);
            
            echo "<script>window.location.href = '../../'</script>";
    
        }
        else{//meaning valid
            //kapag nakalogged in na then nagtry iredirect sa ibang pages na hindi naman nila role
            if($_SESSION['role']  == 'Guard'){
                echo "<script>window.location.href = '../scanAccountQR.php'</script>";
            }
            elseif($_SESSION['role']  == 'Visitor' || $_SESSION['role']  == 'Guest'){
                echo "<script>window.location.href = '../userProfile.php'</script>";
            }
            //end
        }
    }

    $fromDate = $_GET['fromDate'];
    $toDate = $_GET['toDate'];

    $fromDateFormat = date("F j, Y", strtotime($fromDate));
    $toDateFormat = date("F j, Y", strtotime($toDate));
    
    $sqlGetDoneVisit = mysqli_query($connection, "SELECT visitation_records.visitation_id, visitation_records.account_id, visitation_records.typeOfForm, visitation_records.destination_id, visitation_records.numberOfVisitor, visitation_records.timestampDestination 
                                        FROM visitation_records INNER JOIN visit_timein_university 
                                        ON visitation_records.visitation_id = visit_timein_university.visitation_id 
                                        WHERE done = 3 AND DATE(visit_timein_university.dateVisit) >= DATE('$fromDate') AND DATE(visit_timein_university.dateVisit) <= DATE('$toDate')");
    
    if (mysqli_num_rows($sqlGetDoneVisit) > 0) {
        $numberOfDoneVisit = mysqli_num_rows($sqlGetDoneVisit);
        $allvisitation = array();

        while ($row = mysqli_fetch_array($sqlGetDoneVisit)) {
            $visitationID = $row['visitation_id'];

            // Retrieve dateVisit and timestamp from visit_timein_university
            $getTimeinInfo = mysqli_query($connection, "SELECT dateVisit, timestamp FROM visit_timein_university WHERE visitation_id = '$visitationID'");
            
            // Initialize arrays to store dateVisit and timestamp values

            while ($timeinRow = mysqli_fetch_array($getTimeinInfo)) {
                // Add dateVisit and timestamp values to their respective arrays
                $dateVisit = $timeinRow['dateVisit'];
                $timestamp = $timeinRow['timestamp'];
            }

            // Retrieve and set the destination name
            $destinationID = $row['destination_id'];
            $getDestinationName = mysqli_query($connection, "SELECT destination FROM destination_list WHERE destination_id = '$destinationID'");
            $destinationRow = mysqli_fetch_array($getDestinationName);
            $row['destination'] = $destinationRow['destination'];

            // Add dateVisit and timestamp arrays to the row
            $row['dateVisit'] = $dateVisit;
            $row['timestamp'] = $timestamp;

            // Add the row to the result array
            $allvisitation[] = $row;
        }

        $totalVisitor = 0;
        $lastDateVisit = null;
        $lastTimestamp = null;
        $lastAccountID = null;

        for ($i = 0; $i < $numberOfDoneVisit; $i++) {
            $dateVisit = $allvisitation[$i]['dateVisit'];
            $timestamp = $allvisitation[$i]['timestamp'];
            $accountID = $allvisitation[$i]['account_id'];

            // Check if the current interval matches the last interval
            if (
                is_null($lastDateVisit) ||
                $accountID != $lastAccountID ||
                $dateVisit != $lastDateVisit ||
                $timestamp != $lastTimestamp
            ) {
                // If it doesn't match, add the numberOfVisitor to the total
                $totalVisitor += $allvisitation[$i]['numberOfVisitor'];
            }

            // Update the last interval
            $lastDateVisit = $dateVisit;
            $lastTimestamp = $timestamp;
            $lastAccountID = $accountID;
        }
    }

    /*
    echo "<pre>";
    echo print_r($allvisitation);
    echo "</pre>";
    */


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Report Printing</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!--SWEET ALERT 2-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
    
    <!-- Favicon -->
    <link href="../../images/vysmoprintlogo.png" rel="icon">

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
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../css/style.css" rel="stylesheet">
    <style>
        @media print {
            thead {
                font-size: 10pt;
            }
            tbody{
                font-size: 10pt;

            }
            

        }
        @page 
        {
            margin-top: 8mm;  /* this affects the margin in the printer settings */
            margin-bottom: 0mm;  /* this affects the margin in the printer settings */

        }

        .totalVisitHeader{
        font-size: 18px;
        font-weight: bold;
        color: black;
    }
    
    </style>
</head>
<body class="d-flex justify-content-center pb-3" style="background-color: #cce6ff;">
    <div class="position-relative">
        <div class="contentPrintQR">
            <div class="container-fluid">
                <div class="row d-flex justify-content-center mt-3">
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary print-button" onclick="printContent()">Print Report</button>
                    </div>

                </div>

                <div class="printable "><!-- Your content inside the printable area -->
                    <div class="row justify-content-center align-items-center pb-4 px-5 pt-3 mx-0 border border-dark border-start-0 border-end-0 border-top-0" id="header">
                        
                        <div class="col-1 p-0 logoQRBorder   align-middle">
                            <img src="../../images/logo.png" alt="" class="logoQR">
                        </div>

                        <div class="col ">
                            <div class="text-center">
                                <span class="headerTitle">Republic of the Philippines</span>
                            </div>
                            <div class="text-center">
                                <h5 class="headerTitle">Nueva Ecija University of Science and Technology</h5>
                            </div>
                            <div class="text-center">
                                <span class="headerTitle">Cabanatuan City, Nueva Ecija</span>
                            </div>
                        </div>
                        <div class="col-1 vysLogoBorder  p-0">
                            <img src="../../images/vysmoprintlogo.png" class="vysLogo" alt="" id="">
                        </div>
                        
                    </div>
                    <div class="row justify-content-center m-0 mt-1 ">
                        <div class="col-auto ">   
                        <span class="totalVisitHeader">This is a <i>NEUST-Visitor Monitoring System</i> generated report. Signature is not required.</span>
                        </div>
                    </div>
                    <div class="row justify-content-center m-0 mt-3">
                        <div class="col-auto ">   
                            <span class="totalVisitHeader"><i>From: <?php echo $fromDateFormat; ?> To: <?php echo $toDateFormat; ?></i></span>
                        </div>
                    </div>
                    <div class="row justify-content-center m-0">
                        <div class="col-auto ">   
                            <span class="totalVisitHeader">Total Number of Visitors: <?php echo $totalVisitor;?></span>
                        </div>
                    </div>
                    <div id="content" class="px-3">
                        
                    <div class="row ">
                            <div class="col ">
                                <div class="table-responsive  mt-3" id="downloadReportTable" >
                                    <table class="table text-center align-middle table-bordered table-hover mb-0">
                                        <thead class="sticky-top" style="z-index:2">
                                            <tr class="text-dark" >
                                                <th scope="col" class="col-auto">Name of Visitor</th>
                                                <th scope="col"class="col-auto">Guard on Duty</th>
                                                <th scope="col"class="col-auto">Type of Form</th>
                                                <th scope="col"class="col-auto">Destination</th>
                                                <th scope="col"class="col-auto">#Visitor</th>
                                                <th scope="col"class="col-auto">Date Visit</th>
                                                <th scope="col"class="col-auto">Time In University</th>
                                                <th scope="col"class="col-auto">Time In Destination</th>
                                            
                                            </tr>
                                        </thead>
                                        <tbody  id="tDataPrintVisitationHistory">
                                            <?php
                                            for($i=0; $i < $numberOfDoneVisit; $i++){
                                                //getname of visitor
                                                //getname of visitor
                                                $sqlGetVisitorName = mysqli_query($connection, "SELECT firstName, middleInitial, lastName, suffixName FROM visitor_info WHERE account_id = '".$allvisitation[$i]['account_id']."'");
                                                if(mysqli_num_rows($sqlGetVisitorName) > 0){
                                                    $visitorFullName = mysqli_fetch_array($sqlGetVisitorName);

                                                    /*
                                                    echo "<pre>";
                                                    echo print_r($visitorFullName);
                                                    echo "</pre>";
                                                    */

                                                    //getname of guard timein   
                                                    if ($visitorFullName['suffixName'] == "Jr" || $visitorFullName['suffixName'] == "Sr") {
                                                        $suffixName =  $visitorFullName['suffixName'] . '.';

                                                    } else {
                                                        $suffixName = $visitorFullName['suffixName'];
                                                    }
                                                }

                                                
                                                //TIME IN UNIV TABLES
                                                $sqlVisitTimeIn = mysqli_query($connection, "SELECT guard_account_id, dateVisit, timestamp FROM visit_timein_university WHERE visitation_id = '".$allvisitation[$i]['visitation_id']."'");
                                                if(mysqli_num_rows($sqlVisitTimeIn) > 0){
                                                    $visitTimeIn = mysqli_fetch_array($sqlVisitTimeIn);
                                                    /*
                                                    echo "<pre>";
                                                    echo print_r($visitTimeIn);
                                                    echo "</pre>";
                                                    */
                                                }   

                                                

                                                //GUARD FULL NAME
                                                $sqlGuardFullName = mysqli_query($connection, "SELECT firstName, middleName, lastName, suffixName FROM guard_info WHERE account_id = '".$visitTimeIn['guard_account_id']."'");
                                                $guardFullName = mysqli_fetch_array($sqlGuardFullName);
                                                
                                                /*
                                                echo "<pre>";
                                                echo print_r($guardFullName);
                                                echo "</pre>";
                                                */

                                                if ($guardFullName['suffixName'] == "Jr" || $guardFullName['suffixName'] == "Sr") {
                                                    $suffixNameG =  $guardFullName['suffixName'] . '.';

                                                } else {
                                                    $suffixNameG = $guardFullName['suffixName'];
                                                }


                                                /*
                                                echo "<pre>";
                                                echo print_r($visitDestination);
                                                echo "</pre>";
                                                */


                                            ?>
                                                
                                            <tr>
                                            <td class="col">
                                                    <?php 
                                                        if($visitorFullName['firstName'] == ''){
                                                            echo 'Guest Visitor';
                                                        }else{
                                                            echo $visitorFullName['firstName'] . ' ' . $visitorFullName['middleInitial'] . ' ' . $visitorFullName['lastName'] . ' ' . $suffixName; 
                                                        }
                                                    ?>
                                                </td>
                                                <td class="col">
                                                    <?php 
                                                        echo $guardFullName['lastName'] . ', '. $guardFullName['firstName'] . ' ' . $guardFullName['middleName'] . ' ' . $suffixNameG;  
                                                    ?>
                                                </td>
                                                <td><?php echo $allvisitation[$i]['typeOfForm']; ?></td>
                                                <td><?php echo $allvisitation[$i]['destination'];?></td>
                                                <td><?php echo $allvisitation[$i]['numberOfVisitor']; ?></td>
                                                <td class="col">
                                                    <?php 
                                                        echo date("F j, Y", strtotime($visitTimeIn['dateVisit'])); 
                                                    ?>
                                                </td>
                                                <td><?php echo $visitTimeIn['timestamp']; ?></td>
                                                <td><?php echo $allvisitation[$i]['timestampDestination'];?></td>
                                            </tr>
                                            
                                            
                                            
                                            

                                            <?php
                                                }
                                            ?>

                                            <!--
                                            <tr>
                                                <td>Christopher Jay S. Manubay</td>
                                                <td>Kathlyn May M. Pineda</td>
                                                <td>Individual Person</td>
                                                <td>CICT</td>
                                                <td>3</td>
                                                <td>september 23, 2021</td>
                                                <td>9:03 AM</td>
                                                <td>10:03 AM</td>
                                                <td>11:03 AM</td>
                                            </tr>

                                             -->

                                        </tbody>
                                    </table>                                           

                                </div>
                            </div>
                        </div>
                    </div>

                    

                </div>

            </div>
        </div>
    </div>


    <script>
        function printContent() {
            // Hide the print button
            document.querySelector('.print-button').style.display = 'none';
            document.querySelector('.printable').style.border = 'none';
            // Trigger the print dialog
            window.print();

        }

        window.onafterprint = function () {
            closeWindow();
        };

        function closeWindow() {
            document.querySelector('.print-button').style.display = 'block';
        }
    </script>
        <!-- JavaScript Libraries -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Javascript -->
    <script src="../js/main.js"></script>
</body>
</html>
