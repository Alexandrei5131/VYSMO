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

    $showAcc =  mysqli_query($connection, "SELECT * FROM guard_info");//for fetching data
    $numOfGuard = mysqli_num_rows($showAcc);
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

    <!-- SWEET ALERT -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>

    <!-- Favicon -->
    <link href="../images/vysmoprintlogo.png" rel="icon">

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

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link type="text/css" href="css/style2.php?version=<?php echo time(); ?>" rel="stylesheet">
    <!-- WHEN OUTSIDE MODAL CLICKED -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        th,td{
            text-align:center;
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
                                <a href="addGuard.php" class="nav-item nav-link active"><i class="bi bi-shield-lock"></i> Guard List</a>
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



            <!-- Add Guard Start -->
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
            <div class="container-fluid bodyContent mt-3 col-11">
                <div class="bg-light text-center rounded p-4">
                    <div class="align-items-center justify-content-between mb-4">
                        <div class="row">
                            <div class="col">
                                <input type="search" class="form-control searchBtn" id="inlineFormInputName" placeholder="Search">
                            </div>
                            <div class="col-auto ">
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary " data-toggle="modal" data-target="#addGuard">
                                        Add Guard
                                    </button>
                            </div>

                                    
                                    <!-- Modal -->
                                            <div class="modal fade" id="addGuard" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header"><!--Modal Header-->
                                                            <h5 class="modal-title" id="exampleModalLongTitle">Guard Information</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">  <!-- Modal body -->
                                                            <form action="addGuardQuery.php" method="POST" enctype="multipart/form-data">
                                                                <div class="row align-items-start"><!-- Photo-->
                                                                    <div class="column">
                                                                        <div class="row justify-content-center">
                                                                            <img id="pic" class="rounded-circle" style="width:190px; height: 155px;" src="../images/userLogo.jpg" data-original="../images/userLogo.jpg" alt="sample" >
                                                                        </div>
                                                                        <div class="row justify-content-center mt-2">
                                                                            <div class="col">
                                                                                    <label id="custom-file-upload" class="btn btn-primary">
                                                                                        <input type="file"  name="pic2x2" id="pic2x2"  alt="Submit" onchange="pic2x2Preview()" hidden accept="image/jpg, image/jpeg, image/png" required >Upload
                                                                                    </label>
                                                                                    <button type="reset" id ="clear" class="btn btn-danger" onclick="resetPic2x2()">Clear</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div><br>

                                                                <div class="container text-center p-0" >
                                                                    <div class="row align-items-start">
                                                                        <div class="col">
                                                                            <label for="inputEmail4" class="form-label">Email</label>
                                                                            <input type="email" class="form-control" name ="email" id="inputEmail4" style="text-align: center;" required>
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <label class="form-label" for="inlineRadio1">Gender</label>
                                                                            <br>
                                                                            <div class="form-control" id="gender">
                                                                                <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="Male" required>
                                                                                <label class="form-check-label" for="inlineRadio1">Male</label>
                                                                            
                                                                        
                                                                                <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="Female" required>
                                                                                <label class="form-check-label" for="inlineRadio2">Female</label>
                                                                            </div>                                                                                                                                      
                                                                        </div>
                                                                    </div>
                                                                </div><br>
                                    
                                                                <div class="container text-center p-0" >
                                                                    <div class="row align-items-start">
                                                                        <div class="row">
                                                                            <div class="col-10">
                                                                                <label for="inputAddress2" class="form-label">Name</label>
                                                                            </div>
                                                                            <div class="col-2">
                                                                                <label for="inputAddress2" class="form-label">Suffix Name</label>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        
                                                                        <div class="col-auto">
                                                                            <input type="text" class="form-control" name="firstName" id="inputAddress2" placeholder="First Name" style="text-align: center;" required>
                                                                        </div>
                                                                        <div class="col-auto">
                                                                            <input type="text" class="form-control" name="middleName" id="inputAddress2" placeholder="Middle Name" style="text-align: center;">
                                                                        </div>
                                                                        <div class="col-auto">
                                                                            <input type="text" class="form-control"  name="lastName" id="inputAddress2" placeholder="Last Name" style="text-align: center;" required>
                                                                        </div>
                                                                        
                                                                        <div class="col-sm form-group mt-0">
                                                                        
                                                                            <select class="form-select" name="suffixName" aria-label="Default select example" style="text-align: center;">
                                                                                <option selected value=""></option>
                                                                                <option value="Jr">Jr.</option>
                                                                                <option value="II">II</option>
                                                                                <option value="III">III</option>
                                                                                <option value="Sr">Sr.</option>
                                                                            </select>
                                                                        </div>
                                    
                                                                    </div><br>
                                    
                                                                    <div class="container text-center p-0" >
                                                                        <div class="row align-items-start">
                                                                            <label for="inputAddress2" class="form-label">Address</label>
                                                                            <div class="col">
                                                                                <input type="text" class="form-control" id="inputAddress2"  name="houseNumber" placeholder="House Number" style="text-align: center;" required>
                                                                            </div>
                                                                            <div class="col">
                                                                                <input type="text" class="form-control" id="inputAddress2"  name="street" placeholder="Street" style="text-align: center;" required>
                                                                            </div>
                                                                            <div class="col">
                                                                                <input type="text" class="form-control" id="inputAddress2"  name="barangay" placeholder="Barangay" style="text-align: center;" required>
                                                                            </div>
                                                                            <div class="col">
                                                                                <input type="text" class="form-control" id="inputAddress2"  name="city" placeholder="City" style="text-align: center;" required>
                                                                            </div>
                                                                            <div class="col">
                                                                                <input type="text" class="form-control" id="inputAddress2"  name="province" placeholder="Province" style="text-align: center;" required>
                                                                            </div>
                                                                        </div>
                                                                    </div><br>
                                    
                                                                
                                                            
                                                                    </div><!-- Modal body -->
                                                                    <div class="modal-footer justify-content-center"><!-- Modal Footer -->
                                                                        <button type="submit" name="createGuardAccount" class="btn btn-primary">Create Guard Account</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>  
                                            </div>
                                    <!-- End of Modal -->



                            
                        </div>
                        
                        

                        <div class="row "><!--Guard List-->
                            <div class="col tableContent">
                                <div class="table-responsive" id="tableGuardList">
                                    <table class="table text-start align-middle table-bordered table-hover mb-0">
                                        <thead class="sticky-top " style="z-index: 2;">
                                            <tr class="text-dark bg-light">
                                                <th scope="col">Picture </th>
                                                <th scope="col">Email</th>      
                                                <th scope="col">Gender</th>
                                                <th scope="col">Guard Name</th>
                                                <th scope="col">Address</th>
                                                <!--<th scope="col">Status</th>-->
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tData">

                                            <?php 
                                                echo '<input type="hidden" id="numOfGuard" value="'.$numOfGuard.'">';

                                                $i=0;
                                                while($results = mysqli_fetch_array($showAcc)) 
                                                {
                                                    $accountID =  $results['account_id'];
                                                    $getEmail = mysqli_query($connection, "SELECT email FROM accounts WHERE account_id = '$accountID'");
                                                    $email = mysqli_fetch_array($getEmail);
                                            ?>
                                                
                                                <tr>
                                                    <td width="70"><img class="rounded-circle" src="guard2x2/<?php echo $results['pic2x2'] ?>" height="50" width="55"></td>
                                                    <td><?php echo $email[0]; ?></td>
                                                    <td><?php echo $results['gender']; ?></td>
                                                    <?php

                                                        if ($results['suffixName'] == "Jr" || $results['suffixName'] == "Sr") {
                                                            $suffixName =  $results['suffixName'] . '.';
                                                        } else {
                                                            $suffixName = $results['suffixName'];
                                                        }

                                                    ?>
                                                    <td class="col-3"><?php echo $results['firstName'] . ' ' . $results['middleName'] . ' ' . $results['lastName'] . ' ' . $suffixName; ?></td>
                                                    <td><?php echo '#' . $results['houseNumber'] . ' ' . $results['street'] . ', ' . $results['barangay'] . ', ' . $results['city'] . ', ' . $results['province'];?></td>
                                                    <!--<td><button class="btn btn-sm btn-danger" style="pointer-events: none" >Off Duty</button></td>-->
                                                    <td class="col-3">
                                                        <button class="btn btn-secondary lgScreen" data-toggle="modal" data-target="#editGuard<?php echo $i;?>">Edit</button>
                                                        <!--<button class="btn btn-danger lgScreen" data-toggle="modal" data-target="#deleteModal<?php //echo $i;?>">Delete</button>-->
                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newQRModal<?php echo $i;?>">New Qr Code</button>

                                                        <button class="btn btn-secondary smScreen" id="editEventButton'.$i.'" data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Edit" data-target="#editGuard<?php echo $i;?>"><i class="bi bi-pencil"></i></button>
                                                        <button class="btn btn-danger smScreen" data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Delete" data-target="#deleteModal<?php echo $i;?>"><i class="bi bi-trash"></i></button>

                                                    </td>
                                                </tr>

                                                <!--New QR Button-->
                                                <div class="modal fade" id="newQRModal<?php echo $i;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true"  data-backdrop="static" data-keyboard="false"> <!-- Modal for add Event-->
                                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-body">
                                                                <form action="addGuardQuery.php" method="POST">
                                                                    <input type="hidden" name="accountID" value="<?php echo $results['account_id'];?>">
                                                                    <p id="deleteBody"> <b>Note:</b> This will generate a new Account Qr Code for this Guard.<br> Do you still want to continue?</p>
                                                                    <button type="submit" class="btn btn-primary" name="newGuardQR">Yes</button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>  <!-- End of New QR Button-->

                                                <!--Delete Button-->
                                                <div class="modal fade" id="deleteModal<?php echo $i;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true"  data-backdrop="static" data-keyboard="false"> <!-- Modal for add Event-->
                                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-body">
                                                                <form action="addGuardQuery.php" method="POST">
                                                                    <input type="hidden" name="accountID" value="<?php echo $results['account_id'];?>">
                                                                    <p id="deleteBody"> Are you sure you want to Delete Guard?</p>
                                                                    <button type="submit" class="btn btn-warning" name="deleteGuard">Delete</button>
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>  <!-- End of Delete Button-->
                                                <!-- Modal -->
                                                    <div class="modal fade" id="editGuard<?php echo $i;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header"><!--Modal Header-->
                                                                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Guard Information</h5>
                                                                </div>
                                                                <div class="modal-body">  <!-- Modal body -->
                                                                    <?php 
                                                                        $accountID = $results['account_id'];
                                                                        $getEmailEdit = mysqli_query($connection, "SELECT email FROM accounts WHERE account_id = '$accountID'");
                                                                        $dbEmail = mysqli_fetch_array($getEmailEdit);

                                                                        echo '<input type="hidden" id="guardEmaildb'.$i.'" value="'.$dbEmail[0].'">';
                                                                        echo '<input type="hidden" id="firstNamedb'.$i.'" value="'.$results['firstName'].'">';
                                                                        echo '<input type="hidden" id="middleNamedb'.$i.'" value="'.$results['middleName'].'">';
                                                                        echo '<input type="hidden" id="genderdb'.$i.'" value="'.$results['gender'].'">';
                                                                        echo '<input type="hidden" id="lastNamedb'.$i.'" value="'.$results['lastName'].'">';
                                                                        echo '<input type="hidden" id="suffixNamedb'.$i.'" value="'.$results['suffixName'].'">';
                                                                        echo '<input type="hidden" id="houseNumberdb'.$i.'" value="'.$results['houseNumber'].'">';
                                                                        echo '<input type="hidden" id="streetdb'.$i.'" value="'.$results['street'].'">';
                                                                        echo '<input type="hidden" id="barangaydb'.$i.'" value="'.$results['barangay'].'">';
                                                                        echo '<input type="hidden" id="citydb'.$i.'" value="'.$results['city'].'">';
                                                                        echo '<input type="hidden" id="provincedb'.$i.'" value="'.$results['province'].'">';
                                                                    
                                                                    ?>
                                                                    <form action="addGuardQuery.php" method="POST" enctype="multipart/form-data">
                                                                        
                                                                        <?php 
                                                                            echo '<input type="hidden" name="accountID" value="'.$accountID.'">';
                                                                            echo '<input type="hidden" id="pic2x2db'.$i.'" name="pic2x2db" value="'.$results['pic2x2'].'">';
                                                                        ?>
                                                                        <div class="row align-items-start"><!-- Photo-->
                                                                            <div class="column">
                                                                                <div class="row justify-content-center">
                                                                                    <img id="picEdit<?php echo $i;?>" class="rounded-circle" style="width:190px; height: 155px;" src="guard2x2/<?php echo $results['pic2x2'];?>" data-original="guard2x2/<?php echo $results['pic2x2'];?>" alt="sample" >
                                                                                </div>
                                                                                <div class="row justify-content-center mt-2">
                                                                                    <div class="col">
                                                                                            <label id="custom-file-upload" class="btn btn-primary">
                                                                                                <input type="file"  name="pic2x2" id="pic2x2Edit<?php echo $i;?>"  alt="Submit" hidden accept="image/jpg, image/jpeg, image/png" >Upload
                                                                                            </label>
                                                                                            <button type="reset" id ="clearEdit<?php echo $i;?>" class="btn btn-danger">Clear</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div><br>

                                                                        <div class="container text-center p-0" >
                                                                            <div class="row align-items-start">
                                                                                <div class="col">
                                                                                    <label for="inputEmail4" class="form-label">Email</label>
                                                                                    <input type="email" class="form-control" name ="guardEmail" id="guardEmailModal<?php echo $i;?>" style="text-align: center; " value="<?php echo $dbEmail[0];?>" required>
                                                                                </div>
                                                                                <div class="col-4">
                                                                                    <label class="form-label" for="inlineRadio1">Gender</label>
                                                                                    <br>
                                                                                    <div class="form-control" id="gender">
                                                                                        <?php
                                                                                            if($results['gender'] == 'Male'){
                                                                                                ?>
                                                                                                    <input class="form-check-input" type="radio" name="gender" id="genderMale<?php echo $i;?>" value="Male" checked required>
                                                                                                    <label class="form-check-label" for="inlineRadio1">Male</label>
                                                                                                    <input class="form-check-input" type="radio" name="gender" id="genderFemale<?php echo $i;?>" value="Female" required>
                                                                                                    <label class="form-check-label" for="inlineRadio2">Female</label>
                                                                                                <?php
                                                                                            }
                                                                                            else{
                                                                                                ?>
                                                                                                    <input class="form-check-input" type="radio" name="gender" id="genderMale<?php echo $i;?>" value="Male" required>
                                                                                                    <label class="form-check-label" for="inlineRadio1">Male</label>
                                                                                                    <input class="form-check-input" type="radio" name="gender" id="genderFemale<?php echo $i;?>" value="Female" checked required>
                                                                                                    <label class="form-check-label" for="inlineRadio2">Female</label>
                                                                                                <?php
                                                                                            }
                                                                                        ?>
                                                                                        
                                                                                    </div>                                                                                                                                      
                                                                                </div>
                                                                            </div>
                                                                        </div><br>
                                                                        <div class="container text-center p-0" >
                                                                            <div class="row align-items-start">
                                                                                <div class="row">
                                                                                    <div class="col-10">
                                                                                        <label for="inputAddress2" class="form-label">Name</label>
                                                                                    </div>
                                                                                    <div class="col-2">
                                                                                        <label for="inputAddress2" class="form-label">Suffix Name</label>
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                
                                                                                <div class="col-auto">
                                                                                    <input type="text" class="form-control" name="firstName" id="firstNameModal<?php echo $i;?>" placeholder="First Name" style="text-align: center;" value="<?php echo $results['firstName'];?>" required>
                                                                                </div>
                                                                                <div class="col-auto">
                                                                                    <input type="text" class="form-control" name="middleName" id="middleNameModal<?php echo $i;?>" placeholder="Middle Name" style="text-align: center;" value="<?php echo $results['middleName'];?>">
                                                                                </div>
                                                                                <div class="col-auto">
                                                                                    <input type="text" class="form-control"  name="lastName" id="lastNameModal<?php echo $i;?>" placeholder="Last Name" style="text-align: center;" value="<?php echo $results['lastName'];?>" required>
                                                                                </div>
                                                                                
                                                                                <div class="col-sm form-group mt-0">
                                                                                
                                                                                    <select class="form-select" name="suffixName" id="suffixNameModal<?php echo $i;?>" aria-label="Default select example" style="text-align: center;">
                                                                                        <option value=""></option>
                                                                                        <option value="Jr" <?php if($results['suffixName'] == 'Jr'){echo 'selected';} ?>>Jr.</option>
                                                                                        <option value="II" <?php if($results['suffixName'] == 'II'){echo 'selected';} ?>>II</option>
                                                                                        <option value="III" <?php if($results['suffixName'] == 'III'){echo 'selected';} ?>>III</option>
                                                                                        <option value="Sr" <?php if($results['suffixName'] == 'Sr'){echo 'selected';} ?>>Sr.</option>
                                                                                    </select>
                                                                                </div>
                                            
                                                                            </div><br>
                                            
                                                                            <div class="container text-center p-0" >
                                                                                <div class="row align-items-start">
                                                                                    <label for="inputAddress2" class="form-label">Address</label>
                                                                                    <div class="col">
                                                                                        <input type="text" class="form-control" id="houseNumberModal<?php echo $i;?>"  name="houseNumber" placeholder="House Number" style="text-align: center;" value="<?php echo $results['houseNumber'];?>" required>
                                                                                    </div>
                                                                                    <div class="col">
                                                                                        <input type="text" class="form-control" id="streetModal<?php echo $i;?>"  name="street" placeholder="Street" style="text-align: center;" value="<?php echo $results['street'];?>" required>
                                                                                    </div>
                                                                                    <div class="col">
                                                                                        <input type="text" class="form-control" id="barangayModal<?php echo $i;?>"  name="barangay" placeholder="Barangay" style="text-align: center;" value="<?php echo $results['barangay'];?>" required>
                                                                                    </div>
                                                                                    <div class="col">
                                                                                        <input type="text" class="form-control" id="cityModal<?php echo $i;?>"  name="city" placeholder="City" style="text-align: center;" value="<?php echo $results['city'];?>" required>
                                                                                    </div>
                                                                                    <div class="col">
                                                                                        <input type="text" class="form-control" id="provinceModal<?php echo $i;?>"  name="province" placeholder="Province" style="text-align: center;" value="<?php echo $results['province'];?>" required>
                                                                                    </div>
                                                                                </div>
                                                                            </div><br>
                                                                        
                                                                    
                                                                            </div><!-- Modal body -->
                                                                            <div class="modal-footer justify-content-center"><!-- Modal Footer -->
                                                                                <button type="submit" name="editInformation" class="btn btn-primary">Edit Information</button>
                                                                                <button type="button" id="cancelButton<?php echo $i; ?>"  class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                                            </div>
                                                                    </form>
                                                            </div>
                                                        </div>
                                                    </div>  
                                            <!-- End of Modal -->
                                            <?php 
                                                    $i++;
                                                }
                                            ?>

                                                
                                            <script>

                                                var numOfGuard = document.getElementById('numOfGuard');
                                                //db data. para kapag nicancel nakaset pa rin sa default yung inputs
                                                
                                                //EDIT LIST
                                                for (var i = 0; i < numOfGuard.value; i++) {
                                                    (function (index) { //kaya may index foreach ng typeOfEvent0, 1, 2 etc maaaccess yung script.
                                                        var editCancelElement = "cancelButton" + index;


                                                        //DATABASE VALUES
                                                        var guardEmailElement = "guardEmaildb" + index;
                                                        var firstNameElement = "firstNamedb" + index;
                                                        var middleNameElement = "middleNamedb" + index;
                                                        var lastNameElement = "lastNamedb" + index;
                                                        var houseNumberElement = "houseNumberdb" + index;
                                                        var streetElement = "streetdb" + index;
                                                        var barangayElement = "barangaydb" + index;
                                                        var cityElement = "citydb" + index;
                                                        var provinceElement = "provincedb" + index;

                                                        var guardEmailModal = "guardEmailModal" + index;
                                                        var firstNameModal = "firstNameModal" + index;
                                                        var middleNameModal = "middleNameModal" + index;
                                                        var lastNameModal = "lastNameModal" + index;
                                                        var houseNumberModal = "houseNumberModal" + index;
                                                        var streetModal = "streetModal" + index;
                                                        var barangayModal = "barangayModal" + index;
                                                        var cityModal = "cityModal" + index;
                                                        var provinceModal = "provinceModal" + index;


                                                        
                                                        var suffixNameElement = "suffixNamedb" + index;
                                                        
                                                        var suffixNameModal = "suffixNameModal" + index;

                                                        var genderMaleElement = "genderMale" + index; // Add this line
                                                        var genderFemaleElement = "genderFemale" + index; // Add this line

                                                        var genderElement = "genderdb" + index; // Add this line
                                                        // KAPAG NAGCANCEL SA MODAL BACK TO RESET YUNG INPUT NA NANDON
                                                        document.getElementById(editCancelElement).addEventListener('click', function () {
                                                            //kaya ganto puro document.getelement, it wont work if ilalagay nlanag natin siya sa isang variable pero value magiiba. dahil nagloloop;
                                                            
                                                            //EMAIL
                                                            document.getElementById(guardEmailModal).value = document.getElementById(guardEmailElement).value;
                                                            document.getElementById(firstNameModal).value = document.getElementById(firstNameElement).value;
                                                            document.getElementById(middleNameModal).value = document.getElementById(middleNameElement).value;
                                                            document.getElementById(lastNameModal).value = document.getElementById(lastNameElement).value;

                                                            // Set the gender radio button based on the gender from the database
                                                            var genderValue = document.getElementById(genderElement).value;
                                                                if (genderValue === 'Male') {
                                                                    document.getElementById(genderMaleElement).checked = true;
                                                                    document.getElementById(genderFemaleElement).checked = false;
                                                                } else {
                                                                    document.getElementById(genderMaleElement).checked = false;
                                                                    document.getElementById(genderFemaleElement).checked = true;
                                                                }

                                                            document.getElementById(houseNumberModal).value = document.getElementById(houseNumberElement).value;
                                                            document.getElementById(streetModal).value = document.getElementById(streetElement).value;
                                                            document.getElementById(barangayModal).value = document.getElementById(barangayElement).value;
                                                            document.getElementById(cityModal).value = document.getElementById(cityElement).value;
                                                            document.getElementById(provinceModal).value = document.getElementById(provinceElement).value;

                                                            //typeofEvent
                                                            document.getElementById(suffixNameModal).innerHTML = '';


                                                            var suffixName = ['', 'Jr', 'II', 'III', 'Sr'];
                                                            var countsuffixName = suffixName.length;
                                                            var optionType = [];
                                                            var selectedSuffixNameDBValue = document.getElementById(suffixNameElement).value;

                                                            for(var a=0; a < countsuffixName; a++){
                                                                optionType[a] = "optionType" + a;
                                                                optionType[a]= document.createElement('option');
                                                                optionType[a].text = suffixName[a];
                                                                optionType[a].value = suffixName[a];

                                                                if(selectedSuffixNameDBValue === suffixName[a]){
                                                                    optionType[a].selected = true;
                                                                }
                                                                else{
                                                                    optionType[a].selected = false;
                                                                }
                                                                document.getElementById(suffixNameModal).appendChild(optionType[a]);
                                                            }

                                                        });

                                                        var pic2x2EditElement = "pic2x2Edit" + index;
                                                        var picEditElement = "picEdit" + index;
                                                        document.getElementById(pic2x2EditElement).addEventListener('change', function() {
                                                            var fileInput = document.getElementById(pic2x2EditElement);
                                                            var image = document.getElementById(picEditElement);
                                                            var file = fileInput.files[0];
                                                            var reader = new FileReader();
                                                        
                                                            reader.onload = function(e) {
                                                            image.src = e.target.result;
                                                            }
                                                        
                                                            reader.readAsDataURL(file);
                                                        });

                                                        var clearEditElement = "clearEdit" + index;
                                                        document.getElementById(clearEditElement).addEventListener('click', function() {
                                                            var image = document.getElementById(picEditElement);
                                                            var originalSrc = image.getAttribute('data-original');
                                                            image.src = originalSrc;
                                                            var fileInput = document.getElementById(pic2x2EditElement);
                                                            fileInput.value = '';
                                                        
                                                            event.preventDefault();
                                                        });

                                                    })(i);
                                                }


                                            </script>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><br><!--End of Guard List-->                     
                    </div>

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
