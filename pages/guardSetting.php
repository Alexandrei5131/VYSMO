<?php
    //GUARD

    include('../database.php');
    include('session.php');
    
    //kapag nakalogged in na then nagtry iredirect sa ibang pages na hindi naman nila role
    if($_SESSION['role'] == 'Admin'){
        echo "<script>window.location.href = 'dashboard.php'</script>";
    }
    elseif($_SESSION['role']  == 'Visitor' || $_SESSION['role']  == 'Guest'){
        echo "<script>window.location.href = 'userProfile.php'</script>";
    }

    $roleAcc = $_SESSION['role'];
    $accountID = $_SESSION['accountID'];

    //start sidebar infos
    $sqlGInfo = mysqli_query($connection, "SELECT firstName, middleName, lastName, suffixName, pic2x2 FROM guard_info WHERE account_id='{$accountID}'");
    if(mysqli_num_rows($sqlGInfo) > 0){
        $arrayGuard = array();
        // look through query
        while($row = mysqli_fetch_array($sqlGInfo)){
        // add each row returned into an array
            $arrayGuard = $row;
        }
        /*
        echo "<pre>";
        echo print_r($arrayGuard);
        echo "</pre>";
        */

    }

    $getQrName = mysqli_query($connection, "SELECT qrName FROM account_qr WHERE account_id = '$accountID'");
    $qrName = mysqli_fetch_array($getQrName);

//end sidebar infos

    date_default_timezone_set("Asia/Manila");
    $dateToday = date("Y-m-d");

    //GET LOGS
    $getLogs = mysqli_query($connection, "SELECT * FROM guard_logs WHERE account_id = '$accountID' ORDER BY log_id DESC");
    $numOfLogs = mysqli_num_rows($getLogs);
    if($numOfLogs > 0){
        while($row = mysqli_fetch_array($getLogs)){
        // add each row returned into an array
            $log_list[] = $row;
        }
        /*
        echo "<pre>";
        echo print_r($log_list);
        echo "</pre>";
        */
    }
    
    $checkQuickLogin = mysqli_query($connection, "SELECT quick_login FROM accounts WHERE account_id = '$accountID'");
    $quickLoginStatus = mysqli_fetch_array($checkQuickLogin);


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

    <!--camera-->
    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

    <!--SWEET ALERT 2-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>

    
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

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
            <div class="row text-center  w-100 p-0 m-0 justify-content-center">
                <div class="col">
                    <img src="../images/vyslogo.png" alt="" id="vysLogoUserProfile">
                </div>
            </div>
            <div class="navbar navbar-light ">
                <div class="row w-100 p-0 m-0 mb-3 justify-content-center">
                    <div class="mb-0 justify-content-center">
                        <div class="col-12">
                            <a class="navbar-brand mx-0 mb-3 ">
                                <h3  id="head" class="text-primary text-center">VYSMO</h3>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row w-100 p-0 m-0 justify-content-center">
                    <div class="mb-4">
                        <div class="col">
                        <div class="row pe-0 justify-content-center">
                            <?php
                                if($roleAcc == 'Admin'){
                            ?>
                                    <img src="../images/adminprofile.png" alt="" style="width: 120px; height: 90px;">
                            <?php
                                }
                                elseif($roleAcc == 'Guard'){
                            ?>
                                  <img class="rounded-circle" src="guard2x2/<?php echo $arrayGuard['pic2x2']?>" alt="" style="width: 120px; height: 90px;">
                            <?php  
                                }
                            ?>
                        </div>
                        <div class="row pe-0 mt-3 justify-content-center">
                                    
                            <?php if(isset($arrayGuard['suffixName'])){ 
                                    if($arrayGuard['suffixName'] == 'Jr' or $arrayGuard['suffixName'] == 'Sr'){
                                            $suffix = $arrayGuard['suffixName'].'.'; 
                                    } 
                                    else{ 
                                        $suffix = $arrayGuard['suffixName'];
                                    } 
                                }
                            ?>
                            <h5 class="mb-0 text-center" id="lastName"><?php echo $arrayGuard['lastName'];?> </h5>
                        </div>
                        <div class="row pe-0 text-center">
                                    
                            <?php if(isset($arrayGuard['suffixName'])){ 
                                    if($arrayGuard['suffixName'] == 'Jr' or $arrayGuard['suffixName'] == 'Sr'){
                                            $suffix = $arrayGuard['suffixName'].'.'; 
                                    } 
                                    else{ 
                                        $suffix = $arrayGuard['suffixName'];
                                    } 
                                }
                            ?>
                            <h6 class="mb-0 text-center" id="firstName"><?php if(isset($arrayGuard['firstName'])){ echo $arrayGuard['firstName'].' '.$arrayGuard['middleName'].' '.$suffix; } ?></h6>
                        </div>
                        <div class="row pe-0 text-center">
                                    
                            <?php if(isset($arrayGuard['suffixName'])){ 
                                    if($arrayGuard['suffixName'] == 'Jr' or $arrayGuard['suffixName'] == 'Sr'){
                                            $suffix = $arrayGuard['suffixName'].'.'; 
                                    } 
                                    else{ 
                                        $suffix = $arrayGuard['suffixName'];
                                    } 
                                }
                            ?>
                            <span class="text-center text-dark" style="color: #5A5A5A;"><?php echo $_SESSION['role']; ?></span>
                        </div>

                        </div>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                <?php
                    if($_SESSION['role'] == 'Guard'){
                        echo '
                            <a href="scanAccountQR.php" class="nav-item nav-link "><i class="bi bi-camera"></i> Scan Account QR</a>
                            <a href="kioskVisit.php" class="nav-item nav-link"><i class="bi bi-table"></i> Kiosk Visit</a>
                            <a href="guardSetting.php" class="nav-item nav-link active"><i class="bi bi-gear"></i> Guard Setting</a>
                        ';
                    }        
                ?>
                    <a href="logout.php" class="nav-item nav-link "><i class="bi bi-box-arrow-left"></i> Logout</a>
                </div>
                </div>
        </div>
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content" >


                <!-- Add Guard Start -->
                <nav class="navbar navbar-light justify-content-center mb-2 head1">
                    <a href="#" class="sidebar-toggler flex-shrink-0">
                        <i class="fa fa-bars"></i>
                    </a>
                    <div class="position-relative">
                            <img class="rounded-circle guardLogo" src="../images/logo.png" alt="" >
                        </div>
                    <span class="navbar-brand mb-0 h1">Nueva Ecija University of Science and Technology</span>
                   
                </nav>

                


                <div class="modal fade" id="activationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                   <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Activation of QR Code Log In</h1>
                            </div>
                            <div class="modal-body">
                                <h6 class="text-center">Are you sure you want to Activate account Qr code Log In</h6>
                            </div>
                            <form action="guardSettingQuery.php" method="POST">
                                <?php
                                    echo '<input type="hidden" name="accountID" value="'.$accountID.'">';
                                ?>
                                <div class=" row justify-content-center mb-3">
                                    <div class=" col-auto">
                                        <button type="submit" name="activateQR" class="btn btn-success" >Yes</button>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="deactivationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Deactivation of QR Code Log In</h1>
                            </div>
                            <div class="modal-body">
                                <h6 class="text-center">Are you sure you want to Deactivate account Qr code Log In</h6>
                            </div>
                            <form action="guardSettingQuery.php" method="POST">
                                <?php
                                    echo '<input type="hidden" name="accountID" value="'.$accountID.'">';
                                ?>
                                <div class="mb-3 row justify-content-center">
                                    <div class=" col-auto">
                                        <button type="submit" name="deactivateQR" class="btn btn-success" >Yes</button>
                                    </div>
                                    
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="showGuardAccountQR" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Guard Account Qr Code</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                            </div>
                            <div class="modal-body">
                                <div class="mb-3 row justify-content-center">
                                    <div class="col-auto">
                                        <img src="guardQR/<?php echo $qrName[0];?>" style="width: 300px;" alt="">
                                    </div>
                                </div>                           
                            </div>
                                
                        </div>
                    </div>
                </div>


                <div class="container-fluid pt-4 px-3 ">
                    <div class="bg-light rounded  itemContain">
                        <form action="" method="POST">
                            <div class="formContent">
                                    <nav class="navbar navbar-light justify-content-center mb-4 visitInfo">
                                        <span class="navbar-brand mb-0 h1 text-white">Logs</span>
                                    </nav>
                                    
                                    <div class="row justify-content-center m-0 p-3">
                                        <div class="col rounded tableApproveScan p-0">                                            
                                            <div class="table-responsive" id="tableGuardSetting" >
                                                <table class="table text-start align-middle table-bordered table-hover mb-0">
                                                <thead class="sticky-top bg-light text-center shadow p-3 mb-5 rounded" style="z-index: 2;">
                                                    <tr class="text-dark">
                                                        <th scope="col" class="col-1">#</th>
                                                        <th scope="col" class="col-auto">Date</th>
                                                        <th scope="col" class="col-auto">Activity</th>
                                                        <th scope="col" class="col-auto">Time</th>
                                                    </tr>
                                                </thead>
                                                
                                                <tbody  id="tData" class="text-center">
                                                    <?php 
                                                        for($i=0, $num =$numOfLogs; $i < $numOfLogs; $i++, $num--){
                                                    ?>
                                                            <tr>
                                                                <td><?php echo $num; ?></td>
                                                                <td><?php echo date("F j, Y", strtotime($log_list[$i]['date_logs'])); ?></td>
                                                                <td><i><?php echo $log_list[$i]['activity']; ?></i></td>
                                                                <td><?php echo $log_list[$i]['time_logs']; ?></td>
                                                            </tr>
                                                    <?php
                                                        }
                                                    ?>
                                                </tbody>
                                                </table>
                                            </div>                                  
                                        </div>
                                    </div>
      
                                    <div class="row mb-2 me-1 justify-content-end">
                                        <div class="col-auto d-flex justify-content-center" >
                                                <button type="button" name="timeIn" class="btn btn-secondary timeInAccount lgScreen" data-bs-toggle="modal" data-bs-target="#showGuardAccountQR"><i class="bi bi-qr-code"></i> Guard Account Qr</button>              
                                                <button type="button" name="timeIn" class="btn btn-secondary timeInAccount smScreen" data-bs-toggle="modal" data-bs-target="#showGuardAccountQR" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Guard Account Qr"><i class="bi bi-qr-code"></i></button>              

                                        </div>
                                        <?php
                                            if($quickLoginStatus[0] == 1){
                                                //activate
                                        ?>
                                            <div class="col-auto d-flex justify-content-center" >
                                                <button type="button" name="timeIn" class="btn btn-success timeInAccount lgScreen" data-bs-toggle="modal" data-bs-target="#activationModal"><i class="bi bi-power"></i> Activate QR Log In</button>              
                                                <button type="button" name="timeIn" class="btn btn-success timeInAccount smScreen" data-bs-toggle="modal" data-bs-target="#activationModal" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Activate QR Log In"><i class="bi bi-power"></i></button>              

                                            </div>
                                        <?php
                                            }
                                            elseif($quickLoginStatus[0] == 0){
                                                //deactivate
                                        ?>
                                            <div class="col-auto d-flex justify-content-center">
                                                <button type="button" name="timeIn" class="btn btn-danger timeInAccount lgScreen" data-bs-toggle="modal" data-bs-target="#deactivationModal"><i class="bi bi-power"></i> Deactivate  QR Log In</button>              
                                                <button type="button" name="timeIn" class="btn btn-danger timeInAccount smScreen" data-bs-toggle="modal" data-bs-target="#deactivationModal" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Deactivate QR Log In"><i class="bi bi-power"></i></button>              

                                            </div>
                                        <?php
                                            }
                                        ?>
                                        
                                        
                                    </div>  

                            </div>
                        </form>
                    </div>
                </div>

                <?php //PASSWORD SETTING
    
    $display = "display: none;";
    $displayCurr = "display: block;";
                    if(isset($_POST['btnCurrentPass'])){
                        
                        $currentPassword = $_POST['currentpassword'];
                        $salt = "45a4748f715f501377bf2c6de1b259b1"; //visitor monitoring system (md5)
                        $hashpassword = md5($salt.$currentPassword);

                        if (empty($currentPassword)){
                            echo 
                                "<script>
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'No Input',
                                        text: 'Enter current password to create new password!'
                                    })
                                </script>";
                        }
                        else{
                            //
                            $sqlCheckRole = mysqli_query($connection,"SELECT * FROM accounts WHERE account_id='{$accountID}' AND role = 'Guard'");
                            if(mysqli_num_rows($sqlCheckRole) > 0){
                                
                                $sqlCurrPass = mysqli_query($connection, "SELECT * FROM accounts WHERE account_id='{$accountID}' AND password = '{$hashpassword}'");

                                if(mysqli_num_rows($sqlCurrPass) > 0){
                                    $display = "display: block;";
                                    $displayCurr = "display: none";                                   }
                                else{
                                    echo
                                        "<script>
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Incorrect Password',
                                                text: 'Please input the correct password'
                                            });
                                        </script>";
                                }

                            }
                            else{
                                echo
                                    "<script>
                                        Swal.fire({
                                            icon: 'info',
                                            title: 'Update Information First',
                                            text: 'guest'
                                        });
                                    </script>";
                            }
                            
                        }

                    }

                ?>

                <div class="row m-1 justify-content-center"> 
                    <div class="col " style="<?php echo $displayCurr;?>">
                        <div class="container-fluid pt-4 m-0 p-0 currentPwd">
                            <div class="bg-light rounded itemContain">
                                <div>            
                                    <form method="POST" >
                                        <div class="formContent">
                                            <nav class="navbar navbar-light justify-content-center mb-4 visitInfo">
                                                <span class="navbar-brand mb-0 h1 text-white passSettingLabel">Password Setting</span>

                                            </nav>
                                            <div class="container-fluid mt-3 col-11">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="col pwrd text-center">
                                                            <label><strong>Current Password:</strong>  </label>
                                                        </div>
                                                        <div class="col mb-3 input-group">
                                                            <input type="password" class="form-control " id="currpass" name="currentpassword" minlength="8" required></input>
                                                            <span  class="btn btn-dark" id="btnEye" onclick="showHideCurrPass() "><i id="toggleCurrPass" class="bi bi-eye"></i></span>   
                                                        </div>
                                                            
                                                        <div class="col d-flex justify-content-center mb-3">
                                                            <button type="submit" class="btn btn-primary" name="btnCurrentPass">Enter</button>
                                                        </div>


                                                        <script>
                                                            function showHideCurrPass() {
                                                                var passwordInput = document.getElementById("currpass");
                                                                var iconEye = document.getElementById("toggleCurrPass");

                                                                if (passwordInput.type === "password") {
                                                                    passwordInput.type = "text";
                                                                    iconEye.classList.remove("bi-eye");
                                                                    iconEye.classList.add("bi-eye-slash");
                                                                } else {
                                                                    passwordInput.type = "password";
                                                                    iconEye.classList.remove("bi-eye-slash");
                                                                    iconEye.classList.add("bi-eye");
                                                                }
                                                            }
                                                        </script>
                                                            
                                                    </div>
                                                    
                                                </div>
                                                
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6" style="<?php echo $display;?>">
                        <div class="container-fluid pt-4 m-0 p-0 ">
                            <div class="bg-light rounded itemContain">
                                <div>            
                                    <form method="POST" action="guardSetting.php">
                                        <div class="formContent">
                                            <nav class="navbar navbar-light justify-content-center mb-4 visitInfo">
                                                <span class="navbar-brand mb-0 h1 text-white">Change Password</span>
                                            </nav>
                                            <div class="container-fluid mt-3 col-11">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="col pwrd text-center">
                                                            <label><strong>New Password:</strong>  </label>
                                                        </div>
                                                        <div class="col mb-3 input-group">
                                                            <input type="password" class="form-control" id="newpassword" placeholder="Enter New Password" oninput="checkPasswordMatch()" name="newpassword" minlength="8" required>
                                                            <span  class="btn btn-dark" id="btnEye" onclick="showHideNewPass()"><i id="toggleNewPass" class="bi bi-eye"></i></span>   
                                                        </div>
                                                        <div class="col pwrd text-center">
                                                            <label><strong>Confirm Password:</strong>  </label>
                                                        </div>
                                                        <div class="col mb-3 input-group">
                                                            <input type="password" class="form-control" id="confirmpassword" placeholder="Confirm New Password" oninput="checkPasswordMatch()" name="confirmpassword" minlength="8" required>
                                                            <span  class="btn btn-dark" id="btnEye" onclick="showHideConfirmNewPass()"><i id="toggleConfirmNewPass" class="bi bi-eye"></i></span>   
                                                        </div>
                                                        <p id="passwordMatchMessage" style="color:red; text-align:center; margin-top:-8px; padding-top:0;"></p>

                                                        <div class="col d-flex justify-content-center mb-3">
                                                            <button type="submit" class="btn btn-primary" name="changepassword">Change Password</button>
                                                        </div>

                                                        <script>
                                                            function showHideNewPass() {
                                                                var passwordInput = document.getElementById("newpassword");
                                                                var iconEye = document.getElementById("toggleNewPass");

                                                                if (passwordInput.type === "password") {
                                                                    passwordInput.type = "text";
                                                                    iconEye.classList.remove("bi-eye");
                                                                    iconEye.classList.add("bi-eye-slash");
                                                                } else {
                                                                    passwordInput.type = "password";
                                                                    iconEye.classList.remove("bi-eye-slash");
                                                                    iconEye.classList.add("bi-eye");
                                                                }
                                                            }

                                                            function showHideConfirmNewPass() {
                                                                var passwordInput = document.getElementById("confirmpassword");
                                                                var iconEye = document.getElementById("toggleConfirmNewPass");

                                                                if (passwordInput.type === "password") {
                                                                    passwordInput.type = "text";
                                                                    iconEye.classList.remove("bi-eye");
                                                                    iconEye.classList.add("bi-eye-slash");
                                                                } else {
                                                                    passwordInput.type = "password";
                                                                    iconEye.classList.remove("bi-eye-slash");
                                                                    iconEye.classList.add("bi-eye");
                                                                }
                                                            }
                                                        </script>
                                                        
                                                            
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <script>
                                            function checkPasswordMatch() {
                                                var password = document.getElementById("newpassword").value;
                                                var confirmPassword = document.getElementById("confirmpassword").value;
                                                var passwordMatchMessage = document.getElementById("passwordMatchMessage");

                                                if (password === confirmPassword) {
                                                    passwordMatchMessage.textContent = "";
                                                } else {
                                                    passwordMatchMessage.textContent = "Passwords do not match";
                                                }
                                            }
                                                
                                        </script>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Add Guard End -->


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



<?php

    if(isset($_POST['changepassword'])){

        $newpassword = mysqli_real_escape_string($connection,$_POST['newpassword']);
        $confirmpassword = mysqli_real_escape_string($connection,$_POST['confirmpassword']);

        $salt = "45a4748f715f501377bf2c6de1b259b1"; //visitor monitoring system (md5)
        $newhashpassword = md5($salt.$confirmpassword);

        if($newpassword == $confirmpassword){
            $sqlChangePass = "UPDATE accounts SET password = '{$newhashpassword}' WHERE account_id = '{$accountID}'";
            if(mysqli_query($connection, $sqlChangePass)){
                echo 
                "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Password Changed Successfully'
                    })
                </script>";
            }
        }
        else{
            echo 
              "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Password did not match',
                    text: 'Please try again!'
                })
              </script>";
        }

    }


?>