<?php
//GUARD
include('../database.php');
include('session.php');

//kapag nakalogged in na then nagtry iredirect sa ibang pages na hindi naman nila role
if($_SESSION['role'] == 'Admin'){
    echo "<script>window.location.href = 'dashboard.php'</script>";
}
elseif($_SESSION['role']  == 'Visitor' || $_SESSION['role']  == 'Guest'){
    echo "<script>window.location.href = 'userProfile.php'</script>";
}

$roleAcc = $_SESSION['role'];
$accountID = $_SESSION['accountID'];

//start sidebar infos
$queryGInfo = "SELECT firstName, middleName, lastName, suffixName, pic2x2 FROM guard_info WHERE account_id='{$accountID}'";
$sqlGInfo = mysqli_query($connection, $queryGInfo);

$arrayGuard = array();

// look through query
while($row = mysqli_fetch_array($sqlGInfo)){
// add each row returned into an array
    $arrayGuard = $row;
}
/*
echo "<pre>";
echo print_r($arrayGuard);
echo "</pre>";
*/

//end sidebar infos

date_default_timezone_set("Asia/Manila");
$dateToday = date("Y-m-d");

//update all visitation that visitation


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>VYSMO</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    
    <script src="js/nodevtool.js"></script>

    <!-- Favicon -->
    <link href="../images/vysmoprintlogo.png" rel="icon">

    <!--camera-->
    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

    <!--SWEET ALERT 2-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>

    
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

    <!-- Template Stylesheet
    <link href="css/style.css" rel="stylesheet"> -->
    <link href="css/style2.php" rel="stylesheet">
    <style>
        .guestName{
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            font-size: 34px;
            font-weight: 400;
            font: bold;
            color: black;
            text-shadow: 40px;
            background-color: #a6a6a6;
        }
    </style>
</head>

<body>

<?php

if(isset($_POST['encryptedQR'])){
    //hbhb
    $encryptedQR = $_POST['encryptedQR'];

    //decrypt qr
    $getAccountQRDB = mysqli_query($connection, "SELECT keyQR, ivQR FROM account_qr WHERE encryptedQrContent = '$encryptedQR'");
    if(mysqli_num_rows($getAccountQRDB) > 0){
    
        while($row = mysqli_fetch_array($getAccountQRDB)){
            $qrInfo = $row;  
        }
        $key = base64_decode($qrInfo[0]);
        $iv = base64_decode($qrInfo[1]);

        $decryptedQR = openssl_decrypt(base64_decode($encryptedQR), 'aes-128-cbc', $key, 0, $iv);
        //end decrypt qr
        $visitorAccountID = $decryptedQR;
        //echo $visitorAccountID;
        
        $sqlAccount = mysqli_query($connection,"SELECT role FROM accounts WHERE account_id = '$visitorAccountID'");
        if(mysqli_num_rows($sqlAccount) > 0){

            while($row = mysqli_fetch_array($sqlAccount)){
                $role = $row;//[0]
            }
            

            if($role[0] == 'Visitor'){ //VISITOR INFORMATION
                $queryVInfo = "SELECT firstName, middleInitial, lastName, suffixName, gender, mobileNumber, houseNumber, street, barangay, city, province, nationality, typeOfID, selfieWithID, frontID, backID FROM visitor_info WHERE account_id='{$visitorAccountID}'";
                $sqlVInfo = mysqli_query($connection, $queryVInfo);
                if(mysqli_num_rows($sqlVInfo) > 0){
                    $array = array();
                    //visitor infos
                    // look through query
                    while($row = mysqli_fetch_array($sqlVInfo)){
                        // add each row returned into an array
                        $array[] = $row;
                    }

                }
                $typeOfVisit = "Requested Visit";
            }
            elseif($role[0] == 'Guest'){
                $queryGuestInfo = "SELECT firstName, middleInitial, lastName, suffixName FROM visitor_info WHERE account_id='{$visitorAccountID}'";
                $sqlGuestInfo = mysqli_query($connection, $queryGuestInfo);
                if(mysqli_num_rows($sqlGuestInfo) > 0){
                    //visitor infos
                    // look through query
                    while($row = mysqli_fetch_array($sqlGuestInfo)){
                        // add each row returned into an array
                        $guestInfo = $row;
                    }

                }
                

                //CHECK IF KIOSK OR REQUESTED VISIT
                $checkTypeOfVisit = mysqli_query($connection, "SELECT * FROM visitation_records WHERE account_id = '$visitorAccountID' AND done = 0 AND typeOfVisit='Requested Visit'");
                if(mysqli_num_rows($checkTypeOfVisit) > 0){
                    $typeOfVisit = "Requested Visit";
                }
                else{
                    $typeOfVisit = "Kiosk Visit";
                }

            }
            else{
                echo "<script>alert('This is not Visitor Account QR Code'); window.location = 'scanAccountQR.php';</script>";
            }
            

            $selectPreviousVisit = mysqli_query($connection, "SELECT DISTINCT visit_timein_university.dateVisit, visitation_records.typeOfForm, visitation_records.destination_id, visitation_records.timestampDestination, visit_timein_university.timestamp, visitation_records.done 
                                                            FROM visitation_records INNER JOIN visit_timein_university ON visitation_records.visitation_id = visit_timein_university.visitation_id 
                                                            WHERE visitation_records.account_id = '$visitorAccountID' AND visitation_records.done != 0 AND visitation_records.done != 1 AND visitation_records.done != 2 AND visitation_records.done != 5 
                                                            ORDER BY visitation_records.visitation_id DESC");
            $numberOfPreviousVisit = mysqli_num_rows($selectPreviousVisit);

            if (mysqli_num_rows($selectPreviousVisit) > 0) {

                $previousVisit = array();
                
                while ($row = mysqli_fetch_array($selectPreviousVisit)) {
                    // Fetch the destinationName based on destination_id
                    $destinationId = $row['destination_id'];
                    $getDestinationName = mysqli_query($connection, "SELECT destinationName FROM destination_list WHERE destination_id = '$destinationId'");
                    $destinationRow = mysqli_fetch_array($getDestinationName);
                    $row['destinationName'] = $destinationRow['destinationName'];
                    
                    // Add the updated row to the $previousVisit array
                    $previousVisit[] = $row;
                }
                /*
                echo "<pre>";
                echo print_r($previousVisit);
                echo "</pre>";
                */
            }

            

            //REQUESTED VISIT DONE
            $selectRequestVisit = mysqli_query($connection, "SELECT visitation_records.visitation_id, visitation_records.numberOfVisitor, visitation_records.typeOfForm, visitation_records.destination_id 
                                                        FROM visitation_records INNER JOIN visit_requested 
                                                        ON visitation_records.visitation_id = visit_requested.visitation_id 
                                                        WHERE visitation_records.account_id = '{$visitorAccountID}' AND visitation_records.done = 0 AND visitation_records.typeOfVisit='$typeOfVisit'");
            $numberOfRequestVisit = mysqli_num_rows($selectRequestVisit);
            //echo "request".$numberOfRequestVisit;
            if (mysqli_num_rows($selectRequestVisit) > 0) {
                $requestVisit = array();
                
                while ($row = mysqli_fetch_array($selectRequestVisit)) {
                    $visitationID = $row['visitation_id'];
                    $numberOfVisitor = $row['numberOfVisitor'];
                    $typeOfForm = $row['typeOfForm'];
                    $destinationID = $row['destination_id'];
                    
                    //appointmentvisit
                    $getAppointmentVisit = mysqli_query($connection, "SELECT appointmentVisit FROM visit_requested WHERE visitation_id = '{$visitationID}'");
                    $appointmentVisitRow = mysqli_fetch_array($getAppointmentVisit);
                    $appointmentVisit = $appointmentVisitRow[0];
                    
                    //destination
                    $getDestination = mysqli_query($connection, "SELECT destination FROM destination_list WHERE destination_id = '{$destinationID}'");
                    $destinationRow = mysqli_fetch_array($getDestination);
                    $destination = $destinationRow[0];
                    
                    //purpose
                    $purpose = "";
                    if ($typeOfForm == 'Individual Person') {
                        $getPersonPurpose = mysqli_query($connection, "SELECT personPurpose FROM visit_person WHERE visitation_id ='{$visitationID}'");
                        $purposeRow = mysqli_fetch_array($getPersonPurpose);
                        $purpose = $purposeRow[0];
                    } elseif ($typeOfForm == 'Office/Building') {
                        $getOfficeBldgPurpose = mysqli_query($connection, "SELECT officeBldgPurpose FROM visit_officebldg WHERE visitation_id ='{$visitationID}'");
                        $purposeRow = mysqli_fetch_array($getOfficeBldgPurpose);
                        $purpose = $purposeRow[0];
                    } elseif ($typeOfForm == 'Event') {
                        $getEventPurpose = mysqli_query($connection, "SELECT eventPurpose FROM visit_event WHERE visitation_id ='{$visitationID}'");
                        $purposeRow = mysqli_fetch_array($getEventPurpose);
                        $purpose = $purposeRow[0];
                    }
                    
                    // Create a subarray with the retrieved data
                    $requestVisit[] = array(
                        'visitation_id' => $visitationID,
                        'numberOfVisitor' => $numberOfVisitor,
                        'typeOfForm' => $typeOfForm,
                        'destination_id' => $destinationID,
                        'appointmentVisit' => $appointmentVisit,
                        'destination' => $destination,
                        'purpose' => $purpose
                    );
                }
                
                /*
                echo "<pre>";
                print_r($requestVisit);
                echo "</pre>";
                */
            }

        }
        else{
            echo "<script>alert('This is Account QR Code does not exist'); window.location = 'scanAccountQR.php';</script>";
        }

    }
    else{
        echo "<script>alert('This is not Account QR Code'); window.location = 'scanAccountQR.php';</script>";
    }
  
}

?>
    <div class="position-relative">
        <!-- Spinner Start -->
        
        <!-- Spinner End -->


        <!-- Sidebar Start -->
        <div class="sidebar pe-0 ">
            <div class="col-sm d-flex justify-content-end mt-2 me-2">
                <button type="button" class="close1" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="row text-center  w-100 p-0 m-0 justify-content-center">
                <div class="col">
                    <img src="../images/vyslogo.png" alt="" id="vysLogoUserProfile">
                </div>
            </div>
            <div class="navbar navbar-light ">
                <div class="row w-100 p-0 m-0 mb-3 justify-content-center">
                    <div class="mb-0 justify-content-center">
                        <div class="col-12">
                            <a class="navbar-brand mx-0 mb-3 ">
                                <h3  id="head" class="text-primary text-center">VYSMO</h3>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row w-100 p-0 m-0 justify-content-center">
                    <div class="mb-4">
                        <div class="col">
                        <div class="row pe-0 justify-content-center">
                            <?php
                                if($roleAcc == 'Admin'){
                            ?>
                                    <img src="../images/adminprofile.png" alt="" style="width: 120px; height: 90px;">
                            <?php
                                }
                                elseif($roleAcc == 'Guard'){
                            ?>
                                  <img class="rounded-circle" src="guard2x2/<?php echo $arrayGuard['pic2x2']?>" alt="" style="width: 120px; height: 90px;">
                            <?php  
                                }
                            ?>
                        </div>
                        <div class="row pe-0 mt-3 justify-content-center">
                                    
                            <?php if(isset($arrayGuard['suffixName'])){ 
                                    if($arrayGuard['suffixName'] == 'Jr' or $arrayGuard['suffixName'] == 'Sr'){
                                            $suffix = $arrayGuard['suffixName'].'.'; 
                                    } 
                                    else{ 
                                        $suffix = $arrayGuard['suffixName'];
                                    } 
                                }
                            ?>
                            <h5 class="mb-0 text-center" id="lastName"><?php echo $arrayGuard['lastName'];?> </h5>
                        </div>
                        <div class="row pe-0 text-center">
                                    
                            <?php if(isset($arrayGuard['suffixName'])){ 
                                    if($arrayGuard['suffixName'] == 'Jr' or $arrayGuard['suffixName'] == 'Sr'){
                                            $suffix = $arrayGuard['suffixName'].'.'; 
                                    } 
                                    else{ 
                                        $suffix = $arrayGuard['suffixName'];
                                    } 
                                }
                            ?>
                            <h6 class="mb-0 text-center" id="firstName"><?php if(isset($arrayGuard['firstName'])){ echo $arrayGuard['firstName'].' '.$arrayGuard['middleName'].' '.$suffix; } ?></h6>
                        </div>
                        <div class="row pe-0 text-center">
                                    
                            <?php if(isset($arrayGuard['suffixName'])){ 
                                    if($arrayGuard['suffixName'] == 'Jr' or $arrayGuard['suffixName'] == 'Sr'){
                                            $suffix = $arrayGuard['suffixName'].'.'; 
                                    } 
                                    else{ 
                                        $suffix = $arrayGuard['suffixName'];
                                    } 
                                }
                            ?>
                            <span class="text-center text-dark" style="color: #5A5A5A;"><?php echo $_SESSION['role']; ?></span>
                        </div>

                        </div>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                <?php
                        echo '
                            <a href="scanAccountQR.php" class="nav-item nav-link active"><i class="bi bi-camera"></i> Scan Account QR</a>
                            <a href="kioskVisit.php" class="nav-item nav-link"><i class="bi bi-table"></i> Kiosk Visit</a>
                            <a href="guardSetting.php" class="nav-item nav-link"><i class="bi bi-gear"></i> Guard Setting</a>
                        ';   
                ?>
                    <a href="logout.php" class="nav-item nav-link "><i class="bi bi-box-arrow-left"></i> Logout</a>
                </div>
            </div>
        </div>
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content pb-4" >

            <form action="scanAccountQR.php" method="POST">
                <input type="hidden" id="encryptedQR" name="encryptedQR" value="<?php if(isset($_POST["encryptedQR"])) { echo $encryptedQR; }?>"> <!--eto yung nakatago na text na paglalagyan ng visitoremail-->
            </form>
                <!-- Add Guard Start -->
                <nav class="navbar navbar-light justify-content-center mb-2 head1">
                    <a href="#" class="sidebar-toggler flex-shrink-0">
                        <i class="fa fa-bars"></i>
                    </a>
                    <div class="position-relative">
                            <img class="rounded-circle guardLogo" src="../images/logo.png" alt="" >
                        </div>
                    <span class="navbar-brand mb-0 h1">Nueva Ecija University of Science and Technology</span>
                    
                </nav>

                    <div class="d-flex justify-content-center mb-2">
                        <video id="QRCode" class="p-0">  </video>
                    </div>

                <div class="container-fluid pt-0 px-4" style="<?php if($role[0] == 'Visitor'){echo 'display: block'; }else{ echo 'display:none'; } ?>">
                    <div class="bg-light rounded itemContain">
                        <div>            
                            <form action="scanAccountQRQuery.php" method="POST">
                                <div class="formContent">
                                    <nav class="navbar navbar-light justify-content-center mb-4 visitInfo">
                                        <span class="navbar-brand mb-0 h1 text-white">Visitor Information</span>
                                    </nav>
                                    <div class="p-4">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="Name">
                                                        <label><strong>Full Name:</strong>  </label>
                                                    </div>
                                                    <div class="column">
                                                        <input type="text" class="form-control" id="fullName" name="fullName" value="<?php if(isset($_POST['encryptedQR'])){ 
                                                            if($array[0]['suffixName'] == 'Jr' or $array[0]['suffixName'] == 'Sr'){ $suffix = $array[0]['suffixName'].'.'; } 
                                                            else{ $suffix = $array[0]['suffixName']; }
                                                            echo $array[0]['firstName']." ".$array[0]['middleInitial']." ".$array[0]['lastName']." ".$suffix; } ?>"disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-auto">
                                                    <div id="gender1">
                                                        <label><strong>Gender:</strong></label>
                                                    </div>
                                                    <div class="column">
                                                        <fieldset class="form-control" id="genderDisable" style=" text-align: center; pointer-events: none;" disabled>
                                                            <input type="radio" name="gender" <?php if(isset($_POST["encryptedQR"])) { if($array[0]['gender'] == 'Male'){ echo "checked"; } }?>>
                                                            <label for="male">Male</label>
                                                            <input type="radio" name="gender" <?php if(isset($_POST["encryptedQR"])) { if($array[0]['gender'] == 'Female'){ echo "checked"; } }?>>
                                                            <label for="female">Female</label>
                                                        </fieldset><br>
                                                    </div>
                                                </div>
                                                <div class="col col-lg-2">
                                                    <div class="num">
                                                        <label class="num"><strong>Mobile Number:</strong></label>
                                                    </div>
                                                    <div class="column">
                                                        <input type="tel" class="form-control" id="num" name="mobileNum" placeholder="09123456789" pattern="[0-9]{11}" value="<?php if(isset($_POST["encryptedQR"])) {echo $array[0]['mobileNumber'];} ?>" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <div class="form-group">
                                                        <label for="nationality"><strong>Nationality</strong></label>
                                                        <select class="form-control" name="nationality" disabled>
                                                            <option value=""><?php if(isset($_POST["encryptedQR"])) { echo $array[0]['nationality']; } ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="address">
                                                        <label><strong>Address:</strong></label>
                                                    </div>
                                                    <div class="col">
                                                        <input type="text" class="form-control" name="address" id="address" value="<?php if(isset($_POST["encryptedQR"])) { echo "#".$array[0]['houseNumber']." ".$array[0]['street'].", Brgy. ".$array[0]['barangay'].", ".$array[0]['city'].", ".$array[0]['province']; } ?>" disabled><br>                        
                                                    </div>
                                                </div>

                                                <script>
                                                    // Function to handle the dropdown box change event
                                                    window.onload = function() {
                                                        var selection = document.getElementById("typeOfID").value;

                                                        // Show or hide content based on the selection
                                                        var landscapeID = document.getElementById("landscapeID");
                                                        var landscapeIDback = document.getElementById("landscapeIDback");
                                                        var portraitID = document.getElementById("portraitID");
                                                        var portraitIDback = document.getElementById("portraitIDback");
                                                        if (selection === "School ID") {
                                                            landscapeID.style.display = "none";
                                                            landscapeIDback.style.display = "none";
                                                            portraitID.style.display = "block";
                                                            portraitIDback.style.display = "block";
                                                        } else{
                                                            landscapeID.style.display = "block";
                                                            landscapeIDback.style.display = "block";
                                                            portraitID.style.display = "none"; 
                                                            portraitIDback.style.display = "none";
                                                        }
                                                    }
                                                </script>

                                                <div class="col-md-auto">
                                                    <label for="typeOfID"><strong>Type of ID:</strong></label>
                                                    <select id="typeOfID" class="form-control" disabled>
                                                        <option selected value="<?php if(isset($_POST["encryptedQR"])) { echo $array[0]['typeOfID']; } ?>"><?php if(isset($_POST["encryptedQR"])) { echo $array[0]['typeOfID']; } ?></option>
                                                    </select><br>
                                                </div>
                                            </div>
                                        
                                        
                        
                                            <div class="row justify-content-center">
                                                <div class="col-sm" id="landscapeID">
                                                    <div class="frontID">
                                                        <label class="frontID"><strong>Front ID:</strong></label>
                                                    </div>
                                                    <div class="column">
                                                        <img id="validFrontID"  src="<?php if(isset($_POST["encryptedQR"])) {echo "../registration/frontID/".$array[0]['frontID']; } else{ echo "../images/frontLandscapeID.png"; } ?>" data-original="../images/frontLandscapeID.png" alt="sample"  >
                                                    </div>
                                                </div>
                                                <div class="col-sm" id="landscapeIDback">
                                                    <div class="backID">
                                                        <label class="backID "><strong>Back ID:</strong></label>
                                                    </div>
                                                    <div class="column">
                                                        <img id="validBackID"  src="<?php if(isset($_POST["encryptedQR"])) {echo "../registration/backID/".$array[0]['backID']; } else{ echo "../images/backLandscapeID.png"; } ?>" data-original="../images/backLandscapeID.png" alt="sample" >
                                                    </div>
                                                </div>
                                                <div class="col-sm" id="portraitID" >
                                                    <div class="frontID">
                                                        <label class="frontID"><strong>Front ID:</strong></label>
                                                    </div>
                                                    <div class="column">
                                                        <img id="validFrontIDportrait"  src="<?php if(isset($_POST["encryptedQR"])) {echo "../registration/frontID/".$array[0]['frontID']; } else{ echo "../images/frontPortraitID.png"; } ?>" data-original="../images/frontPortraitID.png" alt="sample"  >
                                                    </div>
                                                </div>
                                                <div class="col-sm" id="portraitIDback" >
                                                    <div class="backID">
                                                        <label class="backID "><strong>Back ID:</strong></label>
                                                    </div>
                                                    <div class="column">
                                                        <img id="validBackIDportrait"  src="<?php if(isset($_POST["encryptedQR"])) {echo "../registration/backID/".$array[0]['backID']; } else{ echo "../images/backPortraitID.png"; } ?>" data-original="../images//backPortraitID.png" alt="sample" >
                                                    </div>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="backID">
                                                        <label class="backID "><strong>Selfie With ID:</strong></label>
                                                    </div>
                                                    <div class="column">
                                                        <img id="selfie"  src="<?php if(isset($_POST["encryptedQR"])) {echo "../registration/selfie/".$array[0]['selfieWithID']; } else{ echo "../images/selfiewithID.png"; } ?>" data-original="../images/selfiewithID.png" alt="sample" >
                                                    </div>
                                                </div>
                                
                                            </div> 
                                    </div>
                                </div>
                            
                        </div>

                    </div>
                </div>
            <!-- START VISITATION INFO -->
                <div class="container-fluid pt-4 px-4 ">
                    <div class="bg-light rounded pb-4 itemContain">
                            <div class="formContent">
                                    <nav class="navbar navbar-light justify-content-center mb-4 visitInfo">
                                        <span class="navbar-brand mb-0 h1 text-white">Visitation Information</span>
                                    </nav>
                                    <?php

                                        if( isset($role[0]) && $role[0] == 'Guest'){
                                            if(isset($guestInfo['suffixName'])){ 
                                                if($guestInfo['suffixName'] == 'Jr' or $guestInfo['suffixName'] == 'Sr'){
                                                        $suffix = $guestInfo['suffixName'].'.'; 
                                                } 
                                                else{ 
                                                    $suffix = $guestInfo['suffixName'];
                                                } 
                                            }

                                    ?>    
                                            <div class="row justify-content-center mt-0">
                                                <div class="col-auto">
                                                    <span class="text-center m-0 guestName shadow-lg rounded p-2" >Guest Name: <i><?php echo $guestInfo['lastName'] . ", " . $guestInfo['firstName'] . " " . $suffix ?></i></span>
                                                </div>
                                            </div> 

                                            <!--<label for="">Guest Name:</label>
                                            <label for=""> <?php echo $guestInfo['lastName'] . ", " . $guestInfo['firstName'] . " " . $suffix ?> </label>-->
                                    
                                    <?php
                                        }
                                    ?>



<form action="scanAccountQRQuery.php" method="POST">
                                    <?php
                                        if(isset($numberOfRequestVisit)){
                                            if($numberOfRequestVisit > 0){

                                    ?>
                                    
                                    <div class="row d-flex justify-content-center mt-5">
                                        <div class="row col-auto requestVisit  d-flex align-item-center">
                                            <h2 class="text-center m-0"> <?php echo $typeOfVisit;?> </h2>
                                        </div>
                                    </div>   

                                    <input type="hidden" id="numberOfRequestedVisit" name="numberOfRequestedVisit" value="<?php echo $numberOfRequestVisit; ?>" >
                                    <div class="row justify-content-center m-0 p-3">
                                        <div class="col rounded tableRequestScan p-0">                                            
                                            <div class="table-responsive" id="table1" >
                                                <table class="table text-start align-middle table-bordered table-hover mb-0">
                                                    <thead class="sticky-top text-center" style="z-index:2">
                                                        <tr class="text-dark bg-light">
                                                            <th scope="col">   
                                                                <input type="checkbox" class="form-check-input justify-content-center" id="selectAll" name="selectAll" onclick="toggle(this);"data-toggle="tooltip" data-placement="top" title="Select All" >
                                                            </th>
                                                            <th scope="col"># of Visitor</th>
                                                            <th scope="col">Type of Form</th>
                                                            <th scope="col">Destination</th>
                                                            <th scope="col">Purpose of the Visit</th>
                                                            <th scope="col">Appointment Visit</th>
                                                            <th scope="col">Reject Reason</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody  id="tDataRequestedVisit" class="text-center">  
                                                        <?php
                                                            for($i=0; $i < $numberOfRequestVisit; $i++){
                                                            
                                                        ?> <input type="hidden" name="visitationID<?php echo $i;?>" value="<?php echo $requestVisit[$i]['visitation_id'];?>">
                                                            <tr>
                                                                <td>
                                                                    <input type="checkbox" class="form-check-input justify-content-center" name="selectVisit<?php echo $i;?>" id="selectVisit<?php echo $i;?>">
                                                                </td>
                                                                <td class="col-1"> <input type="number" name="numberOfVisitor<?php echo $i; ?>" class="col-7" min="1" style="text-align:center" value="<?php echo $requestVisit[$i]['numberOfVisitor'];?>"> </td>
                                                                <td class="col-auto"><?php echo $requestVisit[$i]['typeOfForm'];?></td>
                                                                <td class="col-auto"><?php echo $requestVisit[$i]['destination'];?></td>
                                                                <td class="col-2"> <textarea style="resize:none; text-align:center;" rows="2" disabled><?php echo $requestVisit[$i]['purpose'];?></textarea></td>
                                                                <td class="col-auto"><?php echo date("F j, Y", strtotime($requestVisit[$i]['appointmentVisit']));?></td>
                                                                <td class="col-2"> <textarea name="rejectReason<?php echo $i;?>" id="rejectReason<?php echo $i;?>" style="resize:none; text-align: center;" rows="2" required></textarea> </td>
                                                            </tr>
                                                        <?php
                                                            }
                                                        ?>       
                                                        <script>
                                                            var numberOfRequestedVisit = document.getElementById("numberOfRequestedVisit");
                                                            var selectAllCheckbox = document.getElementById("selectAll");
                                                            selectAllCheckbox.addEventListener("change", function () {
                                                                if(numberOfRequestedVisit.value == 0){
                                                                    Swal.fire({
                                                                        icon: 'info',
                                                                        title: 'No Visitation Data',
                                                                        timer: 3500
                                                                    });
                                                                    this.checked = false;
                                                                }
                                                                else{
                                                                    for (var i = 0; i < numberOfRequestedVisit.value; i++) {
                                                                        var textbox = rejectReasons["rejectReason" + i]; // Get the corresponding textbox
                                                                    
                                                                        if (this.checked) {
                                                                            textbox.removeAttribute("required");
                                                                            textbox.value = '';
                                                                            textbox.disabled = true;
                                                                        } else {
                                                                            textbox.setAttribute("required", "required");
                                                                            textbox.value = '';
                                                                            textbox.disabled = false;
                                                                        }
                                                                    }
                                                                }
                                                            });

                                                            var rejectReasons = {};

                                                            for (var i = 0; i < numberOfRequestedVisit.value; i++) {
                                                                var elementId = "rejectReason" + i;
                                                                rejectReasons[elementId] = document.getElementById(elementId);
                                                            }

                                                            for (var i = 0; i < numberOfRequestedVisit.value; i++) {
                                                                var checkboxId = "selectVisit" + i;
                                                                var checkbox = document.getElementById(checkboxId);

                                                                checkbox.addEventListener("change", function () {
                                                                    var index = this.id.replace("selectVisit", ""); // Get the index from the checkbox ID
                                                                    var textbox = rejectReasons["rejectReason" + index]; // Get the corresponding textbox

                                                                    if (this.checked) {
                                                                        textbox.removeAttribute("required");
                                                                        textbox.value = '';
                                                                        textbox.disabled = true; // This disables the textarea
                                                                    } else {
                                                                        textbox.setAttribute("required", "required");
                                                                        textbox.value = '';
                                                                        textbox.disabled = false; // This enables the textarea
                                                                    }
                                                                });
                                                            }

                                                        </script>                                           
                                                        
                                                    </tbody>
                                                </table>
                                            </div>                                  
                                        </div>
                                    </div> 
                                    <input type="hidden" name="visitorAccountID" value="<?php echo $visitorAccountID;?>">
                                    <input type="hidden" name="totalRequestVisit" value="<?php echo $numberOfRequestVisit;?>">
                                    <div class="mt-3 ">
                                        <div class="col-auto d-flex justify-content-center">
                                            <button type="submit" name="timeInApproveVisit" class="btn btn-primary timeInAccount">Time In</button>              
                                        </div>
                                    </div> 
                                    
                                    <?php
                                            }
                                            
                                            else{
                                                echo 
                                                    "<script>
                                                        Swal.fire({
                                                            icon: 'warning',
                                                            title: 'No Visitation Request $typeOfVisit',
                                                            text: 'This Visitor does not have request visit',
                                                            timer: 4000
                                                        }).then(function() {
                                                            window.location = 'scanAccountQR.php';
                                                        });
                                                    </script>"; 
                                            }
                                            
                                        }
                                    ?> 
                                </form>



                                    <input type="hidden" id="numberOfPreviousVisit" name="numberOfPreviousVisit" value="<?php echo $numberOfPreviousVisit; ?>" >
                                    <?php  
                                        if(isset($numberOfPreviousVisit)){
                                            if($numberOfPreviousVisit > 0){
                                        
                                        
                                    ?>
                                            <div class="row d-flex justify-content-center mt-5" >
                                                <div class="row col-auto todayHead  d-flex align-item-center">
                                                    <h2 class="text-center m-0" >Previous Visit</h2>
                                                </div>
                                            </div>   

                                            <div class="row justify-content-center m-0 p-3">
                                                <div class="col rounded tableApproveScan p-0">                                            
                                                    <div class="table-responsive" id="table1" >
                                                        <table class="table text-start align-middle table-bordered table-hover mb-0">
                                                            <thead class="sticky-top text-center" style="z-index:2">
                                                                <tr class="text-dark bg-light">
                                                                    <th scope="col">Date Visit</th>
                                                                    <th scope="col">Type of Form</th>
                                                                    <th scope="col">Destination</th>
                                                                    <th scope="col">University</th>
                                                                    <th scope="col">Destination Time-in</th>
                                                                    <th scope="col">Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody  id="tDataPreviousData" class="text-center"> 
                                                                <?php
                                                                    for($i=0; $i < $numberOfPreviousVisit; $i++){
                                                                ?>
                                                                        <tr>
                                                                            <td class="col-2"><?php echo date("F j, Y", strtotime($previousVisit[$i]['dateVisit']));?></td>
                                                                            <td class="col-auto"><?php echo $previousVisit[$i]['typeOfForm'];?></td>
                                                                            <td class="col-auto"><?php echo $previousVisit[$i]['destinationName'];?></td>
                                                                            <td class="col-auto"><?php echo $previousVisit[$i]['timestamp'];?></td>
                                                                            <td class="col-auto"><?php echo $previousVisit[$i]['timestampDestination'];?></td>
                                                                            <td class="col-1"> 
                                                                                <?php 
                                                                                    if($previousVisit[$i]['done'] == 3){//success
                                                                                ?>
                                                                                        <span class="btn btn-success">Success</span>
                                                                                <?php
                                                                                    }
                                                                                    elseif($previousVisit[$i]['done'] == 4){//invalid
                                                                                ?>
                                                                                        <span class="btn btn-danger">Invalid</span>
                                                                                <?php
                                                                                    }
                                                                                ?>
                                                                            </td>
                                                                        </tr>
                                                                <?php
                                                                    }
                                                                ?>                                                       
                                                                
                                                            </tbody>
                                                        </table>
                                                    </div>                                  
                                                </div>
                                            </div>
                                    <?php
                                            }
                                        }
                                    ?>
                                    


                            </div>
                    </div>
                </div>
           <!-- END VISITATION INFO -->


        </div>
        <!-- Content End -->


    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Javascript -->
    <script src="js/main.js"></script>
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
        let encryptedQR = document.getElementById('encryptedQR').value=qrContents;
        document.forms[0].submit(); 

    });
   

</script>


