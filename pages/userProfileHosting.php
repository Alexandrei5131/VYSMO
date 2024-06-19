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

    //include tas bukod na php\
    $accountID = $_SESSION['accountID'];
    //$accountEmail = $_SESSION['email'];
    $accountQR = $_SESSION['accountQR'];
    $role = $_SESSION['role'];
    
    $queryVInfo = "SELECT firstName, middleInitial, lastName, suffixName, gender, mobileNumber, houseNumber, street, barangay, city, province, nationality, typeOfID, selfieWithID, frontID, backID FROM visitor_info WHERE account_id='{$accountID}'";
    $sqlVInfo = mysqli_query($connection, $queryVInfo);
    if(mysqli_num_rows($sqlVInfo) > 0){

        $array = array();

        while($row = mysqli_fetch_array($sqlVInfo)){
            $array[] = $row;
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
    <!-- Favicon -->
    <link href="../images/vysmoprintlogo.png" rel="icon">
    
    <script src="js/nodevtool.js"></script>

    <!--SWEET ALERT 2-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">
    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel=”stylesheet” href=”css/bootstrap-responsive.css”>

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    
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
                $sqlCheckRole = mysqli_query($connection,"SELECT * FROM accounts WHERE account_id='{$accountID}' AND role = 'Visitor'");
                if(mysqli_num_rows($sqlCheckRole) > 0){
                    
                    $sqlCurrPass = mysqli_query($connection, "SELECT * FROM accounts WHERE account_id='{$accountID}' AND password = '{$hashpassword}'");

                    if(mysqli_num_rows($sqlCurrPass) > 0){
                        $display = "display: block;";
                        $displayCurr = "display: none";
                    }
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
                                icon: 'warning',
                                title: 'Insufficient authorization',
                                text: 'Please update your information first before changing your password'
                            });
                        </script>";
                        
                }
                
            }

        }

    ?>
    <div class="position-relative">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Sidebar Start -->
        <div class="sidebar pe-0 pb-3 ">
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
                    <a href="userProfile.php" class="nav-item nav-link active"><i class="bi bi-person-lines-fill"></i> My Profile</a>
                    <a href="qrScanner.php" class="nav-item nav-link "><i class="bi bi-qr-code-scan"></i> Scanner</a>
                    <a href="visitationForm.php" id="visitationForm" class="nav-item nav-link "><i class="bi bi-people"></i> Visitation Form</a>
                    <a href="visitationList.php" class="nav-item nav-link"><i class="bi bi-clock-history"></i> Visitation List</a>
                    <a href="logout.php" class="nav-item nav-link "><i class="bi bi-box-arrow-left"></i> Logout</a>
                </div>
            </div>
        </div>

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
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content mb-4 bg-light" id="sidebar">
                <!-- Add Visit Info Start -->
                <nav class="navbar navbar-light d-flex justify-content-center bg-light head1">
                            <a href="#" class="sidebar-toggler flex-shrink-0">
                                <i class="fa fa-bars"></i>
                            </a>
                        <div class="position-relative">
                                <img class="rounded-circle guardLogo" src="../images/logo.png" alt="" >
                                
                        </div>
                        <span class="navbar-brand mb-0 h1">Nueva Ecija University of Science and Technology</span>
                    
                    
                   
                </nav>
                
                <div class="container-fluid pt-4 px-4">
                    <div class="bg-light rounded itemContain">
                        <div>            
                            <form action="updateInformation.php" method="POST" enctype="multipart/form-data" <?php if($_SESSION['role'] == 'Guest'){ echo 'onsubmit="return validateSubmit();"'; } ?>>
                                <!--id file name from database-->
                                <input type="hidden" name="dbFrontID" value="<?php if(isset($array[0]['frontID'])){ echo $array[0]['frontID']; } ?>">
                                <input type="hidden" name="dbBackID" value="<?php if(isset($array[0]['backID'])){ echo $array[0]['backID']; } ?>">
                                <input type="hidden" name="dbSelfieWithID" value="<?php if(isset($array[0]['selfieWithID'])){ echo $array[0]['selfieWithID']; } ?>">

                                <div class="formContent">
                                    <nav class="navbar navbar-light bg-light justify-content-center mb-4 visitInfo">
                                        <span class="navbar-brand mb-0 h1 text-white">Visitor Information</span>
                                    </nav>
                                        <div class="pt-4 pb-0">                                        
                                            <div class="row justify-content-center px-4">
                                                <div class="col-auto col-sm-2 fNameForm" id="">
                                                    <div class="Name  text-center">
                                                        <label><strong>First Name:</strong>  </label>
                                                    </div>
                                                    <div class="column">
                                                        <input type="text" class="form-control text-center" id="fName" name="firstName" value = "<?php if(isset($array[0]['firstName'])){ echo $array[0]['firstName']; } ?>" required>
                                                    </div>
                                                    
                                                </div>
                                                <div class="col-auto col-sm-2 mNameForm" id="">
                                                    <div class="Name text-center mNameLbl">
                                                        <label class=""><strong>Middle Initial:</strong>  </label>
                                                    </div>
                                                    <div class="column ">
                                                        <input type="text" class="form-control text-center" id="mName" maxlength="4" name="middleInitial" value = "<?php if(isset($array[0]['middleInitial'])){ echo $array[0]['middleInitial']; } ?>">
                                                    </div>
                                                    
                                                </div>
                                                <div class="col-auto col-sm-2 lNameForm" id="">
                                                    <div class="Name text-center">
                                                        <label><strong>Last Name:</strong>  </label>
                                                    </div>
                                                    <div class="column">
                                                        <input type="text" class="form-control text-center" id="lName" name="lastName" value = "<?php if(isset($array[0]['lastName'])){ echo $array[0]['lastName']; } ?>" required>
                                                    </div>
                                                    
                                                </div>
                                                <div class="col-auto col-sm-2 sNameForm">
                                                    <div class="Suffix text-center">
                                                        <label><strong>Suffix Name:</strong>  </label>
                                                    </div>
                                                    <div>
                                                        <select name="suffixName" class="form-control bg-white text-center" >
                                                            <option value="" selected></option>
                                                            <option value="Jr"  <?php if(isset($array[0]['suffixName'])){ if($array[0]['suffixName'] == 'Jr'){ echo "selected";} } ?>>Jr.</option>
                                                            <option value="II"  <?php if(isset($array[0]['suffixName'])){ if($array[0]['suffixName'] == 'II'){ echo "selected";} } ?>>II</option>
                                                            <option value="III"  <?php if(isset($array[0]['suffixName'])){ if($array[0]['suffixName'] == 'III'){ echo "selected";} } ?>>III</option>
                                                            <option value="Sr"  <?php if(isset($array[0]['suffixName'])){ if($array[0]['suffixName'] == 'Sr'){ echo "selected";} } ?>>Sr.</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-auto col-sm-2 genderForm">
                                                    <div id="genderLabel" class="text-center">
                                                        <label><strong>Sex:</strong></label>
                                                    </div>
                                                    <div class="column">
                                                        <fieldset class="form-control" id="gender" style=" text-align: center;" required>
                                                            <input type="radio" name="gender" value="Male" <?php if(isset($array[0]['gender'])){ if($array[0]['gender'] == 'Male'){ echo "checked";} } ?> >
                                                            <label for="male">Male</label>
                                                            <input type="radio" name="gender" value="Female" <?php if(isset($array[0]['gender'])){ if($array[0]['gender'] == 'Female'){ echo "checked";} } ?> >
                                                            <label for="female">Female</label>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                                <div class="col-auto col-sm-2 numForm">
                                                    <div class="num text-center">
                                                        <label class="num"><strong>Mobile Number:</strong></label>
                                                    </div>
                                                    <div class="column">
                                                        <input type="tel" class="form-control text-center" id="num" name="mobileNumber" placeholder="09123456789" required value="<?php if(isset($array[0]['mobileNumber'])){ echo $array[0]['mobileNumber']; } ?>" pattern="[0-9]{11}"  >
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        
                                    

                                            <div class="row justify-content-center px-4">                                            
                                                <div class="col-auto col-sm-2 nationalityForm">
                                                    <div class="form-group text-center">
                                                        <label for="nationality"><strong>Nationality</strong></label>
                                                        <select class="form-control nationality text-center" name="nationality" required>
                                                            <option value="" disabled selected></option>
                                                            <option value="Afghan" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Afghan') echo "selected"; ?>>Afghan</option>
                                                            <option value="Albanian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Albanian') echo "selected"; ?>>Albanian</option>
                                                            <option value="Algerian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Algerian') echo "selected"; ?>>Algerian</option>
                                                            <option value="American" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'American') echo "selected"; ?>>American</option>
                                                            <option value="Andorran" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Andorran') echo "selected"; ?>>Andorran</option>
                                                            <option value="Angolan" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Angolan') echo "selected"; ?>>Angolan</option>
                                                            <option value="Antiguans" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Antiguans') echo "selected"; ?>>Antiguans</option>
                                                            <option value="Argentinean" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Argentinean') echo "selected"; ?>>Argentinean</option>
                                                            <option value="Armenian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Armenian') echo "selected"; ?>>Armenian</option>
                                                            <option value="Australian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Australian') echo "selected"; ?>>Australian</option>
                                                            <option value="Austrian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Austrian') echo "selected"; ?>>Austrian</option>
                                                            <option value="Azerbaijani" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Azerbaijani') echo "selected"; ?>>Azerbaijani</option>
                                                            <option value="Bahamian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Bahamian') echo "selected"; ?>>Bahamian</option>
                                                            <option value="Bahraini" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Bahraini') echo "selected"; ?>>Bahraini</option>
                                                            <option value="Bangladeshi" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Bangladeshi') echo "selected"; ?>>Bangladeshi</option>
                                                            <option value="Barbadian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Barbadian') echo "selected"; ?>>Barbadian</option>
                                                            <option value="Barbudans" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Barbudans') echo "selected"; ?>>Barbudans</option>
                                                            <option value="Batswana" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Batswana') echo "selected"; ?>>Batswana</option>
                                                            <option value="Belarusian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Belarusian') echo "selected"; ?>>Belarusian</option>
                                                            <option value="Belgian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Belgian') echo "selected"; ?>>Belgian</option>
                                                            <option value="Belizean" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Belizean') echo "selected"; ?>>Belizean</option>
                                                            <option value="Beninese" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Beninese') echo "selected"; ?>>Beninese</option>
                                                            <option value="Bhutanese" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Bhutanese') echo "selected"; ?>>Bhutanese</option>
                                                            <option value="Bolivian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Bolivian') echo "selected"; ?>>Bolivian</option>
                                                            <option value="Bosnian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Bosnian') echo "selected"; ?>>Bosnian</option>
                                                            <option value="Brazilian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Brazilian') echo "selected"; ?>>Brazilian</option>
                                                            <option value="British" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'British') echo "selected"; ?>>British</option>
                                                            <option value="Bruneian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Bruneian') echo "selected"; ?>>Bruneian</option>
                                                            <option value="Bulgarian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Bulgarian') echo "selected"; ?>>Bulgarian</option>
                                                            <option value="Burkinabe" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Burkinabe') echo "selected"; ?>>Burkinabe</option>
                                                            <option value="Burmese" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Burmese') echo "selected"; ?>>Burmese</option>
                                                            <option value="Burundian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Burundian') echo "selected"; ?>>Burundian</option>
                                                            <option value="Cambodian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Cambodian') echo "selected"; ?>>Cambodian</option>
                                                            <option value="Cameroonian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Cameroonian') echo "selected"; ?>>Cameroonian</option>
                                                            <option value="Canadian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Canadian') echo "selected"; ?>>Canadian</option>
                                                            <option value="Cape verdean" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Cape verdean') echo "selected"; ?>>Cape Verdean</option>
                                                            <option value="Central african" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Central african') echo "selected"; ?>>Central African</option>
                                                            <option value="Chadian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Chadian') echo "selected"; ?>>Chadian</option>
                                                            <option value="Chilean" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Chilean') echo "selected"; ?>>Chilean</option>
                                                            <option value="Chinese" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Chinese') echo "selected"; ?>>Chinese</option>
                                                            <option value="Colombian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Colombian') echo "selected"; ?>>Colombian</option>
                                                            <option value="Comoran" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Comoran') echo "selected"; ?>>Comoran</option>
                                                            <option value="Congolese" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Congolese') echo "selected"; ?>>Congolese</option>
                                                            <option value="Costa rican" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Costa rican') echo "selected"; ?>>Costa Rican</option>
                                                            <option value="Croatian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Croatian') echo "selected"; ?>>Croatian</option>
                                                            <option value="Cuban" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Cuban') echo "selected"; ?>>Cuban</option>
                                                            <option value="Cypriot" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Cypriot') echo "selected"; ?>>Cypriot</option>
                                                            <option value="Czech" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Czech') echo "selected"; ?>>Czech</option>
                                                            <option value="Danish" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Danish') echo "selected"; ?>>Danish</option>
                                                            <option value="Djiboutian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Djiboutian') echo "selected"; ?>>Djiboutian</option>
                                                            <option value="Dominican" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Dominican') echo "selected"; ?>>Dominican</option>
                                                            <option value="Dutch" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Dutch') echo "selected"; ?>>Dutch</option>
                                                            <option value="East timorese" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'East timorese') echo "selected"; ?>>East Timorese</option>
                                                            <option value="Ecuadorean" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Ecuadorean') echo "selected"; ?>>Ecuadorean</option>
                                                            <option value="Egyptian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Egyptian') echo "selected"; ?>>Egyptian</option>
                                                            <option value="Emirian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Emirian') echo "selected"; ?>>Emirian</option>
                                                            <option value="Equatorial guinean" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Equatorial guinean') echo "selected"; ?>>Equatorial Guinean</option>
                                                            <option value="Eritrean" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Eritrean') echo "selected"; ?>>Eritrean</option>
                                                            <option value="Estonian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Estonian') echo "selected"; ?>>Estonian</option>
                                                            <option value="Ethiopian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Ethiopian') echo "selected"; ?>>Ethiopian</option>
                                                            <option value="Fijian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Fijian') echo "selected"; ?>>Fijian</option>
                                                            <option value="Filipino" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Filipino') echo "selected"; ?>>Filipino</option>
                                                            <option value="Finnish" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Finnish') echo "selected"; ?>>Finnish</option>
                                                            <option value="French" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'French') echo "selected"; ?>>French</option>
                                                            <option value="Gabonese" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Gabonese') echo "selected"; ?>>Gabonese</option>
                                                            <option value="Gambian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Gambian') echo "selected"; ?>>Gambian</option>
                                                            <option value="Georgian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Georgian') echo "selected"; ?>>Georgian</option>
                                                            <option value="German" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'German') echo "selected"; ?>>German</option>
                                                            <option value="Ghanaian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Ghanaian') echo "selected"; ?>>Ghanaian</option>
                                                            <option value="Greek" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Greek') echo "selected"; ?>>Greek</option>
                                                            <option value="Grenadian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Grenadian') echo "selected"; ?>>Grenadian</option>
                                                            <option value="Guatemalan" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Guatemalan') echo "selected"; ?>>Guatemalan</option>
                                                            <option value="Guinea-bissauan" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Guinea-bissauan') echo "selected"; ?>>Guinea-Bissauan</option>
                                                            <option value="Guinean" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Guinean') echo "selected"; ?>>Guinean</option>
                                                            <option value="Guyanese" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Guyanese') echo "selected"; ?>>Guyanese</option>
                                                            <option value="Haitian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Haitian') echo "selected"; ?>>Haitian</option>
                                                            <option value="Herzegovinian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Herzegovinian') echo "selected"; ?>>Herzegovinian</option>
                                                            <option value="Honduran" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Honduran') echo "selected"; ?>>Honduran</option>
                                                            <option value="Hungarian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Hungarian') echo "selected"; ?>>Hungarian</option>
                                                            <option value="Icelander" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Icelander') echo "selected"; ?>>Icelander</option>
                                                            <option value="Indian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Indian') echo "selected"; ?>>Indian</option>
                                                            <option value="Indonesian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Indonesian') echo "selected"; ?>>Indonesian</option>
                                                            <option value="Iranian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Iranian') echo "selected"; ?>>Iranian</option>
                                                            <option value="Iraqi" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Iraqi') echo "selected"; ?>>Iraqi</option>
                                                            <option value="Irish" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Irish') echo "selected"; ?>>Irish</option>
                                                            <option value="Israeli" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Israeli') echo "selected"; ?>>Israeli</option>
                                                            <option value="Italian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Italian') echo "selected"; ?>>Italian</option>
                                                            <option value="Ivorian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Ivorian') echo "selected"; ?>>Ivorian</option>
                                                            <option value="Jamaican" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Jamaican') echo "selected"; ?>>Jamaican</option>
                                                            <option value="Japanese" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Japanese') echo "selected"; ?>>Japanese</option>
                                                            <option value="Jordanian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Jordanian') echo "selected"; ?>>Jordanian</option>
                                                            <option value="Kazakhstani" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Kazakhstani') echo "selected"; ?>>Kazakhstani</option>
                                                            <option value="Kenyan" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Kenyan') echo "selected"; ?>>Kenyan</option>
                                                            <option value="Kittian and nevisian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Kittian and nevisian') echo "selected"; ?>>Kittian and Nevisian</option>
                                                            <option value="Kuwaiti" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Kuwaiti') echo "selected"; ?>>Kuwaiti</option>
                                                            <option value="Kyrgyz" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Kyrgyz') echo "selected"; ?>>Kyrgyz</option>
                                                            <option value="Laotian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Laotian') echo "selected"; ?>>Laotian</option>
                                                            <option value="Latvian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Latvian') echo "selected"; ?>>Latvian</option>
                                                            <option value="Lebanese" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Lebanese') echo "selected"; ?>>Lebanese</option>
                                                            <option value="Liberian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Liberian') echo "selected"; ?>>Liberian</option>
                                                            <option value="Libyan" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Libyan') echo "selected"; ?>>Libyan</option>
                                                            <option value="Liechtensteiner" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Liechtensteiner') echo "selected"; ?>>Liechtensteiner</option>
                                                            <option value="Lithuanian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Lithuanian') echo "selected"; ?>>Lithuanian</option>
                                                            <option value="Luxembourger" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Luxembourger') echo "selected"; ?>>Luxembourger</option>
                                                            <option value="Macedonian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Macedonian') echo "selected"; ?>>Macedonian</option>
                                                            <option value="Malagasy" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Malagasy') echo "selected"; ?>>Malagasy</option>
                                                            <option value="Malawian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Malawian') echo "selected"; ?>>Malawian</option>
                                                            <option value="Malaysian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Malaysian') echo "selected"; ?>>Malaysian</option>
                                                            <option value="Maldivan" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Maldivan') echo "selected"; ?>>Maldivan</option>
                                                            <option value="Malian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Malian') echo "selected"; ?>>Malian</option>
                                                            <option value="Maltese" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Maltese') echo "selected"; ?>>Maltese</option>
                                                            <option value="Marshallese" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Marshallese') echo "selected"; ?>>Marshallese</option>
                                                            <option value="Mauritanian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Mauritanian') echo "selected"; ?>>Mauritanian</option>
                                                            <option value="Mauritian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Mauritian') echo "selected"; ?>>Mauritian</option>
                                                            <option value="Mexican" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Mexican') echo "selected"; ?>>Mexican</option>
                                                            <option value="Micronesian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Micronesian') echo "selected"; ?>>Micronesian</option>
                                                            <option value="Moldovan" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Moldovan') echo "selected"; ?>>Moldovan</option>
                                                            <option value="Monacan" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Monacan') echo "selected"; ?>>Monacan</option>
                                                            <option value="Mongolian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Mongolian') echo "selected"; ?>>Mongolian</option>
                                                            <option value="Moroccan" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Moroccan') echo "selected"; ?>>Moroccan</option>
                                                            <option value="Mosotho" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Mosotho') echo "selected"; ?>>Mosotho</option>
                                                            <option value="Motswana" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Motswana') echo "selected"; ?>>Motswana</option>
                                                            <option value="Mozambican" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Mozambican') echo "selected"; ?>>Mozambican</option>
                                                            <option value="Namibian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Namibian') echo "selected"; ?>>Namibian</option>
                                                            <option value="Nauruan" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Nauruan') echo "selected"; ?>>Nauruan</option>
                                                            <option value="Nepalese" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Nepalese') echo "selected"; ?>>Nepalese</option>
                                                            <option value="New zealander" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'New zealander') echo "selected"; ?>>New Zealander</option>
                                                            <option value="Nicaraguan" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Nicaraguan') echo "selected"; ?>>Nicaraguan</option>
                                                            <option value="Nigerian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Nigerian') echo "selected"; ?>>Nigerian</option>
                                                            <option value="Nigerien" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Nigerien') echo "selected"; ?>>Nigerien</option>
                                                            <option value="North korean" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'North korean') echo "selected"; ?>>North Korean</option>
                                                            <option value="Northern irish" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Northern irish') echo "selected"; ?>>Northern Irish</option>
                                                            <option value="Norwegian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Norwegian') echo "selected"; ?>>Norwegian</option>
                                                            <option value="Omani" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Omani') echo "selected"; ?>>Omani</option>
                                                            <option value="Pakistani" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Pakistani') echo "selected"; ?>>Pakistani</option>
                                                            <option value="Palauan" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Palauan') echo "selected"; ?>>Palauan</option>
                                                            <option value="Panamanian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Panamanian') echo "selected"; ?>>Panamanian</option>
                                                            <option value="Papua new guinean" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Papua new guinean') echo "selected"; ?>>Papua New Guinean</option>
                                                            <option value="Paraguayan" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Paraguayan') echo "selected"; ?>>Paraguayan</option>
                                                            <option value="Peruvian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Peruvian') echo "selected"; ?>>Peruvian</option>
                                                            <option value="Polish" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Polish') echo "selected"; ?>>Polish</option>
                                                            <option value="Portuguese" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Portuguese') echo "selected"; ?>>Portuguese</option>
                                                            <option value="Qatari" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Qatari') echo "selected"; ?>>Qatari</option>
                                                            <option value="Romanian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Romanian') echo "selected"; ?>>Romanian</option>
                                                            <option value="Russian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Russian') echo "selected"; ?>>Russian</option>
                                                            <option value="Rwandan" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Rwandan') echo "selected"; ?>>Rwandan</option>
                                                            <option value="Saint lucian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Saint lucian') echo "selected"; ?>>Saint Lucian</option>
                                                            <option value="Salvadoran" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Salvadoran') echo "selected"; ?>>Salvadoran</option>
                                                            <option value="Samoan" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Samoan') echo "selected"; ?>>Samoan</option>
                                                            <option value="San marinese" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'San marinese') echo "selected"; ?>>San Marinese</option>
                                                            <option value="Sao tomean" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Sao tomean') echo "selected"; ?>>Sao Tomean</option>
                                                            <option value="Saudi" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Saudi') echo "selected"; ?>>Saudi</option>
                                                            <option value="Scottish" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Scottish') echo "selected"; ?>>Scottish</option>
                                                            <option value="Senegalese" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Senegalese') echo "selected"; ?>>Senegalese</option>
                                                            <option value="Serbian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Serbian') echo "selected"; ?>>Serbian</option>
                                                            <option value="Seychellois" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Seychellois') echo "selected"; ?>>Seychellois</option>
                                                            <option value="Sierra leonean" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Sierra leonean') echo "selected"; ?>>Sierra Leonean</option>
                                                            <option value="Singaporean" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Singaporean') echo "selected"; ?>>Singaporean</option>
                                                            <option value="Slovakian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Slovakian') echo "selected"; ?>>Slovakian</option>
                                                            <option value="Slovenian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Slovenian') echo "selected"; ?>>Slovenian</option>
                                                            <option value="Solomon islander" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Solomon islander') echo "selected"; ?>>Solomon Islander</option>
                                                            <option value="Somali" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Somali') echo "selected"; ?>>Somali</option>
                                                            <option value="South african" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'South african') echo "selected"; ?>>South African</option>
                                                            <option value="South korean" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'South korean') echo "selected"; ?>>South Korean</option>
                                                            <option value="Spanish" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Spanish') echo "selected"; ?>>Spanish</option>
                                                            <option value="Sri lankan" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Sri lankan') echo "selected"; ?>>Sri Lankan</option>
                                                            <option value="Sudanese" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Sudanese') echo "selected"; ?>>Sudanese</option>
                                                            <option value="Surinamer" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Surinamer') echo "selected"; ?>>Surinamer</option>
                                                            <option value="Swazi" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Swazi') echo "selected"; ?>>Swazi</option>
                                                            <option value="Swedish" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Swedish') echo "selected"; ?>>Swedish</option>
                                                            <option value="Swiss" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Swiss') echo "selected"; ?>>Swiss</option>
                                                            <option value="Syrian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Syrian') echo "selected"; ?>>Syrian</option>
                                                            <option value="Taiwanese" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Taiwanese') echo "selected"; ?>>Taiwanese</option>
                                                            <option value="Tajik" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Tajik') echo "selected"; ?>>Tajik</option>
                                                            <option value="Tanzanian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Tanzanian') echo "selected"; ?>>Tanzanian</option>
                                                            <option value="Thai" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Thai') echo "selected"; ?>>Thai</option>
                                                            <option value="Togolese" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Togolese') echo "selected"; ?>>Togolese</option>
                                                            <option value="Tongan" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Tongan') echo "selected"; ?>>Tongan</option>
                                                            <option value="Trinidadian or tobagonian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Trinidadian or tobagonian') echo "selected"; ?>>Trinidadian or Tobagonian</option>
                                                            <option value="Tunisian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Tunisian') echo "selected"; ?>>Tunisian</option>
                                                            <option value="Turkish" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Turkish') echo "selected"; ?>>Turkish</option>
                                                            <option value="Tuvaluan" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Tuvaluan') echo "selected"; ?>>Tuvaluan</option>
                                                            <option value="Ugandan" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Ugandan') echo "selected"; ?>>Ugandan</option>
                                                            <option value="Ukrainian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Ukrainian') echo "selected"; ?>>Ukrainian</option>
                                                            <option value="Uruguayan" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Uruguayan') echo "selected"; ?>>Uruguayan</option>
                                                            <option value="Uzbekistani" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Uzbekistani') echo "selected"; ?>>Uzbekistani</option>
                                                            <option value="Vatican citizen" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Vatican citizen') echo "selected"; ?>>Vatican Citizen</option>
                                                            <option value="Venezuelan" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Venezuelan') echo "selected"; ?>>Venezuelan</option>
                                                            <option value="Vietnamese" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Vietnamese') echo "selected"; ?>>Vietnamese</option>
                                                            <option value="Welsh" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Welsh') echo "selected"; ?>>Welsh</option>
                                                            <option value="Yemenite" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Yemenite') echo "selected"; ?>>Yemenite</option>
                                                            <option value="Zambian" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Zambian') echo "selected"; ?>>Zambian</option>
                                                            <option value="Zimbabwean" <?php if(isset($array[0]['nationality']) && $array[0]['nationality'] == 'Zimbabwean') echo "selected"; ?>>Zimbabwean</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-auto col-sm-2 houseNum houseNumForm" id="">
                                                    <div class="address text-center">
                                                        <label class="houseNumFormLbl"><strong>House #:</strong></label>
                                                    </div>
                                                    <div class="col">
                                                        <input type="number"  class="form-control text-center" name="houseNumber" id="address" min="1" value="<?php if(isset($array[0]['houseNumber'])){ echo $array[0]['houseNumber']; } ?>" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "5" >                  
                                                    </div>
                                                </div>
                                                <div class="col-auto col-sm-2 streetForm">
                                                    <div class="address text-center">
                                                        <label><strong>Street:</strong></label>
                                                    </div>
                                                    <div class="col">
                                                        <input type="text" class="form-control text-center" name="street" id="address" value="<?php if(isset($array[0]['street'])){ echo $array[0]['street']; } ?>" >                  
                                                    </div>
                                                </div>
                                                <div class="col-auto col-sm-2 streetForm">
                                                    <div class="address text-center">
                                                        <label><strong>Barangay:</strong></label>
                                                    </div>
                                                    <div class="col">
                                                        <input type="text" class="form-control text-center" name="barangay" id="address" value="<?php if(isset($array[0]['barangay'])){ echo $array[0]['barangay']; } ?>" >                        
                                                    </div>
                                                </div>
                                                <div class="col-auto col-sm-2 streetForm">
                                                    <div class="address text-center">
                                                        <label><strong>City:</strong></label>
                                                    </div>
                                                    <div class="col">
                                                        <input type="text" class="form-control text-center" name="city" id="address" value="<?php if(isset($array[0]['city'])){ echo $array[0]['city']; } ?>" >                        
                                                    </div>
                                                </div>
                                                <div class="col-auto col-sm-2 streetForm">
                                                    <div class="address text-center">
                                                        <label><strong>Province:</strong></label>
                                                    </div>
                                                    <div class="col">
                                                        <input type="text" class="form-control text-center" name="province" id="address" value="<?php if(isset($array[0]['province'])){ echo $array[0]['province']; } ?>" >                        
                                                    </div>
                                                </div>

                                                

                                            </div>
                                            <div class="row justify-content-start px-4">
                                                <script>
                                                    // Function to handle the dropdown box change event
                                                    function showtypeOfID() {
                                                        var selection = document.getElementById("typeOfID").value;

                                                        // Show or hide content based on the selection
                                                        var landscapeID = document.getElementById("landscapeID");
                                                        var portraitID = document.getElementById("portraitID");
                                                        var frontIDLandscape = document.getElementById("frontID");
                                                        var backIDLandscape = document.getElementById("backID");
                                                        var frontIDPortrait = document.getElementById("frontIDPortrait");
                                                        var backIDPortrait = document.getElementById("backIDPortrait");
                                                        if (selection === "School ID") {
                                                            landscapeID.style.display = "none";
                                                            landscapeIDback.style.display = "none";
                                                            portraitID.style.display = "block";
                                                            portraitIDback.style.display = "block";
                                                        } else{
                                                            landscapeID.style.display = "block";
                                                            landscapeIDback.style.display = "block";
                                                            portraitID.style.display = "none"; 
                                                            portraitIDback.style.display = "none";
                                                        }
                                                    }
                                                </script>

                                                <div class="col-auto typeID text-center ">
                                                        <label for="typeOfID"><strong>Type of ID:</strong></label>
                                                        <select id="typeOfID" name="typeOfID" onchange="showtypeOfID()" class="form-control text-center bg-white" >
                                                            <option disabled selected>-- select ID --</option>
                                                            <option value="National ID" <?php if(isset($array[0]['typeOfID']) && $array[0]['typeOfID'] == 'National ID') echo "selected"; ?> >National ID</option>
                                                            <option value="Drivers License" <?php if(isset($array[0]['typeOfID']) && $array[0]['typeOfID'] == 'Drivers License') echo "selected"; ?>>Driver's License</option>
                                                            <option value="SSS ID" <?php if(isset($array[0]['typeOfID']) && $array[0]['typeOfID'] == 'SSS ID') echo "selected"; ?>>SSS ID</option>
                                                            <option value="Postal ID" <?php if(isset($array[0]['typeOfID']) && $array[0]['typeOfID'] == 'Postal ID') echo "selected"; ?>>Postal ID</option>
                                                            <option value="PRC ID" <?php if(isset($array[0]['typeOfID']) && $array[0]['typeOfID'] == 'PRC ID') echo "selected"; ?>>PRC ID</option>
                                                            <option value="UMID" <?php if(isset($array[0]['typeOfID']) && $array[0]['typeOfID'] == 'UMID') echo "selected"; ?>>UMID</option>
                                                            <option value="Voters ID" <?php if(isset($array[0]['typeOfID']) && $array[0]['typeOfID'] == 'Voters ID') echo "selected"; ?>>Voter's ID</option>
                                                            <option value="Passport ID" <?php if(isset($array[0]['typeOfID']) && $array[0]['typeOfID'] == 'Passport ID') echo "selected"; ?>>Passport ID</option>
                                                            <option value="PAGIBIG ID" <?php if(isset($array[0]['typeOfID']) && $array[0]['typeOfID'] == 'PAGIBIG ID') echo "selected"; ?>>PAGIBIG ID</option>
                                                            <option value="School ID" <?php if(isset($array[0]['typeOfID']) && $array[0]['typeOfID'] == 'School ID') echo "selected"; ?>>School ID</option>
                                                        </select><br>
                                                    </div>
                                            </div>
                                        
                                        
                        
                                            <div class="row justify-content-center px-4">
                                                <div class="col-sm" id="landscapeID" style="<?php if(isset($array[0]['typeOfID']) && $array[0]['typeOfID'] == 'School ID'){ echo 'display: none';} ?>"> <!--Landscape ID-->
                                                    <div class="frontID text-center">
                                                        <label class="frontID"><strong>Front ID:</strong></label>
                                                    </div>
                                                    <div class="column">
                                                        <img id="validFrontID"   src="<?php if(isset($array[0]['frontID'])  && $array[0]['typeOfID'] != 'School ID' && !empty($array[0]['typeOfID'])){ echo "../registration/frontID/".$array[0]['frontID']; } else{ echo '../images/frontLandscapeID.png'; } ?>" data-original="../images/frontLandscapeID.png"   alt="sample"  >
                                                    </div>
                                                    <div class="d-flex justify-content-center mt-2">
                                                        <label id="custom-file-upload" class="btn btn-primary">
                                                        <input type="file" name="frontLandscape" id="frontID"  alt="Submit"  onchange="frontPreviewImage()" hidden accept="image/jpg, image/jpeg, image/png">Upload</label>
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-sm" id="landscapeIDback" style="<?php if(isset($array[0]['typeOfID']) && $array[0]['typeOfID'] == 'School ID'){ echo 'display: none';} ?>">
                                                    <div class="backID text-center">
                                                        <label class="backID "><strong>Back ID:</strong></label>
                                                    </div>
                                                    <div class="column">
                                                        <img id="validBackID"  src="<?php if(isset($array[0]['backID'])  && $array[0]['typeOfID'] != 'School ID' && !empty($array[0]['typeOfID'])){ echo '../registration/backID/'.$array[0]['backID']; } else{ echo '../images/backLandscapeID.png'; } ?>" data-original="../images/backLandscapeID.png" alt="sample" >
                                                    </div>
                                                    <div class="d-flex justify-content-center mt-2">
                                                        <label id="custom-file-upload" class="btn btn-primary">
                                                        <input type="file" name="backLandscape" id="backID"  alt="Submit"  onchange="backPreviewImage()" hidden accept="image/jpg, image/jpeg, image/png">Upload</label>
                                                        
                                                    </div>
                                                </div><!--End of Landscape ID-->
                                                
                                                <div class="col-sm" id="portraitID" style="<?php if(isset($array[0]['typeOfID']) && $array[0]['typeOfID'] != 'School ID'){ echo 'display: none';} ?>" ><!--Portrait ID-->
                                                    <div class="frontID text-center">
                                                        <label class="frontID"><strong>Front ID:</strong></label>
                                                    </div>
                                                    <div class="column">
                                                        <img id="validFrontIDportrait"  src="<?php if(isset($array[0]['frontID']) && $array[0]['typeOfID'] == 'School ID' && !empty($array[0]['typeOfID'])){ echo '../registration/frontID/'.$array[0]['frontID']; } else{ echo '../images/frontPortraitID.png'; } ?>" data-original="../images/frontPortraitID.png" alt="sample"  >
                                                    </div>
                                                    <div class="d-flex justify-content-center mt-2">
                                                        <label id="custom-file-upload" class="btn btn-primary">
                                                        <input type="file" name="frontPortrait" id="frontIDPortrait"  alt="Submit"  onchange="frontPreviewImage1()" hidden accept="image/jpg, image/jpeg, image/png">Upload</label>
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-sm" id="portraitIDback" style="<?php if(isset($array[0]['typeOfID']) && $array[0]['typeOfID'] != 'School ID'){ echo 'display: none';} ?>" >
                                                    <div class="backID text-center">
                                                        <label class="backID "><strong>Back ID:</strong></label>
                                                    </div>
                                                    <div class="column">
                                                        <img id="validBackIDportrait"  src="<?php if(isset($array[0]['backID'])  && $array[0]['typeOfID'] == 'School ID' && !empty($array[0]['typeOfID'])){ echo '../registration/backID/'.$array[0]['backID']; } else{ echo '../images/backPortraitID.png'; } ?>" data-original="../images//backPortraitID.png" alt="sample" >
                                                    </div>
                                                    <div class="d-flex justify-content-center mt-2">
                                                        <label id="custom-file-upload" class="btn btn-primary">
                                                        <input type="file" name="backPortrait" id="backIDPortrait"  alt="Submit"  onchange="backPreviewImage1()" hidden accept="image/jpg, image/jpeg, image/png">Upload</label>
                                                        
                                                    </div>
                                                </div><!--End of Portrait ID-->
                                                
                                                <div class="col-sm"><!--Selfie with ID-->
                                                    <div class="backID text-center">
                                                        <label class="backID "><strong>Selfie With ID:</strong></label>
                                                    </div>
                                                    <div class="column">
                                                        <img id="selfie"  src="<?php if(isset($array[0]['selfieWithID']) && !empty($array[0]['selfieWithID'])){ echo '../registration/selfie/'.$array[0]['selfieWithID']; } else{ echo '../images/selfiewithID.png'; } ?>" data-original="../images/selfiewithID.png" alt="sample" >
                                                    </div>
                                                    <div class="d-flex justify-content-center mt-2">
                                                        <label id="custom-file-upload" class="btn btn-primary">
                                                        <input type="file" name="selfieID" id="selfieID"  alt="Submit"  onchange="selfiePreview()" hidden accept="image/jpg, image/jpeg, image/png">Upload</label>
                                                    
                                                    </div>
                                                </div><!--End of Selfie with ID-->
                                
                                            </div>
                                            <div class="d-flex justify-content-center mt-5">
                                                        
                                                <button class="btn btn-primary" name="updateInformation" id="update"  alt="Submit">Update Information</button>
                                                <!--<button id ="clear" name="clear" class="btn btn-danger">Clear</button>-->
                                            </div>
                                            <!-- Button trigger modal -->
                                            <div class="row mx-0 justify-content-end mt-2 lgScreen"> <!--For desktop-->
                                                <div class="col-auto px-0 showGuardQR">
                                                    <button type="button" class="btn btn-success btn-sm showQR" data-toggle="modal" data-target="#exampleModalCenter" style="font-size:large;">
                                                        <i class="bi bi-qr-code"></i> Account QR
                                                    </button>
                                                </div>
                                            </div><!-- Button trigger modal -->
                                            <div class="row mx-0 justify-content-center mt-2 mb-2 smScreen"><!--For mobile-->
                                                <div class="col-auto px-0 showGuardQR">
                                                    <button type="button" class="btn btn-success btn-sm showQR" data-toggle="modal" data-target="#exampleModalCenter" style="font-size:large;">
                                                        <i class="bi bi-qr-code"></i> Account QR
                                                    </button>
                                                </div>
                                            </div><!-- Button trigger modal -->
                                            <!-- modal -->
                                            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle">Account QR Code</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row justify-content-center">
                                                                <img id="pic" src="../registration/visitorQR/<?php echo $accountQR;?>" class="" style="width:300px; height: 300px;" alt="QR Code" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- modal -->

                                        </div>                                
                                </div>
                                
                                <?php
                                
                                if($_SESSION['role'] == 'Guest'){
                                    echo "
                                        <script>//validate the submission
                                            function validateSubmit() {
                                                var typeofid = document.getElementById('typeOfID').value;

                                                if(typeofid == 'School ID'){
                                                
                                                    var frontIDPortrait = document.getElementById('frontIDPortrait');
                                                    var backIDPortrait = document.getElementById('backIDPortrait');

                                                    if (frontIDPortrait.files.length === 0) {
                                                        // Show SweetAlert alert for missing file
                                                        Swal.fire({
                                                            icon: 'error',
                                                            title: 'No Front ID selected',
                                                            text: 'Please upload your Front ID photo'
                                                        });
                                                        return false; // Prevent form submission
                                                    }
                                                    else if (backIDPortrait.files.length === 0){
                                                        Swal.fire({
                                                            icon: 'error',
                                                            title: 'No Back ID selected',
                                                            text: 'Please upload your Back ID photo.'
                                                        });
                                                        return false; // Prevent form submission
                                                    }

                                                }
                                                else{
                                                    var frontIDLandscape = document.getElementById('frontID');
                                                    var backIDLandscape = document.getElementById('backID');

                                                    if (frontIDLandscape.files.length === 0) {
                                                        // Show SweetAlert alert for missing file
                                                        Swal.fire({
                                                            icon: 'error',
                                                            title: 'No Front ID selected',
                                                            text: 'Please upload your Front ID photo'
                                                        });
                                                        return false; // Prevent form submission
                                                    }
                                                    else if (backIDLandscape.files.length === 0){
                                                        Swal.fire({
                                                            icon: 'error',
                                                            title: 'No Back ID selected',
                                                            text: 'Please upload your Back ID photo.'
                                                        });
                                                        return false; // Prevent form submission
                                                    }
                                                }

                                                var selfieID = document.getElementById('selfieID');
                                                if (selfieID.files.length === 0) {
                                                    // Show SweetAlert alert for missing file
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'No Selfie with ID selected',
                                                        text: 'Please upload your Selfie with Uploaded ID'
                                                    });
                                                    return false; // Prevent form submission
                                                }

                                                return true; // Allow form submission
                                            }
                                        </script>
                                    
                                    
                                    ";
                                }
                                
                                ?>

                            </form>
                        </div>
                </div>                
                <!-- Add Visit Info End -->
                <div class="row"> 
                    <div class="col" style="<?php echo $displayCurr;?>">
                        <div class="container-fluid pt-4 m-0 p-0 currentPwd">
                            <div class="bg-light rounded itemContain">
                                <div>            
                                    <form method="POST" action="userProfile.php">
                                        <div class="formContent">
                                            <nav class="navbar navbar-light bg-light justify-content-center mb-4 visitInfo">
                                                <span class="navbar-brand mb-0 h1 text-white">Password Setting</span>
                                            </nav>
                                            <div class="container-fluid mt-3 col-11">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="col pwrd text-center">
                                                            <label><strong>Current Password:</strong>  </label>
                                                        </div>
                                                        <div class="col mb-3 input-group">
                                                            <input type="password" class="form-control " id="currpass" name="currentpassword" required></input>
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
                    <div class="col" style="<?php echo $display;?>">

                        <div class="container-fluid pt-4 m-0 p-0 ">
                            <div class="bg-light rounded itemContain">
                                <div>            
                                    <form method="POST" action="userProfile.php">
                                        <div class="formContent">
                                            <nav class="navbar navbar-light bg-light justify-content-center mb-4 visitInfo">
                                                <span class="navbar-brand mb-0 h1 text-white">Change Password</span>
                                            </nav>
                                            <div class="container-fluid mt-3 col-11">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="col pwrd text-center">
                                                            <label><strong>New Password:</strong>  </label>
                                                        </div>
                                                        <div class="col mb-3 input-group">
                                                            <input type="password" class="form-control" id="newpassword" placeholder="Enter New Password" oninput="checkPasswordMatch()" name="newpassword" required>
                                                            <span  class="btn btn-dark" id="btnEye" onclick="showHideNewPass()"><i id="toggleNewPass" class="bi bi-eye"></i></span>   
                                                        </div>
                                                        <div class="col pwrd text-center">
                                                            <label><strong>Confirm Password:</strong>  </label>
                                                        </div>
                                                        <div class="col mb-3 input-group">
                                                            <input type="password" class="form-control" id="confirmpassword" placeholder="Confirm New Password" oninput="checkPasswordMatch()" name="confirmpassword" required>
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


