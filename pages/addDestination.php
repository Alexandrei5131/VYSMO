<?php
include('../database.php');
include('session.php');

//kapag nakalogged in na then nagtry iredirect sa ibang pages na hindi naman nila role
if ($_SESSION['role']  == 'Guard') {
    echo "<script>window.location.href = 'scanAccountQR.php'</script>";
} elseif ($_SESSION['role']  == 'Visitor' || $_SESSION['role']  == 'Guest') {
    echo "<script>window.location.href = 'userProfile.php'</script>";
}
//end

$dateToday = date('Y-m-d');
$role = $_SESSION['role'];


$sqlAllDestination = mysqli_query($connection, "SELECT * FROM destination_list");
$numberOfDestination = mysqli_num_rows($sqlAllDestination);

if ($numberOfDestination > 0) {
    $destinationList = array();

    while ($row = mysqli_fetch_array($sqlAllDestination)) {
        $destinationList[] = $row;
    }
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>VYSMO</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <script src="js/nodevtool.js"></script>
    <!--SWEET ALERT 2-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>

    <!-- Favicon -->
    <link href="../images/vysmoprintlogo.png" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link type="text/css" href="css/style2.php" rel="stylesheet">

    <!-- WHEN OUTSIDE MODAL CLICKED -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        th,
        td {
            text-align: center;
        }

        #printAll {
            background-color: lightblue;
        }

        #printAll:hover {
            background-color: blue;
        }

        .printHead {
            color: black;
            font-weight: 800;
        }
    </style>

</head>

<body>
    <div class="position-relative">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sidebar Start -->
        <div class="sidebar pe-0 ">
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


                <div class=" justify-content-center">
                    <div class="col">
                        <a class="navbar-brand mx-0 ">
                            <h3 id="head" class="text-primary">VYSMO</h3>
                        </a>

                    </div>

                </div>
                <div class="row ms-2 mb-2">

                    <div class="row pe-0 justify-content-center">
                        <?php
                        if ($role == 'Admin') {
                        ?>
                            <img src="../images/adminprofile.png" alt="" style="width: 120px; height: 90px;">
                        <?php
                        } elseif ($role == 'Guard') {
                        ?>
                            <img class="rounded-circle" src="guard2x2/<?php echo $array['pic2x2'] ?>" alt="" style="width: 120px; height: 90px;">
                        <?php
                        }
                        ?>

                    </div>
                    <div class="row pe-0 mt-3">
                        <?php
                        if ($role == 'Admin') {
                            echo '<h5 class="mb-0 text-center">Admin</h5>';
                        } elseif ($role == 'Guard') {
                        ?>

                            <?php if (isset($array['suffixName'])) {
                                if ($array['suffixName'] == 'Jr' or $array['suffixName'] == 'Sr') {
                                    $suffix = $array['suffixName'] . '.';
                                } else {
                                    $suffix = $array['suffixName'];
                                }
                            }
                            ?>
                            <h5 class="mb-0 text-center" id="lastName"><?php if ($_SESSION['role'] == 'Guard') {
                                                                            echo $array['lastName'];
                                                                        } ?> </h5>
                            <h6 class="mb-0 text-center" id="firstName"><?php if (isset($array['firstName'])) {
                                                                            echo $array['firstName'] . ' ' . $array['middleName'] . ' ' . $suffix;
                                                                        } ?></h6>
                            <span class="text-center" style="color: #5A5A5A;"><?php echo $_SESSION['role']; ?></span>
                        <?php
                        }
                        ?>

                    </div>


                </div>
                <div class="navbar-nav w-100">
                    <?php
                    if ($_SESSION['role'] == 'Admin') {
                        echo '
                                <a href="dashboard.php" class="nav-item nav-link "><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                                <a href="addDestination.php" class="nav-item nav-link active"><i class="bi bi-buildings"></i> Destination List</a>
                                <a href="addGuard.php" class="nav-item nav-link "><i class="bi bi-shield-lock"></i> Guard List</a>
                                <a href="addEvent.php" class="nav-item nav-link "><i class="bi bi-calendar-event"></i> Event List</a>
                                <a href="evaluationListAdmin.php" class="nav-item nav-link "><i class="bi bi-card-checklist"></i> Evaluation List</a>
                                <a href="visitationHistory.php" class="nav-item nav-link "><i class="bi bi-clock-history"></i> Visitation History</a>
                                <a href="openEventHistory.php" class="nav-item nav-link "><i class="bi bi-clock-history"></i> Open Event History</a>
                            ';
                    }
                    ?>
                    <a href="logout.php" class="nav-item nav-link "><i class="bi bi-box-arrow-left"></i> Logout</a>
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content">



            <!-- Add Event Start -->
            <nav class="navbar navbar-light d-flex justify-content-center head1">
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <div class="position-relative">
                    <img class="rounded-circle guardLogo" src="../images/logo.png" alt="">

                </div>
                <span class="navbar-brand mb-0 h1">Nueva Ecija University of Science and Technology</span>
                <!--Color Changer-->
                <div id="theme-open" class="fas fa-bars"></div>

                <div class="themes-container">

                    <div id="theme-close" class="fas fa-times"></div>

                    <h3>Sidebar Color</h3>

                    <div class="theme-colors">
                        <div class="color colorDefault" style="background:rgb(65, 126, 128)">Default</div>
                        <div class="color" style="background:#007bff;"></div>

                        <div class="color" style="background:#2980b9"></div>
                        <div class="color" style="background:#27ae60;"></div>
                        <div class="color" style="background:#ffa502;"></div>
                        <div class="color" style="background:#8e44ad;"></div>
                        <div class="color" style="background:#0fb9b1;"></div>
                        <div class="color" style="background:#ffd32a;"></div>
                        <div class="color" style="background:#ff0033;"></div>
                        <div class="color" style="background:#e84393;"></div>
                    </div><br>
                    <h3>Header Color</h3>
                    <div class="theme-colorsBG">
                        <div class="colorBG colorDefault" style="background:rgb(65, 157, 158)">Default</div>
                        <div class="colorBG" style="background:#ff9900;"></div>

                        <div class="colorBG" style="background:#2980b9"></div>
                        <div class="colorBG" style="background:#27ae60;"></div>
                        <div class="colorBG" style="background:#ffa502;"></div>
                        <div class="colorBG" style="background:#8e44ad;"></div>
                        <div class="colorBG" style="background:#0fb9b1;"></div>
                        <div class="colorBG" style="background:#ffd32a;"></div>
                        <div class="colorBG" style="background:#ff0033;"></div>
                        <div class="colorBG" style="background:#e84393;"></div>
                    </div><br>
                    <h3>Sub-header Color</h3>
                    <div class="theme-colorsBG1">
                        <div class="colorBG1 colorDefault" style="background:rgb(65, 157, 158)">Default</div>
                        <div class="colorBG1" style="background:#6f42c1"></div>

                        <div class="colorBG1" style="background:#2980b9"></div>
                        <div class="colorBG1" style="background:#27ae60;"></div>
                        <div class="colorBG1" style="background:#ffa502;"></div>
                        <div class="colorBG1" style="background:#8e44ad;"></div>
                        <div class="colorBG1" style="background:#0fb9b1;"></div>
                        <div class="colorBG1" style="background:#ffd32a;"></div>
                        <div class="colorBG1" style="background:#ff0033;"></div>
                        <div class="colorBG1" style="background:#e84393;"></div>
                    </div>
                    <h3>Body Color</h3>
                    <div class="theme-colorsBG2">
                        <div class="colorBG2 colorDefault" style="background:#F3F6F9; color:black;">Default</div>
                        <div class="colorBG2" style="background:rgb(140, 140, 140)"></div>

                        <div class="colorBG2" style="background:#2980b9"></div>
                        <div class="colorBG2" style="background:#27ae60;"></div>
                        <div class="colorBG2" style="background:#ffa502;"></div>
                        <div class="colorBG2" style="background:#8e44ad;"></div>
                        <div class="colorBG2" style="background:#0fb9b1;"></div>
                        <div class="colorBG2" style="background:#ffd32a;"></div>
                        <div class="colorBG2" style="background:#ff0033;"></div>
                        <div class="colorBG2" style="background:#e84393;"></div>
                    </div>
                </div>
                <!--Color Changer-->


            </nav>
            <div class="container-fluid bodyContent mt-3 col-11">
                <div class="bg-light text-center rounded p-4">
                    <div class="align-items-center justify-content-between mb-4">
                        <div class="row">
                            <div class="col ">
                                <input type="search" class="form-control searchBtn" id="inlineFormInputName" placeholder="Search">
                            </div>
                            <div class="col-auto">
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEvent">
                                    Add Destination
                                </button> <br><br>
                            </div>
                        </div>

                        <!--Add destination List-->
                        <div class="modal fade" id="addEvent" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false"> <!-- Modal for add Event-->
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header"><!--Modal Header-->
                                        <h5 class="modal-title" id="exampleModalLongTitle">Destination Information</h5>
                                        <button type="button" id="xbutton" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form method="POST" action="addDestinationQuery.php">
                                        <div class="modal-body"> <!-- Modal body -->

                                            <div class="container text-center p-0">
                                                <div class="row justify-content-center">
                                                    <div class="col-12 col-sm-6">
                                                        <label for="typeOfDestination" class="form-label">Type of Destination:</label>
                                                        <select class="form-control text-center bg-white" id="typeOfDestination" name="typeOfDestination" required>
                                                            <option disabled selected></option>
                                                            <option value="Department">Department</option>
                                                            <option value="Transactional">Transactional</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row align-items-start">
                                                    <div class="col-12 col-sm-4">
                                                        <label for="destination" class="form-label">Destination:</label>
                                                        <input type="text" name="destination" class="form-control text-center" id="destination" required>
                                                    </div>
                                                    <div class="col-12 col-sm">
                                                        <label for="destinationName" class="form-label">Destination Name:</label>
                                                        <input type="text" name="destinationName" class="form-control text-center" id="destinationName" required>
                                                    </div>
                                                </div>
                                                <div class="row align-items-start">
                                                    <div class="col-12">
                                                        <label for="destinationLink" class="form-label">Destination Guide Link:</label>
                                                        <input type="text" name="destinationLink" class="form-control text-center" id="destinationLink" required>
                                                    </div>
                                                </div>
                                            </div><br>

                                        </div><!-- Modal body -->
                                        <div class="modal-footer justify-content-center"><!-- Modal Footer -->
                                            <button type="submit" name="addDestination" class="btn btn-primary">Add Destination</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div> <!--End of Add Destination List -->



                        <div class="row"><!--destination List-->
                            <div class="col">
                                <div class="table-responsive p-1" id="tableDestination">
                                    <table class="table text-start align-middle table-bordered table-hover mb-0">
                                        <thead>
                                            <tr class="text-dark">
                                                <th scope="col">Type of Destination</th>
                                                <th scope="col">Destination</th>
                                                <th scope="col">Destination Name</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Actions</th>
                                                <th scope="col">
                                                    Print
                                                    <!-- <button class="btn" id="printAll" data-toggle="modal"  data-toggle="tooltip" data-placement="top" title="Print All" data-target="#printButton<?php echo $i; ?>"><i class="bi bi-printer-fill"></i></button> -->
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="tData">
                                            <?php
                                            echo '<input type="hidden" id="numberOfDestination" value="' . $numberOfDestination . '">';
                                            for ($i = 0; $i < $numberOfDestination; $i++) {

                                            ?>
                                                <tr>
                                                    <td class="col-1"><?php echo  $destinationList[$i]['typeOfDestination']; ?></td>
                                                    <td class="col-auto"><?php echo  $destinationList[$i]['destination']; ?></td>
                                                    <td class="col-auto"><?php echo  $destinationList[$i]['destinationName']; ?></td>
                                                    <td class="col-auto">
                                                        <?php
                                                        if ($destinationList[$i]['status'] == 1) {
                                                        ?>
                                                            <span class="btn btn-warning">
                                                                Unavailable
                                                            </span>
                                                        <?php
                                                        } else if ($destinationList[$i]['status'] == 0) {
                                                        ?>
                                                            <span class="btn btn-success">
                                                                Available
                                                            </span>
                                                        <?php
                                                        } else {
                                                            echo "invalid visit";
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="col-auto">
                                                        <div class="row justify-content-center">
                                                            <div class="col-auto">
                                                                <button class="btn btn-secondary lgScreen" id="edit" data-toggle="modal" data-target="#editDestination<?php echo $i; ?>">Edit</button><!--For Large Screen-->
                                                                <button class="btn btn-secondary smScreen" id="edit" data-toggle="modal" data-target="#editDestination<?php echo $i; ?>" data-toggle="tooltip" data-placement="top" title="Edit" data-target="#editDestination"><i class="bi bi-pencil"></i></button><!--For Small Screen-->

                                                                <button class="btn btn-danger lgScreen" data-toggle="modal" data-target="#deleteDestination<?php echo $i; ?>" style="display:none">Delete</button><!--For Large Screen-->
                                                                <button class="btn btn-danger smScreen" data-toggle="modal" data-target="#deleteDestination<?php echo $i; ?>" style="display:none" data-toggle="tooltip" data-placement="top" title="Delete" data-target="#Delete"><i class="bi bi-trash"></i></button><!--For Samll Screen-->

                                                                <?php

                                                                if ($destinationList[$i]['status'] == 1) { //unavailable
                                                                ?>
                                                                    <button class="btn btn-success lgScreen" data-toggle="modal" data-target="#available<?php echo $i; ?>">Available</button>
                                                                    <button class="btn btn-success smScreen" data-toggle="modal" data-target="#available<?php echo $i; ?>" data-toggle="tooltip" data-placement="top" title="Available"><i class="bi bi-check-lg"></i></button>
                                                                <?php
                                                                } elseif ($destinationList[$i]['status'] == 0) { //available
                                                                ?>
                                                                    <button class="btn btn-warning lgScreen" data-toggle="modal" data-target="#unavailable<?php echo $i; ?>">Unavailable</button>
                                                                    <button class="btn btn-warning smScreen" data-toggle="modal" data-target="#unavailable<?php echo $i; ?>" data-toggle="tooltip" data-placement="top" title="Unavailable"><i class="bi bi-x-square"></i></button>
                                                                <?php
                                                                }

                                                                ?>

                                                            </div>

                                                        </div>
                                                    </td>
                                                    <td class="col-auto">
                                                        <button class="btn btn-primary" data-toggle="modal" id="print" data-toggle="tooltip" data-placement="top" title="print" data-target="#printButton<?php echo $i; ?>"><i class="bi bi-printer"></i></button>
                                                    </td>
                                                </tr>

                                                <!--Print Button-->
                                                <div class="modal fade" id="printButton<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false"> <!-- Modal for add Event-->
                                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-body justify-content-center">
                                                                <?php

                                                                $checkFirstTime = mysqli_query($connection, "SELECT * FROM destination_list WHERE bldgQR = '' AND destination_id = '" . $destinationList[$i]['destination_id'] . "'");
                                                                if (mysqli_num_rows($checkFirstTime) > 0) {
                                                                    $new = 'This will generate a ';
                                                                    $newlast = '';
                                                                } else {
                                                                    $new = '<b>Note:</b> This will generate a New ';
                                                                    $newlast = '<h6>The Last Generated QR Code will be invalid.</h6>';
                                                                }
                                                                ?>

                                                                <div class="row justify-content-center">
                                                                    <p id="deleteBody" class="text-center"><?php echo $new; ?> QR Code for the <b><?php echo $destinationList[$i]['destination']; ?> Building</b>.<br> Do you still want to continue?<br><?php echo $newlast; ?></p>
                                                                </div>

                                                                <div class="row justify-content-center">
                                                                    <div class="col-auto">
                                                                        <button type="submit" id="generateQRAndOpenWindow<?php echo $i; ?>" class="btn btn-warning" data-dismiss="modal">Yes</button>
                                                                        <?php
                                                                        echo '
                                                                                            <input type="hidden" id="destinationID' . $i . '" name="destinationID" value="' . $destinationList[$i]['destination_id'] . '">
                                                                                            <input type="hidden" id="destination' . $i . '" name="destination" value="' . $destinationList[$i]['destination'] . '">
                                                                                            <input type="hidden" id="destinationName' . $i . '" name="destinationName" value="' . $destinationList[$i]['destinationName'] . '">
                                                                                            ';
                                                                        ?>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <!--Unavailable Button-->
                                                <div class="modal fade" id="unavailable<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false"> <!-- Modal for add Event-->
                                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <form method="POST" action="addDestinationQuery.php">
                                                                <?php echo '<input type="hidden" id="destinationID' . $i . '" name="destinationID" value="' . $destinationList[$i]['destination_id'] . '">'; ?>
                                                                <div class="modal-body">
                                                                    <div class="row justify-content-center">
                                                                        <p id="deleteBody" class="text-center">Note: This will remove the destination in the list of choices.<br> Do you still want to continue?</p>
                                                                    </div>
                                                                    <button type="submit" name="unavailableModal" class="btn btn-secondary">Yes</button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End of Unavailable Button-->

                                                <!--Unavailable Button-->
                                                <div class="modal fade" id="available<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false"> <!-- Modal for add Event-->
                                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <form method="POST" action="addDestinationQuery.php">
                                                                <?php echo '<input type="hidden" id="destinationID' . $i . '" name="destinationID" value="' . $destinationList[$i]['destination_id'] . '">'; ?>
                                                                <div class="modal-body">
                                                                    <div class="row justify-content-center">
                                                                        <p id="deleteBody" class="text-center">Note: This will add the destination in the list of choices.<br> Do you still want to continue?</p>
                                                                    </div>
                                                                    <button type="submit" name="availableModal" class="btn btn-success">Yes</button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End of Unavailable Button-->

                                                <script>
                                                    var numberOfDestination = document.getElementById('numberOfDestination');

                                                    for (var a = 0; a < numberOfDestination.value; a++) {
                                                        (function(index) {
                                                            var generateQRAndOpenWindowElement = "generateQRAndOpenWindow" + index;
                                                            var destinationIDElement = "destinationID" + index;
                                                            var destinationElement = "destination" + index;
                                                            var destinationNameElement = "destinationName" + index;

                                                            var generateQRAndOpenWindow = document.getElementById(generateQRAndOpenWindowElement);
                                                            var destinationID = document.getElementById(destinationIDElement);
                                                            var destination = document.getElementById(destinationElement); // Corrected variable name
                                                            var destinationName = document.getElementById(destinationNameElement);

                                                            if (generateQRAndOpenWindow) {
                                                                generateQRAndOpenWindow.addEventListener('click', function() {
                                                                    var encdata = destinationID.value + '***' + destination.value + '***' + destinationName.value;
                                                                    var encodedData = btoa(encdata); // Use btoa to encode to Base64

                                                                    // Get the URL of the image that is already set in the main PHP file 
                                                                    const windowName = `qr_window_${Date.now()}`;
                                                                    // Open qr.php in a new window with the image URL as a query parameter
                                                                    const newWindow = window.open(`printdocs/printBuildingQR.php?encdata=${encodedData}`, windowName);
                                                                });
                                                            }
                                                        })(a);
                                                    }
                                                </script>


                                                <!-- End of Print Button-->
                                                <!--Delete Button-->
                                                <div class="modal fade" id="deleteDestination<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false"> <!-- Modal for add Event-->
                                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-body justify-content-center">
                                                                <form method="POST" action="addDestinationQuery.php">
                                                                    <?php echo '<input type="hidden" id="destinationID' . $i . '" name="destinationID" value="' . $destinationList[$i]['destination_id'] . '">'; ?>
                                                                    <div class="row justify-content-center">
                                                                        <p id="deleteBody" class="text-center"> Are you sure you want to Delete the destination?</p>
                                                                    </div>

                                                                    <div class="row justify-content-center">
                                                                        <div class="col-auto">
                                                                            <button type="submit" name="deleteDestination" class="btn btn-warning">Delete</button>
                                                                        </div>
                                                                        <div class="col-auto">
                                                                            <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End of Delete Button-->

                                                <!-- Start of Edit Destination-->
                                                <div class="modal fade" id="editDestination<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false"> <!-- Modal for Edit Destination-->
                                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header"><!--Modal Header-->
                                                                <h5 class="modal-title" id="exampleModalLongTitle">Edit Destination Information</h5>
                                                            </div>
                                                            <?php
                                                            echo '<input type="hidden" id="destinationdb' . $i . '" value="' . $destinationList[$i]['destination'] . '">';
                                                            echo '<input type="hidden" id="destinationNamedb' . $i . '" value="' . $destinationList[$i]['destinationName'] . '">';
                                                            echo '<input type="hidden" id="destinationLinkdb' . $i . '" value="' . $destinationList[$i]['destinationLink'] . '">';
                                                            ?>

                                                            <form method="POST" action="addDestinationQuery.php">
                                                                <?php
                                                                echo '<input type="hidden" id="destinationIDdb' . $i . '" name="destinationID" value="' . $destinationList[$i]['destination_id'] . '">';
                                                                ?>
                                                                <div class="modal-body"> <!-- Modal body -->
                                                                    <div class="container text-center p-0">
                                                                        <div class="row justify-content-center">
                                                                            <div class="col-12 col-sm-6">
                                                                                <label for="typeOfDestination" class="form-label">Type of Destination:</label>
                                                                                <select class="form-control text-center bg-white" id="typeOfDestination" name="typeOfDestination" required>
                                                                                    <option value="" disabled></option>
                                                                                    <option value="Department" <?php if ($destinationList[$i]['typeOfDestination'] == 'Department') {
                                                                                                                    echo 'selected';
                                                                                                                } ?>>Department Building</option>
                                                                                    <option value="Transactional" <?php if ($destinationList[$i]['typeOfDestination'] == 'Transactional') {
                                                                                                                        echo 'selected';
                                                                                                                    } ?>>Transactional Building</option>
                                                                                </select>
                                                                            </div>

                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-12 col-sm-4">
                                                                                <label for="destination" class="form-label">Destination:</label>
                                                                                <input type="text" name="destination" class="form-control text-center" value="<?php echo $destinationList[$i]['destination']; ?>" id="destinationModal<?php echo $i; ?>" required>
                                                                            </div>
                                                                            <div class="col-12 col-sm">
                                                                                <label for="destinationName" class="form-label">Destination Name:</label>
                                                                                <input type="text" name="destinationName" class="form-control text-center" value="<?php echo $destinationList[$i]['destinationName']; ?>" id="destinationNameModal<?php echo $i; ?>" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row align-items-start">
                                                                            <div class="col-12">
                                                                                <label for="destinationLink" class="form-label">Destination Guide Link:</label>
                                                                                <input type="text" name="destinationLink" class="form-control text-center" value="<?php echo $destinationList[$i]['destinationLink']; ?>" id="destinationLinkModal<?php echo $i; ?>" required>
                                                                            </div>
                                                                        </div>
                                                                    </div><br>


                                                                </div><!-- Modal body -->
                                                                <div class="modal-footer justify-content-center"><!-- Modal Footer -->
                                                                    <button type="submit" name="editDestination" class="btn btn-primary">Edit Destination</button>
                                                                    <button type="button" id="editCancel<?php echo $i; ?>" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End of Edit Destination-->
                                            <?php
                                            }
                                            ?>

                                            <script>
                                                var numOfDestination = document.getElementById('numberOfDestination').value;
                                                //db data. para kapag nicancel nakaset pa rin sa default yung inputs
                                                console.log(numOfDestination);

                                                //EDIT LIST
                                                for (var i = 0; i < numOfDestination; i++) {
                                                    (function(index) { //kaya may index foreach ng typeOfEvent0, 1, 2 etc maaaccess yung script.
                                                        var editCancelElement = "editCancel" + index;

                                                        //DATABASE VALUES
                                                        var destinationdbElement = "destinationdb" + index;
                                                        var destinationNamedbElement = "destinationNamedb" + index;

                                                        var destinationModal = "destinationModal" + index;
                                                        var destinationNameModal = "destinationNameModal" + index;

                                                        var destinationLinkModalElement = "destinationLinkModal" + index;
                                                        var destinationLinkdbElement = "destinationLinkdb" + index;

                                                        // KAPAG NAGCANCEL SA MODAL BACK TO RESET YUNG INPUT NA NANDON
                                                        document.getElementById(editCancelElement).addEventListener('click', function() {
                                                            document.getElementById(destinationModal).value = document.getElementById(destinationdbElement).value;
                                                            document.getElementById(destinationNameModal).value = document.getElementById(destinationNamedbElement).value;
                                                            document.getElementById(destinationLinkModalElement).value = document.getElementById(destinationLinkdbElement).value;

                                                        });

                                                    })(i);
                                                }
                                            </script>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><br><!--End of Destination List-->




                    </div>


                </div>
            </div> <!-- Add Event End -->
        </div><!-- Content End -->
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Javascript -->
    <script src="js/main.js"></script>



</body>

</html>

<?php



?>