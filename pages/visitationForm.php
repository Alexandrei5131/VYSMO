<?php
    //VISITOR
    include('../database.php');
    include('session.php');

    //kapag nakalogged in na then nagtry iredirect sa ibang pages na hindi naman nila role
    if($_SESSION['role'] == 'Admin'){
        echo "<script>window.location.href = 'dashboard.php'</script>";
    }
    elseif($_SESSION['role']  == 'Guard'){
        echo "<script>window.location.href = 'scanAccountQR.php'</script>";
    }

    $accountID = $_SESSION['accountID'];
    $accountEmail = $_SESSION['email'];
    $accountQR = $_SESSION['accountQR'];
    $role = $_SESSION['role'];
    
    include('getAccountInfo.php');


    $getDestinationList = mysqli_query($connection, "SELECT destination_id, destination, destinationName FROM destination_list WHERE status = 0 ORDER BY destination ASC");
    $numberOfDestination = mysqli_num_rows($getDestinationList);
    if(mysqli_num_rows($getDestinationList) > 0){

        $destinationlist = array();
        while($row = mysqli_fetch_array($getDestinationList)){
            // add each row returned into an array
            $destinationlist[] = $row; //info of eventlist

        }
    }
        
    $sqlActiveEvent = mysqli_query($connection, "SELECT event_id, eventName, eventVenue, eventEnd FROM event_list WHERE  typeOfEvent = 'Exclusive Event' AND status = 1");

    if(mysqli_num_rows($sqlActiveEvent) > 0){
        $numOfActiveEvent = mysqli_num_rows($sqlActiveEvent);
        $activeEvent = array();

        while($row = mysqli_fetch_array($sqlActiveEvent)){
            // add each row returned into an array
            $destinationID = $row['eventVenue'];
            $getVenueDestination = mysqli_query($connection, "SELECT destination FROM destination_list WHERE destination_id = '$destinationID'");
            // Fetch the destination
            $destinationRow = mysqli_fetch_array($getVenueDestination);
            $destination = $destinationRow['destination'];

            // Add the destination to the $row array
            $row['destination'] = $destination;

            $activeEvent[] = $row; //info of eventlist
        }

        /*
        echo "<pre>";
        echo print_r($activeEvent);
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

    <!--SWEET ALERT-->
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
    <link rel=”stylesheet” href=”css/bootstrap-responsive.css”>

    <!-- Template Stylesheet
    <link href="css/style.css" rel="stylesheet"> -->
    <link href="css/style2.php" rel="stylesheet">

    <style>
         #forms li {
            cursor: pointer;
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
                    <a href="visitationForm.php" id="visitationForm" class="nav-item nav-link active"><i class="bi bi-people"></i> Visitation Form</a>
                    <a href="visitationList.php" class="nav-item nav-link"><i class="bi bi-clock-history"></i> Visitation List</a>
                    <a href="logout.php" class="nav-item nav-link "><i class="bi bi-box-arrow-left"></i> Logout</a>
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->
        


        <!-- Content Start -->
        <div class="content" id="sidebar">

                <!-- Add Guard Start -->
                <nav class="navbar navbar-light d-flex justify-content-center head1">
                        <a href="#" class="sidebar-toggler flex-shrink-0">
                                <i class="fa fa-bars"></i>
                        </a>
                        <div class="position-relative">
                                <img class="rounded-circle guardLogo" src="../images/logo.png" alt="" >
                        </div>
                        <span class="navbar-brand mb-0 h1">Nueva Ecija University of Science and Technology</span>
                    
                </nav>
                
                <div class="container-fluid pt-4 px-4 visitFormCopy">
                    <div class="rounded itemContain bg-light">
                                
                                <div class="formContent">
                                    <nav class="navbar justify-content-center mb-0 visitInfo">
                                        
                                    <ul id="forms" class="">
                                        <li><a class="" href="#person" id="person" onclick="showVisitForm('personForm'); clearText();">Person</a></li>
                                        <li><a class="" href="#office" id="office" onclick="showVisitForm('officeForm'); clearText();">Office/Building</a></li>
                                        <li><a class="" href="#event" id="event" onclick="showVisitForm('eventForm'); clearText();">Event</a></li>
                                    </ul>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                        var navItems = document.getElementsByTagName('li');

                                        navItems[0].classList.add('active');    
                                        
                                        for (var i = 0; i < navItems.length; i++) {
                                            navItems[i].addEventListener('click', function() {
                                            
                                            for (var j = 0; j < navItems.length; j++) {
                                                navItems[j].classList.remove('active');
                                                navItems[j].style.pointerEvents = 'auto';
                                            }
                                            this.classList.add('active');
                                            this.style.pointerEvents = 'none';

                                            });
                                        }
                                        });
                                    </script>
                                    </nav>
                                    <!--person-->
                                    <form action="" method="POST" enctype="multipart/form-data" class="visitForm" id="personForm">
                                        
                                        <div class="p-4 personContent mt-5">
                                            <div class="row justify-content-center numVisDate">
                                                
                                                <div class="col-auto numVis">
                                                    <div class="Name  text-center">
                                                        <label for="visNum" style="font-size: larger;"><strong>Number of Visitor:</strong>  </label>
                                                    </div>
                                                    <div class="column">
                                                        <input type="number" class="form-control text-center" id="numberOfVisitorPerson" name="numberOfVisitor" min="1" required>
                                                    </div>
                                                    
                                                </div>
                                                <div class="col-auto date">
                                                    <div class="Name  text-center">
                                                        <label style="font-size: larger;"><strong>Date to Visit:</strong>  </label>
                                                    </div>
                                                    <div class="column">
                                                        <input type="date" class="form-control text-center" id="dateToVisitPerson" name="dateToVisit" value="<?php echo date('Y-m-d');?>" required>
                                                    </div>
                                                </div>
  
                                            </div> 
                                            
                                            <div class="row">
                                                    <div class="d-flex justify-content-center mt-3">
                                                        <div class="">
                                                            <label style="font-size: larger;"><strong>Name to Visit:</strong></label>
                                                        </div>
                                                    </div>
                                            </div>

                                            <div class="row justify-content-center">

                                                        <div class="col-auto fname">
                                                            <div class="Name  text-center">
                                                                <label><strong>First Name:</strong>  </label>
                                                            </div>
                                                            <div class="column">
                                                                <input type="text" class="form-control text-center" id="fNamePerson" name="firstNamePerson" required>
                                                            </div>
                                                        </div>
                                                   
                                                        <div class="col-auto lname">
                                                            <div class="Name text-center">
                                                                <label><strong>Last Name:</strong>  </label>
                                                            </div>
                                                            <div class="column">
                                                                <input type="text" class="form-control text-center" id="lNamePerson" name="lastNamePerson" required>
                                                            </div>
                                                        
                                                        </div>
                                                    

                                                    
                                                        <div class="col-auto sname">
                                                            <div class="Suffix text-center">
                                                                <label><strong>Suffix:</strong>  </label>
                                                            </div>
                                                            <div>
                                                                <select class="form-control bg-white text-center" id="suffix" name="suffixNamePerson">
                                                                    <option value="" selected></option>
                                                                    <option value="Jr">Jr.</option>
                                                                    <option value="II">II</option>
                                                                    <option value="III">III</option>
                                                                    <option value="Sr">Sr.</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                </div>
                                            
                                            
                                                    <div class="row justify-content-center">
                                                        <div class="col-auto person">
                                                            <div class="Name text-center">
                                                                <label><strong>Person to Visit:</strong>  </label>
                                                            </div>
                                                            <div class="column">
                                                                <select id="personVisit" name="personToVisit" required class="form-control text-center bg-white" >
                                                                <option value="" disabled selected>--select--</option>
                                                                <option value="Student">Student</option>
                                                                <option value="Faculty">Faculty</option>
                                                                <option value="Staff">Staff</option>
                                                                <option value="Dean">Dean</option>
                                                            </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-auto relationship">
                                                            <div class="Name text-center">
                                                                <label><strong>Relationship:</strong>  </label>
                                                            </div>
                                                            <div class="column">
                                                                <input type="text" class="form-control text-center" id="rel" name="relationshipPerson" required>
                                                            </div>
                                                        </div>
                                                                                                     
                                                        <div class="col-auto dept">
                                                            <div class="Name  text-center">
                                                                <label><strong>Department:</strong>  </label>
                                                            </div>
                                                            <div class="column">
                                                            <select id="dept" name="departmentPerson" class="form-control text-center bg-white" required>
                                                                <option value="" selected disabled>--select--</option>
                                                                <?php
                                                                    for($i=0; $i < $numberOfDestination; $i++){
                                                                ?>
                                                                        <option value="<?php echo $destinationlist[$i]['destination_id'];?>"><?php echo $destinationlist[$i]['destinationName'];?></option>
                                                                <?php
                                                                    }
                                                                ?>
                                                            </select>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>

                                            <div class="row justify-content-center mt-3">
                                                <div class="col-auto purpose">
                                                    <div class="Name text-center">
                                                        <label><strong>Purpose of Visit:</strong>  </label>
                                                    </div>
                                                    <div class="column">
                                                        <textarea  class="form-control text-center" id="purpose" name="purposePerson" required style="resize:none;"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-center mt-3">
                                                <button type="submit" name="submitPerson" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>    
                                    </form>  

                                    <!--office-->
                                    <form action="" method="POST" enctype="multipart/form-data" class="visitForm" id="officeForm" style="display:none;">
                                        <div class="p-4 personContent mt-5">
                                            <div class="row justify-content-center numVisDate">
                                                
                                                <div class="col-auto numVis">
                                                    <div class="Name  text-center">
                                                        <label for="visNum" style="font-size: larger;"><strong>Number of Visitor:</strong>  </label>
                                                    </div>
                                                    <div class="column">
                                                        <input type="number" class="form-control text-center" id="numberOfVisitorOffice" name="numberOfVisitor" min="1" required>
                                                    </div>
                                                    
                                                </div>
                                                <div class="col-auto date">
                                                    <div class="Name  text-center">
                                                        <label style="font-size: larger;"><strong>Date to Visit:</strong>  </label>
                                                    </div>
                                                    <div class="column">
                                                        <input type="date" class="form-control text-center" id="dateToVisitOffice" name="dateToVisit" value="<?php echo date('Y-m-d');?>" required>
                                                    </div>
                                                    
                                                </div>
  
                                            </div> 
                                            
                                            
                                            
                                            <div class="row justify-content-center mt-5">                                                                                              
                                                <div class="col-auto justify-content-center office">                                                   
                                                    
                                                        <div class="Name  text-center">
                                                            <label><strong>Office/Building Name:</strong>  </label>
                                                        </div>
                                                        <div class="column">
                                                            <select id="office" name="officeBldgName" class="form-control text-center bg-white" required>
                                                            <option value="" selected disabled>--select--</option>
                                                                <?php
                                                                    for($i=0; $i < $numberOfDestination; $i++){
                                                                ?>
                                                                        <option value="<?php echo $destinationlist[$i]['destination_id'];?>"><?php echo $destinationlist[$i]['destinationName'];?></option>
                                                                <?php
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        
                                                                                                                                                                                                             
                                                </div>
                                            </div>

                                            <div class="row justify-content-center mt-3">
                                                <div class="col-auto mx-2 purpose">
                                                    <div class="Name text-center">
                                                        <label><strong>Purpose of Visit:</strong>  </label>
                                                    </div>
                                                    <div class="column">
                                                        <textarea  class="form-control text-center" id="purpose1" name="purposeOffice" required style="resize:none;"></textarea>
                                                    </div>
                                                        
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-center mt-3">
                                                <button type="submit" name="submitOffice" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>   
                                    </form>

                                    <!--event-->
                                    <form action="" onsubmit="return validateEventSubmit();" method="POST" enctype="multipart/form-data" class="visitForm" id="eventForm" style="display:none;">
                                        <input type="hidden" id="eventID" name="eventID" placeholder="eventID" value="">
                                        <input type="hidden" id="eventEnd" placeholder="eventEnd" value="">
                                        <div class="p-4 personContent mt-5">
                                        <div class="row justify-content-center numVisDate">
                                                
                                                <div class="col-auto numVis">
                                                    <div class="Name  text-center">
                                                        <label for="visNum" style="font-size: larger;"><strong>Number of Visitor:</strong>  </label>
                                                    </div>
                                                    <div class="column">
                                                        <input type="number" class="form-control text-center" id="numberOfVisitorEvent" name="numberOfVisitor" min="1" required>
                                                    </div>
                                                    
                                                </div>
                                                <div class="col-auto date">
                                                    <div class="Name  text-center">
                                                        <label style="font-size: larger;"><strong>Date to Visit:</strong>  </label>
                                                    </div>
                                                    <div class="column">
                                                        <input type="date" class="form-control text-center" id="dateToVisitEvent" name="dateToVisit" value="<?php echo date('Y-m-d');?>" required>
                                                    </div>
                                                    
                                                </div>
  
                                            </div> 
                                            
                                            
                                            
                                            <div class="row justify-content-center mt-5">                                                                                              
                                                <div class="col-auto justify-content-center event">                                                   
                                                    
                                                        <div class="Name  text-center">
                                                            <label><strong>Event Name:</strong>  </label>
                                                        </div>
                                                        <div class="column">
                                                            <select id="eventName" name="eventName" class="form-control text-center bg-white" required>
                                                                <option value="" disabled selected>--select--</option>
                                                                <?php
                                                                    for($i=0; $i < $numberOfDestination; $i++){

                                                                        for($x=0; $x < $numOfActiveEvent; $x++){
                                                                            if($destinationlist[$i]['destination_id'] == $activeEvent[$x]['eventVenue']){
                                                                                echo '<option value="'.$activeEvent[$x]['eventVenue'].'">'.$activeEvent[$x]['eventName'].'</option>';
                                                                            }
                                                                        }
                                                                        
                                                                    }
                                                                
                                                                ?>
                                                            </select><br>
                                                        </div>
                                                        
                                                                                                                                                                                                             
                                                </div>
                                            </div>
                                            <div class="row justify-content-center mt-2">                                                                                              
                                                <div class="col-auto justify-content-center venue">                                                   
                                                    
                                                        <div class="Name  text-center">
                                                            <label><strong>Venue:</strong>  </label>
                                                        </div>
                                                        <div class="column">
                                                            <select id="venue" name="venue" class="form-control text-center bg-white" style="pointer-events: none" required>
                                                                <option value="" selected disabled>--select--</option>
                                                            </select>
                                                        </div>
                                                                                                                                                                                                             
                                                </div>
                                            </div>

                                            <div class="row justify-content-center mt-2">
                                                <div class="col-auto mx-2 purpose">
                                                    <div class="Name text-center">
                                                        <label><strong>Purpose of Visit:</strong>  </label>
                                                    </div>
                                                    <div class="column">
                                                        <textarea  class="form-control text-center" id="purpose2" name="purposeEvent" required style="resize:none;"></textarea>
                                                    </div>
                                                        
                                                </div>
                                            </div>
                                            
                                            <div class="d-flex justify-content-center mt-3">
                                                <button type="submit" name="submitEvent" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>  
                                    </form>  

                                </div>

                                <script>
                                    // Define an object to store event name and venue data
                                    var venueData = {
                                        <?php
                                        foreach ($activeEvent as $event) {
                                            echo $event['eventVenue'] . ': "' . $event['destination'] . '", ';
                                        }
                                        ?>
                                    
                                    };
                                    
                                    var eventEndData = {
                                        <?php
                                            foreach ($activeEvent as $eventEnd) {
                                                echo $eventEnd['eventVenue'] . ': "' . $eventEnd['eventEnd'] . '", ';
                                            }
                                        ?>
                                    };

                                    var eventIDData = {
                                        <?php
                                            foreach ($activeEvent as $eventID) {
                                                echo '"' . $eventID['eventVenue'] . '": "' . $eventID['event_id'] . '", ';
                                            }
                                        ?>
                                    };

                                    //eventName is used only for the return eventsubmit
                                    var eventNameData = {
                                        <?php
                                            foreach ($activeEvent as $eventName) {
                                                echo '"' . $eventName['eventVenue'] . '": "' . $eventName['eventName'] . '", ';
                                            }
                                        ?>
                                    };

                                    // Bind the updateEventVenue function to the change event of eventName dropdown
                                    document.getElementById('eventName').addEventListener('change', function() {
                                        var selectedEvent = document.getElementById('eventName').value;//value neto is event_id
                                        var venueDropdown = document.getElementById('venue');
                                        var eventEndInput = document.getElementById('eventEnd');
                                        var eventIDInput = document.getElementById('eventID');



                                        // Add the default option
                                        var defaultOption = document.createElement('option');
                                        defaultOption.value = '';
                                        defaultOption.text = '--Select Event Name--';
                                        defaultOption.selected = true;
                                        venueDropdown.appendChild(defaultOption);

                                        // If a valid event is selected, add the corresponding venue as an option
                                        if (selectedEvent in venueData) {
                                            // Clear the venue dropdown
                                            venueDropdown.innerHTML = '';
                                            var venueOption = document.createElement('option');
                                            venueOption.value = selectedEvent;
                                            venueOption.text = venueData[selectedEvent];
                                            venueDropdown.appendChild(venueOption);

                                            eventEndInput.value = eventEndData[selectedEvent];
                                            eventIDInput.value = eventIDData[selectedEvent];
                                        }

                                    });
                                </script>

                                <script>

                                    function validateEventSubmit() {
                                        var selectedDate = new Date(document.getElementById('dateToVisitEvent').value);
                                        var eventName =document.getElementById('eventName').value;
                                        var eventEndString = document.getElementById('eventEnd').value;
                                        var eventEnd = new Date(eventEndString);

                                        if (selectedDate > eventEnd) {
                                            // Format the eventEnd date into word date format
                                            var eventEndFormatted = eventEnd.toLocaleDateString(undefined, {
                                                year: 'numeric',
                                                month: 'long',
                                                day: 'numeric'
                                            });


                                            Swal.fire({
                                                icon: 'warning',
                                                title: 'Invalid Date To Visit',
                                                text: eventNameData[eventName] + ' Event ends on ' + eventEndFormatted,
                                                timer: 3000
                                            });
                                            return false; // Prevent form submission
                                        }

                                        return true; // Allow form submission
                                    }
                                </script>


                                <script>//-
                                    // Get the input element by its ID
                                    const dateInputPerson = document.getElementById('dateToVisitPerson');
                                    const dateInputOffice = document.getElementById('dateToVisitOffice');
                                    const dateInputEvent = document.getElementById('dateToVisitEvent');
                                    

                                    //asia manila time
                                    // Get the current date in the user's local timezone
                                    const todayLocal = new Date();
                                    // Get the offset in minutes for the Asia/Manila timezone (UTC+8)
                                    const manilaOffset = 8 * 60; // 8 hours * 60 minutes
                                    // Calculate the time for Asia/Manila timezone
                                    const manilaTime = new Date(todayLocal.getTime() + (manilaOffset * 60 * 1000));
                                    // Format the date and time as a string
                                    const today = manilaTime.toISOString().split('T')[0];

                                    // Set the default value of the input field to today's date
                                    dateInputPerson.defaultValue = today;

                                    // Add an event listener to check for changes in the input
                                    dateInputPerson.addEventListener('change', function() {
                                        const selectedDate = dateInputPerson.value;

                                        // Check if the selected date is in the past
                                        if (selectedDate < today) {
                                            dateInputPerson.value = today; // Reset to today's date
                                        }
                                    });

                                    // Set the default value of the input field to today's date
                                    dateInputOffice.defaultValue = today;

                                    // Add an event listener to check for changes in the input
                                    dateInputOffice.addEventListener('change', function() {
                                        const selectedDate = dateInputOffice.value;

                                        // Check if the selected date is in the past
                                        if (selectedDate < today) {
                                            dateInputOffice.value = today; // Reset to today's date
                                        }
                                    });
                                    
                                    // Set the default value of the input field to today's date
                                    dateInputEvent.defaultValue = today;

                                    // Add an event listener to check for changes in the input
                                    dateInputEvent.addEventListener('change', function() {
                                        const selectedDate = dateInputEvent.value;

                                        // Check if the selected date is in the past
                                        if (selectedDate < today) {
                                            dateInputEvent.value = today; // Reset to today's date
                                        }
                                    });
                                </script>
                                
                                <script>
                                    // JavaScript function to show/hide content based on the selected navigation link
                                    function showVisitForm(contentId) {
                                        // Hide all content divs
                                        var contents = document.getElementsByClassName('visitForm');
                                        for (var i = 0; i < contents.length; i++) {
                                            contents[i].style.display = 'none';
                                        }
                                        
                                        // Show the selected content div
                                        var selectedContent = document.getElementById(contentId);
                                        if (selectedContent) {
                                            selectedContent.style.display = 'block';
                                        }

                                    }
                                    function clearText(){
                                        document.getElementById('numberOfVisitorPerson').value = '';
                                        document.getElementById('dateToVisitPerson').value = '<?php echo date('Y-m-d'); ?>';
                                        document.getElementById('fNamePerson').value = '';
                                        document.getElementById('lNamePerson').value = '';
                                        document.getElementById('suffix').selectedIndex = 0;
                                        document.getElementById('personVisit').selectedIndex = 0;
                                        document.getElementById('dept').selectedIndex = 0;
                                        document.getElementById('rel').value = '';
                                        document.getElementById('purpose').value = '';  
                                        
                                        document.getElementById('numberOfVisitorOffice').value = '';
                                        document.getElementById('dateToVisitOffice').value = '<?php echo date('Y-m-d'); ?>';
                                        document.getElementById('office').selectedIndex = 0; 
                                        document.getElementById('purpose1').value = ''; 

                                        document.getElementById('numberOfVisitorEvent').value = '';
                                        document.getElementById('dateToVisitEvent').value = '<?php echo date('Y-m-d'); ?>';
                                        document.getElementById('eventName').selectedIndex = 0; 
                                        document.getElementById('venue').selectedIndex = 0; 
                                        document.getElementById('purpose2').value = ''; 
                                    }
                                    
                                </script>

                </div>                
                <!-- Add Guard End -->
                
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

<?php
    date_default_timezone_set("Asia/Manila");
    
    if(isset($_POST['submitPerson'])){
//PERSON
        //echo "<script>alert($accountID)</script>";
        $typeOfVisit = "Requested Visit";
        $typeOfForm = "Individual Person";
        
        $destination = mysqli_real_escape_string($connection, $_POST['departmentPerson']);
        $numberOfVisitor = mysqli_real_escape_string($connection, $_POST['numberOfVisitor']);
        
        $insertVisitationRecord = "INSERT INTO visitation_records (account_id, typeOfVisit, typeOfForm, destination_id, numberOfVisitor) VALUE ('$accountID', '$typeOfVisit','$typeOfForm', '$destination', '$numberOfVisitor')";
        echo $insertVisitationRecord;
        if(mysqli_query($connection, $insertVisitationRecord)){
            $visitationID = mysqli_insert_id($connection); //visitationID

            $dateSubmit = date("Y-m-d");
            $appointmentVisit = date('Y-m-d', strtotime($_POST['dateToVisit']));
            

            $insertVisitRequest = "INSERT INTO visit_requested (visitation_id, dateSubmit, appointmentVisit) VALUE ('$visitationID', '$dateSubmit', '$appointmentVisit')";

            if(mysqli_query($connection, $insertVisitRequest)){

                $personToVisit = mysqli_real_escape_string($connection, $_POST['personToVisit']);
                $firstNamePerson = mysqli_real_escape_string($connection, $_POST['firstNamePerson']);
                $lastNamePerson = mysqli_real_escape_string($connection, $_POST['lastNamePerson']);
                $suffixNamePerson = mysqli_real_escape_string($connection, $_POST['suffixNamePerson']);
                $relationshipPerson = mysqli_real_escape_string($connection, $_POST['relationshipPerson']);
                $purposePerson = mysqli_real_escape_string($connection, $_POST['purposePerson']);

                $insertVisitPerson = "INSERT INTO visit_person (visitation_id, personToVisit, personFirstName, personLastName, personSuffixName, personRelationship, personPurpose) VALUES ('$visitationID', '$personToVisit', '$firstNamePerson', '$lastNamePerson', '$suffixNamePerson', '$relationshipPerson', '$purposePerson')";
                if(mysqli_query($connection, $insertVisitPerson)){

                    $getDestinationName = mysqli_query($connection, "SELECT destinationName, destinationLink FROM destination_list WHERE destination_id = '$destination'");
                    $destinationName = mysqli_fetch_array($getDestinationName);
                    echo "<script>window.location = 'sendEmail/emailVisitationForm.php?email=$accountEmail&destinationName=$destinationName[0]&destinationLink=$destinationName[1]';</script>";
                    
                }

            }
            else{
                echo "<script>alert('visit requested insertfailed')</script>";
            }


        }
        else{
            echo "<script>alert('visitation records insertfailed')</script>";
        }
        

    }
    elseif(isset($_POST['submitOffice'])){
//OFFICE
        $typeOfVisit = "Requested Visit";
        $typeOfForm = "Office/Building";
        
        $destination = mysqli_real_escape_string($connection, $_POST['officeBldgName']);
        $numberOfVisitor = mysqli_real_escape_string($connection, $_POST['numberOfVisitor']);

        $insertVisitationRecord = "INSERT INTO visitation_records (account_id, typeOfVisit, typeOfForm, destination_id, numberOfVisitor) VALUES ('$accountID', '$typeOfVisit','$typeOfForm', '$destination','$numberOfVisitor')";
        if(mysqli_query($connection, $insertVisitationRecord)){
            $visitationID = mysqli_insert_id($connection); //visitationID

            $dateSubmit = date("Y-m-d");
            $appointmentVisit = date('Y-m-d', strtotime($_POST['dateToVisit']));

            $insertVisitRequest = "INSERT INTO visit_requested (visitation_id, dateSubmit, appointmentVisit) VALUE ('$visitationID', '$dateSubmit', '$appointmentVisit')";

            if(mysqli_query($connection, $insertVisitRequest)){
                //destination yung officeBldgName
                $purposeOffice = mysqli_real_escape_string($connection, $_POST['purposeOffice']);

                $sqlVisitationOffice = "INSERT INTO visit_officebldg (visitation_id, officeBldgPurpose) VALUES ('$visitationID', '$purposeOffice')";
                if(mysqli_query($connection, $sqlVisitationOffice)){
                    
                    $getDestinationName = mysqli_query($connection, "SELECT destinationName, destinationLink FROM destination_list WHERE destination_id = '$destination'");
                    $destinationName = mysqli_fetch_array($getDestinationName);
                    echo "<script>window.location = 'sendEmail/emailVisitationForm.php?email=$accountEmail&destinationName=$destinationName[0]&destinationLink=$destinationName[1]';</script>";
                    
                }

            }                                        
            else{
                echo "visit requested insertfailed";
            }


        }
        else{
            echo "<script>alert('visitation records insertfailed')</script>";
        }

    }
    elseif(isset($_POST['submitEvent'])){
//EVENT
        $typeOfVisit = "Requested Visit";
        $typeOfForm = "Event";
        
        $destination = mysqli_real_escape_string($connection, $_POST['venue']);
        //echo "asdasdasdsadnasodnasd".$destination;
        $numberOfVisitor = mysqli_real_escape_string($connection, $_POST['numberOfVisitor']);

        $insertVisitationRecord = "INSERT INTO visitation_records (account_id, typeOfVisit, typeOfForm, destination_id, numberOfVisitor) VALUE ('$accountID', '$typeOfVisit','$typeOfForm', '$destination','$numberOfVisitor')";
 
        if(mysqli_query($connection, $insertVisitationRecord)){
            $visitationID = mysqli_insert_id($connection); //visitationID

            $dateSubmit = date("Y-m-d");
            $appointmentVisit = date('Y-m-d', strtotime($_POST['dateToVisit']));

            $insertVisitRequest = "INSERT INTO visit_requested (visitation_id, dateSubmit, appointmentVisit) VALUE ('$visitationID', '$dateSubmit', '$appointmentVisit')";

            if(mysqli_query($connection, $insertVisitRequest)){
                //destination yung venue
                $eventID = mysqli_real_escape_string($connection, $_POST['eventID']);
                $purposeEvent = mysqli_real_escape_string($connection, $_POST['purposeEvent']);

                $sqlVisitationEvent = 'INSERT INTO visit_event (visitation_id, event_id, eventPurpose) VALUES ("'.$visitationID.'","'.$eventID.'","'.$purposeEvent.'")';
                if(mysqli_query($connection, $sqlVisitationEvent)){
                    
                    $getDestinationName = mysqli_query($connection, "SELECT destinationName, destinationLink FROM destination_list WHERE destination_id = '$destination'");
                    $destinationName = mysqli_fetch_array($getDestinationName);
                    echo "<script>window.location = 'sendEmail/emailVisitationForm.php?email=$accountEmail&destinationName=$destinationName[0]&destinationLink=$destinationName[1]';</script>";

                }

            }                                        
            else{
                echo "<script>alert('visit requested insertfailed')</script>";
            }

        }
        else{
            echo "<script>alert('visitation records insertfailed')</script>";
        }

    }

?>


