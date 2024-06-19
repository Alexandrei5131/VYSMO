<?php

    include('../database.php');
    include('session.php');

    //kapag nakalogged in na then nagtry iredirect sa ibang pages na hindi naman nila role
    if($_SESSION['role']  == 'Guard'){
        echo "<script>window.location.href = 'scanAccountQR.php'</script>";
    }
    elseif($_SESSION['role']  == 'Visitor' || $_SESSION['role']  == 'Guest'){
        echo "<script>window.location.href = 'userProfile.php'</script>";
    }
    //end
    
    $role = $_SESSION['role'];
    date_default_timezone_set("Asia/Manila");

    $evaluationID = $_GET['evaluationID'];
    $eventName = $_GET['eventName'];
    $questionID = $_GET['questionID'];

    $sqlGetScale = mysqli_query($connection, "SELECT * FROM evaluation_scale");
    $numOfScale = mysqli_num_rows($sqlGetScale);
    if($numOfScale > 0){
        $scale = array();
        while($row = mysqli_fetch_array($sqlGetScale)){
            // add each row returned into an array
            $scale[] = $row;
        }/*
        echo "<pre>";
        echo print_r($scale);
        echo "</pre>";
        */
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

    
    <script src="js/nodevtool.js"></script>

    <!-- Favicon -->
    <link href="../images/vysmoprintlogo.png" rel="icon">

    <!-- SWEET ALERT -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet
    <link href="css/style.css" rel="stylesheet"> -->
    <link href="css/style2.php" rel="stylesheet">
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
                            <h3  id="head" class="text-primary">VYSMO</h3>
                        </a>
                       
                    </div>
                    
                </div>
                <div class="row ms-2 mb-2">

                        <div class="row pe-0 justify-content-center">
                            <?php
                                if($role == 'Admin'){
                            ?>
                                    <img src="../images/adminprofile.png" alt="" style="width: 120px; height: 90px;">
                            <?php
                                }
                                elseif($role == 'Guard'){
                            ?>
                                  <img class="rounded-circle" src="guard2x2/<?php echo $array['pic2x2']?>" alt="" style="width: 120px; height: 90px;">
                            <?php  
                                }
                            ?>
                            
                        </div>
                        <div class="row pe-0 mt-3">
                            <?php
                                if($role == 'Admin'){
                                    echo '<h5 class="mb-0 text-center">Admin</h5>';
                                }
                                elseif($role == 'Guard'){
                                    ?>
                                    
                                    <?php if(isset($array['suffixName'])){ 
                                        if($array['suffixName'] == 'Jr' or $array['suffixName'] == 'Sr'){
                                             $suffix = $array['suffixName'].'.'; 
                                        } 
                                        else{ 
                                            $suffix = $array['suffixName'];
                                        } 
                                        }
                                    ?>
                                    <h5 class="mb-0 text-center" id="lastName"><?php if($_SESSION['role'] == 'Guard'){ echo $array['lastName'];}?> </h5>
                                    <h6 class="mb-0 text-center" id="firstName"><?php if(isset($array['firstName'])){ echo $array['firstName'].' '.$array['middleName'].' '.$suffix; } ?></h6>
                                    <span class="text-center" style="color: #5A5A5A;"><?php echo $_SESSION['role']; ?></span>
                            <?php        
                                }
                            ?>
                            
                        </div>
                </div>
                <div class="navbar-nav w-100">
                <?php
                        if($_SESSION['role'] == 'Admin'){
                            echo '
                            <a href="dashboard.php" class="nav-item nav-link "><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                            <a href="addDestination.php" class="nav-item nav-link "><i class="bi bi-building"></i> Destination List</a>
                            <a href="addGuard.php" class="nav-item nav-link "><i class="bi bi-shield-lock"></i> Guard List</a>
                            <a href="addEvent.php" class="nav-item nav-link "><i class="bi bi-calendar-event"></i> Event List</a>
                            <a href="evaluationListAdmin.php" class="nav-item nav-link active"><i class="bi bi-card-checklist"></i> Evaluation List</a>
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


            <nav class="navbar navbar-light justify-content-center head1">
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <div class="position-relative">
                    <img class="rounded-circle guardLogo" src="../images/logo.png" alt="" >
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
            <div class="container-fluid bodyContent mt-3 col-11"  >
                    <div class="bg-light text-center rounded">
                        <nav class="navbar navbar-light justify-content-center visitInfo">
                            <span class="navbar-brand mb-0 text-white">Evaluation Responses</span>
                        </nav>
                        <div class="p-4">
                            <div class="row">
                                <div class="col-auto">
                                    <form action="results.php?">
                                        <input type="hidden" name="evaluationID" value="<?php echo $evaluationID;?>">
                                        <input type="hidden" name="eventName" value="<?php echo $eventName;?>">
                                        <button type="submit" class="btn btn-primary" >Back</button>
                                    </form>
                                    
                                </div>                                                       
                            </div>
                    
                            <div class="table-responsive" id="tableResponses" >
                            <table class="table text-start align-middle table-striped table-hover mb-0 shadow p-3 rounded">
                                <thead class="sticky-top text-center shadow p-3 mb-5 rounded" style="z-index: 2;">
                                    <tr class="text-dark">
                                        <th scope="col" class="col" >Scale</th>
                                        <th scope="col" class="col">Number of Responses</th>                                        
                                    </tr>
                                </thead>
                                
                                <tbody  id="tData" class="text-center">   
                                
                                <?php
                                    for($s=0; $s < $numOfScale; $s++){
                                        $sqlCountResponses = mysqli_query($connection, "SELECT COUNT('".$scale[$s]['scaleID']."') FROM evaluate_answer WHERE evaluation_id = '$evaluationID' AND questionID = '$questionID' AND scaleID = '".$scale[$s]['scaleID']."'");
                                        $numOfResponses = mysqli_fetch_array($sqlCountResponses);
                                        if ($numOfResponses) {
                                            $totalNum = $numOfResponses[0];
                                        } else {
                                            $totalNum = "N/A"; // or some default value if no result is found
                                        }

                                ?>

                                        <tr >
                                            <td class="col"><?php echo $scale[$s]['description'];?></td>
                                            <td class="col"><?php echo $totalNum;?></td>                                  
                                        </tr>

                                <?php
                                    }
                                ?>
                                                                                                                               
                                    <!--End of Modal-->
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </div>
            <!-- Recent Visits End -->


        </div>
        <!-- Content End -->


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

