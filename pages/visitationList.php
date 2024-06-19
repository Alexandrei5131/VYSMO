<?php
    //VISITOR OR GUEST
    include('../database.php');
    include('session.php');

    //kapag nakalogged in na then nagtry iredirect sa ibang pages na hindi naman nila role
    if($_SESSION['role'] == 'Admin'){
        echo "<script>window.location.href = 'dashboard.php'</script>";
    }
    elseif($_SESSION['role']  == 'Guard'){
        echo "<script>window.location.href = 'scanAccountQR.php'</script>";
    }
    

    //include tas bukod na php
    $accountID = $_SESSION['accountID'];
    $accountEmail = $_SESSION['email'];
    $accountQR = $_SESSION['accountQR'];
    $role = $_SESSION['role'];
    
    include('getAccountInfo.php');


    $getVisitationRecords = mysqli_query($connection, "SELECT * FROM visitation_records WHERE account_id = '$accountID'");
    $numberOfVisitationRecords = mysqli_num_rows($getVisitationRecords);
    if($numberOfVisitationRecords > 0){

        $visitList = array();
        
        while($row = mysqli_fetch_array($getVisitationRecords)){
            $destinationId = $row['destination_id'];
            $getDestinationName = mysqli_query($connection, "SELECT destination FROM destination_list WHERE destination_id = '$destinationId'");
            $destinationRow = mysqli_fetch_array($getDestinationName);
            
            $row['destination'] = $destinationRow['destination'];
            // add each row returned into an array
            $visitList[] = $row;

        }

    }

       
    $sqlOpenEvent = mysqli_query($connection, "SELECT * FROM visit_openevent WHERE email = '$accountEmail'");
    $numOfOpenEventVisit = mysqli_num_rows($sqlOpenEvent);

    if ($numOfOpenEventVisit > 0) {
        $openEventVisit = array();

        while ($row = mysqli_fetch_array($sqlOpenEvent)) {
            // Get the eventID from the current row
            $eventID = $row['event_id'];

            // Query the eventlist table to fetch eventName and eventVenue
            $sqlGetEventName = mysqli_query($connection, "SELECT eventName, eventVenue FROM event_list WHERE event_id = '$eventID'");

            if ($sqlGetEventName) {
                // Fetch the eventName and eventVenue
                $eventInfo = mysqli_fetch_assoc($sqlGetEventName);

                // Add dateVisit and timeIn from the original row to $eventInfo
                $eventInfo['dateVisit'] = $row['dateVisit'];
                $eventInfo['timestamp'] = $row['timestamp'];

                // Add $eventInfo to the $openEventVisit array
                $openEventVisit[] = $eventInfo;
            }
        }
        /*
        echo "<pre>";
        echo print_r($openEventVisit);
        echo "</pre>";
        */
    }

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

    <!--SWEET ALERT 2-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
    
    <!-- Favicon -->
    <link href="../images/vysmoprintlogo.png" rel="icon">

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
        <div class="sidebar pe-0 pb-3 ">
            <div class="col-sm d-flex justify-content-end mt-2 me-2">
                <button type="button" class="close1" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="row justify-content-center">
            <div class="col">
                    <img src="../images/vyslogo.png" alt="" id="vysLogo">
                </div>
            </div>
            <nav class="navbar navbar-light justify-content-center ">


                <div class="mb-4 justify-content-center">
                    <div class="col">
                        <a class="navbar-brand mx-0 mb-3 ">
                            <h3  id="head" class="text-primary">VYSMO</h3>
                        </a>
                       
                    </div>
                    
                </div>
                
                <div class="row w-100 p-0 m-0 justify-content-center">

                    <div class="mb-4">
                        <div class="col">
                            <div class="row pe-0 text-center">

                                <?php if(isset($array[0]['suffixName'])) { if($array[0]['suffixName'] == 'Jr' or $array[0]['suffixName'] == 'Sr'){ $suffix = $array[0]['suffixName'].'.'; } 
                                    else{ $suffix = $array[0]['suffixName'];} }?>
                                <h5 class="mb-0 text-center" id="lastName"><?php if(isset($array[0]['lastName'])){ echo $array[0]['lastName']; }?></h5>
                                
                            </div>
                            <div class="row pe-0 text-center">

                                <?php if(isset($array[0]['suffixName'])) { if($array[0]['suffixName'] == 'Jr' or $array[0]['suffixName'] == 'Sr'){ $suffix = $array[0]['suffixName'].'.'; } 
                                    else{ $suffix = $array[0]['suffixName'];} }?>
                                <h6 class="mb-0 text-center" id="firstName"><?php if(isset($array[0]['firstName'])){ echo $array[0]['firstName'].' '.$array[0]['middleInitial'].' '.$suffix; } ?></h6>
                            </div>
                            <div class="row pe-0 text-center">

                                <?php if(isset($array[0]['suffixName'])) { if($array[0]['suffixName'] == 'Jr' or $array[0]['suffixName'] == 'Sr'){ $suffix = $array[0]['suffixName'].'.'; } 
                                    else{ $suffix = $array[0]['suffixName'];} }?>
                                
                                <span class="text-center text-dark"><?php echo $_SESSION['role']; ?></span>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="userProfile.php" class="nav-item nav-link "><i class="bi bi-person-lines-fill"></i> My Profile</a>
                    <a href="qrScanner.php" class="nav-item nav-link "><i class="bi bi-qr-code-scan"></i> Scanner</a>
                    <a href="visitationForm.php" id="visitationForm" class="nav-item nav-link "><i class="bi bi-people"></i> Visitation Form</a>
                    <a href="visitationList.php" class="nav-item nav-link active"><i class="bi bi-clock-history"></i> Visitation List</a>
                    <a href="logout.php" class="nav-item nav-link "><i class="bi bi-box-arrow-left"></i> Logout</a>
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->

        <script>
            var userRole = '<?php echo $_SESSION['role']; ?>'; // Embed session variable in JavaScript
            
            // Add a click event listener to the <a> tag
            document.getElementById('visitationForm').addEventListener('click', function (e) {
                if (userRole == 'Guest') {
                    e.preventDefault(); // Prevent the default link behavior
                    // Display SweetAlert if the condition is not met
                    Swal.fire({
                        icon: 'warning',
                        title: 'Complete Visitor Information',
                        text: 'Insufficient authorization to access Visitation Form.'
                    });
                }
            });
        </script>
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content">
            <!-- Recent Visits Start -->
            <nav class="navbar navbar-light justify-content-center head1">
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <div class="position-relative">
                    <img class="rounded-circle guardLogo" src="../images/logo.png" alt="" >
                </div>
                <span class="navbar-brand mb-0 h1">Nueva Ecija University of Science and Technology</span>
           
            </nav>
            <div class="container-fluid bodyContentVisitList mt-3 col ">                
                <div class="bg-light text-center rounded">
                    <nav class="navbar navbar-light justify-content-center visitInfo">
                        <span class="navbar-brand mb-0 h1 text-white">Visitation List</span>
                    </nav>
                    <div class="p-4">
                        <input type="search" class="form-control searchBtnVisitationList" id="searchVisitList" placeholder="Search"> <br>                  
                        <div class="table-responsive" id="tableVisitList" >
                            <table class="table text-start align-middle table-striped table-hover mb-0 shadow p-3 rounded">
                                <thead class="sticky-top bg-light text-center shadow p-3 mb-5 rounded" style="z-index: 2;">
                                    <tr class="text-dark">
                                        <th scope="col">Date Submitted</th>
                                        <th scope="col">Type of Visit</th>
                                        <th scope="col">Type of Form</th>
                                        <th scope="col">Destination</th>
                                        <th scope="col">Appointment Visit</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Reject Reason</th>
                                    </tr>
                                </thead>
                                
                                <tbody  id="tData1" class="text-center">

                                    <?php
                                        for($i=0; $i < $numberOfVisitationRecords; $i++){
                                            $visitationID = $visitList[$i]['visitation_id'];

                                            $visittimeinuniv = mysqli_query($connection, "SELECT dateSubmit, appointmentVisit, rejectReason FROM visit_requested WHERE visitation_id = '$visitationID'");
                                            if(mysqli_num_rows($visittimeinuniv) > 0){
                                                while($row = mysqli_fetch_array($visittimeinuniv)){
                                                    // add each row returned into an array
                                                    $dateSubmit = date("F j, Y", strtotime($row['dateSubmit']));
                                                    $appointmentVisit = date("F j, Y", strtotime($row['appointmentVisit']));
                                                    $rejectReason = $row['rejectReason'];
                                                }

                                            }
                                            $visittimeinuniv = mysqli_query($connection, "SELECT dateVisit FROM visit_timein_university WHERE visitation_id = '$visitationID' ");
                                            
                                    ?>
                                        <tr>
                                            <td><?php echo $dateSubmit; ?></td>
                                            <td><?php echo $visitList[$i]['typeOfVisit']; ?></td>
                                            <td><?php echo $visitList[$i]['typeOfForm']; ?></td>
                                            <td><?php echo $visitList[$i]['destination']; ?></td>
                                            <td><?php echo $appointmentVisit;?></td>
                                            <td class="col">
                                                <div class= "btn btn-<?php 
                                                    if($visitList[$i]['done'] == 5){
                                                        echo 'secondary';
                                                    }
                                                    elseif($visitList[$i]['done'] == 4){
                                                        echo 'dark';
                                                    }
                                                    elseif($visitList[$i]['done'] == 3){ 
                                                        echo 'success'; 
                                                    } 
                                                    elseif($visitList[$i]['done'] == 2){ 
                                                        echo 'danger'; 

                                                    }elseif($visitList[$i]['done'] == 1){ 
                                                        echo 'primary'; 
                                                    } 
                                                    else{ 
                                                        echo 'warning'; 
                                                    } ?> px-3" style="pointer-events: none;">
                                                <?php 
                                                    if($visitList[$i]['done'] == 5){
                                                        echo 'Expired';
                                                    }elseif($visitList[$i]['done'] == 4){ 
                                                        echo 'Invalid';
                                                    }elseif($visitList[$i]['done'] == 3){ 
                                                        echo 'Success'; 
                                                    } elseif($visitList[$i]['done'] == 2){ 
                                                        echo 'Rejected'; 
                                                    }elseif($visitList[$i]['done'] == 1){ 
                                                        echo 'Ongoing'; 
                                                    } else{ 
                                                        echo 'Pending'; 
                                                    }?>
                                                </div>
                                            </td>
                                            <td class="col-1">
                                                <textarea cols="15" rows="2" style="resize: none; text-align: center; display: <?php if($visitList[$i]['done'] != 2){ echo 'none'; }else{ echo 'block';} ?>" disabled> <?php echo $rejectReason; ?> </textarea>
                                            
                                            </td>
                                        </tr>

                                    <?php
                                    }
                                    ?>
                                        
                                    
                                    <!--End of Modal-->

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Recent Visits End -->


            <div class="container-fluid bodyContentVisitList mt-4 mb-3 col ">                
                <div class="bg-light text-center rounded">
                    <nav class="navbar navbar-light justify-content-center visitInfo">
                        <span class="navbar-brand mb-0 h1 text-white">Open Event List</span>
                    </nav>
                    <div class="p-4">
                        <input type="search" class="form-control searchBtnVisitationList" id="inlineFormInputName" placeholder="Search"> <br>                  
                        <div class="table-responsive" id="tableVisitList" >
                            <table class="table text-start align-middle table-striped table-hover mb-0 shadow p-3 rounded">
                                <thead class="sticky-top bg-light text-center shadow p-3 mb-5 rounded" style="z-index: 2;">
                                    <tr class="text-dark">
                                        <th scope="col">#</th>
                                        <th scope="col">Event Name</th>
                                        <th scope="col">Venue</th>
                                        <th scope="col">Date Visit</th>
                                        <th scope="col">Timestamp</th>
                                    </tr>
                                </thead>
                                
                                <tbody  id="tData" class="text-center">
                                    <?php
                                        for($i=0, $num=1; $i < $numOfOpenEventVisit; $i++, $num++){
                                    ?>
                                        <tr>
                                            <td><?php echo $num; ?></td>
                                            <td><?php echo $openEventVisit[$i]['eventName'];?></td>
                                            <td><?php echo $openEventVisit[$i]['eventVenue'];?></td>
                                            <td><?php echo date("F j, Y", strtotime($openEventVisit[$i]['dateVisit']));?></td>
                                            <td><?php echo $openEventVisit[$i]['timestamp'];?></td>
                                        </tr>
                                    <?php
                                        }
                                    ?>
                                    
                                    <!--End of Modal-->

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Recent Visits End -->


        </div>
        <!-- Content End -->


    </div>

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
    <script src="js/main.js"></script>
</body>

</html>