<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <script src="js/nodevtool.js"></script>
    <!-- Favicon -->
    <link href="../images/vysmoprintlogo.png" rel="icon">
    <!--SWEET ALERT 2-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
</head>
<body style="background-color: #cce6ff;">

</body>
</html>
<?php
    session_start();
    require('../database.php');
    if(isset($_POST['updateInformation'])){

        $accountID = $_SESSION['accountID'];

        $firstName = $_POST['firstName'];   
        $middleInitial = $_POST['middleInitial'];
        $lastName = $_POST['lastName'];
        $suffixName = $_POST['suffixName'];
        $gender = $_POST['gender'];
        $mobileNumber = $_POST['mobileNumber'];
        $houseNumber = $_POST['houseNumber'];
        $street = $_POST['street'];
        $barangay = $_POST['barangay'];
        $city = $_POST['city'];
        $province = $_POST['province'];
        $nationality = $_POST['nationality'];


        $sqlvisitorInfo = "UPDATE visitor_info SET firstName = '{$firstName}', middleInitial = '{$middleInitial}', lastName = '{$lastName}', suffixName = '{$suffixName}', gender = '{$gender}', mobileNumber = '{$mobileNumber}', houseNumber = '{$houseNumber}', street = '{$street}', barangay = '{$barangay}', city = '{$city}', province = '{$province}', nationality = '{$nationality}' WHERE account_id = '{$accountID}'";
        if(mysqli_query($connection, $sqlvisitorInfo)){

            $typeOfID = $_POST['typeOfID'];

            //image
            if($typeOfID == 'School ID'){
                $frontID = $_FILES['frontPortrait'];
                $backID = $_FILES['backPortrait'];
            }
            else{
                $frontID = $_FILES['frontLandscape'];
                $backID = $_FILES['backLandscape'];
            }
            
            //frontID
            $frontName = $frontID['name'];
            $frontTmpName = $frontID['tmp_name'];
            $frontSize = $frontID['size'];
            $frontError = $frontID['error'];
            $frontType = $frontID['type'];
    
            $frontExt = explode('.', $frontName);//para mapaghiwalay yung file name and yung extension
            $frontActualExt = strtolower(end($frontExt));
    
            //backID
            $backName = $backID['name'];
            $backTmpName = $backID['tmp_name'];
            $backSize = $backID['size'];
            $backError = $backID['error'];
            $backType = $backID['type'];
    
            $backExt = explode('.', $backName);//para mapaghiwalay yung file name and yung extension
            $backActualExt = strtolower(end($backExt));
    
            //selfie withID
            $selfieWithID = $_FILES['selfieID'];
    
            $selfieName = $selfieWithID['name'];
            $selfieTmpName = $selfieWithID['tmp_name'];
            $selfieSize = $selfieWithID['size'];
            $selfieError = $selfieWithID['error'];
            $selfieType = $selfieWithID['type'];
    
            $selfieExt = explode('.', $selfieName);//para mapaghiwalay yung file name and yung extension
            $selfieActualExt = strtolower(end($selfieExt));
            /*
            //kinuha yung type of id na nakasave sa database para maidentify if magkakaron ba ng changes yung photos or not
            $selectTypeOfID = mysqli_query($connection,"SELECT typeOfID FROM visitorinfo WHERE email = '{$email}'");
            while($row = mysqli_fetch_array($selectTypeOfID)){
                $arrayTypeOfID = $row;
            }
            */
            

            if(!empty($frontName) && !empty($backName) && !empty($selfieName)){
                //nangyari lang dito ay kinuha yung filename from database kasi nakauniqid yon 
                //para mareplace nalang yung image sa nasasave instead of creating another file. 
                //it will only reproduce other file para lang mapalitan ng filetype like jpg png gif
                
                $dbFrontID = $_POST['dbFrontID'];

                $frontDBExt = explode('.', $dbFrontID);//pinaghiwalay yung filename na nasa db and yung ext
                if($_SESSION['role'] == 'Guest'){
                    $front = uniqid('', true);
                }
                else{
                    $front = "$frontDBExt[0].$frontDBExt[1]"; 
                }
                

                $frontExt = explode('.', $frontName);//para mapaghiwalay yung file name and yung extension
                $frontActualExt = strtolower(end($frontExt));

                
                $dbBackID = $_POST['dbBackID'];
                $backDBExt = explode('.', $dbBackID);//pinaghiwalay yung filename na nasa db and yung ext
                if($_SESSION['role'] == 'Guest'){
                    $back = uniqid('', true);
                }
                else{
                    $back = "$backDBExt[0].$backDBExt[1]";
                }
                
                
                $backExt = explode('.', $backName);//para mapaghiwalay yung file name and yung extension ng inupload
                $backActualExt = strtolower(end($backExt));

                $dbSelfieWithID = $_POST['dbSelfieWithID'];
                $selfieDBExt = explode('.', $dbSelfieWithID);//pinaghiwalay yung filename na nasa db and yung ext
                if($_SESSION['role'] == 'Guest'){
                    $selfie = uniqid('', true);
                }
                else{
                    $selfie = "$selfieDBExt[0].$selfieDBExt[1]";
                }

                $selfieExt = explode('.', $selfieName);//para mapaghiwalay yung file name and yung extension ng inupload
                $selfieActualExt = strtolower(end($selfieExt));

                $allowedImg = array('jpg', 'jpeg', 'png');// mga iaallow nating filetype. pwede rin pdf, docx, etc.

                if(in_array($selfieActualExt, $allowedImg) and in_array($frontActualExt, $allowedImg) and in_array($backActualExt, $allowedImg)){

                    if($selfieError === 0 and $frontError === 0 and $backError === 0){

                        if($selfieSize <= 10000000 and $frontSize <= 10000000 and $backSize <= 10000000 ){

                            $selfieNameNew = $selfie.".".$selfieActualExt;// extension name na kinuha galing db
                            $selfieDestination = '../registration/selfie/'.$selfieNameNew; //name ng folder na paglalagyan ng file natin
                            move_uploaded_file($selfieTmpName, $selfieDestination);

                            $frontNameNew = $front.".".$frontActualExt; 
                            $frontDestination = '../registration/frontID/'.$frontNameNew; //name ng folder na paglalagyan ng file natin
                            move_uploaded_file($frontTmpName, $frontDestination);

                            //BACK ID
                            $backNameNew = $back.".".$backActualExt;
                            $backDestination = '../registration/backID/'.$backNameNew; //name ng folder na paglalagyan ng file natin
                            move_uploaded_file($backTmpName, $backDestination);


                            $sqlUpdatePic =  "UPDATE visitor_info SET typeOfID = '{$typeOfID}', selfieWithID = '{$selfieNameNew}', frontID = '{$frontNameNew}', backID = '{$backNameNew}' WHERE account_id = '{$accountID}'";
                            
                            if($_SESSION['role'] == 'Guest'){
                                $sqlGuest = "UPDATE accounts SET role = 'Visitor' WHERE email = '{$email}'";
                                if(mysqli_query($connection, $sqlGuest)){
                                    $_SESSION['role'] = 'Visitor';
                                }
                            }

                            if(mysqli_query($connection, $sqlUpdatePic)){

                                echo 
                                    "<script>
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Update Success',
                                            text: 'Profile have been updated!'
                                        }).then(function() {
                                        window.location = 'userProfile.php';
                                    });
                                    </script>";
                                
                            }

                        }
                    
                    }

                }
                else {  
                    echo 
                    "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'You cannot upload this file type!',
                            text: 'jpg, jpeg, png is only allowed'
                        })
                    </script>";
                }

            }
            else{
                echo 
                    "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Your personal information has been updated'
                        }).then(function() {
                          window.location = 'userProfile.php';
                      });
                    </script>";
                    
            }

            
            
            
        }

    }
    elseif(isset($_POST['clear'])){

    }

?>