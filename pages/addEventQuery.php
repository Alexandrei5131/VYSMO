
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VYSMO</title>

    <!-- Favicon -->
    <link href="../images/vysmoprintlogo.png" rel="icon">

    <!--SWEET ALERT 2-->
    <script src="js/nodevtool.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
</head>
<body style="background-color: #cce6ff;">

</body>
</html>

<?php
    include('../database.php');

    if(isset($_POST['addEvent'])){
        $typeOfEvent = $_POST['typeOfEvent'];
        $eventName = $_POST['eventName'];
        $eventVenue = $_POST['eventVenue'];
        $eventStart = $_POST['addEventStart'];
        $eventEnd = $_POST['addEventEnd'];
        //eventstart event end


        if(isset($_POST['addEvaluation'])){
            $addEvaluation = 1;
        }
        else{
            $addEvaluation = 0;
        }

        if($eventStart != $dateToday){//pending
            $sqlAddEvent = "INSERT INTO event_list (typeOfEvent, eventName, eventVenue, eventStart, eventEnd, evaluationForm, status) VALUE ('$typeOfEvent', '$eventName', '$eventVenue', '$eventStart', '$eventEnd', '$addEvaluation', 3)";
        }
        else{
            $sqlAddEvent = "INSERT INTO event_list (typeOfEvent, eventName, eventVenue, eventStart, eventEnd, evaluationForm, status) VALUE ('$typeOfEvent', '$eventName', '$eventVenue', '$eventStart', '$eventEnd', '$addEvaluation', 1)";
        }

        if(mysqli_query($connection, $sqlAddEvent)){
            $eventID = mysqli_insert_id($connection);

            if($typeOfEvent == 'Open Event'){

                $sqlOpenEventQR = "INSERT INTO event_openqr (event_id) VALUE ('$eventID')";
                if(mysqli_query($connection, $sqlOpenEventQR)){
                    
                    if($addEvaluation == 1){
                        $sqladdEvaluation = mysqli_query($connection,"INSERT INTO evaluation_list (event_id, status) VALUE ('$eventID', 1)");
                        $evaluationID = mysqli_insert_id($connection);
                        $sqlInsertDefaultQuestions = mysqli_query($connection,"INSERT INTO evaluation_question 
                        (question, evaluation_id) VALUES 
                        ('Event Theme', '$evaluationID'), 
                        ('Security and Safety', '$evaluationID'),
                        ('Speaker, Host, and Guest Engagement', '$evaluationID'), 
                        ('Event Organization', '$evaluationID'), 
                        ('Timing and Schedule', '$evaluationID'), 
                        ('Venue and Facilities', '$evaluationID'), 
                        ('Networking Opportunities', '$evaluationID'), 
                        ('Entertainment or Social Activities', '$evaluationID'), 
                        ('Technical Equipment and Support', '$evaluationID'), 
                        ('Overall Satisfaction', '$evaluationID')");
                    }
                    else{
                        $sqladdEvaluation = mysqli_query($connection,"INSERT INTO evaluation_list (event_id) VALUE ('$eventID')");//status 0
                    }
                    
                    echo 
                        "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Event Added Successfully'
                            }).then(function() {
                                window.location = 'addEvent.php';
                            });
                        </script>";


                }
                else{
                    echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'open event add ayaw'
                            }).then(function() {
                                window.location = 'addEvent.php';
                            });
                        </script>";
                }

            }
            else{//EXCLUSIVE EVENT\
                if($addEvaluation == 1){
                    $sqladdEvaluation = mysqli_query($connection,"INSERT INTO evaluation_list (event_id, status) VALUE ('$eventID', 1)");
                    $evaluationID = mysqli_insert_id($connection);
                    $sqlAddExclusiveQR = mysqli_query($connection,"INSERT INTO eval_exclusiveevent_qr (evaluation_id) VALUE ('$evaluationID')");

                    $sqlInsertDefaultQuestions = mysqli_query($connection,"INSERT INTO evaluation_question 
                    (question, evaluation_id) VALUES 
                    ('Event Theme', '$evaluationID'), 
                    ('Security and Safety', '$evaluationID'),
                    ('Speaker, Host, and Guest Engagement', '$evaluationID'), 
                    ('Event Organization', '$evaluationID'), 
                    ('Timing and Schedule', '$evaluationID'), 
                    ('Venue and Facilities', '$evaluationID'), 
                    ('Networking Opportunities', '$evaluationID'), 
                    ('Entertainment or Social Activities', '$evaluationID'), 
                    ('Technical Equipment and Support', '$evaluationID'), 
                    ('Overall Satisfaction', '$evaluationID')");
                }
                else{
                    $sqladdEvaluation = mysqli_query($connection,"INSERT INTO evaluation_list (event_id) VALUE ('$eventID')");//status 0
     
                }

                echo 
                    "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Event Added Successfully',
                            timer: 3000
                        }).then(function() {
                            window.location = 'addEvent.php';
                        });
                    </script>";
            }
            

        }
    }
    elseif(isset($_POST['editEvent'])){

        $eventID = $_POST['eventID'];
        $sqlCheckActive = mysqli_query($connection, "SELECT * FROM event_list WHERE event_id = '$eventID' AND status = 0");
        
        if(mysqli_num_rows($sqlCheckActive) > 0){//inactive

            $typeOfEvent = $_POST['typeOfEvent'];
            $eventName = $_POST['eventName'];
            $eventVenue = $_POST['eventVenue'];
            if(isset($_POST['editEvaluation'])){
                $editEvaluation = 1;
                //para mawala sa evaluation list.php
                $sqlUpdateEval =  mysqli_query($connection,"UPDATE evaluation_list SET status = 1 WHERE event_id = '$eventID'");
            }
            else{
                $editEvaluation = 0;
                $sqlUpdateEval =  mysqli_query($connection,"UPDATE evaluation_list SET status = 0 WHERE event_id = '$eventID'");
            }

            $sqlEditEvent = "UPDATE event_list SET typeOfEvent = '$typeOfEvent', eventName = '$eventName', eventVenue = '$eventVenue', evaluationForm = '$editEvaluation'  WHERE event_id = '$eventID'";
            if(mysqli_query($connection, $sqlEditEvent)){

                if($typeOfEvent == 'Open Event'){

                    $sqlCheckEventOpen = mysqli_query($connection, "SELECT * FROM event_openqr WHERE event_id = '$eventID'");
                    if(mysqli_num_rows($sqlCheckEventOpen) > 0){
                        //echo "meron na";
                    }
                    else{
                        //never pa naging open event
                        $sqlEditOpenEvent = mysqli_query($connection, "INSERT INTO event_openqr (event_id) VALUE ('$eventID')");
                    }

                    
                }else{
                    $deleteOpenEvent = "DELETE FROM event_openqr WHERE event_id = '$eventID'";
                    if(mysqli_query($connection, $deleteOpenEvent)){
                        //echo "deleted";
                    }
                }

                //GET EVALUATION ID
                $sqlGetEvalID = mysqli_query($connection,"SELECT evaluation_id FROM evaluation_list WHERE event_id = '$eventID'");
                if(mysqli_num_rows($sqlGetEvalID) > 0){

                    while($row = mysqli_fetch_array($sqlGetEvalID)){
                        // add each row returned into an array
                        $evaluationID = $row; //EVAL_ID
                    }

                    //CHECK YUNG EVALUATION ID SA QUESTIONS IF INSERTED NA HINDI NA MAG-IINSERT ULIT
                    $sqlQuestionsEval = mysqli_query($connection,"SELECT * FROM evaluation_question WHERE evaluation_id = '$evaluationID[0]'");
                    if(mysqli_num_rows($sqlQuestionsEval) == 0){ //wala pang questions sa eval

                        $sqlInsertDefaultQuestions = mysqli_query($connection,"INSERT INTO evaluation_question 
                        (question, evaluation_id) VALUES 
                        ('Event Theme', '$evaluationID[0]'), 
                        ('Security and Safety', '$evaluationID[0]'),
                        ('Speaker, Host, and Guest Engagement', '$evaluationID[0]'), 
                        ('Event Organization', '$evaluationID[0]'), 
                        ('Timing and Schedule', '$evaluationID[0]'), 
                        ('Venue and Facilities', '$evaluationID[0]'), 
                        ('Networking Opportunities', '$evaluationID[0]'), 
                        ('Entertainment or Social Activities', '$evaluationID[0]'), 
                        ('Technical Equipment and Support', '$evaluationID[0]'), 
                        ('Overall Satisfaction', '$evaluationID[0]')");

                    }

                    $sqlAddExclusiveQR = mysqli_query($connection,"INSERT INTO eval_exclusiveevent_qr (evaluation_id) VALUE ('$evaluationID[0]')");

                }
                
                echo 
                    "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Update Event Successfully',
                            timer: 2000
                        }).then(function() {
                            window.location = 'addEvent.php';
                        });
                    </script>";
            }

        }

    }
    elseif(isset($_POST['deleteEvent'])){
        $eventID = $_POST['eventID'];
       
        $sqlDeleteEvent = "DELETE FROM event_list WHERE event_id = '$eventID'";
            if(mysqli_query($connection, $sqlDeleteEvent)){
                echo 
                    "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Event Deleted Successfully'
                        }).then(function() {
                            window.location = 'addEvent.php';
                        });
                    </script>";
            }
    }
    else if (isset($_POST['activateEvent'])){
        $eventID = $_POST['eventID'];
        
        $sqlCheckActive = mysqli_query($connection, "SELECT * FROM event_list WHERE event_id = '$eventID' AND status = 0");
        if(mysqli_num_rows($sqlCheckActive) > 0){//inactive
            
            $typeOfEvent = $_POST['typeOfEvent'];
            $eventName = $_POST['eventName'];
            $eventStart = $_POST['eventStart'];
            $eventEnd = $_POST['eventEnd'];

            if($eventStart != $dateToday){ //pending
                $sqlActivateEvent = "UPDATE event_list SET eventStart = '$eventStart', eventEnd = '$eventEnd', status = 3 WHERE event_id = '$eventID'";
            }
            else{//active
                $sqlActivateEvent = "UPDATE event_list SET eventStart = '$eventStart', eventEnd = '$eventEnd', status = 1 WHERE event_id = '$eventID'";
            }
            if(mysqli_query($connection, $sqlActivateEvent)){
                
                echo 
                "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Event has been Activated Successfully',
                        timer: 3000
                    }).then(function() {
                        window.location = 'addEvent.php';
                    });
                </script>";
                
            }
        
        }
    }
    else if (isset($_POST['deactivateEvent'])){
        $eventID = $_POST['eventID'];
        
        $sqlDeactivateEvent = "UPDATE event_list SET eventStart = '', eventEnd = '', status = 0 WHERE event_id = '$eventID'";
        
        if(mysqli_query($connection, $sqlDeactivateEvent)){
            echo 
                "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Event has been Deactivated Successfully',
                        timer: 3000
                    }).then(function() {
                        window.location = 'addEvent.php';
                    });
                </script>";
        }

    }
?>