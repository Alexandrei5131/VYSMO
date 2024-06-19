<?php
    include('database.php');
?>
<!DOCTYPE html>
<html>
<head>
    
    <link href="images/vysmoprintlogo.png" rel="icon">
    <title>VYSMO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="nodevtool.js"></script>
    <!-- Customized Bootstrap Stylesheet -->
    <link href="pages/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="pages/css/style.css" rel="stylesheet">
    <!-- WHEN OUTSIDE MODAL CLICKED -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://kit.fontawesome.com/051c506296.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
    <link rel="stylesheet" href="registration/registration.css">
    <script src="registration/script.js"></script>
    


</head>
<style>
    #deleteBody{
    text-align: center;
    font-size: 24px;
    color: black;
    font-family: klavika;}



</style>
<body class="regBody" >

    <?php

    $encodedData = $_GET['encdata'];
    //typeofevent, eventid, eventname, evaluationid, status
    $decodedData = base64_decode($encodedData);
    $qrData = explode("***", $decodedData);
    //echo print_r($decodedData);
    $typeOfEvent = $qrData[0];

    if($typeOfEvent == 'Exclusive Event'){    
        $evaluationID = $qrData[1];
        $eventName = $qrData[2];
        $key = $qrData[3];
        $queryCheck = mysqli_query($connection, "SELECT * FROM eval_exclusiveevent_qr INNER JOIN evaluation_list ON eval_exclusiveevent_qr.evaluation_id = evaluation_list.evaluation_id  WHERE evaluation_list.status = 1 AND  eval_exclusiveevent_qr.evaluation_id = '$evaluationID' AND eval_exclusiveevent_qr.keyScan = '$key'");
    }
    elseif($typeOfEvent == 'Open Event'){//open event
        $eventID = $qrData[1];
        $eventName = $qrData[2];
        $evaluationID = $qrData[3];
        $statusEval =  $qrData[4];
        $key =  $qrData[5];
        $queryCheck = mysqli_query($connection, "SELECT * FROM event_openqr INNER JOIN event_list ON event_openqr.event_id = event_list.event_id WHERE event_list.status = 1 AND event_openqr.event_id = '$eventID' AND event_openqr.keyScan = '$key'");
    }

//CHECK IF VALID PA YUNG QR
    if(mysqli_num_rows($queryCheck) > 0){
        if($typeOfEvent == 'Exclusive Event'){ //direct to form agad
            echo "<script>window.location = 'evaluationEvent.php?encdata=$encodedData'</script>";
        }
        elseif($typeOfEvent == 'Open Event'){ //popup muna then direct to form

    ?>
        <!-- Modal for "Do you have an account?" -->
        <div class="modal fade" id="accountModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content d-flex align-items-center">
                    <div class="modal-body">
                        <p class="text-center" id="deleteBody"> Do you have a VYSMO Account?</p>
                        <div class="row justify-content-center">
                            <div class="col-auto">
                                <button type="button" class="btn btn-warning" id="yesButton">Yes</button>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-danger" id="noButton">No</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="" method="POST">
            <input type="hidden" name="eventID" value="<?php echo $eventID;?>">
            <input type="hidden" name="encdata" value= "<?php echo $decodedData;?>">
            <input type="hidden" name="status" value= "<?php echo $statusEval;?>">
            <!-- Modal for entering email and time-in -->
            <div class="modal" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Please fill out this form and submit</h5>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <label for="numberOfVisitor">Number Of Visitor:</label>
                                <input type="number" class="form-control" id="numberOfVisitor" min="1" name="numberOfVisitor" required>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-auto">
                                    <button type="submit" name="timeInVisitor" class="btn btn-primary">Submit</button>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-secondary" id="cancelVisitor" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>           
        </form>

        <form action="" method="POST">
            <input type="hidden" name="eventID" value="<?php echo $eventID;?>">
            <input type="hidden" name="encdata" value= "<?php echo $decodedData;?>">
            <input type="hidden" name="status" value= "<?php echo $statusEval;?>">
            <div class="modal" id="guestModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Please fill out this form and submit</h5>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-3">

                                <label for="firstName">First Name:</label>
                                <input type="text" class="form-control" id="guestFirstName"  name="firstName" required>
                                <label for="lastName">Last Name:</label>
                                <input type="text" class="form-control"  name="lastName" required>
                                <label for="suffixName">Suffix Name:</label>
                                <select class="form-control" name="suffixName">
                                    <option value="" selected>--suffix--</option>
                                    <option value="Jr">Jr.</option>
                                    <option value="II">II</option>
                                    <option value="III">III</option>
                                    <option value="Sr">Sr.</option>
                                </select><br>

                                <label for="numberOfGuest">Number Of Visitor:</label>
                                <input type="number" class="form-control" id="numberOfGuest" min="1" name="numberOfGuest" required>
                            </div>
                            <div class="row d-flex justify-content-center">
                                <div class="col-auto">
                                    <button type="submit" name="timeInGuest" class="btn btn-primary">Submit</button>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-secondary" id="cancelGuest" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>         
        </form> 
        
    


        <?php
            if($typeOfEvent == 'Open Event'){
        ?>

        <script>
            $(document).ready(function() {
                $('#accountModal').modal('show');
                
                // Prevent the modal from closing when clicking outside
                $('#accountModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });

                // Get references to the "Yes" and "No" buttons
                var yesButton = document.getElementById('yesButton');
                var noButton = document.getElementById('noButton');

                var emailInput = document.getElementById('email');
                var numOfVisitorInput = document.getElementById('numberOfVisitor');
                var numOfGuestInput = document.getElementById('numberOfGuest');

                
                var cancelVisitor = document.getElementById('cancelVisitor');
                var cancelGuest = document.getElementById('cancelGuest');

               // Add click event listener to the "Yes" button
                yesButton.addEventListener('click', function () {
                    $('#accountModal').modal('hide');
                    $('#emailModal').modal('show');

                });

                // Add click event listener to the "No" button
                noButton.addEventListener('click', function () {
                    $('#accountModal').modal('hide');
                    $('#guestModal').modal('show');
                });

                cancelVisitor.addEventListener('click', function () {
                    $('#accountModal').modal('show');
                    $('#emailModal').modal('hide');

                });
                cancelGuest.addEventListener('click', function () {
                    $('#accountModal').modal('show');
                    $('#guestModal').modal('hide');

                });
            });
        </script>

        <?php
        
            }
        }
        ?>


    <?php
    }
    else{
    ?>
        <body class="p-0">
            <div class="content1">
                <nav class="navbar fixed-top">
                        <div class="row headerError justify-content-center mx-0 mt-2" id="">
                            <div class="row logoRow justify-content-center">
                                <div class="col-auto p-0 logoQRBorder1 ">
                                    <img src="images/logo.png" alt="" class="logoQR1">
                                </div>
                                <div class="col-auto p-0 vysLogoBorder1 ">
                                    <img src="images/vysmoprintlogo.png" class="vysLogo1" alt="" id="vysLogo1">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col headerTitleMain1">
                                    <div class="text-center">
                                        <span class="headerTitle2 ">Nueva Ecija University of Science and Technology</span>
                                    </div>
                                    <div class="text-center">
                                        <span class="headerTitle2 ">Visitor Monitoring System</span>         
                                    </div>
                                </div>
                            </div> 
                        </div>
                </nav>
            </div>
            <div class="center">
                <div class="container col-12 col-sm-8" >
                    <div class="row">
                        <div class="col">
                            <div class="items">
                            <p class="text-center textErrorDesign">We apologize for any inconvenience, but it seems that the QR Code you've scanned is either invalid or has expired. 
                            Please inform the guard that the QR Code is invalid. Thanks!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
    <?php
    }

    ?>



</body>
</html>

<?php 

if(isset($_POST['timeInVisitor'])){
    $emailOfVisitor = mysqli_real_escape_string($connection, $_POST['email']);
    $numberOfVisitor = mysqli_real_escape_string($connection, $_POST['numberOfVisitor']);
    $status = $_POST['status'];
    
    $checkEmail = mysqli_query($connection, "SELECT account_id FROM accounts WHERE email='$emailOfVisitor' AND role='Visitor'");
    if(mysqli_num_rows($checkEmail)>0){
        $accountID = mysqli_fetch_array($checkEmail);

        $getVisitorName = mysqli_query($connection, "SELECT firstName, lastName, suffixName FROM visitor_info WHERE account_id = 5");
        while($row = mysqli_fetch_array($getVisitorName)){
            $name = $row;
        }
        $firstName = $name[0];
        $lastName = $name[1];
        $suffixName = $name[2];


        $eventID = $_POST['eventID'];
        
        $encdata = $_POST['encdata'] . "***" . "Visitor" . "***" . $accountID[0];
        $encode = base64_encode($encdata);
        
        $insertOpenEvent = mysqli_query($connection, "INSERT INTO visit_openevent (event_id, email, firstName, lastName, suffixName, numberOfVisitor, dateVisit, timestamp) VALUE ('$eventID', '$emailOfVisitor', '$firstName', '$lastName', '$suffixName', '$numberOfVisitor', '$dateToday', '$currentTime')");
        if($insertOpenEvent){

            if($status == 1){

                echo "<script>window.location = 'evaluationEvent.php?encdata=$encode'</script>";
            }
            else{
                echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Thank you for attending the Open Event',
                            timer: 3000
                        }).then(function() {
                            window.location = '../vysmo';
                        });
                    </script>";
            }
            
        }
    }
    else{
        
        echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Account Does Not Exist',
                    timer: 3000
                });
            </script>";
    }

}
elseif(isset($_POST['timeInGuest'])){
    
    $eventID = mysqli_real_escape_string($connection, $_POST['eventID']);
    $firstName = mysqli_real_escape_string($connection, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($connection, $_POST['lastName']);
    $suffixName = mysqli_real_escape_string($connection, $_POST['suffixName']);

    $numberOfGuest = mysqli_real_escape_string($connection, $_POST['numberOfGuest']);
    $encdata = $_POST['encdata'] . "***" . "Guest" . "***" . $firstName . "***" . $lastName . "***" . $suffixName;
    $encode = base64_encode($encdata);
    $status = $_POST['status'];

    $insertOpenEvent = mysqli_query($connection, "INSERT INTO visit_openevent (event_id, firstName, lastName, suffixName, numberOfVisitor, dateVisit, timestamp) VALUE ('$eventID', '$firstName', '$lastName', '$suffixName', '$numberOfGuest', '$dateToday', '$currentTime')");
    if($insertOpenEvent){
        if($status == 1){
            echo "<script>window.location = 'evaluationEvent.php?encdata=$encode'</script>";
        }
        else{
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Thank you for attending the Open Event',
                        timer: 3000
                    }).then(function() {
                        Swal.fire({
                            icon: 'question',
                            title: 'Do you want to create a VYSMO Account?',
                            text: '',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No',
                            backdrop: 'static',
                            keyboard: false,
                            allowOutsideClick: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '/vysmo/registration/?fromeval=yes';
                            }
                            else{
                                window.location.href = '/vysmo/';
                            }
                        });
                    });
                </script>";
        }
        
    }

}

?>
