<?php 
    $queryVInfo = "SELECT firstName, middleInitial, lastName, suffixName, gender, mobileNumber, houseNumber, street, barangay, city, province, nationality, typeOfID, selfieWithID, frontID, backID FROM visitor_info WHERE account_id='{$accountID}'";
    $sqlVInfo = mysqli_query($connection, $queryVInfo);

    $array = array();

    // look through query
    while($row = mysqli_fetch_array($sqlVInfo)){
    // add each row returned into an array
        $array[] = $row;

    }

    
?>