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
    date_default_timezone_set("Asia/Manila");

    $sqlEventList = mysqli_query($connection, "SELECT * FROM event_list WHERE evaluationForm = 1 ");
    $numOfEvaluation = mysqli_num_rows($sqlEventList);

    if ($numOfEvaluation > 0) {
        $evaluationList = array();

        while ($row = mysqli_fetch_array($sqlEventList)) {
            // Fetch the destination for this event
            $destinationID = $row['eventVenue'];

            if($destinationID == 'Whole University'){
                $row['destination'] = "Whole University";
            }
            else{
                $getDestination = mysqli_query($connection, "SELECT destination FROM destination_list WHERE destination_id = '$destinationID'");
                $destinationInfo = mysqli_fetch_array($getDestination);
                $row['destination'] = $destinationInfo['destination'];
            }
            

            // Add the modified row to the $evaluationList array
            $evaluationList[] = $row;
        }
        

    }
    $getNumOfExclusiveEvent = mysqli_query($connection, "SELECT * FROM event_list WHERE evaluationForm = 1 AND typeOfEvent = 'Exclusive Event'");
    $numOfExclusiveEvent = mysqli_num_rows($getNumOfExclusiveEvent);
   /* 
    echo "<pre>";
    echo print_r($evaluationList);
    echo "</pre>";
    */
    
    //GET EVALUATION ID 
    $sqlGetEvalID = mysqli_query($connection,"SELECT * FROM evaluation_list WHERE status = 1 OR status = 2");
    if(mysqli_num_rows($sqlGetEvalID) > 0){

        $evaluationID = array();

        while($row = mysqli_fetch_array($sqlGetEvalID)){    
            // add each row returned into an array
            $evaluationID[] = $row; //EVAL_ID
        }
        
        /*
        echo "<pre>";
        echo print_r($evaluationID);
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
    <!-- Favicon -->
    <link href="../images/vysmoprintlogo.png" rel="icon">

    <!-- SWEET ALERT -->
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link type="text/css" href="css/style2.php" rel="stylesheet">
    
    <!-- WHEN OUTSIDE MODAL CLICKED -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .vewFB{
            background-color: #009999;
        }
    </style>
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
                                <a href="evaluationListAdmin.php" class="nav-item nav-link active"><i class="bi bi-card-checklist"></i> Evaluation List</a>
                                <a href="visitationHistory.php" class="nav-item nav-link "><i class="bi bi-clock-history"></i> Visitation History</a>
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
            <div class="container-fluid bodyContent mt-3 col"  >
                    <div class="bg-light text-center rounded">
                        <nav class="navbar navbar-light justify-content-center visitInfo">
                            <span class="navbar-brand mb-0 h1 text-white">Evaluation List</span>
                        </nav>
                        <div class="p-4">
                            <div class="row">
                                <div class="col">
                                    <input type="search" class="form-control searchBtnEvalList" id="inlineFormInputName" placeholder="Search"> <br>
                                </div>                     
                            </div>
                    
                            <div class="table-responsive" id="tableEvalListAdmin" style="overflow-x: hidden;">
                            <table class="table text-start align-middle table-striped table-hover mb-0 shadow p-3 rounded" >
                                <thead class="sticky-top text-center shadow p-3 mb-5 rounded" style="z-index: 2;">
                                    <tr class="text-dark">
                                        <th scope="col">Type Of Event</th>
                                        <th scope="col">Event Name</th>
                                        <th scope="col">Venue</th>
                                        <th scope="col" class="col-5">Action</th>
                                    </tr>
                                </thead>
                                
                                <tbody  id="tData" class="text-center">

                                    <?php
                                        echo '<input type="hidden" id="numOfEvaluation" value="'.$numOfEvaluation.'">';
                                        echo '<input type="hidden" id="numOfExclusiveEvent" value="'.$numOfExclusiveEvent.'">';
                                        
                                        $counterExclusiveEvent = 0;
                                        for($i=0 ; $i < $numOfEvaluation; $i++){
                                            $eventID = $evaluationList[$i]['event_id'];

                                            $checkActivate = mysqli_query($connection, "SELECT status FROM evaluation_list WHERE event_id = '$eventID'");
                                            if(mysqli_num_rows($checkActivate) > 0){
                                                $status = mysqli_fetch_array($checkActivate);
                                                
                                                /*
                                                echo "<pre>";
                                                echo print_r($status);
                                                echo "</pre>";
                                                */
                                            }
                                            

                                    ?>
                                    <tr>
                                        <td><?php echo $evaluationList[$i]['typeOfEvent']; ?></td>
                                        <td><?php echo $evaluationList[$i]['eventName']; ?></td>
                                        <td><?php echo $evaluationList[$i]['destination']; ?></td>
                                        <td >
                                            <div class="row justify-content-center">
                                                <?php
                                                    if($status[0] == 1){
                                                ?>
                                                        <div class="col-auto">
                                                            <form action="evaluationQuestion.php?">
                                                                <?php echo '<input type="hidden" name="evaluationID" value="'.$evaluationID[$i]['evaluation_id'].'">';?>
                                                                <?php echo '<input type="hidden" name="eventName" value="'.$evaluationList[$i]['eventName'].'">';?>
                                                                <button type="submit" class="btn btn-primary">Manage</button>
                                                            </form>
                                                        </div>
                                                        
                                                <?php
                                                    }
                                                ?>
                                                <?php
                                                    //if($status[0] == 2){
                                                ?>
                                                        <div class="col-auto">
                                                            <form action="results.php?">
                                                                <?php echo '<input type="hidden" name="evaluationID" value="'.$evaluationID[$i]['evaluation_id'].'">';?>
                                                                <?php echo '<input type="hidden" name="eventName" value="'.$evaluationList[$i]['eventName'].'">';?>
                                                                <button type="submit" class="btn btn-success">View Results</button>
                                                            </form>
                                                        </div>
                                                        <div class="col-auto">
                                                            <form action="feedback.php?">
                                                                <?php echo '<input type="hidden" name="evaluationID" value="'.$evaluationID[$i]['evaluation_id'].'">';?>
                                                                <?php echo '<input type="hidden" name="eventName" value="'.$evaluationList[$i]['eventName'].'">';?>
                                                                <button type="submit" class="btn vewFB text-light">View Feedback</button>
                                                            </form>

                                                        </div>
                                                        
                                                        <div class="col-auto">
                                                            <?php 
                                                                if($evaluationList[$i]['typeOfEvent'] == 'Exclusive Event'){
                                                                    if($status[0] == 1){
                                                                        echo '<button class="btn btn-info" data-toggle="modal" id="print"  data-toggle="tooltip" data-placement="top" title="print" data-target="#printButton'.$counterExclusiveEvent.'"><i class="bi bi-printer"></i></button>';
                                                            ?>

                                                                    <!--Print Button-->
                                                                    <div class="modal fade" id="printButton<?php echo $counterExclusiveEvent;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true"  data-backdrop="static" data-keyboard="false"> <!-- Modal for add Event-->
                                                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-body justify-content-center">
                                                                                    <?php

                                                                                        $checkFirstTime = mysqli_query($connection, "SELECT * FROM eval_exclusiveevent_qr WHERE evaluationEventQR = '' AND evaluation_id = '".$evaluationID[$i]['evaluation_id']."'");
                                                                                        if(mysqli_num_rows($checkFirstTime) > 0){
                                                                                            $new = 'This will generate a ';
                                                                                            $newlast = '';
                                                                                        }
                                                                                        else{
                                                                                            $new = '<b>Note:</b> This will generate a New';
                                                                                            $newlast = '<h6>The Last Generated QR Code will be invalid.</h6>';
                                                                                        }
                                                                                    ?>
                                                                                    <div class="row justify-content-center">
                                                                                        <p id="deleteBody" class="text-center"><?php echo $new; ?> QR Code for the <b><?php echo $evaluationList[$i]['eventName']; ?> Evaluation</b>.<br> Do you still want to continue?<br><?php echo $newlast?></p>
                                                                                    </div>
                                                                                    <div class="row justify-content-center">
                                                                                        <div class="col-auto">
                                                                                            <button type="submit" id="generateQRAndOpenWindow<?php echo $counterExclusiveEvent;?>" class="btn btn-warning" data-dismiss="modal">Yes</button>
                                                                                            <?php
                                                                                                 echo '<input type="hidden" id="evalIDexclusive'.$counterExclusiveEvent.'" value="'.$evaluationID[$i]['evaluation_id'].'">';
                                                                                                 echo '<input type="hidden" id="exclusiveEventName'.$counterExclusiveEvent.'" value="'.$evaluationList[$i]["eventName"].'">';
                                                                                                 
                                                                                            ?>
                                                                                        </div>
                                                                                        <div class="col-auto">
                                                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                                                                                        </div>
                                                                                    </div> 
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>  
                                                            
                                                            <?php
                                                                   
                                                                    $counterExclusiveEvent++;
                                                                    }
                                                                }
                                                            ?>
                                                        </div>
                                                <?php
                                                  //  }
                                                ?>
                                                        
                                            </div>
                                        </td>
                                        
                                    </tr>
                                    <script>
                                        var numOfExclusiveEvent = document.getElementById('numOfExclusiveEvent');

                                        for (var a = 0; a < numOfExclusiveEvent.value; a++) {
                                            (function (index) {

                                                var generateQRAndOpenWindowElement = "generateQRAndOpenWindow" + index;
                                                var exclusiveEventNameElement = "exclusiveEventName" + index;
                                                var evalEventIDElement = "evalIDexclusive" + index;

                                                var generateQRAndOpenWindow = document.getElementById(generateQRAndOpenWindowElement);
                                                var evalEventID = document.getElementById(evalEventIDElement);
                                                var exclusiveEventName = document.getElementById(exclusiveEventNameElement);

                                                if (generateQRAndOpenWindow) {
                                                    generateQRAndOpenWindow.addEventListener('click', function() {
                                                        var encdata = "Exclusive Event" + '***' + evalEventID.value + '***' + exclusiveEventName.value; 
                                                        var encodedData = btoa(encdata); // Use btoa to encode to Base64

                                                        // Get the URL of the image that is already set in the main PHP file 
                                                        const windowName = `qr_window_${Date.now()}`;
                                                        // Open qr.php in a new window with the image URL as a query parameter
                                                        const newWindow = window.open(`printdocs/printExclusiveEvalQR.php?encdata=${encodedData}`, windowName);
                                                    });
                                                }

                                            })(a);
                                        }
                                    </script>

                                    <?php } ?>
                                        
                                                          

                                    
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
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Javascript -->
    <script src="js/main.js"></script>
</body>

</html>

