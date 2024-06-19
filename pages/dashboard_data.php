<?php
// Include your database connection code here if needed
include('../database.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Process the AJAX request
if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    error_log("Received start_date: $start_date, end_date: $end_date");

    // Fetch data for the third chart (e.g., pie chart)
    $dataForExpectedVisit = fetchDataForExpectedVisit($start_date, $end_date, $connection);

    // Fetch data for the third chart (e.g., pie chart)
    $dataForSuccessfulVisit = fetchDataForSuccessfulVisit($start_date, $end_date, $connection);
    // Fetch data for the third chart (e.g., pie chart)
    $dataForTotalVisitors = fetchDataForTotalVisitors($start_date, $end_date, $connection);
    // Fetch data for the third chart (e.g., pie chart)
    $dataForChart1 = fetchDataForChart1($start_date, $end_date, $connection);
    // Fetch data for the third chart (e.g., pie chart)
    $dataForChart2 = fetchDataForChart2($start_date, $end_date, $connection);
    // Fetch data for the third chart (e.g., pie chart)
    $dataForChart3 = fetchDataForChart3($start_date, $end_date, $connection);
    // Fetch data for the third chart (e.g., pie chart)
    $dataForChartOpenEvent1 = fetchDataForChartOpenEvent1($start_date, $end_date, $connection);

    // Combine the data into an array or object
    $chartData = [
        'expectedVisit' => $dataForExpectedVisit,
        'successfulVisit' => $dataForSuccessfulVisit,
        'totalVisitors' => $dataForTotalVisitors,
        'chart1' => $dataForChart1,
        'chart2' => $dataForChart2,
        'chart3' => $dataForChart3,
        'chartOpenEvent1' => $dataForChartOpenEvent1
        
    ];

    // Return the combined data as JSON
    echo json_encode($chartData);
} 
else {
    // Handle invalid or missing parameters
    echo 'Invalid request';
}

//EXPECTED VISITS
    function fetchDataForExpectedVisit($start_date, $end_date, $connection)
    {
        date_default_timezone_set("Asia/Manila");
        $dateToday = date('Y-m-d');
    
        $startDateObj = new DateTime($start_date);
        $endDateObj = new DateTime($end_date);
    
        $countExpectedVisit = 0;
    
        // Generate labels for the date range
        while ($startDateObj <= $endDateObj) {
            $date = $startDateObj->format('Y-m-d');
    
            if ($date == $dateToday) {
                // Count expected visits for the current date
                $sqlExpectedVisits = mysqli_query($connection, "SELECT visitation_id FROM visitation_records WHERE done = 0");
                
                while ($row = mysqli_fetch_array($sqlExpectedVisits)) {
                    $visitationID = $row['visitation_id'];
    
                    $checkNumOfRequested = mysqli_query($connection, "SELECT * FROM visit_requested WHERE visitation_id = '$visitationID' AND appointmentVisit = '$dateToday'");
                    
                    // Check if any visit requests exist for this visitation_id and today's date
                    if (mysqli_num_rows($checkNumOfRequested) > 0) {
                        $countExpectedVisit++;
                    }
                }
            }
    
            $startDateObj->modify('+1 day'); // Increment the date by one day
        }
    
        return $countExpectedVisit;
    }
    
//SUCCESSFUL VISITS
    function fetchDataForSuccessfulVisit($start_date, $end_date, $connection)
    {
        $startDateObj = new DateTime($start_date);
        $endDateObj = new DateTime($end_date);

        $countSuccessfulVisit = 0;

        // Generate labels for the date range
        while ($startDateObj <= $endDateObj) {
            $date = $startDateObj->format('Y-m-d');

            // Count expected visits for the current date
            $sqlSuccessfulVisit = mysqli_query($connection, "SELECT * FROM visitation_records WHERE done = 3"); 
            if(mysqli_num_rows($sqlSuccessfulVisit) > 0){
                while ($row = mysqli_fetch_assoc($sqlSuccessfulVisit)) {
                    $visitationID = $row['visitation_id'];
                    
                    $sqlCheckVisitDestination = mysqli_query($connection, "SELECT * FROM visit_timein_university WHERE visitation_id = '$visitationID' AND dateVisit = '$date'");
                    // If a record is found, increment the count
                    if (mysqli_num_rows($sqlCheckVisitDestination) > 0) {
                        $countSuccessfulVisit++;
                    }
                }
            }
            
            $startDateObj->modify('+1 day'); // Increment the date by one day
        }

        return $countSuccessfulVisit;
    }


    
    //TOTAL VISITORS
    function fetchDataForTotalVisitors($start_date, $end_date, $connection)
{
    
    $startDateObj = new DateTime($start_date);
    $endDateObj = new DateTime($end_date);
    
    $dateStart = $startDateObj->format('Y-m-d');
    $dateEnd = $endDateObj->format('Y-m-d');

    $sqlGetDoneVisit = mysqli_query($connection, "SELECT visitation_records.visitation_id, visitation_records.account_id, visitation_records.typeOfForm, visitation_records.destination_id, visitation_records.numberOfVisitor, visitation_records.timestampDestination 
                                    FROM visitation_records INNER JOIN visit_timein_university 
                                    ON visitation_records.visitation_id = visit_timein_university.visitation_id 
                                    WHERE visitation_records.done = 3 AND DATE(visit_timein_university.dateVisit) >= DATE('$dateStart') AND DATE(visit_timein_university.dateVisit) <= DATE('$dateEnd')");

        if (mysqli_num_rows($sqlGetDoneVisit) > 0) {
            $numberOfDoneVisit = mysqli_num_rows($sqlGetDoneVisit);
            $allvisitation = array();

            while ($row = mysqli_fetch_array($sqlGetDoneVisit)) {
                $visitationID = $row['visitation_id'];

                // Retrieve dateVisit and timestamp from visit_timein_university
                $getTimeinInfo = mysqli_query($connection, "SELECT dateVisit, timestamp FROM visit_timein_university WHERE visitation_id = '$visitationID'");
                
                // Initialize arrays to store dateVisit and timestamp values

                while ($timeinRow = mysqli_fetch_array($getTimeinInfo)) {
                    // Add dateVisit and timestamp values to their respective arrays
                    $dateVisit = $timeinRow['dateVisit'];
                    $timestamp = $timeinRow['timestamp'];
                }

                // Retrieve and set the destination name
                $destinationID = $row['destination_id'];
                $getDestinationName = mysqli_query($connection, "SELECT destination FROM destination_list WHERE destination_id = '$destinationID'");
                $destinationRow = mysqli_fetch_array($getDestinationName);
                $row['destination'] = $destinationRow['destination'];

                // Add dateVisit and timestamp arrays to the row
                $row['dateVisit'] = $dateVisit;
                $row['timestamp'] = $timestamp;

                // Add the row to the result array
                $allvisitation[] = $row;
            }
            $countTotalVisitors = 0;
            $lastDateVisit = null;
            $lastTimestamp = null;
            $lastAccountID = null;

            for ($i = 0; $i < $numberOfDoneVisit; $i++) {
                $dateVisit = $allvisitation[$i]['dateVisit'];
                $timestamp = $allvisitation[$i]['timestamp'];
                $accountID = $allvisitation[$i]['account_id'];

                // Check if the current interval matches the last interval
                if (
                    is_null($lastDateVisit) ||
                    $accountID != $lastAccountID ||
                    $dateVisit != $lastDateVisit ||
                    $timestamp != $lastTimestamp
                ) {
                    // If it doesn't match, add the numberOfVisitor to the total
                    $countTotalVisitors += $allvisitation[$i]['numberOfVisitor'];
                }

                // Update the last interval
                $lastDateVisit = $dateVisit;
                $lastTimestamp = $timestamp;
                $lastAccountID = $accountID;
            }
        }
        else{
            $countTotalVisitors = 0;
        }


    return $countTotalVisitors;
}


   
//BAR GRAPH
function fetchDataForChart1($start_date, $end_date, $connection)
{
    $destinationData = array(); // Initialize an array to store destination data

    // Query to get all destinations that meet the specified criteria
    $getAllDestination = mysqli_query($connection, "SELECT destination_id, destination FROM destination_list WHERE status = 0 ORDER BY destination ASC");

    while ($row = mysqli_fetch_array($getAllDestination)) {
        $dest = $row['destination_id'];
        $destination = $row['destination'];

        // Query to count visits for the current destination within the date range
        $sqlCountVisitors = mysqli_query($connection, "SELECT COUNT(*) as visitCount, SUM(visitation_records.numberOfVisitor) as totalVisitors
            FROM visitation_records
            INNER JOIN visit_timein_university ON visitation_records.visitation_id = visit_timein_university.visitation_id
            WHERE visitation_records.done = 3
            AND visitation_records.destination_id = '$dest' AND DATE(visit_timein_university.dateVisit) BETWEEN '$start_date' AND '$end_date'");

        if ($rowCount = mysqli_fetch_assoc($sqlCountVisitors)) {
            $count = (int)$rowCount['visitCount']; // Get the count from the query result
            $totalNumOfVisitor = (int)$rowCount['totalVisitors'];

            // Store destination name, count, and total visitors as an associative array
            $destinationData[] = array('destinationName' => $destination, 'count' => $count, 'totalVisitor' => $totalNumOfVisitor);
        }
    }

    // Now, $destinationData contains the destination names, counts, and total visitors
    // You can use this data as needed, e.g., to create a chart
    return $destinationData;
}

//LINE GRAPH
    function fetchDataForChart2($start_date, $end_date, $connection)
    {
        $successfulVisits = [];
        $expiredVisits = [];
        $labels = [];

        $startDateObj = new DateTime($start_date);
        $endDateObj = new DateTime($end_date);

        // Generate labels for the date range
        while ($startDateObj <= $endDateObj) {
            $date = $startDateObj->format('Y-m-d');
            
            $labels[] = $startDateObj->format('M d'); //month day format
           
            $sqlVisitTimestampDestination = mysqli_query($connection, "SELECT * FROM visitation_records INNER JOIN visit_timein_university 
                                                            ON visitation_records.visitation_id = visit_timein_university.visitation_id
                                                            WHERE DATE(visit_timein_university.dateVisit) = DATE('$date') AND visitation_records.done = 3");
            if(mysqli_num_rows($sqlVisitTimestampDestination) > 0){
                $successfulVisits[] = mysqli_num_rows($sqlVisitTimestampDestination); // Get the count of rows
            } else {
                $successfulVisits[] = 0; // No visits for this date
            }
            
            //EXPIRED VISITS
            $sqlExpiredVisitation = mysqli_query($connection, "SELECT * FROM visitation_records WHERE done = 5 AND dateExpired = '$date'"); //done 1 is approved
            if(mysqli_num_rows($sqlExpiredVisitation) > 0){

                $expiredVisits[] = mysqli_num_rows($sqlExpiredVisitation);
            }
            else{
                $expiredVisits[] = 0;//no expired visits that day
            }


            $startDateObj->modify('+1 day'); // Increment the date by one day
        }

        $dataForChart2 = [
            'SuccessfulVisit' => $successfulVisits,
            'ExpiredVisit' => $expiredVisits,
            'Labels' => $labels
        ];

        return $dataForChart2;
    }




    function fetchDataForChart3($start_date, $end_date, $connection) //SEX DISTRIBUTION
    {


        $startDateObj = new DateTime($start_date);
        $endDateObj = new DateTime($end_date);

        $dateStart = $startDateObj->format('Y-m-d');
        $dateEnd = $endDateObj->format('Y-m-d');
        
        $sql = "SELECT DISTINCT v.account_id, vi.gender, vt.timestamp, vt.dateVisit
                FROM visitation_records v
                JOIN visit_timein_university vt ON v.visitation_id = vt.visitation_id
                JOIN visitor_info vi ON v.account_id = vi.account_id
                WHERE v.done = 3
                AND DATE(vt.dateVisit) >= '$dateStart'
                AND DATE(vt.dateVisit) <= '$dateEnd'";
        
        $result = mysqli_query($connection, $sql);
        
        $maleCount = 0;
        $femaleCount = 0;
        $guestCount = 0;
        
        $lastAccountID = null;
        $lastDateVisit = null;
        $lastTimestamp = null;
        
        while ($row = mysqli_fetch_array($result)) {
            $gender = $row['gender'];
            $timestamp = $row['timestamp'];
        
            if (
                is_null($lastAccountID) ||
                $row['account_id'] != $lastAccountID ||
                $timestamp != $lastTimestamp
            ) {
                if ($gender == 'Male') {
                    $maleCount++;
                } elseif ($gender == 'Female') {
                    $femaleCount++;
                } else {
                    $guestCount++;
                }
            }
        
            $lastAccountID = $row['account_id'];
            $lastDateVisit = $row['dateVisit'];
            $lastTimestamp = $timestamp;
        }
        
        return [$maleCount, $femaleCount, $guestCount];




//------------------2nd version code it doesnt compare the timestamp,-------------------------------
        // $startDateObj = new DateTime($start_date);
        // $endDateObj = new DateTime($end_date);

        // $dateStart = $startDateObj->format('Y-m-d');
        // $dateEnd = $endDateObj->format('Y-m-d');

        // $sql = "SELECT DISTINCT v.account_id, vi.gender
        //         FROM visitation_records v
        //         JOIN visit_timein_university vt ON v.visitation_id = vt.visitation_id
        //         JOIN visitor_info vi ON v.account_id = vi.account_id
        //         WHERE v.typeOfVisit = 'Requested Visit'
        //         AND v.done = 3
        //         AND DATE(vt.dateVisit) >= '$dateStart'
        //         AND DATE(vt.dateVisit) <= '$dateEnd'";

        // $result = mysqli_query($connection, $sql);

        // $maleCount = 0;
        // $femaleCount = 0;
        // $guestCount = 0;

        // while ($row = mysqli_fetch_array($result)) {
        //     $gender = $row['gender'];
        //     if ($gender == 'Male') {
        //         $maleCount++;
        //     } elseif ($gender == 'Female') {
        //         $femaleCount++;
        //     } else {
        //         $guestCount++;
        //     }
        // }

        // return [$maleCount, $femaleCount, $guestCount];




//------------------------1st version code, it still duplicates the number of it.------------------------------------

        // $sqlGetVisitationID = mysqli_query($connection, "SELECT * FROM visitation_records WHERE done = 3");
        // if (mysqli_num_rows($sqlGetVisitationID) > 0) {
        //     $numOfVisitation = mysqli_num_rows($sqlGetVisitationID);
        //     $maleCount = 0;
        //     $femaleCount = 0;
        //     $guestCount = 0;
        //     while ($row = mysqli_fetch_array($sqlGetVisitationID)) {
        //         $accountID = $row['account_id'];
        //         $visitationID = $row['visitation_id'];

        //         $sqlCheckDate = "SELECT * FROM visit_timein_university
        //             WHERE visitation_id = $visitationID
        //             AND DATE(dateVisit) >= DATE('$start_date')
        //             AND DATE(dateVisit) <= DATE('$end_date')";

        //         $result = mysqli_query($connection, $sqlCheckDate);

        //         if (mysqli_num_rows($result) > 0) {
        //             $sqlCheckGender = mysqli_query($connection, "SELECT gender FROM visitor_info WHERE account_id = '$accountID'");
                    
        //             while ($genderRow = mysqli_fetch_array($sqlCheckGender)) {
        //                 $gender = $genderRow['gender'];
        //                 if ($gender == 'Male') {
        //                     $maleCount++;
        //                 } elseif ($gender == 'Female') {
        //                     $femaleCount++;
        //                 }
        //                 else{
        //                     $guestCount++;
        //                 }
        //             }
        //         }
        //     }
        //     $dataForChart3 = [$maleCount, $femaleCount, $guestCount];

        //     return $dataForChart3;
        // } else {
        //     // Handle case where no data is found
        //     return [0, 0, 0];
        // }


        
    }

    

    function fetchDataForChartOpenEvent1($start_date, $end_date, $connection)
{
    $startDateObj = new DateTime($start_date); // Example start date
    $endDateObj = new DateTime($end_date);     // Example end date

    $sqlGetOpenEventName = mysqli_query($connection, "SELECT event_id, eventName, eventStart, eventEnd FROM event_list WHERE typeOfEvent='Open Event'");
    
    // Initialize an array to store the results
    $results = [];

    while ($row = mysqli_fetch_array($sqlGetOpenEventName)) {
        $eventID = $row['event_id'];
        $eventName = $row['eventName'];
        $eventStart = new DateTime($row['eventStart']); // Example event start date
        $eventEnd = new DateTime($row['eventEnd']);     // Example event end date

        // Initialize the day counter
        $dayCounter = 1;
        $currentDate = clone $eventStart; // Start from the event start date

        while ($currentDate <= $eventEnd) {
            // Create an array with currentDate and dayCounter
            $dayInfo = [
                'currentDate' => $currentDate->format('Y-m-d'), // Format as 'YYYY-MM-DD'
                'dayCounter' => $dayCounter,
            ];

            // Compare currentDate with start_date and end_date
            if ($currentDate >= $startDateObj && $currentDate <= $endDateObj) {
                // The current date is within the specified date range
                // Query the database to get the number of visitors for this date and event
                $sqlGetNumberOfVisitor = mysqli_query($connection, "SELECT numberOfVisitor FROM visit_openevent WHERE event_id='$eventID' AND DATE(dateVisit)='{$dayInfo['currentDate']}'");

                $totalVisitor = 0; // Initialize totalVisitor for this day

                while ($visitorRow = mysqli_fetch_array($sqlGetNumberOfVisitor)) {
                    // Add each row's numberOfVisitor to the totalVisitor for this day
                    $totalVisitor += $visitorRow['numberOfVisitor'];
                }

                // Add the information to the results array
                $results[] = [
                    'currentDate' => $dayInfo['currentDate'],
                    'dayCounter' => " $eventName (". "Day ". $dayCounter . ")",
                    'totalNumberOfVisitor' => $totalVisitor,
                ];
            }

            $currentDate->modify('+1 day');
            $dayCounter++;
        }
    }

    // Now $results contains event details with total visitors within the date range
    return $results;
}



?>
