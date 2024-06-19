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

    if(isset($_POST['addDestination'])){

        $typeOfDestination = mysqli_real_escape_string($connection,$_POST['typeOfDestination']);
        $destination = mysqli_real_escape_string($connection,$_POST['destination']);
        $destinationName = mysqli_real_escape_string($connection,$_POST['destinationName']);
        $destinationLink = mysqli_real_escape_string($connection,$_POST['destinationLink']);

        $insertDestination = "INSERT INTO destination_list (typeOfDestination, destination, destinationName, destinationLink) VALUE ('$typeOfDestination', '$destination', '$destinationName', '$destinationLink')";
        if(mysqli_query($connection, $insertDestination)){

            echo 
                "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Destination Added Successfully',
                        timer: 3000
                    }).then(function() {
                        window.location = 'addDestination.php';
                    });
                </script>";

        }

    }
    elseif(isset($_POST['deleteDestination'])){
        $destinationID = mysqli_real_escape_string($connection,$_POST['destinationID']);

        $deleteDestination = "DELETE FROM destination_list WHERE destination_id = '$destinationID'";
        if(mysqli_query($connection, $deleteDestination)){
            echo 
                "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Destination Deleted Successfully',
                        timer: 3000
                    }).then(function() {
                        window.location = 'addDestination.php';
                    });
                </script>";
        }
    }
    elseif(isset($_POST['editDestination'])){
        $destinationID = $_POST['destinationID'];

        $typeOfDestination = mysqli_real_escape_string($connection,$_POST['typeOfDestination']);
        $destination = mysqli_real_escape_string($connection,$_POST['destination']);
        $destinationName = mysqli_real_escape_string($connection,$_POST['destinationName']);
        $destinationLink = mysqli_real_escape_string($connection,$_POST['destinationLink']);

        $updateDestination = "UPDATE destination_list SET typeOfDestination='$typeOfDestination', destination='$destination', destinationName='$destinationName', destinationLink='$destinationLink' WHERE destination_id = '$destinationID'";
        if(mysqli_query($connection, $updateDestination)){

            echo 
                "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Destination Updated Successfully',
                        timer: 3000
                    }).then(function() {
                        window.location = 'addDestination.php';
                    });
                </script>";

        }

    }
    elseif(isset($_POST['unavailableModal'])){

        $destinationID = $_POST['destinationID'];

        $updateDestination = "UPDATE destination_list SET status = 1 WHERE destination_id = '$destinationID'";
        if(mysqli_query($connection, $updateDestination)){

            echo 
                "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Destination is now unavailable',
                        timer: 3000
                    }).then(function() {
                        window.location = 'addDestination.php';
                    });
                </script>";

        }

    }

    elseif(isset($_POST['availableModal'])){

        $destinationID = $_POST['destinationID'];

        $updateDestination = "UPDATE destination_list SET status = 0 WHERE destination_id = '$destinationID'";
        if(mysqli_query($connection, $updateDestination)){

            echo 
                "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Destination is now available',
                        timer: 3000
                    }).then(function() {
                        window.location = 'addDestination.php';
                    });
                </script>";

        }

    }

?>