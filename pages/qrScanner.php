<?php
    //VISITOR OR GUEST

    include('../database.php');
    include('session.php');

    //kapag nakalogged in na then nagtry iredirect sa ibang pages na hindi naman nila role
    if($_SESSION['role'] == 'Admin'){
        echo "<script>window.location.href = 'dashboard.php'</script>";
    }
    elseif($_SESSION['role']  == 'Guard'){
        echo "<script>window.location.href = 'scanAccountQR.php'</script>";
    }

    //include tas bukod na php
    $accountID = $_SESSION['accountID'];
    //$accountEmail = $_SESSION['email'];
    $role = $_SESSION['role'];
    
    include('getAccountInfo.php');
    
    
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>VYSMO</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    
    <script src="js/nodevtool.js"></script>

    <!-- Favicon -->
    <link href="../images/vysmoprintlogo.png" rel="icon">

    <!--SWEET ALERT 2-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>

    <!--camera-->
    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>


    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet
    <link href="css/style.css" rel="stylesheet"> -->
    <link href="css/style2.php" rel="stylesheet">

    <!-- WHEN OUTSIDE MODAL CLICKED -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="position-relative">
        <!-- Spinner Start -->
        
        <!-- Spinner End -->
        
        <!-- Sidebar Start -->
        <div class="sidebar pe-0 pb-3 ">
            <div class="col-sm d-flex justify-content-end mt-2 me-2">
                <button type="button" class="close1" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="row justify-content-center">
            <div class="col">
                    <img src="../images/vyslogo.png" alt="" id="vysLogo">
                </div>
            </div>
            <nav class="navbar navbar-light justify-content-center ">


                <div class="mb-4 justify-content-center">
                    <div class="col">
                        <a class="navbar-brand mx-0 mb-3 ">
                            <h3  id="head" class="text-primary">VYSMO</h3>
                        </a>
                       
                    </div>
                    
                </div>
                
                <div class="row w-100 p-0 m-0 justify-content-center">

                    <div class="mb-4">
                        <div class="col">
                            <div class="row pe-0 text-center">

                                <?php if(isset($array[0]['suffixName'])) { if($array[0]['suffixName'] == 'Jr' or $array[0]['suffixName'] == 'Sr'){ $suffix = $array[0]['suffixName'].'.'; } 
                                    else{ $suffix = $array[0]['suffixName'];} }?>
                                <h5 class="mb-0 text-center" id="lastName"><?php if(isset($array[0]['lastName'])){ echo $array[0]['lastName']; }?></h5>
                                
                            </div>
                            <div class="row pe-0 text-center">

                                <?php if(isset($array[0]['suffixName'])) { if($array[0]['suffixName'] == 'Jr' or $array[0]['suffixName'] == 'Sr'){ $suffix = $array[0]['suffixName'].'.'; } 
                                    else{ $suffix = $array[0]['suffixName'];} }?>
                                <h6 class="mb-0 text-center" id="firstName"><?php if(isset($array[0]['firstName'])){ echo $array[0]['firstName'].' '.$array[0]['middleInitial'].' '.$suffix; } ?></h6>
                            </div>
                            <div class="row pe-0 text-center">

                                <?php if(isset($array[0]['suffixName'])) { if($array[0]['suffixName'] == 'Jr' or $array[0]['suffixName'] == 'Sr'){ $suffix = $array[0]['suffixName'].'.'; } 
                                    else{ $suffix = $array[0]['suffixName'];} }?>
                                
                                <span class="text-center text-dark"><?php echo $_SESSION['role']; ?></span>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="userProfile.php" class="nav-item nav-link "><i class="bi bi-person-lines-fill"></i> My Profile</a>
                    <a href="qrScanner.php" class="nav-item nav-link active"><i class="bi bi-qr-code-scan"></i> Scanner</a>
                    <a href="visitationForm.php" id="visitationForm" class="nav-item nav-link "><i class="bi bi-people"></i> Visitation Form</a>
                    <a href="visitationList.php" class="nav-item nav-link"><i class="bi bi-clock-history"></i> Visitation List</a>
                    <a href="logout.php" class="nav-item nav-link "><i class="bi bi-box-arrow-left"></i> Logout</a>
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->

        <script>
            var userRole = '<?php echo $_SESSION['role']; ?>'; // Embed session variable in JavaScript
            
            // Add a click event listener to the <a> tag
            document.getElementById('visitationForm').addEventListener('click', function (e) {
                if (userRole == 'Guest') {
                    e.preventDefault(); // Prevent the default link behavior
                    // Display SweetAlert if the condition is not met
                    Swal.fire({
                        icon: 'warning',
                        title: 'Complete Visitor Information',
                        text: 'Insufficient authorization to access Visitation Form.'
                    });
                }
            });
        </script>


        <!-- Content Start -->
        <div class="content" id="sidebar">

                <!-- Add Guard Start -->
                <nav class="navbar navbar-light d-flex justify-content-center head1">
                        <a href="#" class="sidebar-toggler flex-shrink-0">
                                <i class="fa fa-bars"></i>
                        </a>
                        <div class="position-relative">
                                <img class="rounded-circle guardLogo" src="../images/logo.png" alt="" >
                        </div>
                        <span class="navbar-brand mb-0 h1">Nueva Ecija University of Science and Technology</span>
    
                </nav>
                <div class="container-fluid pt-4 px-4">
                    <div class="bg-light rounded itemContainQRScan">
                                    <nav class="navbar navbar-light justify-content-center visitInfo">
                                        <span class="navbar-brand mb-0 h1 text-white">Destination QR Code Scanner</span>
                                    </nav>
                                    <div class="p-4">
                                        <form action="qrScannerQuery.php" method="POST">
                                            <input type="hidden" id="ScannedBldgQR" name="ScannedBldgQR" value="<?php if(isset($_POST["ScannedBldgQR"])) { echo $ScannedBldgQR; }?>">
                                        </form>
                                        <div class="col">
                                            <div class="row justify-content-center mb-2">
                                                <div class="col-auto text-center">
                                                    <strong> Please use this scanner to scan the designated QR Code displayed on your destination(s)</strong>
                                                </div>
                                            </div>
                                            <div class="row justify-content-center">
                                                <div class="col-auto">
                                                <video id="qrScanner" class="p-0">  </video>

                                                </div>
                                            </div>
                                            
                                        </div>                                                                
                                    </div>
                        <!-- Modal for "Do you have an account?" -->
                    </div>
                </div>           
                <!-- Add Guard End -->
        </div>
        <!-- Content End -->
    </div>
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Javascript -->
    <script src="js/main.js"></script>
</body>

</html>

<script>
    let scanner = new Instascan.Scanner({ video: document.getElementById('qrScanner') });

    // Get the list of available cameras
    Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
            // Find the first available back-facing camera
            if (cameras[1]) {
                scanner.start(cameras[1]);
            } else {
                // If no back camera is found, find the first front camera and start with it
                scanner.start(cameras[0]);
            }
        } else {
            alert('No cameras found');
        }
    }).catch(function (e) {
        console.error(e);
    });

    scanner.addListener('scan', function (qrContents) {
        let encryptedQR = document.getElementById('ScannedBldgQR').value = qrContents;
        document.forms[0].submit();
    });
</script>




