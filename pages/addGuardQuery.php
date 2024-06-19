<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VYSMO</title>
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
    use PHPMailer\PHPMailer\PHPMailer; 
    use PHPMailer\PHPMailer\SMTP; 
    use PHPMailer\PHPMailer\Exception; 
    require('../database.php');
    session_start();

    //GUARD LIST
    if(isset($_POST['createGuardAccount'])){
        
        $email = $_POST['email'];

        if(filter_var($email,FILTER_VALIDATE_EMAIL)){
            //check if email exist
            $checkEmail = mysqli_query($connection, "SELECT * FROM accounts WHERE email='{$email}'");

            if(mysqli_num_rows($checkEmail) > 0){
            //email exist
                echo 
                    "<script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'Email Already Exist',
                            text: 'Please check your email for your Account QR Code'
                        }).then(function() {
                            window.location = 'addGuard.php';
                        });
                    </script>";
            }
            else{

                $role = "Guard";

                $firstName = mysqli_real_escape_string($connection, $_POST['firstName']);
                $middleName = mysqli_real_escape_string($connection, $_POST['middleName']);
                $lastName = mysqli_real_escape_string($connection, $_POST['lastName']);
                $suffixName = mysqli_real_escape_string($connection, $_POST['suffixName']);
                $gender = mysqli_real_escape_string($connection, $_POST['gender']);
                $houseNumber = mysqli_real_escape_string($connection, $_POST['houseNumber']);
                $street = mysqli_real_escape_string($connection, $_POST['street']);
                $barangay = mysqli_real_escape_string($connection, $_POST['barangay']);
                $city = mysqli_real_escape_string($connection, $_POST['city']);
                $province = mysqli_real_escape_string($connection, $_POST['province']);

                $pic2x2 = $_FILES['pic2x2'];

                //2x2 picture
                $pic2x2Name = $pic2x2['name'];
                $pic2x2TmpName = $pic2x2['tmp_name'];
                $pic2x2Size = $pic2x2['size'];
                $pic2x2Error = $pic2x2['error'];
                $pic2x2Type = $pic2x2['type'];

                $pic2x2Ext = explode('.', $pic2x2Name);//para mapaghiwalay yung file name and yung extension
                $pic2x2ActualExt = strtolower(end($pic2x2Ext));

                $allowedImg = array('jpg', 'jpeg', 'png');

                if(in_array($pic2x2ActualExt, $allowedImg)){
                    
                    if($pic2x2Error === 0){

                        if($pic2x2Size <= 10000000){
                            date_default_timezone_set("Asia/Manila");
                            $dateTime = date('Y-m-d h:i:s A', time());

                            //8 digit password generator (used OTP)
                            $alpha   = str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZ', 4));
                            $numeric = str_shuffle(str_repeat('0123456789', 4));
                            $generatedpassword = substr($alpha, 0, 4) . substr($numeric, 0, 4);
                            $generatedpassword = str_shuffle($generatedpassword);

                            //HASHING
                            $salt = "45a4748f715f501377bf2c6de1b259b1"; //visitor monitoring system (md5)
                            $hashpassword = md5($salt.$generatedpassword);

                            $insertGuardAccount = "INSERT INTO accounts (email, password, datetime_created, role) VALUE ('$email', '$hashpassword', '$dateTime', '$role')";
                            if(mysqli_query($connection, $insertGuardAccount)){

                                $accountID = mysqli_insert_id($connection); //ACCOUNT ID
                        
                                $accountQRDirectory = "guardQR/"; 
                                $qrName = uniqid('',true).'.png';
                                $codeContents = "$accountID***$email";

                                //start ENCRYPTION OF QRCODE DATA
                                $key = openssl_random_pseudo_bytes(16); // 128-bit key for AES-128-CBC
                                $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-128-cbc'));

                                $encryptedBinary = openssl_encrypt($codeContents, 'aes-128-cbc', $key, 0, $iv);
                                $encryptedQrContent = base64_encode($encryptedBinary);

                                // Save QR 
                                $imageUrl = 'https://chart.googleapis.com/chart?chs=500x500&cht=qr&chl='.urlencode($encryptedQrContent).'&choe=UTF-8';
                                $rawImage = file_get_contents($imageUrl);
                                file_put_contents($accountQRDirectory.$qrName, $rawImage);
                                //this will be input sa database and kapag gusto na idecrypt yung qr ireretrieve yung sa database and use key and iv
                                
                                $qrName = mysqli_real_escape_string($connection, $qrName);
                                $encryptedQrDB = mysqli_real_escape_string($connection, $encryptedQrContent);
                                $keyQR = mysqli_real_escape_string($connection, base64_encode($key));
                                $ivQR = mysqli_real_escape_string($connection, base64_encode($iv));

                                //edn ENCRYPTION OF QRCODE DATA

                                $insertAccountQR = "INSERT INTO account_qr (account_id, qrName, encryptedQrContent, keyQR, ivQR) VALUE ('$accountID', '$qrName', '$encryptedQrDB', '$keyQR', '$ivQR')";
                                if(mysqli_query($connection, $insertAccountQR)){
                                   
                                    //pic2x2
                                    $pic2x2NameNew = uniqid('', true).".".$pic2x2ActualExt;
                                    $pic2x2Destination = 'guard2x2/'.$pic2x2NameNew; //name ng folder na paglalagyan ng file natin
                                    move_uploaded_file($pic2x2TmpName, $pic2x2Destination);

                                    $insertGuardInfo = "INSERT INTO guard_info (account_id, firstName, middleName, lastName, suffixName, gender, houseNumber, street, barangay, city, province, pic2x2) VALUE ('$accountID', '$firstName', '$middleName', '$lastName', '$suffixName', '$gender', '$houseNumber', '$street', '$barangay', '$city', '$province', '$pic2x2NameNew')";
                                    if(mysqli_query($connection, $insertGuardInfo)){

                                        echo "<script>window.location = 'sendEmail/emailGuardAccountQR.php?email=$email&password=$generatedpassword&qrName=$qrName';</script>";

                                    }

                                }

                            }

                        }
                    
                    }
                
                }

            }

        }

    }
    elseif(isset($_POST['editInformation'])){
        //update
        $accountID = $_POST['accountID'];
        //check pa if maihahabol change email
        $guardEmail = mysqli_real_escape_string($connection, $_POST['guardEmail']);

        $firstName = mysqli_real_escape_string($connection, $_POST['firstName']);
        $middleName = mysqli_real_escape_string($connection, $_POST['middleName']);
        $lastName = mysqli_real_escape_string($connection, $_POST['lastName']);
        $suffixName = mysqli_real_escape_string($connection, $_POST['suffixName']);
        $gender = mysqli_real_escape_string($connection, $_POST['gender']);
        $houseNumber = mysqli_real_escape_string($connection, $_POST['houseNumber']);
        $street = mysqli_real_escape_string($connection, $_POST['street']);
        $barangay = mysqli_real_escape_string($connection, $_POST['barangay']);
        $city = mysqli_real_escape_string($connection, $_POST['city']);
        $province = mysqli_real_escape_string($connection, $_POST['province']);
        
        
        $pic2x2 = $_FILES['pic2x2'];
        // echo "<pre>";
        // echo print_r($pic2x2);
        // echo "</pre>";
        
        //2x2 picture
        $pic2x2Name = $pic2x2['name'];
        $pic2x2TmpName = $pic2x2['tmp_name'];
        $pic2x2Size = $pic2x2['size'];
        $pic2x2Error = $pic2x2['error'];
        $pic2x2Type = $pic2x2['type'];
        
        if(!empty($pic2x2Name) && !empty($pic2x2TmpName) && !empty($pic2x2Size) && !empty($pic2x2Type)){
            
            $db2x2 = $_POST['pic2x2db'];

            $pic2x2DBExt = explode('.', $db2x2);//pinaghiwalay yung filename na nasa db and yung ext

            $pic2x2FileName = "$pic2x2DBExt[0].$pic2x2DBExt[1]"; 

            $pic2x2Ext = explode('.', $pic2x2Name);//para mapaghiwalay yung file name and yung extension
            $pic2x2ActualExt = strtolower(end($pic2x2Ext));

            $allowedImg = array('jpg', 'jpeg', 'png');

            if(in_array($pic2x2ActualExt, $allowedImg)){
                    
                if($pic2x2Error === 0){

                    if($pic2x2Size <= 5000000){

                        $pic2x2NameNew = $pic2x2FileName.".".$pic2x2ActualExt; 
                        $pic2x2Destination = 'guard2x2/'.$pic2x2NameNew; //name ng folder na paglalagyan ng file natin
                        move_uploaded_file($pic2x2TmpName, $pic2x2Destination);
                        
                        $changeEmail = "UPDATE accounts SET email = '$guardEmail' WHERE account_id = '$accountID'";
                        if(mysqli_query($connection, $changeEmail)){
                            mysqli_query($connection, "UPDATE guard_info SET firstName = '$firstName', middleName = '$middleName', lastName = '$lastName', suffixName = '$suffixName', gender = '$gender', houseNumber = '$houseNumber', street = '$street', barangay = '$barangay', city = '$city', province = '$province', pic2x2 = '$pic2x2NameNew' WHERE account_id = '$accountID'");
                        
                            echo 
                                "<script>
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Edit Information Successfully'
                                    }).then(function() {
                                        window.location = 'addGuard.php';
                                    });
                                </script>";
                        }
                       
                    }
                    else{
                        echo 
                        "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'The image you input is too large!',
                                text: 'The image must be 5MB below'
                            })
                        </script>";
                    }
                }
                else{
                    echo 
                    "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'There was an error uploading your image!'
                        
                        })
                    </script>";
                }
            }
            else {  
                echo 
                "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'You cannot upload this file type!'
                    })
                </script>";
            }
            
        }
        else{
            $changeEmail = "UPDATE accounts SET email = '$guardEmail' WHERE account_id = '$accountID'";
            if(mysqli_query($connection, $changeEmail)){
            mysqli_query($connection, "UPDATE guard_info SET firstName = '$firstName', middleName = '$middleName', lastName = '$lastName', suffixName = '$suffixName', gender = '$gender', houseNumber = '$houseNumber', street = '$street', barangay = '$barangay', city = '$city', province = '$province' WHERE account_id = '$accountID'");
            echo 
                "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Edit Information Successfully'
                    }).then(function() {
                        window.location = 'addGuard.php';
                    });
                </script>";
            }
        }


        



    }
    elseif(isset($_POST['deleteGuard'])){
        $accountID = $_POST['accountID'];

        $sqlDeleteGuard = "DELETE FROM accounts WHERE account_id = '$accountID'";
        if(mysqli_query($connection, $sqlDeleteGuard)){
            echo 
                "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Delete Success'
                    }).then(function() {
                        window.location = 'addGuard.php';
                    });
                </script>";
        }
    }
    elseif(isset($_POST['newGuardQR'])){

        $accountID = $_POST['accountID'];

        $getQRName = mysqli_query($connection, "SELECT qrName FROM account_qr WHERE account_id = '$accountID'");
        if(mysqli_num_rows($getQRName) > 0){
            $accountQRName = mysqli_fetch_array($getQRName);

            $getEmail = mysqli_query($connection, "SELECT email FROM accounts WHERE account_id = '$accountID'");
            $email = mysqli_fetch_array($getEmail);

            $accountQRDirectory = "guardQR/";
            $qrName = $accountQRName[0];
            $codeContents = "$accountID***$email[0]";

            //start ENCRYPTION OF QRCODE DATA
            $key = openssl_random_pseudo_bytes(16); // 128-bit key for AES-128-CBC
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-128-cbc'));

            $encryptedBinary = openssl_encrypt($codeContents, 'aes-128-cbc', $key, 0, $iv);
            $encryptedQrContent = base64_encode($encryptedBinary);

            // Save QR 
            $imageUrl = 'https://chart.googleapis.com/chart?chs=500x500&cht=qr&chl='.urlencode($encryptedQrContent).'&choe=UTF-8';
            $rawImage = file_get_contents($imageUrl);
            file_put_contents($accountQRDirectory.$qrName, $rawImage);

            //this will be input sa database and kapag gusto na idecrypt yung qr ireretrieve yung sa database and use key and iv
            
            $qrName = mysqli_real_escape_string($connection, $qrName);
            $encryptedQrDB = mysqli_real_escape_string($connection, $encryptedQrContent);
            $keyQR = mysqli_real_escape_string($connection, base64_encode($key));
            $ivQR = mysqli_real_escape_string($connection, base64_encode($iv));


            //edn ENCRYPTION OF QRCODE DATA

            $updateAccountQR = "UPDATE account_qr SET qrName = '$qrName', encryptedQrContent = '$encryptedQrDB', keyQR = '$keyQR', ivQR = '$ivQR' WHERE account_id = '$accountID'";
            if(mysqli_query($connection, $updateAccountQR)){
                
                echo "<script>window.location = 'sendEmail/emailGuardAccountQR.php?newQR=yes&email=$email[0]&qrName=$qrName';</script>";

            }

        }


    }

?> 