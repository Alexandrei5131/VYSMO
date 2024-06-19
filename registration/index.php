<?php
  session_start();

  if(isset($_SESSION['statpage'])){
    /* Functions */
    function pathTo($pageName) {
        echo "<script>window.location.href = '/vysmo/pages/$pageName.php'</script>";
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
  



  if(isset($_GET['fromeval']) && $_GET['fromeval'] == 'yes'){
    $eval = $_GET['fromeval'];

    //echo $eval;
  }
?>

<!DOCTYPE html>
<html>

<head>
  <title>VYSMO</title>
  <!-- Favicon -->
  <link href="../images/vysmoprintlogo.png" rel="icon">
  <script src="../nodevtool.js"></script>
  <link rel="stylesheet" href="registration.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  
  <!-- Created by:Alcantara,Macario,Manubay, and Pineda -->
  <style>
  .termIntro,.termOutro{
  text-align:justify;
}
.terms{
    text-align:left;
    margin-top:-90px;
}
#next{
  background-color: peachpuff;
  color: black;
}
#back{
  background-color: lightgray;
  color: black;
}
  #next,#back{
  border: none;
  padding: 10px 16px;
  text-align: center;
  text-decoration: none;
  text-transform: uppercase;
  font-size: 13px;
  -webkit-box-shadow: 0 10px 30px 0 rgba(95,186,233,0.4);
  box-shadow: 0 10px 30px 0 rgba(95,186,233,0.4);
  -webkit-border-radius: 5px 5px 5px 5px;
  border-radius: 5px 5px 5px 5px;
  -webkit-transition: all 0.3s ease-in-out;
  -moz-transition: all 0.3s ease-in-out;
  -ms-transition: all 0.3s ease-in-out;
  -o-transition: all 0.3s ease-in-out;
  transition: all 0.3s ease-in-out;
}
#next:hover{
  background-color: lightsalmon;
  color: white;
}
#back:hover {
  background-color: gray;
  color: white;
}
li{
  width: 100%;
  text-align: justify;
}

.lists{
  margin-left: -20px;
}
.noteList{
  margin-left: -20px;
}
</style>
</head>

<body id="termsBody" onload="disableSubmit()">

<div class="wrapper fadeInDown">
  <div id="formContent" >
   
    <!-- Logo -->
    <div class="fadeIn first">
      <img src="../images/logo.png" id="logoTerms" alt="Logo" />
      <h1 class="head">NEUST-VYSMO</h1>
    </div>

    <!-- Login Form -->
      <div class="wrapper fadeInDown">
          <div class="terms">
              <div>
                  <h2 style="text-align:center;" id="termsHeader">Terms and Conditions</h2>
              </div>
              <div>
                  <p class="termIntro">Welcome to VYSMO: Visitor Monitoring System for 
                    Nueva Ecija University of Science and Technology (NEUST) - Sumacab Campus. 
                    Users must strictly abide by the acceptable and prohibited uses below:
                  </p>
                    <div>
                      <ul class="lists">
                          <p class="noteList"><strong>Acceptable Uses</strong>
                              <!-- <li>The use of the system is granted to the NEUST-Admin, Guard and to the future visitors of the NEUST-Sumacab Campus. </li> -->
                              <li>The system will collect personal and visitation information of the Visitors. </li>
                              <li>The guard on duty shall not allow unauthorized person to use their account.</li>
                              <li id="last">The system is used for storing and updating the university visitor's records. </li>
                          </p>
                      </ul>
                    </div>
                    <div>
                      <ul class="lists">
                        <p class="noteList"><strong>Prohibited Uses</strong>
                            <li>Infringement on individual's right to privacy.</li>
                            <li>Violation of usage restrictions required by the System.</li>
                            <li>Violation of NEUST's policies, rules, and regulation.</li>
                            <li> Unauthorized use of other user accounts, identity or password.</li>
                            <li id="last"> Submission of visitation form with false and incorrect information.</li>
                        </p><br>
                      </ul>
                    </div>

                    <div>
                      <p class="termOutro"><strong>Note:</strong> Please be aware that the NEUST are fully committed in following the guidelines set out in the Philippine Data Privacy Act of 2012, 
                        also known as RA 10173. Rest assured that we will handle your data with the utmost confidentiality and anonymity. 
                        Any data presented will be in the form of a summary or collective combined total.
                        By accepting the University's privacy policy statement, you are entrusting your data to us, 
                        and we promise to do everything in our power to keep your information confidential.</p>
                    </div>
              </div>
          </div><br>
          <label style="font-family: Times New Roman; font-size: 18px;"> 
            <input type="checkbox" style="width: 16px; height: 16px; line-height: normal; margin-top: 10;" name="terms" id="terms" onchange="activateButton(this)">  I Agree to these Terms and Conditions
          </label><br>
          <p id="message" style="display:none; color:red; margin-top: -2%; ">Must agree to Terms and Conditions to proceed.</p>
            <?php
            
              if(isset($_GET['fromeval']) && $eval == 'yes'){
                ?>
                  <button type="button" id="next" onclick="checkAgreeFromEval()">Next <i class="bi bi-arrow-right"></i> </button>
                <?php
              }
              else{
                ?>
                
                <div id="termButton">
                  <a href="../" id="back"><i class="bi bi-arrow-left"></i> Back</a>
                  <button type="button" id="next" onclick="checkAgree()">Next <i class="bi bi-arrow-right"></i> </button>
                </div>
              <?php
              }
            
            ?>
        </div>
      </div>
    <!-- Footer -->

    
  </div>

</div>

<script>
  function checkAgreeFromEval(){
    var checkbox = document.getElementById("terms");
    var message = document.getElementById("message");
    if (checkbox.checked) {
      window.location = 'register.php';
    }
    else{
      console.log("Checkbox is not checked");
      // Show the message element
      message.style.display = "block";
    }

  }
  function checkAgree(){
      // Get the checkbox element
      var checkbox = document.getElementById("terms");

      // Get the message element
      var message = document.getElementById("message");

      // Check if the checkbox is checked
      if (checkbox.checked) {
          console.log("Checkbox is checked");
          // Hide the message element
          Swal.fire({
            title: 'Do you have an account already?',
            text: "",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No, I want to create an account',
            backdrop: 'static', // This makes the popup static
            keyboard: false,    // This disables keyboard interactions
            allowOutsideClick: false
          }).then((result) => {
            if (result.isConfirmed) {
              window.location = '../';
            }
            else{
              //another popup message
              Swal.fire({
                text: "",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#279300',
                confirmButtonText: 'Sign in as Guest',
                cancelButtonText: 'Create Visitor Account',
                backdrop: 'static', // This makes the popup static
                keyboard: false,   // This disables keyboard interactions
                allowOutsideClick: false
              }).then((result) => {

                if (result.isConfirmed) {
                  window.location = 'registerGuest.php';
                }
                else{
                  window.location = 'register.php';
                }

              });
            }
          });
      } else {
          console.log("Checkbox is not checked");
          // Show the message element
          message.style.display = "block";
      }
  }
</script>
</body>

</html>