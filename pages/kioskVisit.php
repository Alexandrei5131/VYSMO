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
    $sqlGInfo = mysqli_query($connection, "SELECT firstName, middleName, lastName, suffixName, pic2x2 FROM guard_info WHERE account_id='{$accountID}'");
    if(mysqli_num_rows($sqlGInfo) > 0){
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

    }

    //end sidebar infos

    date_default_timezone_set("Asia/Manila");
    $dateToday = date("Y-m-d");

    $getApprovedKioskVisit = mysqli_query($connection,"SELECT * FROM visitation_records WHERE typeOfVisit = 'Kiosk Visit' AND done = 1");
    $numOfAKiosk = mysqli_num_rows($getApprovedKioskVisit);
    if(mysqli_num_rows($getApprovedKioskVisit) > 0){
        $arrayAKiosk = array();
        // look through query
        while($row = mysqli_fetch_array($getApprovedKioskVisit)){
        // add each row returned into an array
            $destinationID = $row['destination_id'];
            $getDestinationName = mysqli_query($connection, "SELECT destination FROM destination_list WHERE destination_id = '$destinationID'");
            $destinationRow = mysqli_fetch_array($getDestinationName);
            $row['destination'] = $destinationRow['destination'];

            $arrayAKiosk[] = $row;
        }
        

    }
    // echo "<pre>";
    // echo print_r($arrayAKiosk);
    // echo "</pre>";

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

    <!--SWEET ALERT 2-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>

    
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />


    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet
    <link href="css/style.css" rel="stylesheet"> -->
    <link href="css/style2.php" rel="stylesheet">

    <!-- WHEN OUTSIDE MODAL CLICKED -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="position-relative">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
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
                    if($_SESSION['role'] == 'Guard'){
                        echo '
                            <a href="scanAccountQR.php" class="nav-item nav-link "><i class="bi bi-camera"></i> Scan Account QR</a>
                            <a href="kioskVisit.php" class="nav-item nav-link active"><i class="bi bi-table"></i> Kiosk Visit</a>
                            <a href="guardSetting.php" class="nav-item nav-link "><i class="bi bi-gear"></i> Guard Setting</a>
                        ';
                    }        
                ?>
                    <a href="logout.php" class="nav-item nav-link "><i class="bi bi-box-arrow-left"></i> Logout</a>
                </div>
                </div>
        </div>
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content" >
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

                <div class="container-fluid bodyContent mt-3 col">
                    <div class="bg-light text-center rounded">
                        <nav class="navbar navbar-light justify-content-center visitInfo">
                            <span class="navbar-brand mb-0 h1 text-white">Kiosk Visit Requests</span>
                        </nav>
                        <div class="p-4">
                            <div class="row">
                                <div class="col">
                                    <div class="table-responsive" id="tableVisitHistory" >
                                        <table class="table text-start align-middle table-striped table-hover mb-0 shadow p-3 rounded">
                                            <thead class="sticky-top text-center shadow p-3 mb-5 rounded" style="z-index: 2;">
                                                <tr class="text-dark">  
                                                    <th scope="col">Name of Visitor</th>
                                                    <th scope="col">Type of Form</th>
                                                    <th scope="col">Destination</th>
                                                    <th scope="col">No. of Visitor</th>
                                                    <th scope="col">Time In University</th>
                                                    <th scope="col">Time In Destination</th>
                                                    <th scope="col"></th>
                                                </tr>
                                            </thead>
                                            
                                                <tbody  id="tDataKiosk" class="text-center">
                                                    <?php
                                                        for($i=0; $i < $numOfAKiosk; $i++){
                                                                //getname of visitor
                                                                $sqlGetVisitorName = mysqli_query($connection, "SELECT firstName, middleInitial, lastName, suffixName FROM visitor_info WHERE account_id = '".$arrayAKiosk[$i]['account_id']."'");
                                                                if(mysqli_num_rows($sqlGetVisitorName) > 0){
                                                                    $visitorFullName = mysqli_fetch_array($sqlGetVisitorName);

                                                                    // echo "<pre>";
                                                                    // echo print_r($visitorFullName);
                                                                    // echo "</pre>";

                                                                    //getname of guard timein   
                                                                    if ($visitorFullName['suffixName'] == "Jr" || $visitorFullName['suffixName'] == "Sr") {
                                                                        $suffixName =  $visitorFullName['suffixName'] . '.';
                
                                                                    } else {
                                                                        $suffixName = $visitorFullName['suffixName'];
                                                                    }
                                                                }
                                                                
                                                                //TIME IN UNIV TABLES
                                                                $sqlVisitTimeIn = mysqli_query($connection, "SELECT guard_account_id, dateVisit, timestamp FROM visit_timein_university WHERE visitation_id = '".$arrayAKiosk[$i]['visitation_id']."'");
                                                                if(mysqli_num_rows($sqlVisitTimeIn) > 0){
                                                                    $visitTimeIn = mysqli_fetch_array($sqlVisitTimeIn);
                                                                    
                                                                    // echo "<pre>";
                                                                    // echo print_r($visitTimeIn);
                                                                    // echo "</pre>";
                                                                    
                                                                }   

                                                    ?>

                                                            <tr>
                                                                <td class="col-3">
                                                                    <?php 
                                                                        if($visitorFullName['firstName'] == ''){
                                                                            echo 'Guest Visitor';
                                                                        }else{
                                                                            echo $visitorFullName['firstName'] . ' ' . $visitorFullName['middleInitial'] . ' ' . $visitorFullName['lastName'] . ' ' . $suffixName; 
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td><?php echo $arrayAKiosk[$i]['typeOfForm'];?></td>
                                                                <td><?php echo $arrayAKiosk[$i]['destination'];?></td>
                                                                <td><?php echo $arrayAKiosk[$i]['numberOfVisitor'];?></td>
                                                                <td><?php echo $visitTimeIn[2];?></td>
                                                            <form action="" method="POST">
                                                                <input type="hidden" name="visitationID" value="<?php echo $arrayAKiosk[$i]['visitation_id'];?>">
                                                                <td>
                                                                    <input type="time" class="form-control-sm timeInput" name="timeInDestination" required>
                                                                </td>
                                                                <td>
                                                                    <button class="btn btn-primary" name="editTimeInDestination">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                                                                        <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                                                                        </svg>
                                                                    </button>
                                                                </td>
                                                                
                                                            </form>
                                                            </tr>
                                                    <?php
                                                        }
                                                    ?>
                                                </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div><!-- Content End -->
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

<?php

    if(isset($_POST['editTimeInDestination'])){
        $visitationID = $_POST['visitationID'];
        $timeInDestination = date("h:i a", strtotime($_POST['timeInDestination']));

        $updateTimeIn = "UPDATE visitation_records SET timestampDestination = '$timeInDestination', done = 3 WHERE visitation_id = '$visitationID'";
        
        if(mysqli_query($connection, $updateTimeIn)){
            
            echo
                "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Update Successfully',
                        timer: 3000
                    }).then(function() {
                        window.location = 'kioskVisit.php';
                    });
                </script>";

        }

    }

?>