<?php
//FOR RESTRICTING LOGGED IN USER TO ACCESS THIS PAGE
    session_start();
    
    if(isset($_SESSION['statpage'])){
        /* Functions */
        function pathTo($pageName) {
            echo "<script>window.location.href = '/pages/$pageName.php'</script>";
        }

        if ($_SESSION['statpage'] == 'invalid' || empty($_SESSION['statpage'])) {
            /* Set Default Invalid */
            $_SESSION['statpage'] = 'invalid';
        }

        if ($_SESSION['statpage'] == 'valid') {
            if($_SESSION['role'] == 'Admin'){
                pathTo('dashboard');
            }
            elseif($_SESSION['role']  == 'Guard'){
                pathTo('scanAccountQR');
            }
            elseif($_SESSION['role']  == 'Visitor' || $_SESSION['role']  == 'Guest'){
                pathTo('userProfile');
            }
        } 
    }
    //FOR RESTRICTING LOGGED IN USER TO ACCESS THIS PAGE
?>

<!DOCTYPE html>
<html>
<head>
    <title>VYSMO</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/051c506296.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="registration.css">
    <script src="script.js"></script>
    <script src="../nodevtool.js"></script>
    <!-- Favicon -->
    <link href="../images/vysmoprintlogo.png" rel="icon">
    <style>
            #togglePassword,#togglePassword1{
            margin-left: -20px;
            margin-right: 5px;
            margin-top: 12px;
        }
    </style>
</head>
<body class="regBody" >

    <div class="container" >
        <div class="items">
            <div class="logo">
                <img src="../images/logo.png" id="logo" alt="Logo" />
            </div>
            <h2>VYSMO Account Creation Form</h2>
            <form id="visitorInfo" action="register.php" onsubmit="return validateSubmit();" method="POST" enctype="multipart/form-data">
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" placeholder="Email" required><br>

                <label for="password">Password:</label>
                <label for="confirmpassword" id="confirmPasswordLbl">Confirm Password:</label><br>

                <div id="nameContainer">                
                    <input type="password" id="password" name="password" placeholder="Password" oninput="checkPasswordMatch();" minlength="8" required>
                    <i onclick="showHidePass()" class="bi bi-eye iconEye" id="togglePassword"></i><br>

                    <input type="password" style="margin-left: 10px;" id="confirmPassword" name="confirmpassword" placeholder="Confirm Password" oninput="checkPasswordMatch();" minlength="8" required>
                    <i onclick="showHidePass1()" class="bi bi-eye iconEye1" id="togglePassword1"></i><br>

                </div>
                <p id="passwordMatchMessage" style="color:red; text-align:center; margin-top:0px; padding-top:0;"></p>
                <script>
                        function showHidePass() {
                            var passwordInput = document.getElementById("password");
                            var iconEye = document.getElementById("togglePassword");

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
                    <script>
                        function showHidePass1() {
                            var passwordInput = document.getElementById("confirmPassword");
                            var iconEye = document.getElementById("togglePassword1");

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
                <script>
                    function checkPasswordMatch() {
                        var password = document.getElementById("password").value;
                        var confirmPassword = document.getElementById("confirmPassword").value;
                        var passwordMatchMessage = document.getElementById("passwordMatchMessage");

                        if (password === confirmPassword) {
                            passwordMatchMessage.textContent = "";
                        } else {
                            passwordMatchMessage.textContent = "Passwords do not match";
                        }
                    }

                </script>

                <label for="fName">Name:</label><br>
                <div id="nameContainer">

                <input type="text" id="fName" name="firstName" placeholder="First Name" required><br>
                <input type="text" id="mInitial" name="middleInitial" placeholder="Middle Initial" maxlength="4" ><br>
                <input type="text" id="lName" name="lastName" placeholder="Last Name" required><br>
                </div>

                <select id="suffix" name="suffixName" >
                <option value="" selected>--suffix name--</option>
                    <option value="Jr">Jr.</option>
                    <option value="II">II</option>
                    <option value="III">III</option>
                    <option value="Sr">Sr.</option>
                </select><br>
                <label for="gender">Gender:</label>
                <label for="mobileNum" id="mobileNumLbl" style="margin-left: 44%;">Mobile Number:</label>
                <div id="nameContainer">
                    
                    <fieldset id="gender">
                        <input type="radio" id="male" name="gender" value="Male" required>
                        <label for="male" id="malelbl">Male</label>
                        <input type="radio" id="female" name="gender" value="Female" required>
                        <label for="female" id="femalelbl">Female</label>
                    </fieldset>

                    
                    <input type="tel" id="mobileNum" style="margin-left: 1%;" name="mobileNumber" placeholder="Mobile Number" pattern="[0-9]{11}" required><br>
                </div>
                <label>Address</label><br>
                <div id="addressContainer">
                    <input type="number" id="houseNum" name="houseNumber" placeholder="#" min="1" required>
                    <input type="text" id="st" name="street" placeholder="Street" required>
                    <input type="text" id="brgy" name="barangay" placeholder="Brgy." required>
                    <input type="text" id="city" name="city" placeholder="City" required>
                    <input type="text" id="province" name="province" placeholder="Province" required>
                </div>

                <label for="nationality">Nationality:</label>
                <label for="typeOfID" id="typeOfIDLbl" style="margin-left: 41%;">Type of ID:</label>
                <div id="nameContainer">
                
                <select id="nationality" name="nationality" required>
                    <option disabled selected>-- select --</option>
                    <option value="Afghan">Afghan</option>
                    <option value="Albanian">Albanian</option>
                    <option value="Algerian">Algerian</option>
                    <option value="American">American</option>
                    <option value="Andorran">Andorran</option>
                    <option value="Angolan">Angolan</option>
                    <option value="Antiguans">Antiguans</option>
                    <option value="Argentinean">Argentinean</option>
                    <option value="Armenian">Armenian</option>
                    <option value="Australian">Australian</option>
                    <option value="Austrian">Austrian</option>
                    <option value="Azerbaijani">Azerbaijani</option>
                    <option value="Bahamian">Bahamian</option>
                    <option value="Bahraini">Bahraini</option>
                    <option value="Bangladeshi">Bangladeshi</option>
                    <option value="Barbadian">Barbadian</option>
                    <option value="Barbudans">Barbudans</option>
                    <option value="Batswana">Batswana</option>
                    <option value="Belarusian">Belarusian</option>
                    <option value="Belgian">Belgian</option>
                    <option value="Belizean">Belizean</option>
                    <option value="Beninese">Beninese</option>
                    <option value="Bhutanese">Bhutanese</option>
                    <option value="Bolivian">Bolivian</option>
                    <option value="Bosnian">Bosnian</option>
                    <option value="Brazilian">Brazilian</option>
                    <option value="British">British</option>
                    <option value="Bruneian">Bruneian</option>
                    <option value="Bulgarian">Bulgarian</option>
                    <option value="Burkinabe">Burkinabe</option>
                    <option value="Burmese">Burmese</option>
                    <option value="Burundian">Burundian</option>
                    <option value="Cambodian">Cambodian</option>
                    <option value="Cameroonian">Cameroonian</option>
                    <option value="Canadian">Canadian</option>
                    <option value="Cape verdean">Cape Verdean</option>
                    <option value="Central african">Central African</option>
                    <option value="Chadian">Chadian</option>
                    <option value="Chilean">Chilean</option>
                    <option value="Chinese">Chinese</option>
                    <option value="Colombian">Colombian</option>
                    <option value="Comoran">Comoran</option>
                    <option value="Congolese">Congolese</option>
                    <option value="Costa rican">Costa Rican</option>
                    <option value="Croatian">Croatian</option>
                    <option value="Cuban">Cuban</option>
                    <option value="Cypriot">Cypriot</option>
                    <option value="Czech">Czech</option>
                    <option value="Danish">Danish</option>
                    <option value="Djibouti">Djibouti</option>
                    <option value="Dominican">Dominican</option>
                    <option value="Dutch">Dutch</option>
                    <option value="East timorese">East Timorese</option>
                    <option value="Ecuadorean">Ecuadorean</option>
                    <option value="Egyptian">Egyptian</option>
                    <option value="Emirian">Emirian</option>
                    <option value="Equatorial guinean">Equatorial Guinean</option>
                    <option value="Eritrean">Eritrean</option>
                    <option value="Estonian">Estonian</option>
                    <option value="Ethiopian">Ethiopian</option>
                    <option value="Fijian">Fijian</option>
                    <option value="Filipino">Filipino</option>
                    <option value="Finnish">Finnish</option>
                    <option value="French">French</option>
                    <option value="Gabonese">Gabonese</option>
                    <option value="Gambian">Gambian</option>
                    <option value="Georgian">Georgian</option>
                    <option value="German">German</option>
                    <option value="Ghanaian">Ghanaian</option>
                    <option value="Greek">Greek</option>
                    <option value="Grenadian">Grenadian</option>
                    <option value="Guatemalan">Guatemalan</option>
                    <option value="Guinea-bissauan">Guinea-Bissauan</option>
                    <option value="Guinean">Guinean</option>
                    <option value="Guyanese">Guyanese</option>
                    <option value="Haitian">Haitian</option>
                    <option value="Herzegovinian">Herzegovinian</option>
                    <option value="Honduran">Honduran</option>
                    <option value="Hungarian">Hungarian</option>
                    <option value="Icelander">Icelander</option>
                    <option value="Indian">Indian</option>
                    <option value="Indonesian">Indonesian</option>
                    <option value="Iranian">Iranian</option>
                    <option value="Iraqi">Iraqi</option>
                    <option value="Irish">Irish</option>
                    <option value="Israeli">Israeli</option>
                    <option value="Italian">Italian</option>
                    <option value="Ivorian">Ivorian</option>
                    <option value="Iamaican">Jamaican</option>
                    <option value="Japanese">Japanese</option>
                    <option value="Jordanian">Jordanian</option>
                    <option value="Kazakhstani">Kazakhstani</option>
                    <option value="Kenyan">Kenyan</option>
                    <option value="Kittian and nevisian">Kittian and Nevisian</option>
                    <option value="Kuwaiti">Kuwaiti</option>
                    <option value="Kyrgyz">Kyrgyz</option>
                    <option value="Laotian">Laotian</option>
                    <option value="Latvian">Latvian</option>
                    <option value="Lebanese">Lebanese</option>
                    <option value="Liberian">Liberian</option>
                    <option value="Libyan">Libyan</option>
                    <option value="Liechtensteiner">Liechtensteiner</option>
                    <option value="Lithuanian">Lithuanian</option>
                    <option value="Luxembourger">Luxembourger</option>
                    <option value="Macedonian">Macedonian</option>
                    <option value="Malagasy">Malagasy</option>
                    <option value="Malawian">Malawian</option>
                    <option value="Malaysian">Malaysian</option>
                    <option value="Maldivan">Maldivan</option>
                    <option value="Malian">Malian</option>
                    <option value="Maltese">Maltese</option>
                    <option value="Marshallese">Marshallese</option>
                    <option value="Mauritanian">Mauritanian</option>
                    <option value="Mauritian">Mauritian</option>
                    <option value="Mexican">Mexican</option>
                    <option value="Micronesian">Micronesian</option>
                    <option value="Moldovan">Moldovan</option>
                    <option value="Monacan">Monacan</option>
                    <option value="Mongolian">Mongolian</option>
                    <option value="Moroccan">Moroccan</option>
                    <option value="Mosotho">Mosotho</option>
                    <option value="Motswana">Motswana</option>
                    <option value="Mozambican">Mozambican</option>
                    <option value="Namibian">Namibian</option>
                    <option value="Nauruan">Nauruan</option>
                    <option value="Nepalese">Nepalese</option>
                    <option value="New zealander">New Zealander</option>
                    <option value="Ni-vanuatu">Ni-Vanuatu</option>
                    <option value="Nicaraguan">Nicaraguan</option>
                    <option value="Nigerien">Nigerien</option>
                    <option value="North korean">North Korean</option>
                    <option value="Northern irish">Northern Irish</option>
                    <option value="Norwegian">Norwegian</option>
                    <option value="Omani">Omani</option>
                    <option value="Pakistani">Pakistani</option>
                    <option value="Palauan">Palauan</option>
                    <option value="Panamanian">Panamanian</option>
                    <option value="Papua new guinean">Papua New Guinean</option>
                    <option value="Paraguayan">Paraguayan</option>
                    <option value="Peruvian">Peruvian</option>
                    <option value="Polish">Polish</option>
                    <option value="Portuguese">Portuguese</option>
                    <option value="Qatari">Qatari</option>
                    <option value="Romanian">Romanian</option>
                    <option value="Russian">Russian</option>
                    <option value="Rwandan">Rwandan</option>
                    <option value="Saint lucian">Saint Lucian</option>
                    <option value="Salvadoran">Salvadoran</option>
                    <option value="Samoan">Samoan</option>
                    <option value="San marinese">San Marinese</option>
                    <option value="Sao tomean">Sao Tomean</option>
                    <option value="Saudi">Saudi</option>
                    <option value="Scottish">Scottish</option>
                    <option value="Senegalese">Senegalese</option>
                    <option value="Serbian">Serbian</option>
                    <option value="Seychellois">Seychellois</option>
                    <option value="Sierra leonean">Sierra Leonean</option>
                    <option value="Singaporean">Singaporean</option>
                    <option value="Slovakian">Slovakian</option>
                    <option value="Slovenian">Slovenian</option>
                    <option value="Solomon islander">Solomon Islander</option>
                    <option value="Somali">Somali</option>
                    <option value="South african">South African</option>
                    <option value="South korean">South Korean</option>
                    <option value="Spanish">Spanish</option>
                    <option value="Sri lankan">Sri Lankan</option>
                    <option value="Sudanese">Sudanese</option>
                    <option value="Surinamer">Surinamer</option>
                    <option value="Swazi">Swazi</option>
                    <option value="Swedish">Swedish</option>
                    <option value="Swiss">Swiss</option>
                    <option value="Syrian">Syrian</option>
                    <option value="Taiwanese">Taiwanese</option>
                    <option value="Tajik">Tajik</option>
                    <option value="Tanzanian">Tanzanian</option>
                    <option value="Thai">Thai</option>
                    <option value="Togolese">Togolese</option>
                    <option value="Tongan">Tongan</option>
                    <option value="Trinidadian or Tobagonian">Trinidadian or Tobagonian</option>
                    <option value="Tunisian">Tunisian</option>
                    <option value="Turkish">Turkish</option>
                    <option value="Tuvaluan">Tuvaluan</option>
                    <option value="Ugandan">Ugandan</option>
                    <option value="Ukrainian">Ukrainian</option>
                    <option value="Uruguayan">Uruguayan</option>
                    <option value="Uzbekistani">Uzbekistani</option>
                    <option value="Venezuelan">Venezuelan</option>
                    <option value="Vietnamese">Vietnamese</option>
                    <option value="Welsh">Welsh</option>
                    <option value="Yemenite">Yemenite</option>
                    <option value="Zambian">Zambian</option>
                    <option value="Zimbabwean">Zimbabwean</option>
                </select>
   
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
                            portraitID.style.display = "block";
                            resetFrontImage();
                            resetBackImage();
                        } else{
                            landscapeID.style.display = "block";
                            portraitID.style.display = "none"; 
                            resetFrontImage1();
                            resetBackImage1();
                        }
                    }
                </script>

                
                <select id="typeOfID" name="typeOfID" onchange="showtypeOfID()" style="margin-left: 1%;" required>
                    <option disabled selected>-- select --</option>
                    <option value="National ID">National ID</option>
                    <option value="Drivers License">Driver's License</option>
                    <option value="SSS ID">SSS ID</option>
                    <option value="Postal ID">Postal ID</option>
                    <option value="PRC ID">PRC ID</option>
                    <option value="UMID">UMID</option>
                    <option value="Voters ID">Voter's ID</option>
                    <option value="Passport ID">Passport ID</option>
                    <option value="PAGIBIG ID">PAGIBIG ID</option>
                    <option value="School ID">School ID</option>
                </select>
                </div>

                <div id="pictures">
                    <label for="selfieID"> Selfie with ID <br>(Photo should be landscape)</label><br>
                    <img id="selfie" src="../images/selfiewithID.png" data-original="../images/selfiewithID.png"  alt="sample" ><br>
                    <label id="custom-file-upload">
                    <input type="file" name="selfieID" id="selfieID"  alt="Submit"  onchange="selfiePreview()" hidden accept="image/jpg, image/jpeg, image/png" >Upload</label>
                    <button id ="clear" onclick="resetSelfie()">Clear</button><br><br>


                    <div id="landscapeID" style="display: block;">
                        <label for="frontID"> Front photo of ID <br>(Photo of ID should be landscape)</label><br>
                        <img id="validFrontID" src="../images/frontLandscapeID.png" data-original="../images/frontLandscapeID.png"  alt="sample"  ><br>
                        <label id="custom-file-upload">
                        <input type="file" name="frontLandscape" id="frontID"  alt="Submit"  onchange="frontPreviewImage()" hidden accept="image/jpg, image/jpeg, image/png">Upload</label>
                        <button id ="clear" onclick="resetFrontImage()">Clear</button><br><br>
                
                        <label for="backID"> Back photo of ID <br>(Photo of ID should be landscape)</label><br>
                        <img id="validBackID" src="../images/backLandscapeID.png" data-original="../images/backLandscapeID.png"  alt="sample"  ><br>
                        <label id="custom-file-upload">
                        <input type="file" name="backLandscape" id="backID"  alt="Submit" onchange="backPreviewImage()" hidden accept="image/jpg, image/jpeg, image/png">Upload</label>
                        <button id ="clear" onclick="resetBackImage()">Clear</button><br>
                    </div>

                    <div id="portraitID" style="display: none;">
                        <label for="frontIDPortrait"> Front photo of ID <br>(Photo of ID should be portrait)</label><br>
                        <img id="validFrontIDportrait" src="../images/frontPortraitID.png" data-original="../images/frontPortraitID.png"  alt="sample"  ><br>
                        <label id="custom-file-upload">
                        <input type="file" name="frontPortrait" id="frontIDPortrait"  alt="Submit"  onchange="frontPreviewImage1()" hidden accept="image/jpg, image/jpeg, image/png">Upload</label>
                        <button id ="clear" onclick="resetFrontImage1()">Clear</button><br><br>
                    
                        <label for="backID"> Back photo of ID <br>(Photo of ID should be portrait)</label><br>
                        <img id="validBackIDportrait" src="../images/backPortraitID.png" data-original="../images/backPortraitID.png"  alt="sample"  ><br>
                        <label id="custom-file-upload">
                        <input type="file" name="backPortrait" id="backIDPortrait"  alt="Submit" onchange="backPreviewImage1()" hidden accept="image/jpg, image/jpeg, image/png" >Upload</label>
                        <button id ="clear" onclick="resetBackImage1()">Clear</button><br>
                    </div>
                </div>
                <br>
                <div id="submitbtn">
                    <input type="submit" id="submitAccount" name="submitAccount" value="Submit">
                </div>
                
                <script>//validate the submission
                    function validateSubmit() {
                        var password = document.getElementById("password").value;
                        var confirmPassword = document.getElementById("confirmPassword").value;
                        

                        if (password !== confirmPassword) {
                            // Show SweetAlert alert for password mismatch
                            Swal.fire({
                                icon: 'error',
                                title: 'Passwords do not match',
                                text: 'Please make sure your passwords match!'
                            });
                            return false; // Prevent form submission
                        }

                        var typeofid = document.getElementById("typeOfID").value;

                        if(typeofid == "School ID"){
                           
                            var frontIDPortrait = document.getElementById("frontIDPortrait");
                            var backIDPortrait = document.getElementById("backIDPortrait");

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
                            var frontIDLandscape = document.getElementById("frontID");
                            var backIDLandscape = document.getElementById("backID");

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

                        var selfieID = document.getElementById("selfieID");
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
                
            </form>
        </div>    
    </div><br>



</body>
</html>


<?php
    use PHPMailer\PHPMailer\PHPMailer; 
    use PHPMailer\PHPMailer\SMTP; 
    use PHPMailer\PHPMailer\Exception; 
    include('../database.php');

    if(isset($_POST['submitAccount'])){
        $email = mysqli_real_escape_string($connection, $_POST['email']);

        if(filter_var($email,FILTER_VALIDATE_EMAIL)){
            //check if email exist
            $checkEmail = mysqli_query($connection, "SELECT * FROM accounts WHERE email='{$email}'");

            if(mysqli_num_rows($checkEmail) > 0){
            //email exist
                echo 
                    "<script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'This email already exist!',
                            text: 'Please login your account or reset password.'
                        })
                    </script>";
            }
            else{
                //email does not exist
                
                $role = "Visitor";

                $password = mysqli_real_escape_string($connection, $_POST['password']);
                $salt = "45a4748f715f501377bf2c6de1b259b1"; //visitor monitoring system (md5)
                $hashpassword = md5($salt.$password);

                $firstName = mysqli_real_escape_string($connection, $_POST['firstName']);
                $middleInitial = mysqli_real_escape_string($connection, $_POST['middleInitial']);
                $lastName = mysqli_real_escape_string($connection, $_POST['lastName']);
                $suffixName = mysqli_real_escape_string($connection, $_POST['suffixName']);
                $gender = mysqli_real_escape_string($connection, $_POST['gender']);
                $mobileNumber = mysqli_real_escape_string($connection, $_POST['mobileNumber']);
                $houseNumber = mysqli_real_escape_string($connection, $_POST['houseNumber']);
                $street = mysqli_real_escape_string($connection, $_POST['street']);
                $barangay = mysqli_real_escape_string($connection, $_POST['barangay']);
                $city = mysqli_real_escape_string($connection, $_POST['city']);
                $province = mysqli_real_escape_string($connection, $_POST['province']);
                $nationality = mysqli_real_escape_string($connection, $_POST['nationality']);

                //eto una
                $typeOfID = $_POST['typeOfID'];
                if($typeOfID == "School ID"){
                    $frontID = $_FILES['frontPortrait'];
                    $backID = $_FILES['backPortrait'];
                }
                else{
                    $frontID = $_FILES['frontLandscape'];
                    $backID = $_FILES['backLandscape'];
                }
                //selfie withID
                $selfieWithID = $_FILES['selfieID'];

                $selfieName = $selfieWithID['name'];
                $selfieTmpName = $selfieWithID['tmp_name'];
                $selfieSize = $selfieWithID['size'];
                $selfieError = $selfieWithID['error'];
                $selfieType = $selfieWithID['type'];

                $selfieExt = explode('.', $selfieName);//para mapaghiwalay yung file name and yung extension
                $selfieActualExt = strtolower(end($selfieExt));

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

                $allowedImg = array('jpg', 'jpeg', 'png');// mga iaallow nating filetype. pwede rin pdf, docx, etc.

                if(in_array($selfieActualExt, $allowedImg) and in_array($frontActualExt, $allowedImg) and in_array($backActualExt, $allowedImg)){
                    
                    if($selfieError === 0 and $frontError === 0 and $backError === 0){

                        if($selfieSize <= 10000000 and $frontSize <= 10000000 and $backSize <= 10000000 ){
                            //send account qr code to email

                            date_default_timezone_set("Asia/Manila");
                            $dateTime = date('Y-m-d h:i:s A', time());

                            $sql = "INSERT INTO accounts (email, password, datetime_created, role) VALUE ('$email', '$hashpassword','$dateTime', '$role')";
                            
                            if(mysqli_query($connection, $sql)){
                                $accountID = mysqli_insert_id($connection); //ACCOUNT ID

                                //SELFIE WITH ID
                                $selfieNameNew = uniqid('', true).".".$selfieActualExt;
                                $selfieDestination = 'selfie/'.$selfieNameNew; //name ng folder na paglalagyan ng file natin
                                move_uploaded_file($selfieTmpName, $selfieDestination);

                                //FRONT ID
                                $frontNameNew = uniqid('', true).".".$frontActualExt;
                                $frontDestination = 'frontID/'.$frontNameNew; //name ng folder na paglalagyan ng file natin
                                move_uploaded_file($frontTmpName, $frontDestination);

                                //BACK ID
                                $backNameNew = uniqid('', true).".".$backActualExt;
                                $backDestination = 'backID/'.$backNameNew; //name ng folder na paglalagyan ng file natin
                                move_uploaded_file($backTmpName, $backDestination);

                                $accountQRDirectory = "visitorQR/"; 
                                $qrName = uniqid('',true).'.png';
                                $codeContents = "$accountID";

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

                                //end ENCRYPTION OF QRCODE DATA


                                $insertAccountQR = "INSERT INTO account_qr (account_id, qrName, encryptedQrContent, keyQR, ivQR) VALUE ('$accountID', '$qrName', '$encryptedQrDB', '$keyQR', '$ivQR')";

                                $sqlVisitorInfo = "INSERT INTO visitor_info (account_id, firstName, middleInitial, lastName, suffixName, gender, mobileNumber, houseNumber, street, barangay, city, province, nationality, typeOfID, selfieWithID, frontID, backID) VALUE ('$accountID', '$firstName', '$middleInitial', '$lastName', '$suffixName', '$gender', '$mobileNumber', '$houseNumber', '$street', '$barangay', '$city', '$province', '$nationality', '$typeOfID', '$selfieNameNew', '$frontNameNew', '$backNameNew')";
                                if(mysqli_query($connection, $insertAccountQR) && mysqli_query($connection, $sqlVisitorInfo)){
                                    
                                    echo "<script>window.location = 'sendEmail/emailAccountQR.php?email=$email&qrName=$qrName&role=$role';</script>";
                                    
    
                                }

                            }

                            
                        }
                        else{
                            echo 
                            "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'The image you input is too large!',
                                    text: 'The image must be 10MB below'
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
        }
    }
?>