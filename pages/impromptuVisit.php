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

    $encodedDestination = $_GET['encdata'];

    $destination = explode("***", base64_decode($encodedDestination));
    //echo print_r($destination);
    $destinationID = $destination[0];
    $destinationAbbrev = $destination[1];
    $typeOfDestination = $destination[2];//TRANSACTIONAL OR DEPARTMENT
   

    $getDestinationList = mysqli_query($connection, "SELECT destination_id, destination, destinationName FROM destination_list WHERE destination_id = '$destinationID' AND status = 0 ORDER BY destination ASC");
    $numberOfDestination = mysqli_num_rows($getDestinationList);
    if(mysqli_num_rows($getDestinationList) > 0){

        $destinationlist = array();
        while($row = mysqli_fetch_array($getDestinationList)){
            // add each row returned into an array
            $destinationlist[] = $row; //info of eventlist

        }
    }
    
    $sqlActiveEvent = mysqli_query($connection, "SELECT event_id, eventName, eventVenue, eventEnd FROM event_list WHERE  typeOfEvent = 'Exclusive Event' AND status = 1 AND eventVenue = '$destinationID'");

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
<html>
<head>
    <title>VYSMO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/051c506296.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
    <link rel="stylesheet" href="../registration/registration.css">
    <script src="../registration/script.js"></script>
    <script src="js/nodevtool.js"></script>
    <!-- Favicon -->
    <link href="../images/vysmoprintlogo.png" rel="icon">
    
</head>
<body class="regBody" >


    <div class="container" >
        <div class="items">
            <div class="logo">
                <img src="../images/logo.png" id="logo" alt="Logo" />
            </div>
            <h2><?php echo $destinationAbbrev;?> Impromptu Visit</h2>
            <ul id="forms">
                <li><a href="#person" onclick="showVisitForm('personForm'); clearText();">Person</a></li>
                <li><a href="#office" onclick="showVisitForm('officeForm'); clearText();">Office/Building</a></li>\
                <?php
                    if($typeOfDestination == 'Department'){
                ?>
                        <li><a href="#event" onclick="showVisitForm('eventForm'); clearText();">Event</a></li>
                <?php
                    }
                ?>
            </ul><br>
            
            <!--TO INDICATE THE SELECTED NAVBAR-->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var navItems = document.getElementsByTagName('li');

                    navItems[0].classList.add('active');    
                    
                    for (var i = 0; i < navItems.length; i++) {
                        navItems[i].addEventListener('click', function() {
                        
                        for (var j = 0; j < navItems.length; j++) {
                            navItems[j].classList.remove('active');
                        }

                        this.classList.add('active');
                        });
                    }
                });
            </script>
            
            <form action="impromptuVisitQuery.php" method="POST" enctype="multipart/form-data" class="visitForm" id="personForm" >    
                <div id="visitInfo">
                    <input type="hidden" name="destinationID" value="<?php echo $destinationID;?>">
                    <input type="hidden" name="destinationAbbrev" value="<?php echo $destinationAbbrev;?>">

                    <label for="personVisit">Person to Visit:</label><br>
                    <select id="personVisit" name="personToVisit" required >
                        <option value="" disabled selected>--select--</option>
                        <option value="Student">Student</option>
                        <option value="Faculty">Faculty</option>
                        <option value="Staff">Staff</option>
                        <option value="Dean">Dean</option>
                    </select><br>

                    <div class="nameContainerGuestLabel">
                        <div class="guestFNameLabel">
                            <label for="guestFirstName">Name to Visit:</label>
                        </div>
                    </div>

                    <div class="nameContainerGuest">                        
                        <input type="text" class="firstName" id="fNamePerson"  name="firstNamePerson" placeholder="First Name" required>
                        <input type="text" class="lastName" id="lNamePerson"  name="lastNamePerson" placeholder="Last Name" required>
                        <select class="suffixName" id="suffix" name="suffixNamePerson">
                            <option value="" selected>--suffix--</option>
                            <option value="Jr">Jr.</option>
                            <option value="II">II</option>
                            <option value="III">III</option>
                            <option value="Sr">Sr.</option>
                        </select><br>
                    </div>  


                    <label for="rel">Relationship:</label><br>
                    <input type="text" id="rel" name="relationshipPerson" placeholder="Relationship" required>

                    <label for="purpose">Purpose of Visit:</label><br>
                    <input type="text" id="purpose" name="purposePerson" placeholder="Purpose of Visit" required>
                </div><br>
                <div id="submitbtn">
                    <button type="submit" name="submitPerson" id="submitPerson">Submit</button>
                </div>
            </form>

            <form action="impromptuVisitQuery.php" method="POST" enctype="multipart/form-data" class="visitForm" id="officeForm" style="display:none;" required>
                <div id="visitInfo">
                    <input type="hidden" name="destinationID" value="<?php echo $destinationID;?>">
                    <input type="hidden" name="destinationAbbrev" value="<?php echo $destinationAbbrev;?>">

                    <label for="purpose">Purpose of Visit:</label><br>
                    <input type="text" id="purpose1" name="purposeOffice" placeholder="Purpose of Visit" required>
                </div><br>
                <div id="submitbtn">
                    <button type="submit" name="submitOffice" id="submitOffice">Submit</button>
                </div>
            </form>
            <?php
                if($typeOfDestination == 'Department'){
            ?>
                    <form action="impromptuVisitQuery.php"  method="POST" enctype="multipart/form-data" class="visitForm" id="eventForm" style="display:none;" required>
                            <input type="hidden" name="destinationID" value="<?php echo $destinationID;?>">
                            <input type="hidden" name="destinationAbbrev" value="<?php echo $destinationAbbrev;?>">
                            <input type="hidden" id="eventID" name="eventID" value="" placeholder="eventID" >
                            <input type="hidden" id="eventEnd" value="" placeholder="eventEnd" >
                            <div id="visitInfo">
                            

                            <label for="eventName">Event Name:</label><br>
                            <select id="eventName" name="eventName" required>
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

                            <!--<label for="venue" >Venue:</label><br>-->
                            <select id="venue" name="venue" style="pointer-events: none; display:none " required>
                                <option value="" selected>--Select Event Name--</option>
                            </select><br>

                            <label for="purpose">Purpose of Visit:</label><br>
                            <input type="text" id="purpose2" name="purposeEvent" placeholder="Purpose of Visit" required>
                        </div><br>
                        <div id="submitbtn">
                            <button type="submit" name="submitEvent" id="submitEvent">Submit</button>
                        </div>
                    </form>
            <?php
                }
            ?>

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
                    //document.getElementById('email').value = '';
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
    </div>   


</body>
</html>

