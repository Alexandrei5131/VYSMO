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

    $dateToday = date('Y-m-d');
    $role = $_SESSION['role'];

    $sqlEvents = mysqli_query($connection, "SELECT * FROM event_list ORDER BY status = 0 DESC, status = 3 DESC, status ASC, event_id DESC");
    $numOfEvents = mysqli_num_rows($sqlEvents);
    if($numOfEvents > 0){

        $eventList = array();
        
        while($row = mysqli_fetch_array($sqlEvents)){
            // add each row returned into an array
            //  
            $destinationId = $row['eventVenue'];
            if($destinationId == 'Whole University'){
                $row['eventVenue'] = "Whole University";
            }
            else{
                $getDestinationName = mysqli_query($connection, "SELECT destination FROM destination_list WHERE destination_id = '$destinationId'");
                $destinationRow = mysqli_fetch_array($getDestinationName);
                $row['eventVenue'] = $destinationRow['destination'];
            }
            
            $eventList[] = $row;

        }
        /*
        echo "<pre>";
        echo print_r($eventList); 
        echo "</pre>";
        */

        


    }

    $sqlOpenEvent = mysqli_query($connection, "SELECT * FROM event_list WHERE typeOfEvent = 'Open Event' AND status = 1");
    if(mysqli_num_rows($sqlOpenEvent) > 0){
        $numOfOpenEvent = mysqli_num_rows($sqlOpenEvent);
    }
    else{
        $numOfOpenEvent = 0;
    }

    $getDestinationList = mysqli_query($connection, "SELECT destination_id, destinationName FROM destination_list WHERE typeOfDestination = 'Department' ORDER BY destination ASC");
    $numberOfDestination = mysqli_num_rows($getDestinationList);
    if(mysqli_num_rows($getDestinationList) > 0){

        $destinationlist = array();
        while($row = mysqli_fetch_array($getDestinationList)){
            // add each row returned into an array
            $destinationlist[] = $row; //info of eventlist
        }
        /*
        echo "<pre>";
        echo print_r($destinationlist); 
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

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style2.php" rel="stylesheet">

    <!-- WHEN OUTSIDE MODAL CLICKED -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        th,td{
            text-align:center;
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
                        <a  class="navbar-brand mx-0 ">
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
                                <a href="addEvent.php" class="nav-item nav-link active"><i class="bi bi-calendar-event"></i> Event List</a>
                                <a href="evaluationListAdmin.php" class="nav-item nav-link "><i class="bi bi-card-checklist"></i> Evaluation List</a>
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



            <!-- Add Event Start -->
            <nav class="navbar navbar-light d-flex justify-content-center head1">
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
            <div class="container-fluid bodyContent mt-3 col-11">
                <div class="bg-light text-center rounded p-4">
                    <div class="align-items-center justify-content-between mb-4">
                        <div class="row">
                            <div class="col ">
                                <input type="search" class="form-control searchBtn" id="inlineFormInputName" placeholder="Search">
                            </div>
                            <div class="col-auto">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEvent">
                                    Add Event
                                </button> <br><br>
                            </div>
                        </div>
                        
                        <!--Add Event List-->
                        <div class="modal fade" id="addEvent" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false"> <!-- Modal for add Event-->
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header"><!--Modal Header-->
                                        <h5 class="modal-title" id="exampleModalLongTitle">Event Information</h5>
                                        <button type="button" id="xbutton" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form method="POST" action="addEventQuery.php">
                                        <div class="modal-body">  <!-- Modal body -->
                                            
                                                <div class="container text-center p-0" >
                                                    <div class="row d-flex align-items-center">
                                                        <div class="col">
                                                            <label for="typeOfEvent" class="form-label">Type of Event:</label>
                                                            <select class="form-control text-center bg-white" id="typeOfEvent" name="typeOfEvent" required>
                                                                <option disabled selected></option>
                                                                <option value="Exclusive Event">Exclusive Event</option>
                                                                <option value="Open Event">Open Event</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-auto mt-4">
                                                            <input type="checkbox" class="form-check-input" name="addEvaluation" id="exampleCheck1">
                                                            <label class="form-check-label" for="exampleCheck1"> Add Evaluation</label>
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="row align-items-start mt-2">
                                                    
                                                        <div class="col-12 col-sm-4">
                                                            <label for="eventName" class="form-label">Event Name:</label>
                                                            <input type="text" name="eventName" class="form-control text-center" id="eventName" required>
                                                        </div>
                                                        <div class="col-12 col-sm">
                                                            <label for="eventVenue" class="form-label">Venue:</label>
                                                            <select id="eventVenue" class="form-control text-center bg-white" name="eventVenue" disabled required>
                                                                <option value="" selected></option>
                                                            </select>
                                                            
                                                        </div>
                                                        
                                                    
                                                    </div>
                                                    <div class="row mt-3 justify-content-center">
                                                        <div class="col-6 col-sm-auto">
                                                                <label for="addEventStart" class="col-form-label">Event Start</label>                                                            
                                                                <input type="date" class="form-control" min="<?php echo $dateToday;?>" name="addEventStart" id="addEventStart" value="<?php echo $dateToday; ?>" required>
                                                        </div>
                                                        <div class="col-6 col-sm-auto">
                                                                <label for="addEventEnd" class="col-form-label">Event End</label>
                                                                <input type="date" class="form-control" min="<?php echo $dateToday;?>" name="addEventEnd" id="addEventEnd" value="<?php echo $dateToday; ?>" required>
                                                        </div>
                                                    </div>
                                                </div><br>
                                                
                                        </div><!-- Modal body -->
                                        <div class="modal-footer justify-content-center"><!-- Modal Footer -->
                                            <button type="submit" name="addEvent" class="btn btn-primary">Add Event</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>  <!--End of Add Event List -->

                        <script>
                            // Get references to the select elements
                            var eventTypeSelect = document.getElementById('typeOfEvent');
                            var eventOptionsSelect = document.getElementById('eventVenue');

                            var xbutton = document.getElementById('xbutton');

                            // Add an event listener to the close button
                            xbutton.addEventListener('click', function () {
                                // Clear the input fields
                                eventTypeSelect.value = ''; // Clear the selected value
                                eventOptionsSelect.innerHTML = ''; // Clear the options

                                // Reset the second dropdown to its default state
                                var defaultOption = document.createElement('option');
                                defaultOption.value = '';
                                defaultOption.text = '';
                                eventOptionsSelect.appendChild(defaultOption);

                                // Disable the second dropdown
                                eventOptionsSelect.disabled = true;
                            });

                            // Function to populate the Venue dropdown
                            function populateVenueOptions(selectedEventType) {
                                // Clear the second dropdown's options
                                eventOptionsSelect.innerHTML = '';

                                // Populate the second dropdown based on the selected value
                                if (selectedEventType === 'Exclusive Event') {
                                    var defaultOption = document.createElement('option');
                                    defaultOption.value = '';
                                    defaultOption.text = '';
                                    defaultOption.selected = true;
                                    defaultOption.disabled = true;
                                    eventOptionsSelect.appendChild(defaultOption);

                                    <?php foreach ($destinationlist as $destination) { ?>
                                        var option = document.createElement('option');
                                        option.value = <?php echo $destination['destination_id']; ?>;
                                        option.text = <?php echo json_encode($destination['destinationName']); ?>;
                                        eventOptionsSelect.appendChild(option);
                                    <?php } ?>
                                }
                                else if (selectedEventType === 'Open Event'){
                                    
                                    var option = document.createElement('option');
                                    option.text = 'Whole University';
                                    option.value = 'Whole University';
                                    eventOptionsSelect.appendChild(option);

                                }
                            }

                            // Add an event listener to the first dropdown
                            eventTypeSelect.addEventListener('change', function () {
                                // Enable the second dropdown
                                eventOptionsSelect.disabled = false;

                                // Get the selected value from the first dropdown
                                var selectedEventType = eventTypeSelect.value;

                                // Populate the second dropdown based on the selected value
                                populateVenueOptions(selectedEventType);
                            });

                            // Call the function initially to populate the options based on the initial value
                            populateVenueOptions(eventTypeSelect.value);


                            //ADD Event SUBMISSION
                            document.getElementById('addEvent').addEventListener('submit', function (event) {
                                // Loop through the event inputs and check the date range
                                for (var i = 0; i < numOfEvents.value; i++) {

                                    var start = new Date(document.getElementById('addEventStart').value);
                                    var end = new Date(document.getElementById('addEventEnd').value);

                                    // Check if start date is greater than end date
                                    if (start > end) {
                                        event.preventDefault(); // Prevent form submission

                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Invalid Event Duration',
                                            text: 'Start Date cannot be greater than End Date',
                                            timer: 3000
                                        });
                                        return; // Stop checking further events
                                    }
                                }
                            });
                        </script>
                        

                        <div class="row"><!--Event List-->
                            <div class="col">
                                <div class="table-responsive" id="tableEventList">
                                    <table class="table text-start align-middle table-bordered table-hover mb-0 overflow-scroll">
                                        <thead>
                                            <tr class="text-dark">
                                                <th scope="col">Type</th>
                                                <th scope="col">Event Name</th>
                                                <th scope="col">Venue</th>
                                                <th scope="col">Event Start</th>
                                                <th scope="col">Event End</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tData">
                                                
                                                <input type="hidden" id="numOfEvents" value="<?php if(isset($numOfEvents)){ echo $numOfEvents;}?>">
                                                <input type="hidden" id="numOfOpenEvent" value="<?php echo $numOfOpenEvent; ?>">

                                            <?php
                                                
                                                $counterOpenEvent = 0;
                                                for($i=0 ; $i < $numOfEvents; $i++){
                                                    $eventID = $eventList[$i]['event_id'];

                                                    // Modify the SQL query to retrieve openEventQR with a matching eventID
                                                    $sqlGetEventQR = mysqli_query($connection, "SELECT  event_id, openEventQR FROM event_openqr WHERE event_id = '$eventID'");
                                                    
                                                    if (mysqli_num_rows($sqlGetEventQR) > 0) {
                                                        $openEventQR = array();
                                                        while ($row = mysqli_fetch_array($sqlGetEventQR)) {
                                                            $openEventQR[] = $row;
                                                        }
                                                        
                                                        
                                                    }
                                                    
                                            ?>
                                            <tr>
                                                
                                                <?php
                                                    
                                                ?>
                                                
                                                <td class="col"><?php echo $eventList[$i]['typeOfEvent']; ?></td>
                                                <td class="col-2"><?php echo $eventList[$i]['eventName']; ?></td>
                                                <td class="col-1"><?php echo $eventList[$i]['eventVenue']; ?></td>
                                                
                                                <td class="col-2">
                                                    <?php 
                                                    if($eventList[$i]['eventStart'] != '0000-00-00') {
                                                        $eventStart = date("F j, Y", strtotime($eventList[$i]['eventStart']));
                                                        echo $eventStart;
                                                    }
                                                    else{
                                                        echo "";
                                                    }
                                                    
                                                    ?>
                                                </td>
                                                <td class="col-2">
                                                    <?php 
                                                    if($eventList[$i]['eventEnd'] != '0000-00-00') {
                                                        $eventEnd = date("F j, Y", strtotime($eventList[$i]['eventEnd']));
                                                        echo $eventEnd; 
                                                    }
                                                    else{
                                                        echo "";
                                                    }
                                                    ?>
                                                </td>
                                                <td class="col">
                                                    <?php
                                                        if($eventList[$i]['status'] == 0){//DEACTIVATED
                                                            echo '<a class="btn btn-sm btn-secondary" style="pointer-events: none;">INACTIVE</a>';
                                                        }
                                                        else if($eventList[$i]['status'] == 1){ //#ACTIVATED
                                                                echo '<a class="btn btn-sm btn-warning" style="pointer-events: none; color: white;">ACTIVE</a>';
                                                        }
                                                        elseif($eventList[$i]['status'] == 3){//PENDING
                                                            echo '<a class="btn btn-sm" style="background-color: #87CEEB;pointer-events: none; color: white;">PENDING</a>';
                                                        }
                                                        else{
                                                            echo '<a class="btn btn-sm btn-success" style="pointer-events: none;">COMPLETED</a>';
                                                        }
                                                    ?>
                                                    
                                                </td>
                                                <td class="col-4">
                                                    
                                                    
                                                    <?php

                                                        if($eventList[$i]['status'] == 0){
                                                            //style="display:none" the delete button
                                                            echo ' <button class="btn btn-primary lgScreen" id="editEventButton'.$i.'" data-toggle="modal" data-target="#editEvent'.$i.'">Edit</button>
                                                            <button class="btn btn-danger lgScreen" data-toggle="modal" data-target="#Delete'.$i.'" style="display:none">Delete</button>
                                                            <button class="btn btn-success lgScreen" data-toggle="modal" data-target="#Active'.$i.'">Activate</button>


                                                            <button class="btn btn-success smScreen" data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Activate" data-target="#Active'.$i.'"><i class="bi bi-check-square"></i></button>
                                                            <button class="btn btn-primary smScreen" id="editEventButton'.$i.'" data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Edit" data-target="#editEvent'.$i.'"><i class="bi bi-pencil"></i></button>
                                                            <button class="btn btn-danger smScreen" data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Delete" data-target="#Delete'.$i.'" style="display:none"><i class="bi bi-trash"></i></button>
                                                            ';
                                                            
                                                        }
                                                        else if($eventList[$i]['status'] == 1 OR $eventList[$i]['status'] == 3){
                                                            echo '<button class="btn btn-secondary lgScreen" id="open-modal" data-toggle="modal" data-target="#Inactive'.$i.'">Deactivate</button>';
                                                            echo '<button class="btn btn-secondary smScreen" id="open-modal" data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Inactive" data-target="#Inactive'.$i.'"><i class="bi bi-x-square"></i></button>';
                                                            if($eventList[$i]['typeOfEvent'] == 'Open Event'){
                                                                
                                                                echo '<button class="btn btn-primary" data-toggle="modal" id="print"  data-toggle="tooltip" data-placement="top" title="print" data-target="#printButton'.$counterOpenEvent.'"><i class="bi bi-printer"></i></button>';
                                                                
                                                                ?>
                                                                    
                                                                    <!--Print Button-->
                                                                    <div class="modal fade" id="printButton<?php echo $counterOpenEvent;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true"  data-backdrop="static" data-keyboard="false"> <!-- Modal for add Event-->
                                                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-body justify-content-center">
                                                                                <?php

                                                                                    $checkFirstTime = mysqli_query($connection, "SELECT * FROM event_openqr WHERE openEventQR = '' AND event_id = '".$eventList[$i]['event_id']."'");
                                                                                    if(mysqli_num_rows($checkFirstTime) > 0){
                                                                                        $new = 'This will generate a ';
                                                                                        $newlast = '';
                                                                                    }
                                                                                    else{
                                                                                        $new = '<b>Note:</b> This will generate a New ';
                                                                                        $newlast = '<h6>The Last Generated QR Code will be invalid.</h6>';
                                                                                    }
                                                                                ?>
                                                                                    <div class="row justify-content-center">
                                                                                        <p id="deleteBody" class="text-center"><?php echo $new;?>QR Code for the <b><?php echo $eventList[$i]['eventName']; ?> Event</b>.<br> Do you still want to continue?<br><?php echo $newlast;?></p>
                                                                                    </div>
                                                                                    <div class="row justify-content-center">
                                                                                        <div class="col-auto">
                                                                                            <button type="submit" id="generateQRAndOpenWindow<?php echo $counterOpenEvent;?>" class="btn btn-warning" data-dismiss="modal">Yes</button>
                                                                                            <?php
                                                                                                echo '
                                                                                                <input type="hidden" id="openEventID'.$counterOpenEvent.'" name="openEventID" value="'.$eventList[$i]['event_id'].'">
                                                                                                <input type="hidden" id="openEventName'.$counterOpenEvent.'" name="openEventName" value="'.$eventList[$i]['eventName'].'">
                                                                                                ';
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
                                                                $counterOpenEvent++;
                                                            
                                                            }
                                                            
                                                        }

                                                    ?>
                                                    
                                                </td>
                                            </tr> 


                                            

                                        <!--EDIT LIST-->
                                        <div class="modal fade" id="editEvent<?php echo $i;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true"  data-backdrop="static" data-keyboard="false"> <!-- Modal for add Event-->
                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header"><!--Modal Header-->
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Event Information</h5>
                                                    </div>
                                                    
                                                    <?php echo '<input type="hidden" id="typeOfEventDB'.$i.'" value="'.$eventList[$i]["typeOfEvent"].'">';?>
                                                    <?php echo '<input type="hidden" id="eventNameDB'.$i.'" value="'.$eventList[$i]["eventName"].'">';?>
                                                    <?php echo '<input type="hidden" id="eventVenueDB'.$i.'" value="'.$eventList[$i][3].'">';?>
                                                    
                                                    <form method="POST" id="editEventModal<?php echo $i;?>" action="addEventQuery.php">
                                                    
                                                        <?php echo '<input type="hidden" id="eventID'.$i.'" name="eventID" value="'.$eventID.'">';?>
                                                        <div class="modal-body">  <!-- Modal body -->
                                                            
                                                                <div class="container text-center p-0" >
                                                                    <div class="row d-flex align-items-center mb-4 justify-content-center">
                                                                        <div class="col">                                                                        
                                                                            <label for="typeOfEvent" class="form-label">Type of Event:</label>
                                                                            <select class="form-control text-center bg-white" id="typeOfEvent<?php echo $i; ?>" name="typeOfEvent" required>
                                                                                <option value="Exclusive Event" <?php if($eventList[$i]['typeOfEvent'] == 'Exclusive Event'){ echo 'selected';}?>>Exclusive Event</option>
                                                                                <option value="Open Event" <?php if($eventList[$i]['typeOfEvent'] == 'Open Event'){ echo 'selected';}?>>Open Event</option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-auto mt-4">
                                                                                <input type="checkbox" class="form-check-input" name="editEvaluation" id="editEvalutation<?php echo $i;?>" <?php if($eventList[$i]['evaluationForm'] == 1){ echo 'checked'; } ?>>
                                                                                <label class="form-check-label" for="editEvalutation"> Add Evaluation</label>                                                                            
                                                                        </div>
                                                                    </div>
                                                                    <div class="row justify-content-center">
                                                                        
                                                                        <div class="col-12 col-sm-4">
                                                                            <label for="eventName" class="form-label">Event Name</label>
                                                                            <input type="text" class="form-control text-center" id="eventName<?php echo $i; ?>" name="eventName" value="<?php echo $eventList[$i]['eventName']; ?>" required>
                                                                        </div>
                                                                        <div class="col-12 col-sm">
                                                                            <label for="venue" class="form-label">Venue</label>
                                                                            <select id="eventVenue<?php echo $i; ?>" class="form-control text-center bg-white" name="eventVenue" required>
                                                                                <?php
                                                                                if($eventList[$i]['typeOfEvent'] == 'Exclusive Event'){
                                                                                
                                                                                    foreach ($destinationlist as $destination) { ?>
                                                                                        <option value="<?php echo $destination['destination_id']; ?>"
                                                                                            <?php if(isset($eventList[$i][3])){
                                                                                                    if($destination['destination_id'] == $eventList[$i][3]){
                                                                                                        echo 'selected';
                                                                                                    }
                                                                                                }
                                                                                            ?>
                                                                                        >

                                                                                            <?php echo $destination['destinationName']; ?>
                                                                                        </option>

                                                                                <?php 
                                                                                    }
                                                                                } 
                                                                                else if($eventList[$i]['typeOfEvent'] == 'Open Event'){
                                                                                ?>
                                                                                    <option value="Whole University">Whole University</option>
                                                                                <?php
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                    
                                                                    </div>
                                                                    
                                                                </div><br>
                                    
                                                                
                                                        </div><!-- Modal body -->
                                                        <div class="modal-footer justify-content-center"><!-- Modal Footer -->
                                                            <button type="submit" name="editEvent" class="btn btn-primary">Edit Event</button>
                                                            <button type="button" id="editCancel<?php echo $i;?>" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>  <!-- End of Edit Event in List--> 


                                        <!--Delete Button-->
                                        <div class="modal fade" id="Delete<?php echo $i;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true"  data-backdrop="static" data-keyboard="false"> <!-- Modal for add Event-->
                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body">
                                                        <form action="addEventQuery.php" method="POST">
                                                            <?php echo '<input type="hidden" name="eventID" value="'.$eventID.'">';?>
                                                            <p id="deleteBody"> Are you sure you want to Delete the "<?php echo $eventList[$i]['eventName']; ?>" Event?</p>
                                                            <button type="submit" name="deleteEvent" class="btn btn-warning">Delete</button>
                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>  <!-- End of Delete Button-->


                                        <!--Inactive Button--> 
                                        <div class="modal fade" id="Inactive<?php echo $i;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false"> <!-- Modal for add Event-->
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                <div class="modal-content">
                                                <form method="POST" action="addEventQuery.php"> 
                                                    <?php echo '<input type="hidden" name="eventID" value="'.$eventID.'">';?>
                                                    <div class="modal-body">
                                                        <p id="inactiveBody"> Are you sure you want to Deactivate the Event?</p>
                                                        <button type="submit" name="deactivateEvent" class="btn btn-secondary">Deactivate</button>
                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                    </div>
                                                </form>
                                                    </div>
                                                </div>
                                        </div>  <!-- End of Inactive Button-->

                                        <!--Active Button-->     
                                        <div class="modal fade" id="Active<?php echo $i;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false"> <!-- Modal for add Event-->
                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                <div class="modal-content">
                                                <form id="activateEventForm<?php echo $i;?>" method="POST" action="addEventQuery.php">
                                                    <?php 
                                                    echo '<input type="hidden" name="eventID" value="'.$eventID.'">';
                                                    echo '<input type="hidden" name="eventName" value="'.$eventList[$i]['eventName'].'">';
                                                    echo '<input type="hidden" name="typeOfEvent" value="'.$eventList[$i]['typeOfEvent'].'">';
                                                    if($eventList[$i]['typeOfEvent'] == 'Open Event'){
                                                        echo '<input type="hidden" name ="openQR" value="'.$openEventQR[0]['openEventQR'].'">';
                                                    }

                                                    ?>
                                                    <div class="modal-body">
                                                        <p id="activeBody"> Are you sure you want to Activate the Event?</p>
                                                        <div class="row my-4 "> 
                                                                    
                                                            <div class="col">
                                                                <label for="eventStart">Event Start:</label>
                                                                <input type="date" class="form-control" min="<?php echo $dateToday;?>" id="eventStart<?php echo $i;?>" name="eventStart" value="<?php echo $dateToday; ?>">
                                                            </div>
                                                                    
                                                            <div class="col">
                                                                <label for="eventStart">Event End:</label>
                                                                <input type="date" class="form-control" min="<?php echo $dateToday;?>" id="eventEnd<?php echo $i;?>" name="eventEnd" value="<?php echo $dateToday; ?>">
                                                            </div>                                                      
                                                        </div>
                                                    

                                                        <button type="submit" id="activateEvent<?php echo $i; ?>"name="activateEvent" class="btn btn-success">Activate</button>
                                                        <button type="button" id="activateCancel<?php echo $i; ?>" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                    </div>
                                                </form>
                                                <script>
                                                    
                                                </script>
                                                </div>
                                            </div>
                                        </div><!-- End of Active Button-->
                                        
                                        <?php
                                        }
                                        ?>

                                    </table>
                                </div>
                            </div>
                    </div><br><!--End of Event List-->
                </div>
                <script>

                    var numOfEvents = document.getElementById('numOfEvents');

                    //EDIT LIST
                    for (var i = 0; i < numOfEvents.value; i++) {
                        (function (index) { //kaya may index foreach ng typeOfEvent0, 1, 2 etc maaaccess yung script.

                            var typeOfEventElement = "typeOfEvent" + index;
                            var eventVenueElement = "eventVenue" + index;
                            var eventVenueDBElement = "eventVenueDB" + index; // para kapag binalik yung 

                                 
                            statusDBElement = "statusDB" + index;

                            eventStartDBElement = "eventStartDB" + index;
                            eventEndDBElement = "eventEndDB" + index;

                            document.getElementById(typeOfEventElement).addEventListener('change', function () {
                                // Enable the second dropdown
                                document.getElementById(eventVenueElement).disabled = false;

                                // Clear the second dropdown's options
                                document.getElementById(eventVenueElement).innerHTML = '';

                                var selectedValue = document.getElementById(typeOfEventElement).value;
                                

                                // Populate the second dropdown based on the selected value
                                if (selectedValue === 'Open Event') {
                                    var option = document.createElement('option');
                                    option.text = 'Whole University';
                                    option.value = 'Whole University';
                                    document.getElementById(eventVenueElement).appendChild(option);
                                } else if (selectedValue === 'Exclusive Event') {
                                    var defaultOption = document.createElement('option');
                                    defaultOption.value = '';
                                    defaultOption.text = '';
                                    defaultOption.selected = true;
                                    defaultOption.disabled = true;
                                    document.getElementById(eventVenueElement).appendChild(defaultOption);

                                    var selectedVenueDBValue = document.getElementById(eventVenueDBElement).value;
                                    <?php foreach ($destinationlist as $destination) { ?>
                                        var option = document.createElement('option');
                                        option.value = <?php echo $destination['destination_id']; ?>;
                                        option.text = <?php echo json_encode($destination['destinationName']); ?>;
                                        
                                        if(selectedVenueDBValue === option.value){
                                            option.selected = true;
                                        }
                                        else{
                                            option.selected = false;
                                        }
                                        document.getElementById(eventVenueElement).appendChild(option);
                                    <?php } ?>

                                }
                                

                            });

                            //Edit Event SUBMISSION IF ACTIVE
                            var editEventModalElement = "editEventModal" + index;
                            var editStatusDBElement = "statusDB" + index;

                            document.getElementById(editEventModalElement).addEventListener('submit', function (event) {
                                for (var i = 0; i < numOfEvents.value; i++) {

                                    if (document.getElementById(editStatusDBElement).value == 1) {
                                        event.preventDefault(); // Prevent form submission

                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Deactivate the Event First',
                                            text: 'You must deactivate the event first to edit.',
                                            timer: 3000
                                        });
                                        return; // Stop checking further events
                                    }
                                }
                            });

                            //Activate Event SUBMISSION
                            var activateEventFormElement = "activateEventForm" + index;
                            document.getElementById(activateEventFormElement).addEventListener('submit', function (event) {

                                for (var i = 0; i < numOfEvents.value; i++) {
                                    var eventStartEditElement = "eventStart" + i;
                                    var eventEndEditElement = "eventEnd" + i;

                                    var start = new Date(document.getElementById(eventStartEditElement).value);
                                    var end = new Date(document.getElementById(eventEndEditElement).value);

                                    // Check if start date is greater than end date
                                    if (start > end) {
                                        event.preventDefault(); // Prevent form submission

                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Invalid Event Duration',
                                            text: 'Start Date cannot be greater than End Date',
                                            timer: 3000
                                        });
                                        return; // Stop checking further events
                                    }
                                }
                            });

                            

                            var editCancelElement = "editCancel" + index;
                            
                            var typeOfEventDBElement = "typeOfEventDB" + index;
                            var eventNameElement = "eventName" + index;
                            var eventNameDBElement = "eventNameDB" + index;
                            
                            const todayLocal1 = new Date();
                            // Get the offset in minutes for the Asia/Manila timezone (UTC+8)
                            const manilaOffset1 = 8 * 60; // 8 hours * 60 minutes
                            // Calculate the time for Asia/Manila timezone
                            const manilaTime1 = new Date(todayLocal1.getTime() + (manilaOffset1 * 60 * 1000));
                            // Format the date and time as a string
                            const today1 = manilaTime1.toISOString().split('T')[0];

                            // KAPAG NAGCANCEL SA MODAL BACK TO RESET YUNG INPUT NA NANDON
                            document.getElementById(editCancelElement).addEventListener('click', function () {
                                //kaya ganto puro document.getelement, it wont work if ilalagay nlanag natin siya sa isang variable pero value magiiba. dahil nagloloop;
                                

                                //EVENT NAME
                                document.getElementById(eventNameElement).value = document.getElementById(eventNameDBElement).value;

                                //typeofEvent
                                document.getElementById(typeOfEventElement).innerHTML = '';

                                var typeOfEvent = ['Exclusive Event', 'Open Event'];
                                var countTypeOfEvent = typeOfEvent.length;
                                var optionType = [];
                                var selectedTypeOfEventDBValue = document.getElementById(typeOfEventDBElement).value;

                                for(var a=0; a < countTypeOfEvent; a++){
                                    optionType[a] = "optionType" + a;
                                    optionType[a]= document.createElement('option');
                                    optionType[a].text = typeOfEvent[a];
                                    optionType[a].value = typeOfEvent[a];

                                    if(selectedTypeOfEventDBValue === typeOfEvent[a]){
                                        optionType[a].selected = true;
                                    }
                                    else{
                                        optionType[a].selected = false;
                                    }
                                    document.getElementById(typeOfEventElement).appendChild(optionType[a]);
                                }

                                document.getElementById(eventVenueElement).innerHTML = '';

                                // Get the selected value from the first dropdown
                                var selectedValue = document.getElementById(typeOfEventElement).value;
                                

                                // Populate the second dropdown based on the selected value
                                if (selectedValue === 'Open Event') {
                                    var option = document.createElement('option');
                                    option.text = 'Whole University';
                                    option.value = 'Whole University';
                                    document.getElementById(eventVenueElement).appendChild(option);
                                } else if (selectedValue === 'Exclusive Event') {
                                    var defaultOption = document.createElement('option');
                                    defaultOption.value = '';
                                    defaultOption.text = '';
                                    defaultOption.disabled = true;
                                    document.getElementById(eventVenueElement).appendChild(defaultOption);

                                    var selectedVenueDBValue = document.getElementById(eventVenueDBElement).value;
                                    <?php foreach ($destinationlist as $destination) { ?>
                                        var option = document.createElement('option');
                                        option.value = <?php echo $destination['destination_id']; ?>;
                                        option.text = <?php echo json_encode($destination['destinationName']); ?>;
                                        
                                        if(selectedVenueDBValue === option.value){
                                            option.selected = true;
                                        }
                                        else{
                                            option.selected = false;
                                        }
                                        document.getElementById(eventVenueElement).appendChild(option);
                                    <?php } ?>

                                }

                            });


                        })(i);
                    }

                    //ACTIVATE EVENT

                    
                    var activateNumOfEvents = document.getElementById('numOfEvents');
                    
                    // Get the current date in the user's local timezone
                    const todayLocal2 = new Date();
                    // Get the offset in minutes for the Asia/Manila timezone (UTC+8)
                    const manilaOffset2 = 8 * 60; // 8 hours * 60 minutes
                    // Calculate the time for Asia/Manila timezone
                    const manilaTime2 = new Date(todayLocal2.getTime() + (manilaOffset2 * 60 * 1000));
                    // Format the date and time as a string
                    const today2 = manilaTime2.toISOString().split('T')[0];

                    for (var a = 0; a < activateNumOfEvents.value; a++) {
                        (function (index) {

                            

                            var eventStartActivateElement = "eventStart" + index;

                            // Set the default value of the input field to today's date
                            document.getElementById(eventStartActivateElement).defaultValue = today2;

                            // Add an event listener to check for changes in the input
                            document.getElementById(eventStartActivateElement).addEventListener('change', function() {

                                var selectedStartDate = document.getElementById(eventStartActivateElement).value;

                                // Check if the selected date is in the past
                                if (selectedStartDate < today2) {     
                                    document.getElementById(eventStartActivateElement).value = today2; // Reset to today's date
                                }
                            });
                            

                            var eventEndActivateElement = "eventEnd" + index;
                            // Set the default value of the input field to today's date
                            document.getElementById(eventEndActivateElement).defaultValue = today2;

                            // Add an event listener to check for changes in the input
                            document.getElementById(eventEndActivateElement).addEventListener('change', function() {

                                var selectedEndDate = document.getElementById(eventEndActivateElement).value;

                                // Check if the selected date is in the past
                                if (selectedEndDate < today2 ) {     
                                    document.getElementById(eventEndActivateElement).value = today2; // Reset to today's date
                                }
                            });

                            var cancelActivateElement = "activateCancel" + index;

                            document.getElementById(cancelActivateElement).addEventListener('click', function() {
                                document.getElementById(eventStartActivateElement).value = today2;
                                document.getElementById(eventEndActivateElement).value = today2;
                            });

                        })(a);
                    }

                //PRINT 
                    var numOfOpenEvent = document.getElementById('numOfOpenEvent');

                    for (var a = 0; a < numOfOpenEvent.value; a++) {
                        (function (index) {
                            var generateQRAndOpenWindowElement = "generateQRAndOpenWindow" + index;
                            var openEventIDElement = "openEventID" + index;
                            var openEventNameElement = "openEventName" + index;

                            var generateQRAndOpenWindow = document.getElementById(generateQRAndOpenWindowElement);
                            var openEventID = document.getElementById(openEventIDElement);
                            var openEventName = document.getElementById(openEventNameElement);

                            if (generateQRAndOpenWindow) {
                                generateQRAndOpenWindow.addEventListener('click', function() {
                                    var openID = openEventID ? openEventID.value : "";
                                    var openName = openEventName ? openEventName.value : "";

                                    console.log(openID);
                                    
                                    var encdata = btoa("Open Event"+'***    '+openID+'***'+openName);
                                    

                                    // Get the URL of the image that is already set in the main PHP file 
                                    const windowName = `qr_window_${Date.now()}`;
                                    // Open qr.php in a new window with the image URL as a query parameter
                                    //pwede ihash muna para di makita talaga then i un-hashed
                                    const newWindow = window.open(`printdocs/printEventQR.php?encdata=${encdata}`, windowName);
                                });
                            }
                        })(a);
                    }

                
                    
                    
                    
                    
                    
                        

                    

                </script>

            </div>
        </div> <!-- Add Event End -->
           


        </div><!-- Content End -->
        


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

<?php



?>