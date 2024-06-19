<?php
    include('../database.php');
    session_start();

    if($_SESSION['role'] == 'Guard'){
        $accountID = $_SESSION['accountID'];
        $activity = "LOGGED OUT";

        mysqli_query($connection, "INSERT INTO guard_logs (account_id, date_logs, activity, time_logs) VALUE ('$accountID', '$dateToday', '$activity', '$currentTime')");

    }

    /* Set status to invalid */
    $_SESSION['statpage'] = 'invalid';

    /* Unset user data */
    unset($_SESSION['accountID']);
    unset($_SESSION['email']);
    unset($_SESSION['page']);
    unset($_SESSION['role']);
    unset($_SESSION['accountQR']);
    
    header('Location: ../');

?>