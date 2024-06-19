<?php
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "vysmo_db";

    $connection = mysqli_connect($host, $user, $password, $database);

    if(mysqli_connect_error()){
        echo "<script>alert('Error: Unable to connect to MySQL')</script>";
    }

    date_default_timezone_set("Asia/Manila");
    $currentDateTime = date("Y-m-d g:i:s A");
    $dateToday = date("Y-m-d");
    $currentTime = date('h:i A');


    //PENDING EVENT START -> ACTIVATE
    $getAllPendingEvent = mysqli_query($connection, "SELECT event_id FROM event_list WHERE status = 3 AND eventStart = '$dateToday'");
    if(mysqli_num_rows($getAllPendingEvent) > 0){
        while ($row = mysqli_fetch_array($getAllPendingEvent)) {
            // add each row returned into the $infoPerson array
            $eventIDPending = $row['event_id'];

            $updatePendingEvent = mysqli_query($connection, "UPDATE event_list SET status = 1 WHERE event_id = '$eventIDPending'");
        }  
    }


    //INVALID VISIT (HINDI PUMUNTA SA TIMEIN Appointment Visit) AUTO
    $getAllTimeInUniversity = mysqli_query($connection, "SELECT visitation_records.visitation_id, visitation_records.account_id, visit_timein_university.dateVisit
                                                     FROM visitation_records 
                                                     INNER JOIN visit_timein_university 
                                                     ON visitation_records.visitation_id = visit_timein_university.visitation_id 
                                                     WHERE visitation_records.typeOfVisit = 'Requested Visit' 
                                                     AND DATE(visit_timein_university.dateVisit) < DATE('$dateToday') 
                                                     AND visitation_records.done = 1");
    if(mysqli_num_rows($getAllTimeInUniversity) > 0){
        $numUpdateInvalid = mysqli_num_rows($getAllTimeInUniversity);
        while ($row = mysqli_fetch_array($getAllTimeInUniversity)) {
            // add each row returned into the $infoPerson array
            $visitationID = $row['visitation_id'];
            $getAppointmentVisit = mysqli_query($connection, "SELECT appointmentVisit FROM visit_requested WHERE visitation_id = '$visitationID'");
            $appointmentVisit = mysqli_fetch_array($getAppointmentVisit);
            $row['appointmentVisit'] = $appointmentVisit[0];

            $invalidVisits[] = $row;
        }

        for($i=0; $i < $numUpdateInvalid; $i++){

            $invalidVisitID = $invalidVisits[$i]['visitation_id'];
            $accountID = $invalidVisits[$i]['account_id'];
            $appointmentVisit = $invalidVisits[$i]['appointmentVisit'];;

            if($dateToday > $appointmentVisit){
                $updateInvalidVisit = mysqli_query($connection,"UPDATE visitation_records SET done = 4 WHERE visitation_id = '$invalidVisitID'");

            }
            

        }
        /*
        echo "<pre>";
        echo print_r($invalidVisits);
        echo "</pre>";
        */
    }
    
    //ACTIVE EVENT EXPIRATION EVENT END
    //ALL EVENT AND VISITATION EVENT UPDATE TO BE EXPIRED 
    $sqlGetAllActiveEvent = mysqli_query($connection,"SELECT event_id, eventEnd FROM event_list WHERE status = 1 AND DATE(eventEnd) < DATE('$dateToday')");
    if(mysqli_num_rows($sqlGetAllActiveEvent) > 0){ 
        $arrayUpdateActiveEvent = array();
        while ($row = mysqli_fetch_array($sqlGetAllActiveEvent)) {
            // add each row returned into the $infoPerson array
            $arrayUpdateActiveEvent[] = $row;
        }
        /*
        echo "<pre>";
        echo print_r($arrayUpdateActiveEvent);
        echo "</pre>";
        */
        //updatevisitation
        $sqlGetVisitationEvent = mysqli_query($connection, "SELECT visitation_id FROM visitation_records WHERE typeOfForm ='Event' AND done = 0");
        if(mysqli_num_rows($sqlGetVisitationEvent) > 0){
            
            $arrayVisitationID = array();
            while ($row = mysqli_fetch_array($sqlGetVisitationEvent)) {
                // add each row returned into the $infoPerson array
                $arrayVisitationID[] = $row;
            }
            /*
            echo "<pre>";
            echo print_r($arrayVisitationID);
            echo "</pre>";
            */
            for($a=0; $a < mysqli_num_rows($sqlGetVisitationEvent); $a++){

                if ($dateToday > $arrayUpdateActiveEvent[$a]['eventEnd']) {
                    //expired
                    $sqlUpdateVisitationEvent = mysqli_query($connection,"UPDATE visitation_records SET done = 5, dateExpired = '$dateToday' WHERE visitation_id = '".$arrayVisitationID[$a]['visitation_id']."'");

                }

            }

        }
        //updateevent list status
        for($q=0; $q < mysqli_num_rows($sqlGetAllActiveEvent); $q++){

            $eventEndCheck = $arrayUpdateActiveEvent[$q]['eventEnd'];

            if($eventEndCheck < $dateToday){
                $sqlCompleteEvent = "UPDATE event_list SET status = 2 WHERE event_id = '".$arrayUpdateActiveEvent[$q]['event_id']."'";
                $sqlCompleteEvaluationList = "UPDATE evaluation_list SET status = 2 WHERE event_id = '".$arrayUpdateActiveEvent[$q]['event_id']."'";
                if(mysqli_query($connection, $sqlCompleteEvent) && mysqli_query($connection, $sqlCompleteEvaluationList)){
                    //updated event completed
                    //updated evaluation
                }
                else{
                    echo "Error updating eventlist status: " . mysqli_error($connection);
                }
            }

        }

    }



    //REQUEST VISIT EXPIRATION 5days
    $sqlGetAllApprovedVisit = mysqli_query($connection, "SELECT visitation_records.visitation_id, visit_requested.appointmentVisit
                                        FROM visitation_records INNER JOIN visit_requested 
                                        ON visitation_records.visitation_id = visit_requested.visitation_id 
                                        WHERE DATE(visit_requested.appointmentVisit) < DATE('$dateToday') AND visitation_records.done = 0");
    if(mysqli_num_rows($sqlGetAllApprovedVisit) > 0){
        $arrayDateToVisit = array();
        while ($row = mysqli_fetch_array($sqlGetAllApprovedVisit)) {
            // add each row returned into the $infoPerson array
            $arrayDateToVisit[] = $row;
        }
        
        // echo "<pre>";
        // echo print_r($arrayDateToVisit);
        // echo "</pre>";
        
        // Calculate the date 5 days after the visitation date
        for($q=0; $q < mysqli_num_rows($sqlGetAllApprovedVisit); $q++){
            $fiveDaysAfterDateToVisit = date('Y-m-d', strtotime('+5 days', strtotime($arrayDateToVisit[$q]['appointmentVisit'])));
            
        
            //echo $fiveDaysAfterDateToVisit;
            //expired
            if ($dateToday >= $fiveDaysAfterDateToVisit) {
                // Update the 'done' column to 5 EXPIRED
                $sqlUpdateDone = "UPDATE visitation_records SET done = 5, dateExpired = '$dateToday' WHERE visitation_id = '".$arrayDateToVisit[$q]['visitation_id']."'";

                if (mysqli_query($connection, $sqlUpdateDone)) {
                    
                } else {
                    echo "Error updating visitation status: " . mysqli_error($connection);
                }
            }
        }

    }

/*
    //evaluationEnd automatically after 1 day of the event end

    //ALL EVENT AND VISITATION EVENT UPDATE TO BE EXPIRED 
    $sqlGetAllActiveEvent = mysqli_query($connection,"SELECT eventID, eventEnd FROM eventlist WHERE status = 1 AND DATE(eventEnd) < DATE('$currentDate')");
    if(mysqli_num_rows($sqlGetAllActiveEvent) > 0){ 
        $arrayUpdateActiveEvent = array();
        while ($row = mysqli_fetch_array($sqlGetAllActiveEvent)) {
            // add each row returned into the $infoPerson array
            $arrayUpdateActiveEvent[] = $row;
        }

        
        
        
        echo "<pre>";
        echo print_r($arrayUpdateActiveEvent);
        echo "</pre>";
        


        //updatevisitation
        $sqlGetVisitationEvent = mysqli_query($connection, "SELECT visitationID FROM visitation WHERE typeOfForm ='Event' AND done = 1");
        if(mysqli_num_rows($sqlGetVisitationEvent) > 0){
            $arrayVisitationID = array();
            while ($row = mysqli_fetch_array($sqlGetVisitationEvent)) {
                // add each row returned into the $infoPerson array
                $arrayVisitationID[] = $row;
            }
    
            echo "<pre>";
            echo print_r($arrayVisitationID);
            echo "</pre>";
            for($a=0; $a < mysqli_num_rows($sqlGetVisitationEvent); $a++){

                if ($currentDate > $arrayUpdateActiveEvent[$a]['eventEnd']) {
                    //expired
                    $sqlUpdateVisitationEvent = mysqli_query($connection,"UPDATE visitation SET done = 4, dateExpired = '$currentDate' WHERE visitationID = '".$arrayVisitationID[$a]['visitationID']."'");

                }

            }

        }


        //updateevent list status
        

        for($q=0; $q < mysqli_num_rows($sqlGetAllActiveEvent); $q++){

            $eventEndCheck = $arrayUpdateActiveEvent[$q]['eventEnd'];

            if($eventEndCheck < $currentDate){
                $sqlCompleteEvent = "UPDATE eventlist SET status = 2 WHERE eventID = '".$arrayUpdateActiveEvent[$q]['eventID']."'";
                $sqlCompleteEvaluationList = "UPDATE evaluationlist SET status = 2 WHERE eventID = '".$arrayUpdateActiveEvent[$q]['eventID']."'";
                if(mysqli_query($connection, $sqlCompleteEvent) && mysqli_query($connection, $sqlCompleteEvaluationList)){
                    //updated event completed
                    //updated evaluation
                }
                else{
                    echo "Error updating eventlist status: " . mysqli_error($connection);
                }
            }

        }

        


    }

    //KINUHA LAHAT NG TAPOS NA EVENT, and update status nila into done. 
    //YUNG then we give 1 day for visitor to answer the evaluation if hindi sagutan IT WILL EXPIRE
    $sqlGetAllCompletedEvent = mysqli_query($connection, "SELECT eventID, eventEnd FROM eventlist WHERE status = 2 AND DATE(eventEnd) < DATE('$currentDate')");

    if (mysqli_num_rows($sqlGetAllCompletedEvent) > 0) {
        $arrayUpdateEvent = array();

        while ($row = mysqli_fetch_array($sqlGetAllCompletedEvent)) {
            $arrayUpdateEvent[] = $row;
        }

        $sqlGetAllCompletedEvaluation = mysqli_query($connection, "SELECT * FROM evaluationlist WHERE status = 2");

        if (mysqli_num_rows($sqlGetAllCompletedEvaluation) > 0) {
            $arrayUpdateEvaluation = array();

            while ($row = mysqli_fetch_array($sqlGetAllCompletedEvaluation)) {
                $arrayUpdateEvaluation[] = $row;
            }

            foreach ($arrayUpdateEvent as $event) {
                $eventId = $event['eventID'];
                $endOfEvaluation = date('Y-m-d', strtotime('+1 day', strtotime($event['eventEnd'])));

                if ($currentDate > $endOfEvaluation) {
                    // Find the corresponding evaluation record
                    $evaluationId = ''; // Initialize the variable to store the evaluation ID

                    // Loop through $arrayUpdateEvaluation to find the matching evaluation
                    foreach ($arrayUpdateEvaluation as $evaluation) {
                        if ($evaluation['eventID'] == $eventId) {
                            $evaluationId = $evaluation['evaluationID'];
                            break; // Exit the loop once a match is found
                        }
                    }

                    if (!empty($evaluationId)) {
                        // Update records for this event in the evaluateevent table
                        $sqlUpdate = "UPDATE evaluateevent SET done = 2 WHERE evaluationID = '$evaluationId'";
                        mysqli_query($connection, $sqlUpdate);
                    }
                }
            }
        }
    }
    
    */
?>

