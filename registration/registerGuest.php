<?php

    //FOR RESTRICTING LOGGED IN USER TO ACCESS THIS PAGE
    session_start();
    
    if(isset($_SESSION['statpage'])){
        /* Functions */
        function pathTo($pageName) {
            echo "<script>window.location.href = '/pages/$pageName.php'</script>";
        }

        if ($_SESSION['statpage'] == 'invalid' || empty($_SESSION['statpage'])) {
            /* Set Default Invalid */
            $_SESSION['statpage'] = 'invalid';
        }

        if ($_SESSION['statpage'] == 'valid') {
            if($_SESSION['role'] == 'Admin'){
                pathTo('dashboard');
            }
            elseif($_SESSION['role']  == 'Guard'){
                pathTo('scanAccountQR');
            }
            elseif($_SESSION['role']  == 'Visitor' || $_SESSION['role']  == 'Guest'){
                pathTo('userProfile');
            }
        } 
    }
    //FOR RESTRICTING LOGGED IN USER TO ACCESS THIS PAGE



    include('../database.php');
    
    $getDestinationList = mysqli_query($connection, "SELECT destination_id, destination, destinationName FROM destination_list WHERE status = 0 ORDER BY destination ASC");
    $numberOfDestination = mysqli_num_rows($getDestinationList);
    //echo $numberOfDestination;
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
<html>
<head>
    <title>VYSMO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/051c506296.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
    <link rel="stylesheet" href="registration.css">
    <script src="script.js"></script>
    <script src="../nodevtool.js"></script>
    <!-- Favicon -->
    <link href="../images/vysmoprintlogo.png" rel="icon">
    
</head>
<body class="regBody" >


    <div class="container" >
        <div class="items">
            <div class="logo">
                <img src="../images/logo.png" id="logo" alt="Logo" />
            </div>
            <h2>Visitation Information</h2>
            <ul id="forms">
                <li><a href="#person" onclick="showVisitForm('personForm'); clearText();">Person</a></li>
                <li><a href="#office" onclick="showVisitForm('officeForm'); clearText();">Office/Building</a></li>
                <li><a href="#event" onclick="showVisitForm('eventForm'); clearText();">Event</a></li>
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

            
            <form action="" method="POST" enctype="multipart/form-data" class="visitForm" id="personForm" >    
                <div id="visitInfo">
                    <label for="email">Email:</label><br>
                    <input type="email" id="email" name="email" placeholder="Email" required>

                    <div class="nameContainerGuestLabel">
                        <div class="guestFNameLabel">
                            <label for="guestFirstName">Guest First Name:</label>
                        </div>
                        <div class="guestLNameLabel lgScreen">
                            <label for="guestFirstName">Guest Last Name:</label>     
                        </div>
                        <div class="guestNameSuffixLabel lgScreen">
                            <label for="guestFirstName">Suffix:</label><br>
                        </div>
                    </div>

                    <div class="nameContainerGuest">                        
                        <input type="text" class="guestFirstName" id="guestFirstName"  name="guestFirstName" placeholder="First Name" required>
                        <label for="guestFirstName" class="smScreen">Guest Last Name:</label> <!--label for phone-->    
                        <input type="text" class="guestLastName" id="guestLastName"  name="guestLastName" placeholder="Last Name" required>
                        <label for="guestFirstName" class="smScreen">Suffix:</label><br><!--label for phone-->  
                        <select class="guestSuffix" id="guestSuffix" name="guestSuffixName">
                            <option value="" selected></option>
                            <option value="Jr">Jr.</option>
                            <option value="II">II</option>
                            <option value="III">III</option>
                            <option value="Sr">Sr.</option>
                        </select><br>
                    </div>   

                    <label for="numberOfVisitor">Number of Visitor:</label>
                    <input type="number" id="numberOfVisitorPerson" name="numberOfVisitor" placeholder="Number of Visitors" min="1" required>
                    <label for="date">Date to Visit:</label><br>
                    <input type="date" id="dateToVisitPerson" name="dateToVisit" value="<?php echo date('Y-m-d'); ?>" style="resize: none;" required><br>
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
                        <input type="text" class="firstNamePerson" id="fNamePerson"  name="firstNamePerson" placeholder="First Name" required>
                        <input type="text" class="lastNamePerson" id="lNamePerson"  name="lastNamePerson" placeholder="Last Name" required>
                        <select class="suffixNamePerson" id="suffix" name="suffixNamePerson">
                            <option value="" selected>--suffix--</option>
                            <option value="Jr">Jr.</option>
                            <option value="II">II</option>
                            <option value="III">III</option>
                            <option value="Sr">Sr.</option>
                        </select><br>
                    </div>  
                    <label for="dept">Department:</label><br>
                    <select id="dept" name="departmentPerson" required>
                        <option value="" selected disabled>--select--</option>
                        <?php
                            for($i=0; $i < $numberOfDestination; $i++){
                        ?>
                                <option value="<?php echo $destinationlist[$i]['destination_id'];?>"><?php echo $destinationlist[$i]['destinationName'];?></option>
                        <?php
                            }
                        ?>
                    </select><br>

                    <label for="rel">Relationship:</label><br>
                    <input type="text" id="rel" name="relationshipPerson" placeholder="Relationship" required>

                    <label for="purpose">Purpose of Visit:</label><br>
                    <input type="text" id="purpose" name="purposePerson" placeholder="Purpose of Visit" required>
                </div><br>
                <div id="submitbtn">
                    <button type="submit" name="submitPerson" id="submitPerson">Submit</button>
                </div>
            </form>

            <form action="" method="POST" enctype="multipart/form-data" class="visitForm" id="officeForm" style="display:none;" required>
                <div id="visitInfo">
                     <label for="email">Email:</label><br>
                    <input type="email" id="email" name="email" placeholder="Email" required><br>
                    <div class="nameContainerGuestLabel">
                        <div class="guestFNameLabel">
                            <label for="guestFirstName">Guest First Name:</label>
                        </div>
                        <div class="guestLNameLabel lgScreen">
                            <label for="guestFirstName">Guest Last Name:</label>     
                        </div>
                        <div class="guestNameSuffixLabel lgScreen">
                            <label for="guestFirstName">Suffix:</label><br>
                        </div>
                    </div>

                    <div class="nameContainerGuest">                        
                        <input type="text" class="guestFirstName" id="guestFirstName"  name="guestFirstName" placeholder="First Name" required>
                        <label for="guestFirstName" class="smScreen">Guest Last Name:</label> <!--label for phone-->    
                        <input type="text" class="guestLastName" id="guestLastName"  name="guestLastName" placeholder="Last Name" required>
                        <label for="guestFirstName" class="smScreen">Suffix:</label><br><!--label for phone-->  
                        <select class="guestSuffix" id="guestSuffix" name="guestSuffixName">
                            <option value="" selected></option>
                            <option value="Jr">Jr.</option>
                            <option value="II">II</option>
                            <option value="III">III</option>
                            <option value="Sr">Sr.</option>
                        </select><br>
                    </div>   
                    <label for="numberOfVisitor">Number of Visitor:</label>
                    <input type="number" id="numberOfVisitorOffice" name="numberOfVisitor" placeholder="Number of Visitors" min="1" required>
                    <label for="date">Date to Visit:</label><br>
                    <input type="date" id="dateToVisitOffice" name="dateToVisit" value="<?php echo date('Y-m-d'); ?>" style="resize: none;" required><br>
                    <label for="office">Office/Building Name:</label><br>
                    <select id="office1" name="officeBldgName" required>
                        <option value="" selected disabled>--select--</option>
                        <?php
                            for($i=0; $i < $numberOfDestination; $i++){
                        ?>
                                <option value="<?php echo $destinationlist[$i]['destination_id'];?>"><?php echo $destinationlist[$i]['destinationName'];?></option>
                        <?php
                            }
                        ?>
                    </select><br>
                    <label for="purpose">Purpose of Visit:</label><br>
                    <input type="text" id="purpose1" name="purposeOffice" placeholder="Purpose of Visit" required>
                </div><br>
                <div id="submitbtn">
                    <button type="submit" name="submitOffice" id="submitOffice">Submit</button>
                </div>
            </form>

            <form action="" onsubmit="return validateEventSubmit();" method="POST" enctype="multipart/form-data" class="visitForm" id="eventForm" style="display:none;" required>
                    <input type="hidden" id="eventID" name="eventID" value="" placeholder="eventID" >
                    <input type="hidden" id="eventEnd" value="" placeholder="eventEnd" >
                    <div id="visitInfo">
                    <label for="email">Email:</label><br>
                    <input type="email" id="email" name="email" placeholder="Email" required><br>
                    <div class="nameContainerGuestLabel">
                        <div class="guestFNameLabel">
                            <label for="guestFirstName">Guest First Name:</label>
                        </div>
                        <div class="guestLNameLabel lgScreen">
                            <label for="guestFirstName">Guest Last Name:</label>     
                        </div>
                        <div class="guestNameSuffixLabel lgScreen">
                            <label for="guestFirstName">Suffix:</label><br>
                        </div>
                    </div>

                    <div class="nameContainerGuest">                        
                        <input type="text" class="guestFirstName" id="guestFirstName"  name="guestFirstName" placeholder="First Name" required>
                        <label for="guestFirstName" class="smScreen">Guest Last Name:</label> <!--label for phone-->    
                        <input type="text" class="guestLastName" id="guestLastName"  name="guestLastName" placeholder="Last Name" required>
                        <label for="guestFirstName" class="smScreen">Suffix:</label><br><!--label for phone-->  
                        <select class="guestSuffix" id="guestSuffix" name="guestSuffixName">
                            <option value="" selected></option>
                            <option value="Jr">Jr.</option>
                            <option value="II">II</option>
                            <option value="III">III</option>
                            <option value="Sr">Sr.</option>
                        </select><br>
                    </div>    
                    <label for="numberOfVisitor">Number of Visitor:</label>
                    <input type="number" id="numberOfVisitorEvent" name="numberOfVisitor" placeholder="Number of Visitors" min="1" required>
                    <label for="date">Date to Visit:</label><br>
                    <input type="date" id="dateToVisitEvent" name="dateToVisit" placeholder="Date to visit" value="<?php  echo date('Y-m-d'); ?>" style="resize: none;" required><br>
                    
                            
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

                    <label for="venue">Venue:</label><br>
                    <select id="venue" name="venue"  required>
                        <option value="">--Select Event Name--</option>
                    </select><br>

                    <label for="purpose">Purpose of Visit:</label><br>
                    <input type="text" id="purpose2" name="purposeEvent" placeholder="Purpose of Visit" required>
                </div><br>
                <div id="submitbtn">
                    <button type="submit" name="submitEvent" id="submitEvent">Submit</button>
                </div>
            </form>

            <!-- Add this JavaScript code within a <script> tag in your HTML -->
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
                    document.getElementById('email').value = '';
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
                    
                    document.getElementById('venue').selectedIndex = 0; 
                    document.getElementById('purpose2').value = ''; 
                }
                
            </script>

        </div>
    </div>   


</body>
</html>


<?php
    
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        date_default_timezone_set('Asia/Manila');

        if(isset($_POST['submitPerson'])){
            $email = mysqli_real_escape_string($connection, $_POST['email']);
        
            if(filter_var($email,FILTER_VALIDATE_EMAIL)){
                //check if email exist 
                $checkEmail = mysqli_query($connection, "SELECT email FROM accounts WHERE email='{$email}'");

                if(mysqli_num_rows($checkEmail) > 0){
                //email does exist
                    echo 
                        "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'This email already exist!',
                                text: 'Please log in and complete your personal information to request a new visit.'
                            })
                        </script>";
                }
                else{
                //email does not exist GUEST
                    //GENERATING QR

                    date_default_timezone_set("Asia/Manila");
                    $dateTime = date('Y-m-d h:i:s A', time());

                    $role = "Guest";
                    
                    //8 digit password generator (used OTP)
                    $alpha   = str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZ', 4));
                    $numeric = str_shuffle(str_repeat('0123456789', 4));
                    $generatedpassword = substr($alpha, 0, 4) . substr($numeric, 0, 4);
                    $generatedpassword = str_shuffle($generatedpassword);

                    //HASHING
                    $salt = "45a4748f715f501377bf2c6de1b259b1"; //visitor monitoring system (md5)
                    $hashpassword = md5($salt.$generatedpassword);


                    $sqlGuestAccount = "INSERT INTO accounts (email, password, datetime_created, role) VALUE ('$email', '$hashpassword', '$dateTime', '$role')";
                    if(mysqli_query($connection, $sqlGuestAccount)){
                        $accountID = mysqli_insert_id($connection); //ACCOUNT ID
                        
                        $accountQRDirectory = "visitorQR/"; 
                        $qrName = uniqid('',true).'.png';
                        $codeContents = "$accountID";

                        //start ENCRYPTION OF QRCODE DATA
                        $key = openssl_random_pseudo_bytes(16); // 128-bit key for AES-128-CBC
                        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-128-cbc'));

                        $encryptedBinary = openssl_encrypt($codeContents, 'aes-128-cbc', $key, 0, $iv);
                        $encryptedQrContent = base64_encode($encryptedBinary);

                        // Save QR 
                        $imageUrl = 'https://chart.googleapis.com/chart?chs=500x500&cht=qr&chl='.urlencode($encryptedQrContent).'&choe=UTF-8';
                        $rawImage = file_get_contents($imageUrl);
                        file_put_contents($accountQRDirectory.$qrName, $rawImage);

                        //this will be input sa database and kapag gusto na idecrypt yung qr ireretrieve yung sa database and use key and iv
                        
                        $qrName = mysqli_real_escape_string($connection, $qrName);
                        $encryptedQrDB = mysqli_real_escape_string($connection, $encryptedQrContent);
                        $keyQR = mysqli_real_escape_string($connection, base64_encode($key));
                        $ivQR = mysqli_real_escape_string($connection, base64_encode($iv));

                        //end ENCRYPTION OF QRCODE DATA

                        $insertAccountQR = "INSERT INTO account_qr (account_id, qrName, encryptedQrContent, keyQR, ivQR) VALUE ('$accountID', '$qrName', '$encryptedQrDB', '$keyQR', '$ivQR')";
                        
                        $guestFirstName = mysqli_real_escape_string($connection, $_POST['guestFirstName']);
                        $guestLastName = mysqli_real_escape_string($connection, $_POST['guestLastName']);
                        $guestSuffixName = mysqli_real_escape_string($connection, $_POST['guestSuffixName']);

                        $sqlVisitorInfo = "INSERT INTO visitor_info (account_id, firstName, lastName, suffixName) VALUE ('$accountID', '$guestFirstName', '$guestLastName', '$guestSuffixName')";
                        if(mysqli_query($connection, $insertAccountQR) && mysqli_query($connection, $sqlVisitorInfo)){

                            //naginsert na
                            //visitation
                            $typeOfVisit = "Requested Visit";
                            $typeOfForm = "Individual Person";
                            
                            $destination = mysqli_real_escape_string($connection, $_POST['departmentPerson']);
                            $numberOfVisitor = mysqli_real_escape_string($connection, $_POST['numberOfVisitor']);
                            
                            $insertVisitationRecord = "INSERT INTO visitation_records (account_id, typeOfVisit, typeOfForm, destination_id, numberOfVisitor) VALUES ('$accountID', '$typeOfVisit','$typeOfForm', $destination,'$numberOfVisitor')";
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
                                        echo "<script>window.location = 'sendEmail/emailAccountQR.php?email=$email&password=$generatedpassword&qrName=$qrName&role=$role&destination=$destinationName[0]&destinationLink=$destinationName[1]';</script>";
                                        
                                    }

                                }
                                else{
                                    echo "visit requested insertfailed<br>";
                                }


                            }
                            else{
                                echo "visitation records insertfailed<br>";
                            }
                            

                        }
                        else{
                            echo "visitor info insertfailed<br>";
                        }
                        

                    }
                    else{
                        echo "accounts insertfailed<br>";
                    }
                    
                }
            }
            
            

        }
        elseif(isset($_POST['submitOffice'])){
            $email = mysqli_real_escape_string($connection, $_POST['email']);
        
            if(filter_var($email,FILTER_VALIDATE_EMAIL)){
                //check if email exist 
                $checkEmail = mysqli_query($connection, "SELECT email FROM accounts WHERE email='{$email}'");

                if(mysqli_num_rows($checkEmail) > 0){
                //email does exist
                    echo 
                        "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'This email already exist!',
                                text: 'Please log in and complete your personal information to request a new visit.'
                            })
                        </script>";
                }
                else{
                //email does not exist GUEST
                    //GENERATING QR

                    date_default_timezone_set("Asia/Manila");
                    $dateTime = date('Y-m-d h:i:s A', time());

                    $role = "Guest";
                    
                    //8 digit password generator (used OTP)
                    $alpha   = str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZ', 4));
                    $numeric = str_shuffle(str_repeat('0123456789', 4));
                    $generatedpassword = substr($alpha, 0, 4) . substr($numeric, 0, 4);
                    $generatedpassword = str_shuffle($generatedpassword);

                    //HASHING
                    $salt = "45a4748f715f501377bf2c6de1b259b1"; //visitor monitoring system (md5)
                    $hashpassword = md5($salt.$generatedpassword);


                    $sqlGuestAccount = "INSERT INTO accounts (email, password, datetime_created, role) VALUE ('$email', '$hashpassword', '$dateTime', '$role')";
                    if(mysqli_query($connection, $sqlGuestAccount)){
                        $accountID = mysqli_insert_id($connection); //ACCOUNT ID
                        
                        $accountQRDirectory = "visitorQR/"; 
                        $qrName = uniqid('',true).'.png';
                        $codeContents = "$accountID";

                        //start ENCRYPTION OF QRCODE DATA
                        $key = openssl_random_pseudo_bytes(16); // 128-bit key for AES-128-CBC
                        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-128-cbc'));

                        $encryptedBinary = openssl_encrypt($codeContents, 'aes-128-cbc', $key, 0, $iv);
                        $encryptedQrContent = base64_encode($encryptedBinary);

                        // Save QR 
                        $imageUrl = 'https://chart.googleapis.com/chart?chs=500x500&cht=qr&chl='.urlencode($encryptedQrContent).'&choe=UTF-8';
                        $rawImage = file_get_contents($imageUrl);
                        file_put_contents($accountQRDirectory.$qrName, $rawImage);

                        //this will be input sa database and kapag gusto na idecrypt yung qr ireretrieve yung sa database and use key and iv
                        
                        $qrName = mysqli_real_escape_string($connection, $qrName);
                        $encryptedQrDB = mysqli_real_escape_string($connection, $encryptedQrContent);
                        $keyQR = mysqli_real_escape_string($connection, base64_encode($key));
                        $ivQR = mysqli_real_escape_string($connection, base64_encode($iv));

                        //edn ENCRYPTION OF QRCODE DATA


                        $insertAccountQR = "INSERT INTO account_qr (account_id, qrName, encryptedQrContent, keyQR, ivQR) VALUE ('$accountID', '$qrName', '$encryptedQrDB', '$keyQR', '$ivQR')";
                        
                        $guestFirstName = mysqli_real_escape_string($connection, $_POST['guestFirstName']);
                        $guestLastName = mysqli_real_escape_string($connection, $_POST['guestLastName']);
                        $guestSuffixName = mysqli_real_escape_string($connection, $_POST['guestSuffixName']);

                        $sqlVisitorInfo = "INSERT INTO visitor_info (account_id, firstName, lastName, suffixName) VALUE ('$accountID', '$guestFirstName', '$guestLastName', '$guestSuffixName')";
                        if(mysqli_query($connection, $insertAccountQR) && mysqli_query($connection, $sqlVisitorInfo)){
                        
                            //visitation
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
                                        echo "<script>window.location = 'sendEmail/emailAccountQR.php?email=$email&password=$generatedpassword&qrName=$qrName&role=$role&destination=$destinationName[0]&destinationLink=$destinationName[1]';</script>";
                                        
                                    }

                                }                                        
                                else{
                                    echo "visit requested insertfailed<br>";
                                }

                            }
                            else{
                                echo "visitation records insertfailed<br>";
                            }

                        }
                        else{
                            echo "visitor info insertfailed<br>";
                        }

                    }
                    else{
                        echo "accounts insertfailed<br>";
                    }

                    

                }

            }


        }
        elseif(isset($_POST['submitEvent'])){

            $email = mysqli_real_escape_string($connection, $_POST['email']);
        
            if(filter_var($email,FILTER_VALIDATE_EMAIL)){
                //check if email exist 
                $checkEmail = mysqli_query($connection, "SELECT email FROM accounts WHERE email='{$email}'");

                if(mysqli_num_rows($checkEmail) > 0){
                //email does exist
                    echo 
                        "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: 'This email already exist!',
                                text: 'Please log in and complete your personal information to request a new visit.'
                            })
                        </script>";
                }
                else{
                //email does not exist GUEST
                    //GENERATING QR

                    date_default_timezone_set("Asia/Manila");
                    $dateTime = date('Y-m-d h:i:s A', time());

                    $role = "Guest";
                    
                    //8 digit password generator (used OTP)
                    $alpha   = str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZ', 4));
                    $numeric = str_shuffle(str_repeat('0123456789', 4));
                    $generatedpassword = substr($alpha, 0, 4) . substr($numeric, 0, 4);
                    $generatedpassword = str_shuffle($generatedpassword);

                    //HASHING
                    $salt = "45a4748f715f501377bf2c6de1b259b1"; //visitor monitoring system (md5)
                    $hashpassword = md5($salt.$generatedpassword);


                    $sqlGuestAccount = "INSERT INTO accounts (email, password, datetime_created, role) VALUE ('$email', '$hashpassword', '$dateTime', '$role')";
                    if(mysqli_query($connection, $sqlGuestAccount)){
                        $accountID = mysqli_insert_id($connection); //ACCOUNT ID
                        
                        $accountQRDirectory = "visitorQR/"; 
                        $qrName = uniqid('',true).'.png';
                        $codeContents = "$accountID";

                        //start ENCRYPTION OF QRCODE DATA
                        $key = openssl_random_pseudo_bytes(16); // 128-bit key for AES-128-CBC
                        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-128-cbc'));

                        $encryptedBinary = openssl_encrypt($codeContents, 'aes-128-cbc', $key, 0, $iv);
                        $encryptedQrContent = base64_encode($encryptedBinary);

                        // Save QR 
                        $imageUrl = 'https://chart.googleapis.com/chart?chs=500x500&cht=qr&chl='.urlencode($encryptedQrContent).'&choe=UTF-8';
                        $rawImage = file_get_contents($imageUrl);
                        file_put_contents($accountQRDirectory.$qrName, $rawImage);

                        //this will be input sa database and kapag gusto na idecrypt yung qr ireretrieve yung sa database and use key and iv
                        
                        $qrName = mysqli_real_escape_string($connection, $qrName);
                        $encryptedQrDB = mysqli_real_escape_string($connection, $encryptedQrContent);
                        $keyQR = mysqli_real_escape_string($connection, base64_encode($key));
                        $ivQR = mysqli_real_escape_string($connection, base64_encode($iv));

                        //edn ENCRYPTION OF QRCODE DATA


                        $insertAccountQR = "INSERT INTO account_qr (account_id, qrName, encryptedQrContent, keyQR, ivQR) VALUE ('$accountID', '$qrName', '$encryptedQrDB', '$keyQR', '$ivQR')";
                        
                        $guestFirstName = mysqli_real_escape_string($connection, $_POST['guestFirstName']);
                        $guestLastName = mysqli_real_escape_string($connection, $_POST['guestLastName']);
                        $guestSuffixName = mysqli_real_escape_string($connection, $_POST['guestSuffixName']);

                        $sqlVisitorInfo = "INSERT INTO visitor_info (account_id, firstName, lastName, suffixName) VALUE ('$accountID', '$guestFirstName', '$guestLastName', '$guestSuffixName')";
                        if(mysqli_query($connection, $insertAccountQR) && mysqli_query($connection, $sqlVisitorInfo)){
                        
                            
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
                                        echo "<script>window.location = 'sendEmail/emailAccountQR.php?email=$email&password=$generatedpassword&qrName=$qrName&role=$role&destination=$destinationName[0]&destinationLink=$destinationName[1]';</script>";

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
                        else{
                            echo "visitor info insertfailed<br>";
                        }
                        

                    }
                    else{
                        echo "accounts insertfailed<br>";
                    }

                    

                }

            }

            
        }

    }

?>