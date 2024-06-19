<?php
    include('database.php');

?>
<!DOCTYPE html>
<html>
<head>
    <title>VYSMO</title>
    
    <link href="images/vysmoprintlogo.png" rel="icon">
    <script src="nodevtool.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/051c506296.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
    <link rel="stylesheet" href="registration/registration.css">
    <script src="registration/script.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- Customized Bootstrap Stylesheet -->
    <link href="pages/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="pages/css/style.css" rel="stylesheet">
    <style>
    .center {
        margin: 0;
        width: 80%;
        position: absolute;
        top: 50%;
        left: 50%;
        -ms-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        }
</style>
</head>
<body class="regBody" >

    <?php

    $encodedData = $_GET['encdata'];
    //typeofevent, eventid, eventname, evaluationid, status
    
    $qrData = explode("***", base64_decode($encodedData));
    //echo print_r($qrData);
    $typeOfEvent = $qrData[0];

    if($typeOfEvent == 'Exclusive Event'){ 
        
        $evaluationID = $qrData[1];
        $eventName = $qrData[2];
        $key = $qrData[3];
        
        $queryCheck = mysqli_query($connection, "SELECT * FROM eval_exclusiveevent_qr WHERE evaluation_id = '$evaluationID' AND keyScan = '$key'");
        if(mysqli_num_rows($queryCheck)>0){
            $checkEval = mysqli_query($connection,"SELECT * FROM evaluation_list WHERE evaluation_id='$evaluationID' AND status = 1");
            if(mysqli_num_rows($checkEval) > 0){
                $check = mysqli_num_rows($checkEval);
            }
            else{
                $check = 0;
            }
        }
        else{
            $check = 0;
        }
    }
    elseif($typeOfEvent == 'Open Event'){//open event
        $eventID = $qrData[1];
        $eventName = $qrData[2];
        $evaluationID = $qrData[3];
        $statusEval =  $qrData[4];
        $key =  $qrData[5];
        $role = $qrData[6];
        

        if($role == 'Visitor'){

            $accountID = $qrData[7];

            $getEmail = mysqli_query($connection, "SELECT email FROM accounts WHERE account_id = '$accountID'");
            $email = mysqli_fetch_array($getEmail);
            $visitorEmail = $email[0];
            //echo "<br>". $visitorEmail ."<br>";

            $getName = mysqli_query($connection, "SELECT firstName, lastName, suffixName FROM visitor_info WHERE account_id = '$accountID'");
            while($row = mysqli_fetch_array($getName)){
                $name = $row;
            }

            
            $firstName = $name['firstName'];
            $lastName = $name['lastName'];
            $suffixName = $name['suffixName'];
            
            /*
            echo $firstName."<br>";
            echo $lastName."<br>";
            echo $suffixName."<br>";
            */

        }
        elseif($role == 'Guest'){
            $firstName = $qrData[7];
            $lastName = $qrData[8];
            $suffixName = $qrData[9];
        }

        
        $queryCheck = mysqli_query($connection, "SELECT * FROM event_openqr WHERE event_id = '$eventID' AND keyScan = '$key'");
        if(mysqli_num_rows($queryCheck)>0){
            $checkEval = mysqli_query($connection,"SELECT * FROM evaluation_list WHERE evaluation_id='$evaluationID' AND status = 1");
            if(mysqli_num_rows($checkEval) > 0){
                $check = mysqli_num_rows($checkEval);
            }
            else{
                $check = 0;
            }
        }
        else{
            $check = 0;
        }
    }

                                $sqlGetQuestion = mysqli_query($connection, "SELECT * FROM evaluation_question WHERE evaluation_id = '$evaluationID'");
                                $numOfQuestions = mysqli_num_rows($sqlGetQuestion);
                                if($numOfQuestions > 0){
                                    $question = array();
                                    while($row = mysqli_fetch_array($sqlGetQuestion)){
                                        // add each row returned into an array
                                        $question[] = $row;
                                    }
                                    /*
                                    echo "<pre>";
                                    echo print_r($question);
                                    echo "</pre>";
                                    */
                                }

                                $sqlGetScale = mysqli_query($connection, "SELECT * FROM evaluation_scale ORDER BY scale DESC");
                                $numOfScale = mysqli_num_rows($sqlGetScale);
                                if($numOfScale > 0){
                                    $scale = array();
                                    while($row = mysqli_fetch_array($sqlGetScale)){
                                        // add each row returned into an array
                                        $scale[] = $row;
                                    }
                                    /*
                                    echo "<pre>";
                                    echo print_r($scale);
                                    echo "</pre>";
                                    */
                                    
                                }
//CHECK IF VALID PA YUNG QR
    if($check > 0){

    ?>

        <div class="container col-12 col-sm-8" >
            <div class="items">
                <div class="logo">
                    <img src="images/logo.png" id="logo" alt="Logo" />
                </div><br>
                <h2><?php echo $eventName;?><br> Evaluation Form</h2>
                <div class="row text-center mb-2">
                    <span class="noteEval">Please take a few minutes to provide feedback on your experience at the Event. Your feedback is valuable to us and will help us improve future events. Thank you for your participation!</span>
                </div>

                <form method="POST">
                    <input type="hidden" name="roleSubmit" value="<?php echo $role;?>">
                    <div class="row">
                        <div class="col">
                            <label for="email" class="form-label">Email Address:</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="<?php if($typeOfEvent == 'Open Event'){ if($role =='Visitor'){ echo $visitorEmail; } }?>" <?php if($typeOfEvent == 'Open Event'){ if($role == 'Visitor'){echo 'style="pointer-events: none"';} }?>  required>
                        </div>
                    </div>
                    <?php
                        if($typeOfEvent == 'Open Event'){
                    ?>      
                            <div class="row">
                                <div class="nameContainerGuestLabel">
                                    <div class="guestFNameLabel">
                                        <label for="guestFirstName">First Name:</label>
                                    </div>
                                    <div class="guestLNameLabel lgScreen">
                                        <label for="guestFirstName">Last Name:</label>     
                                    </div>
                                    <div class="guestNameSuffixLabel lgScreen">
                                        <label for="guestFirstName">Suffix:</label><br>
                                    </div>
                                </div>

                                <div class="nameContainerGuest">                        
                                    <input type="text" class="guestFirstName" id="guestFirstName"  name="guestFirstName" placeholder="First Name" value="<?php echo $firstName;?>" required>
                                    <label for="guestFirstName" class="smScreen">Last Name:</label> <!--label for phone-->    
                                    <input type="text" class="guestLastName" id="guestLastName"  name="guestLastName" placeholder="Last Name" value="<?php echo $lastName;?>" required>
                                    <label for="guestFirstName" class="smScreen">Suffix:</label><br><!--label for phone-->  
                                    <select class="guestSuffix" id="guestSuffix" name="guestSuffixName">
                                        <option value="" disabled selected>--suffix--</option>
                                        <option value="Jr" <?php if(isset($suffixName) && $suffixName == "Jr") {echo "selected";}?>>Jr.</option>
                                        <option value="II" <?php if(isset($suffixName) && $suffixName == "II") {echo "selected";}?>>II</option>
                                        <option value="III" <?php if(isset($suffixName) && $suffixName == "III") {echo "selected";}?>>III</option>
                                        <option value="Sr" <?php if(isset($suffixName) && $suffixName == "Sr") {echo "selected";}?>>Sr.</option>x
                                    </select><br>
                                </div> 
                            </div>
                    <?php
                        }
                    ?>
                    <?php
                        for($i=0, $num=1; $i < $numOfQuestions; $i++, $num++){
                    ?>
                            <div id="visitInfo" class="mb-2"> <!--Per Question-->
                                <div class="row ">
                                    <div class="col">
                                        <div class="row justify-content-start">
                                            <div class="col-auto">
                                                <p class="btn btn-dark"><?php echo $num;?>.</p>
                                            </div>

                                        </div>
                                        <div class="row justify-content-center">
                                            <div class="col-auto col-sm-auto mt-0 mb-4">
                                                <input type="hidden" name="questionID[<?php echo $i;?>]" value="<?php echo $question[$i]['questionID'];?>">
                                                <h5 class="text-center"><strong><?php echo $question[$i]['question']; ?></strong></h5>
                                            </div>
                                        </div>
                                        <div class="row justify-content-center">
                                            <?php
                                                for($s=0; $s < $numOfScale; $s++){
                                            ?>

                                                    <div class="col-12 col-sm-auto answersEval py-1">
                                                        <input type="radio" id="scale<?php echo $scale[$s]['scale'].$question[$i]['questionID'];?>" name="scale[<?php echo $question[$i]['questionID'];?>]" value="<?php echo $scale[$s]['scaleID'];?>" required>
                                                        <label for="scale<?php echo $scale[$s]['scale'].$question[$i]['questionID'];?>">
                                                            <?php echo $scale[$s]['description'];?>
                                                        </label>
                                                    </div>

                                            <?php
                                                }
                                            ?>
                                            
                    
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    ?>
                    <div id="visitInfo" class="">
                        <div class="row justify-content-center">
                            <div class="col-auto">
                                <label for="exampleFormControlTextarea1" class="form-label"><strong>Feedback</strong></label>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <textarea class="form-control" name="feedback" style="resize: none; text-align: center" id="exampleFormControlTextarea1" rows="3" required></textarea>
                            </div>
                        </div>
                    </div>

                    <div id="" class="row mt-2 justify-content-between" >
                        <?php
                            if($typeOfEvent == 'Open Event'){
                        ?>
                            <div class="col-auto">
                                <button type="submit" name="submitEvaluation" class="btn btn-success">Submit</button>
                            </div>
                        <?php
                            }
                            else{
                        ?>
                            <div class="col-auto">
                                <button type="submit" name="submitEvaluationExclusive" class="btn btn-success">Submit</button>
                            </div>
                        <?php
                            }
                        ?>
                        
                        <div class="col-auto">
                            <button type="reset" class="btn btn-danger">Clear</button>
                        </div>
                    </div>
                </form>

                

            </div>
        </div>   
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
                            <p class="text-center textErrorDesign">We apologize for any inconvenience, but it seems that the QR Code you've scanned is either invalid, done or has expired. 
                    Please inform the guard that the QR Code is invalid. Thanks!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
        </body>

    <?php
    }

    

    
    //if(mysqli_num_rows($checkKey) > 0){//valid resetpassword
    ?>


</body>
</html>


<?php

    date_default_timezone_set("Asia/Manila");
    $dateTake = date('Y-m-d h:i:s A', time());
                
    

    if(isset($_POST['submitEvaluation'])){
        //update the evaluateevent done 1 completed
        $roleSubmit = $_POST['roleSubmit'];
        $email = mysqli_real_escape_string($connection, $_POST['email']);
        $selected = $_POST['scale'];

        $sqlAnswerScale = "INSERT INTO evaluate_answer (questionID, scaleID, evaluation_id, email, dateTake) VALUES";
        foreach ($selected as $key => $value) {
            // Echo the key (field name) and its corresponding value
            $sqlAnswerScale = $sqlAnswerScale."('$key', '$value', '$evaluationID', '$email', '$dateTake'),";
            // echo "questionID: " . htmlspecialchars($key) . "<br>";
            // echo "scale: " . htmlspecialchars($value) . "<br>";
            // echo "<hr>"; // Add a horizontal line to separate fields
        }
        
        if(mysqli_query($connection,substr_replace($sqlAnswerScale,"", -1))){ //kaya may -1 para matanggal yung , sa dulo ng query

            $feedback = mysqli_real_escape_string($connection, $_POST['feedback']);

            $sqlAnswerFeedback = "INSERT INTO evaluate_feedback (feedback, evaluation_id, email, dateTake) VALUE ('$feedback', '$evaluationID', '$email', '$dateTake')";

            if(mysqli_query($connection, $sqlAnswerFeedback)){

            //UPDATE evaluateEVENT done=1; 

                if($roleSubmit == 'Visitor'){
                    echo 
                    "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Thank you for your feedback!',
                            text: 'Your feedback is important to us. We appreciate your time and participation.',
                            timer: 5000
                        }).then(function() {
                            window.location = '../vysmo/';
                        });
                    </script>";
                }
                elseif($roleSubmit == 'Guest'){
                    echo "<script>
                            Swal.fire({
                                icon: 'success',
                                title: 'Thank you for your feedback!',
                                text: 'Your feedback is important to us. We appreciate your time and participation.',
                                timer: 4000
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
        
    }
    elseif(isset($_POST['submitEvaluationExclusive'])){
        $email = mysqli_real_escape_string($connection, $_POST['email']);
        $selected = $_POST['scale'];

        $checkEmail = "";
        $sqlAnswerScale = "INSERT INTO evaluate_answer (questionID, scaleID, evaluation_id, email, dateTake) VALUES";
        foreach ($selected as $key => $value) {
            // Echo the key (field name) and its corresponding value
            $sqlAnswerScale = $sqlAnswerScale."('$key', '$value', '$evaluationID', '$email', '$dateTake'),";
            //echo "questionID: " . htmlspecialchars($key) . "<br>";
            //echo "scale: " . htmlspecialchars($value) . "<br>";
            //echo "<hr>"; // Add a horizontal line to separate fields
        }
        
        if(mysqli_query($connection,substr_replace($sqlAnswerScale,"", -1))){ //kaya may -1 para matanggal yung , sa dulo ng query

            $feedback = mysqli_real_escape_string($connection, $_POST['feedback']);

            $sqlAnswerFeedback = "INSERT INTO evaluate_feedback (feedback, evaluation_id, email, dateTake) VALUE ('$feedback', '$evaluationID', '$email', '$dateTake')";

            if(mysqli_query($connection, $sqlAnswerFeedback)){
                echo 
                    "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Thank you for your feedback!',
                            text: 'Your feedback is important to us. We appreciate your time and participation.',
                            timer: 5000
                        }).then(function() {
                            window.location = '../vysmo/';
                        });
                    </script>";
            }
                    

        }

    }
    
    
        
         

?>