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
session_start();
$accountID = $_SESSION['accountID']; //visitor

$getNumberOfVisitor = mysqli_query($connection, "SELECT numberOfVisitor FROM visitation_records WHERE typeOfVisit = 'Requested Visit' AND account_id = '$accountID' ORDER BY visitation_id DESC");
if(mysqli_num_rows($getNumberOfVisitor)>0){
    while ($row = mysqli_fetch_array($getNumberOfVisitor)) {
        $entranceNumOfVisitor[] = $row;
    }
}

date_default_timezone_set("Asia/Manila");

if(isset($_POST['submitPerson'])){
//PERSON
    //echo "<script>alert($accountID)</script>";
    $typeOfVisit = "Impromptu Visit";
    $typeOfForm = "Individual Person";
    
    $destination = mysqli_real_escape_string($connection, $_POST['destinationID']);
    $destinationAbbrev = mysqli_real_escape_string($connection, $_POST['destinationAbbrev']);
    $numberOfVisitor = mysqli_real_escape_string($connection,$entranceNumOfVisitor[0][0]);
    
    
    $getTimeInUniv = mysqli_query($connection, "SELECT timestamp, guard_account_id FROM visit_timein_university WHERE visitor_account_id='$accountID' AND dateVisit = '$dateToday' ORDER BY visitation_id DESC");
    if(mysqli_num_rows($getTimeInUniv) > 0){
        $timeInUnivInfo = mysqli_fetch_array($getTimeInUniv);
        $insertVisitationRecord = "INSERT INTO visitation_records (account_id, typeOfVisit, typeOfForm, destination_id, numberOfVisitor, timestampDestination, done) VALUE ('$accountID', '$typeOfVisit','$typeOfForm', '$destination', '$numberOfVisitor', '$currentTime', 3)";
        //echo $insertVisitationRecord;
        if(mysqli_query($connection, $insertVisitationRecord)){
            $visitationID = mysqli_insert_id($connection); //visitationID

            $insertTimeInUniv = "INSERT INTO visit_timein_university (visitation_id, visitor_account_id, dateVisit, timestamp, guard_account_id) VALUE ('$visitationID', '$accountID', '$dateToday', '$timeInUnivInfo[0]', '$timeInUnivInfo[1]')";
            if(mysqli_query($connection, $insertTimeInUniv)){

                $personToVisit = mysqli_real_escape_string($connection, $_POST['personToVisit']);
                $firstNamePerson = mysqli_real_escape_string($connection, $_POST['firstNamePerson']);
                $lastNamePerson = mysqli_real_escape_string($connection, $_POST['lastNamePerson']);
                $suffixNamePerson = mysqli_real_escape_string($connection, $_POST['suffixNamePerson']);
                $relationshipPerson = mysqli_real_escape_string($connection, $_POST['relationshipPerson']);
                $purposePerson = mysqli_real_escape_string($connection, $_POST['purposePerson']);
        
                $insertVisitPerson = "INSERT INTO visit_person (visitation_id, personToVisit, personFirstName, personLastName, personSuffixName, personRelationship, personPurpose) VALUES ('$visitationID', '$personToVisit', '$firstNamePerson', '$lastNamePerson', '$suffixNamePerson', '$relationshipPerson', '$purposePerson')";
                if(mysqli_query($connection, $insertVisitPerson)){
        
                    echo 
                        "<script>
                            Swal.fire({
                                icon: 'info',
                                title: 'Welcome to ".$destinationAbbrev." Building',
                                text: ' Your visit has been recorded. Thank you for your response!'
                            }).then(function() {
                                window.location = 'qrScanner.php';
                            });
                        </script>";
        
                }

            }

        }
        
    }
    else{
        echo "<script>alert('visit requested insertfailed')</script>";
    }

}
elseif(isset($_POST['submitOffice'])){
    $typeOfVisit = "Impromptu Visit";
    $typeOfForm = "Office/Building";
    
    $destination = mysqli_real_escape_string($connection, $_POST['destinationID']);
    $destinationAbbrev = mysqli_real_escape_string($connection, $_POST['destinationAbbrev']);
    $numberOfVisitor = mysqli_real_escape_string($connection, $entranceNumOfVisitor[0][0]);


    $getTimeInUniv = mysqli_query($connection, "SELECT timestamp, guard_account_id FROM visit_timein_university WHERE visitor_account_id='$accountID' AND dateVisit = '$dateToday' ORDER BY visitation_id DESC");
    if(mysqli_num_rows($getTimeInUniv) > 0){
        $timeInUnivInfo = mysqli_fetch_array($getTimeInUniv);
        $insertVisitationRecord = "INSERT INTO visitation_records (account_id, typeOfVisit, typeOfForm, destination_id, numberOfVisitor, timestampDestination, done) VALUE ('$accountID', '$typeOfVisit','$typeOfForm', '$destination', '$numberOfVisitor', '$currentTime', 3)";
        //echo $insertVisitationRecord;
        if(mysqli_query($connection, $insertVisitationRecord)){
            $visitationID = mysqli_insert_id($connection); //visitationID

        
            $insertTimeInUniv = "INSERT INTO visit_timein_university (visitation_id, visitor_account_id, dateVisit, timestamp, guard_account_id) VALUE ('$visitationID', '$accountID', '$dateToday', '$timeInUnivInfo[0]', '$timeInUnivInfo[1]')";
            if(mysqli_query($connection, $insertTimeInUniv)){

                //destination yung officeBldgName
                $purposeOffice = mysqli_real_escape_string($connection, $_POST['purposeOffice']);

                $sqlVisitationOffice = "INSERT INTO visit_officebldg (visitation_id, officeBldgPurpose) VALUES ('$visitationID', '$purposeOffice')";
                if(mysqli_query($connection, $sqlVisitationOffice)){
        
                    echo
                        "<script>
                            Swal.fire({
                                icon: 'info',
                                title: 'Welcome to ".$destinationAbbrev." Building',
                                text: ' Your visit has been recorded. Thank you for your response!'
                            }).then(function() {
                                window.location = 'qrScanner.php';
                            });
                        </script>";
        
                }else{
                    echo "'visit requested insertfailed'";
                }

            }else{
                echo "visit time in";
            }

        }else{
            echo "visitation records";
        }
        
    }
    else{
        echo "select visit time in univ";
    }

}
elseif(isset($_POST['submitEvent'])){
    
    $typeOfVisit = "Impromptu Visit";
    $typeOfForm = "Event";
    
    $destination = mysqli_real_escape_string($connection, $_POST['destinationID']);
    $destinationAbbrev = mysqli_real_escape_string($connection, $_POST['destinationAbbrev']);
    $numberOfVisitor = mysqli_real_escape_string($connection, $entranceNumOfVisitor[0][0]);


    
    $getTimeInUniv = mysqli_query($connection, "SELECT timestamp, guard_account_id FROM visit_timein_university WHERE visitor_account_id='$accountID' AND dateVisit = '$dateToday' ORDER BY visitation_id DESC");
    if(mysqli_num_rows($getTimeInUniv) > 0){
        $timeInUnivInfo = mysqli_fetch_array($getTimeInUniv);
        $insertVisitationRecord = "INSERT INTO visitation_records (account_id, typeOfVisit, typeOfForm, destination_id, numberOfVisitor, timestampDestination, done) VALUE ('$accountID', '$typeOfVisit','$typeOfForm', '$destination', '$numberOfVisitor', '$currentTime', 3)";
        //echo $insertVisitationRecord;
        if(mysqli_query($connection, $insertVisitationRecord)){
            $visitationID = mysqli_insert_id($connection); //visitationID
        
            $insertTimeInUniv = "INSERT INTO visit_timein_university (visitation_id, visitor_account_id, dateVisit, timestamp, guard_account_id) VALUE ('$visitationID', '$accountID', '$dateToday', '$timeInUnivInfo[0]', '$timeInUnivInfo[1]')";
            if(mysqli_query($connection, $insertTimeInUniv)){

                //destination yung venue
                $eventID = mysqli_real_escape_string($connection, $_POST['eventID']);
                $purposeEvent = mysqli_real_escape_string($connection, $_POST['purposeEvent']);

                $sqlVisitationEvent = 'INSERT INTO visit_event (visitation_id, event_id, eventPurpose) VALUES ("'.$visitationID.'","'.$eventID.'","'.$purposeEvent.'")';
                if(mysqli_query($connection, $sqlVisitationEvent)){
                    
                    echo
                        "<script>
                            Swal.fire({
                                icon: 'info',
                                title: 'Welcome to ".$destinationAbbrev." Building',
                                text: ' Your visit has been recorded. Thank you for your response!'
                            }).then(function() {
                                window.location = 'qrScanner.php';
                            });
                        </script>";

                }
                else{
                    echo "'visit event insertfailed')</script>";
                }
                    

            }else{
                echo "visit time in univ ";
            }

        }else{
            echo "visitation records";
        }

    }
    else{
        echo "your records are not checked in the entrance";
    }
    
}
?>