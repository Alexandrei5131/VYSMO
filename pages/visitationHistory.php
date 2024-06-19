<?php

    include('../database.php');
    include('session.php');
    
    //kapag nakalogged in na then nagtry iredirect sa ibang pages na hindi naman nila role
    if($_SESSION['role']  == 'Guard'){
        echo "<script>window.location.href = 'scanAccountQR.php'</script>";
    }
    elseif($_SESSION['role']  == 'Visitor' || $_SESSION['role']  == 'Guest'){
        echo "<script>window.location.href = 'userProfile.php'</script>";
    }
    //end
    
    $role = $_SESSION['role'];


    if($role=='Guard'){
        $accountEmail = $_SESSION['email'];

        $queryGInfo = "SELECT firstName, middleName, lastName, suffixName, pic2x2 FROM guardInfo WHERE email='{$accountEmail}'";
        $sqlGInfo = mysqli_query($connection, $queryGInfo);

        $array = array();

        // look through query
        while($row = mysqli_fetch_array($sqlGInfo)){
        // add each row returned into an array
            $array = $row;
        }
        /*
        echo "<pre>";
        echo print_r($array);
        echo "</pre>";
        */
    }


    $sqlGetVisitationDone = mysqli_query($connection, "SELECT * FROM visitation_records WHERE done = 3");
    if(mysqli_num_rows($sqlGetVisitationDone) > 0){
        $numOfSuccessVisit = mysqli_num_rows($sqlGetVisitationDone);

        $allvisitation = array();

        while($row = mysqli_fetch_array($sqlGetVisitationDone)){
            // add each row returned into an array

            $destinationID = $row['destination_id'];

            $getDestinationName = mysqli_query($connection, "SELECT destination FROM destination_list WHERE destination_id = '$destinationID'");
            $destinationRow = mysqli_fetch_array($getDestinationName);
            $row['destination'] = $destinationRow['destination'];

            $allvisitation[] = $row;
        }

        /*
        echo "<pre>";
        echo print_r($allvisitation);
        echo "</pre>";
        */

    }
    else{
        $numOfSuccessVisit = 0;
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
    

    <style>
        th,td{
            text-align:center;
        }
    </style>
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

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
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
        <div class="sidebar pe-0 ">
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


                <div class=" justify-content-center">
                    <div class="col">
                        <a class="navbar-brand mx-0 ">
                            <h3  id="head" class="text-primary">VYSMO</h3>
                        </a>
                       
                    </div>
                    
                </div>
                <div class="row ms-2 mb-2">

                        <div class="row pe-0 justify-content-center">
                            <?php
                                if($role == 'Admin'){
                            ?>
                                    <img src="../images/adminprofile.png" alt="" style="width: 120px; height: 90px;">
                            <?php
                                }
                                elseif($role == 'Guard'){
                            ?>
                                  <img class="rounded-circle" src="guard2x2/<?php echo $array['pic2x2']?>" alt="" style="width: 120px; height: 90px;">
                            <?php  
                                }
                            ?>
                            
                        </div>
                        <div class="row pe-0 mt-3">
                            <?php
                                if($role == 'Admin'){
                                    echo '<h5 class="mb-0 text-center">Admin</h5>';
                                }
                                elseif($role == 'Guard'){
                                    ?>
                                    
                                    <?php if(isset($array['suffixName'])){ 
                                        if($array['suffixName'] == 'Jr' or $array['suffixName'] == 'Sr'){
                                             $suffix = $array['suffixName'].'.'; 
                                        } 
                                        else{ 
                                            $suffix = $array['suffixName'];
                                        } 
                                        }
                                    ?>
                                    <h5 class="mb-0 text-center" id="lastName"><?php if($_SESSION['role'] == 'Guard'){ echo $array['lastName'];}?> </h5>
                                    <h6 class="mb-0 text-center" id="firstName"><?php if(isset($array['firstName'])){ echo $array['firstName'].' '.$array['middleName'].' '.$suffix; } ?></h6>
                                    <span class="text-center" style="color: #5A5A5A;"><?php echo $_SESSION['role']; ?></span>
                            <?php        
                                }
                            ?>
                            
                        </div>

                   
                </div>
                <div class="navbar-nav w-100">
                <?php
                        if($_SESSION['role'] == 'Admin'){
                            echo '
                                <a href="dashboard.php" class="nav-item nav-link "><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                                <a href="addDestination.php" class="nav-item nav-link "><i class="bi bi-building"></i> Destination List</a>
                                <a href="addGuard.php" class="nav-item nav-link "><i class="bi bi-shield-lock"></i> Guard List</a>
                                <a href="addEvent.php" class="nav-item nav-link "><i class="bi bi-calendar-event"></i> Event List</a>
                                <a href="evaluationListAdmin.php" class="nav-item nav-link "><i class="bi bi-card-checklist"></i> Evaluation List</a>
                                <a href="visitationHistory.php" class="nav-item nav-link active"><i class="bi bi-clock-history"></i> Visitation History</a>
                                <a href="openEventHistory.php" class="nav-item nav-link "><i class="bi bi-clock-history"></i> Open Event History</a>
                            ';
                        }     
                    ?>
                    <a href="logout.php" class="nav-item nav-link "><i class="bi bi-box-arrow-left"></i> Logout</a>
                </div>
            </nav>
        </div>
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
            <!--Color Changer-->
            <div id="theme-open" class="fas fa-bars"></div>

            <div class="themes-container">

            <div id="theme-close" class="fas fa-times"></div>

            <h3>Sidebar Color</h3>

            <div class="theme-colors">
                <div class="color colorDefault" style="background:rgb(65, 126, 128)">Default</div>
                <div class="color" style="background:#007bff;"></div>

                <div class="color" style="background:#2980b9"></div>
                <div class="color" style="background:#27ae60;"></div>
                <div class="color" style="background:#ffa502;"></div>
                <div class="color" style="background:#8e44ad;"></div>
                <div class="color" style="background:#0fb9b1;"></div>
                <div class="color" style="background:#ffd32a;"></div>
                <div class="color" style="background:#ff0033;"></div>
                <div class="color" style="background:#e84393;"></div>
            </div><br>
            <h3>Header Color</h3>
            <div class="theme-colorsBG">
                <div class="colorBG colorDefault" style="background:rgb(65, 157, 158)">Default</div>
                <div class="colorBG" style="background:#ff9900;"></div>

                <div class="colorBG" style="background:#2980b9"></div>
                <div class="colorBG" style="background:#27ae60;"></div>
                <div class="colorBG" style="background:#ffa502;"></div>
                <div class="colorBG" style="background:#8e44ad;"></div>
                <div class="colorBG" style="background:#0fb9b1;"></div>
                <div class="colorBG" style="background:#ffd32a;"></div>
                <div class="colorBG" style="background:#ff0033;"></div>
                <div class="colorBG" style="background:#e84393;"></div>
            </div><br>
            <h3>Sub-header Color</h3>
            <div class="theme-colorsBG1">
                <div class="colorBG1 colorDefault" style="background:rgb(65, 157, 158)">Default</div>
                <div class="colorBG1" style="background:#6f42c1"></div>

                <div class="colorBG1" style="background:#2980b9"></div>
                <div class="colorBG1" style="background:#27ae60;"></div>
                <div class="colorBG1" style="background:#ffa502;"></div>
                <div class="colorBG1" style="background:#8e44ad;"></div>
                <div class="colorBG1" style="background:#0fb9b1;"></div>
                <div class="colorBG1" style="background:#ffd32a;"></div>
                <div class="colorBG1" style="background:#ff0033;"></div>
                <div class="colorBG1" style="background:#e84393;"></div>
            </div>
            <h3>Body Color</h3>
            <div class="theme-colorsBG2">
                <div class="colorBG2 colorDefault" style="background:#F3F6F9; color:black;">Default</div>
                <div class="colorBG2" style="background:rgb(140, 140, 140)"></div>

                <div class="colorBG2" style="background:#2980b9"></div>
                <div class="colorBG2" style="background:#27ae60;"></div>
                <div class="colorBG2" style="background:#ffa502;"></div>
                <div class="colorBG2" style="background:#8e44ad;"></div>
                <div class="colorBG2" style="background:#0fb9b1;"></div>
                <div class="colorBG2" style="background:#ffd32a;"></div>
                <div class="colorBG2" style="background:#ff0033;"></div>
                <div class="colorBG2" style="background:#e84393;"></div>
            </div>
            </div>
            <!--Color Changer--> 
            </nav>
            <div class="container-fluid bodyContent mt-3 col">
                <div class="bg-light text-center rounded">
                    <nav class="navbar navbar-light justify-content-center visitInfo">
                        <span class="navbar-brand mb-0 h1 text-white">Visitation History</span>
                    </nav>
                    <div class="p-4">
                        <div class="row mb-3 lgScreenEvent"> <!--For Desktop-->      <!--From Date-->
                                <div class="col p-0">
                                    <input type="search" class="form-control searchBtnEvent" id="inlineFormInputName" placeholder="Search">
                                </div>
                                <div class="col-auto">
                                    <div class="lgScreenEvent">     
                                        <div class="container-fluid">
                                            <div class="row">
                                                <div class="col-auto">
                                                    <label for="fromDate" class="col-form-label">From:</label>
                                                </div>
                                                <div class="col-auto">
                                                    <input type="date" class="form-control" class="fromDate" id="fromDate" value="<?php echo $dateToday;?>" max="<?php echo $dateToday;?>"  >
                                                </div>
                                                <div class="col-auto">
                                                    <label for="toDate" class="col-form-label">To:</label>
                                                </div>
                                                <div class="col-auto">
                                                    <input type="date" class="form-control" class="toDate" id="toDate" value="<?php echo $dateToday;?>" disabled >
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                    <script>
                                        // Get references to the date inputs
                                        const fromDateInput = document.getElementById('fromDate');
                                        const toDateInput = document.getElementById('toDate');
                                        
                                        // Variable to track whether the "From" date has been changed
                                        let fromDateChanged = false;

                                        // Add an event listener to the "From" date input
                                        fromDateInput.addEventListener('change', function () {
                                            // Get the selected date from the "From" date input
                                            const selectedFromDate = new Date(this.value);
                                            
                                            // Enable the "To" date input when a "From" date is selected
                                            toDateInput.removeAttribute('disabled');

                                            // Set the "min" attribute of the "To" date input to the selected "From" date
                                            toDateInput.min = this.value;

                                            // Set the "max" attribute of the "To" date input to the current date only if "From" date hasn't been changed before
                                            if (!fromDateChanged) {
                                                toDateInput.max = "<?php echo $dateToday; ?>";
                                                toDateInput.value = "<?php echo $dateToday; ?>";
                                            }

                                            // Update the "From" date changed status
                                            fromDateChanged = true;

                                            // Check if the selected "From" date is greater than the current value of the "To" date
                                            const selectedToDate = new Date(toDateInput.value);
                                            if (selectedFromDate > selectedToDate) {
                                                // If "From" date is greater, set "To" date to the same date as "From" date
                                                toDateInput.value = this.value;
                                            }
                                        });
                                    </script>

                                
                                </div>
                                <div class="col-auto">
                                    <a type="button" class="btn btn-primary" id="visitationHistoryReport"> 
                                            Download Report
                                    </a>
                                </div>

                                <script>

                                    document.getElementById('visitationHistoryReport').addEventListener('click', function() {
                                        const fromDate = document.getElementById('fromDate').value;
                                        const toDate = document.getElementById('toDate').value;

                                        // Check if either fromDate or toDate is empty
                                        if (fromDate === "" || toDate === "") {
                                            Swal.fire({
                                            icon: 'info',
                                            title: 'No date range selected',
                                            timer: 3000
                                        });
                                        } else {
                                            // Get the URL of the image that is already set in the main PHP file
                                            const windowName = `qr_window_${Date.now()}`;
                                            // Open qr.php in a new window with the image URL as a query parameter
                                            // pwede ihash muna para di makita talaga then i un-hashed
                                            const newWindow = window.open(`printdocs/printVisitationHistory.php?fromDate=${fromDate}&toDate=${toDate}`, windowName);
                                        }
                                    });

                                </script>

                            </div><!--For Desktop-->  

                            <div class="row smScreenEvent">  <!--For Mobile-->    
                                <div class="col">
                                        <input type="search" class="form-control searchBtn" id="inlineFormInputName" placeholder="Search">
                                    </div>
                                    <div class="col-auto smScreenEvent">
                                        <a type="button" class="btn btn-primary" href="downloadReport.php"> 
                                                Download Report 
                                        </a>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="row smScreenEvent">
                                <div class="col">
                                    <div class="col-auto">   
                                        <div class="smScreenEvent">             
                                            <div class="container-fluid">
                                                <div class="row mb-1">
                                                    <div class="col-6">
                                                        <label for="fromDate" class="col-form-label">From:</label>
                                                        <input type="date" class="form-control" id="fromDate" name="fromDate">
                                                    </div>
                                                    <div class="col-6">
                                                        <label for="toDate" class="col-form-label">To:</label>
                                                        <input type="date" class="form-control" id="toDate" name="toDate">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!--For Mobile-->    <!--To Date--> 
                        <div class="row">
                            <div class="col">
                                <div class="table-responsive" id="tableVisitHistory" >
                                    <table class="table text-center align-middle table-bordered table-hover mb-0">
                                        <thead class="sticky-top" style="z-index:2">
                                            <tr class="text-dark bg-light" >
                                                <th scope="col">Name of Visitor</th>
                                                <th scope="col">Guard on Duty</th>
                                                <th scope="col">Type of Form</th>
                                                <th scope="col">Destination</th>
                                                <th scope="col">#Visitor</th>
                                                <th scope="col">Date Visit</th>
                                                <th scope="col">Time In University</th>
                                                <th scope="col">Time In Destination</th>
                                            </tr>
                                        </thead>
                                        <tbody  id="tData">
                                            <?php
                                                
                                                for($i=0; $i < $numOfSuccessVisit; $i++){
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
                                                <td class="col-3">
                                                    <?php 
                                                        echo $guardFullName['lastName'] . ', '. $guardFullName['firstName'] . ' ' . $guardFullName['middleName'] . ' ' . $suffixNameG;  
                                                    ?>
                                                </td>
                                                <td><?php echo $allvisitation[$i]['typeOfForm']; ?></td>
                                                <td><?php echo $allvisitation[$i]['destination'];?></td>
                                                <td><?php echo $allvisitation[$i]['numberOfVisitor']; ?></td>
                                                <td class="col-2">
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
                                                <td>Batman</td>
                                                <td>Superman</td>
                                                <td>Individual Person</td>
                                                <td>Unknown</td>
                                                <td>August 7, 2023</td>
                                                <td>08:00 AM</td>
                                                <td>08:00 AM</td>
                                                <td><a class="btn btn-sm btn-warning" href="">On Going</a></td>
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
            <!-- Recent Visits End -->


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