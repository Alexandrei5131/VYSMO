<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Favicon -->
    <link href="../images/vysmoprintlogo.png" rel="icon">
    <script src="js/nodevtool.js"></script>
    <!--SWEET ALERT 2-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
</head>
<body style="background-color: #cce6ff;">

</body>
</html>

<?php

    require('../database.php');
    if(isset($_POST['addQuestion'])){
        
        $evaluationID = $_POST['evaluationID'];
        $eventName = $_POST['eventName'];
        $question = $_POST['question'];

        $sqlAddQuestion = "INSERT INTO evaluation_question (question, evaluation_ID) VALUE ('$question', '$evaluationID')";
        if(mysqli_query($connection, $sqlAddQuestion)){

            echo 
                "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Question Added Successfully',
                        timer: 2000
                    }).then(function() {
                        window.location = 'evaluationQuestion.php?evaluationID=$evaluationID&eventName=$eventName';
                    });
                </script>";

        }

    }
    else if (isset($_POST['editQuestion'])){

        $questionID = $_POST['questionID'];
        $editQuestion = $_POST['question'];
        $evaluationID = $_POST['evaluationID'];
        $eventName = $_POST['eventName'];

        $sqlEditQuestion = "UPDATE evaluation_question SET question = '$editQuestion' WHERE questionID = '$questionID'";
        if(mysqli_query($connection, $sqlEditQuestion)){

            echo 
                "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Question Edited Successfully',
                        timer: 2000
                    }).then(function() {
                        window.location = 'evaluationQuestion.php?evaluationID=$evaluationID&eventName=$eventName';
                    });
                </script>";

        }

    }
    else if (isset($_POST['deleteQuestion'])){
        $questionID = $_POST['questionID'];
        $evaluationID = $_POST['evaluationID'];
        $eventName = $_POST['eventName'];

        $sqlDeleteQuestion = "DELETE FROM evaluation_question WHERE questionID = '$questionID'";
        if(mysqli_query($connection, $sqlDeleteQuestion)){

            echo 
                "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Edited Question Successfully',
                        timer: 2000
                    }).then(function() {
                        window.location = 'evaluationQuestion.php?evaluationID=$evaluationID&eventName=$eventName';
                    });
                </script>";

        }
    }

?>