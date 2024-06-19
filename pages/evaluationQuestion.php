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

    $evaluationID = $_GET['evaluationID'];
    $eventName = $_GET['eventName'];

    $questionList = [];
    $sqlGetQuestions = mysqli_query($connection, "SELECT * FROM evaluation_question WHERE evaluation_id = '$evaluationID'");
    $numOfQuestions = mysqli_num_rows($sqlGetQuestions);
    if($numOfQuestions > 0){

        while($row = mysqli_fetch_array($sqlGetQuestions)){
            // add each row returned into an array
            $questionList[] = $row; //EVAL_ID
        }
        /*
        echo "<pre>";
        echo print_r($questionList);
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

    <!-- Template Stylesheet -->
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
              </nav>
            <div class="container-fluid bodyContent mt-3"  >
                <div class="bg-light text-center rounded">
                    <nav class="navbar navbar-light justify-content-center visitInfo">
                        <span class="navbar-brand mb-0 h1 text-white"><?php echo $eventName; ?> Evaluation Questions</span>
                    </nav>
                    <div class="p-4 ">
                        <div class="row justify-content-between">
                            <div class="col-auto">
                                <a class="btn btn-primary" href="evaluationListAdmin.php">Back to List</a>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-secondary" id="addQuestionBtn" data-toggle="modal" data-target="#addQuestion">Add Question</button>
                            </div>                                                          
                        </div>

                        <!--Add Question-->
                        <div class="modal fade" id="addQuestion" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true"  data-backdrop="static" data-keyboard="false"> <!-- Modal for add Event-->
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header"><!--Modal Header-->
                                        <h5 class="modal-title" id="exampleModalLongTitle">Add Question</h5>
                                        <button type="button" id="xbutton" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form method="POST" action="evaluationQuestionQuery.php" >
                                        <?php echo '<input type="hidden" name="evaluationID" value="'.$evaluationID.'">';?>
                                        <?php echo '<input type="hidden" name="eventName" value="'.$eventName.'">';?>
                                        <div class="modal-body">  <!-- Modal body -->
                                            <div class="container text-center p-0" >
                                                <div class="row">                                                                        
                                                    <div class="col">
                                                        <label for="addQuestionTextBox" class="form-label">Question</label>
                                                        <input type="text" class="form-control text-center" id="addQuestionTextBox" name="question"  required>
                                                    </div>                                                                 
                                                </div>           
                                            </div><br>                                          
                                        </div><!-- Modal body -->
                                        <div class="modal-footer justify-content-center"><!-- Modal Footer -->
                                            <button type="submit" name="addQuestion" class="btn btn-primary">Add Question</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>  <!-- End of Add Question--> 
                        <script>

                            var addQuestionTextBox = document.getElementById('addQuestionTextBox');

                            var xbutton = document.getElementById('xbutton');

                            // Add an event listener to the close button
                            xbutton.addEventListener('click', function () {
                                addQuestionTextBox.value='';
                            });

                        </script>
                            
                        <div class="table-responsive" id="tableEvalQuestion" >
                            <table class="table text-start align-middle table-striped table-hover mb-0 shadow p-3 rounded">
                                <thead class="sticky-top text-center shadow p-3 mb-5 rounded" style="z-index: 2;">
                                    <tr class="text-dark">
                                        <th scope="col" class="col-1">#</th>
                                        <th scope="col">Question</th>
                                        <th scope="col" class="col-3">Action</th>
                                    </tr>
                                </thead>
                                
                                <tbody  id="tData" class="text-center">
                                <?php
                                    echo '<input type="hidden" id="numOfQuestions" value="'.$numOfQuestions.'">';
                                    for($i=0, $num=1; $i < $numOfQuestions; $i++, $num++){

                                ?>

                                    <tr>
                                        <td><?php echo $num; ?></td>
                                        <td><?php echo $questionList[$i]['question']; ?></td>
                                        <td>
                                            <button class="btn btn-primary lgScreen" id="editEventButton" data-toggle="modal" data-target="#editQuestion<?php echo $i;?>">Edit</button>
                                            <button class="btn btn-warning lgScreen" data-toggle="modal" data-target="#Delete<?php echo $i;?>">Remove</button>

                                            <button class="btn btn-primary smScreen" id="editEventButton" data-toggle="modal" data-target="#editEvent<?php echo $i;?>"><i class="bi bi-pencil"></i></button>
                                            <button class="btn btn-warning smScreen" data-toggle="modal" data-target="#Delete<?php echo $i;?>"><i class="bi bi-trash"></i></button>
                                        </td>   
                                    </tr>


                                    <!--EDIT Question-->
                                    <div class="modal fade" id="editQuestion<?php echo $i?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true"  data-backdrop="static" data-keyboard="false"> <!-- Modal for add Event-->
                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header"><!--Modal Header-->
                                                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Question</h5>
                                                </div>
                                                <form method="POST" action="evaluationQuestionQuery.php">
                                                    <?php echo '<input type="hidden" name="questionID" value="'.$questionList[$i]['questionID'].'">';?>
                                                    <?php echo '<input type="hidden" id="questionDB'.$i.'" name="questionDB" value="'.$questionList[$i]['question'].'">';?>
                                                    <?php echo '<input type="hidden" name="evaluationID" value="'.$evaluationID.'">';?>
                                                    <?php echo '<input type="hidden" name="eventName" value="'.$eventName.'">';?>
                                                    <div class="modal-body">  <!-- Modal body -->
                                                        <div class="container text-center p-0" >
                                                            <div class="row">                                                                        
                                                                <div class="col">
                                                                    <label for="question" class="form-label">Edit Question</label>
                                                                    <input type="text" class="form-control text-center" id="editQuestionTxt<?php echo $i; ?>" name="question" value="<?php echo $questionList[$i]['question']; ?>" required>
                                                                </div>                                                                 
                                                            </div>           
                                                        </div><br>                                          
                                                    </div><!-- Modal body -->
                                                    <div class="modal-footer justify-content-center"><!-- Modal Footer -->
                                                        <button type="submit" name="editQuestion" class="btn btn-primary">Edit Question</button>
                                                        <button type="button" id="editCancel<?php echo $i; ?>" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>  <!-- End of Edit Question--> 
                                        
                                    <script>
                                        var numOfQuestions = document.getElementById('numOfQuestions');
                                        //db data. para kapag nicancel nakaset pa rin sa default yung inputs
                                        
                                        //EDIT LIST
                                        for (var i = 0; i < numOfQuestions.value; i++) {
                                            (function (index) {
                                            
                                                var editCancelElement = "editCancel" + index;
                                                var questionDBElement = "questionDB" + index;
                                                var editQuestionlElement = "editQuestionTxt" + index;                                    

                                                // KAPAG NAGCANCEL SA MODAL BACK TO RESET YUNG INPUT NA NANDON
                                                document.getElementById(editCancelElement).addEventListener('click', function () {
                                                    //question textbox
                                                    document.getElementById(editQuestionlElement).value = document.getElementById(questionDBElement).value;
                                                });


                                            })(i);
                                        }

                                        
                                    </script>


                                    <!--Delete Button-->
                                    <div class="modal fade" id="Delete<?php echo $i;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true"  data-backdrop="static" data-keyboard="false"> <!-- Modal for add Event-->
                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                            <div class="modal-content">       
                                            <form method="POST" action="evaluationQuestionQuery.php">          
                                                <?php echo '<input type="hidden" name="questionID" value="'.$questionList[$i]['questionID'].'">';?>
                                                <?php echo '<input type="hidden" name="evaluationID" value="'.$evaluationID.'">';?>
                                                <?php echo '<input type="hidden" name="eventName" value="'.$eventName.'">';?>                                   
                                                <div class="modal-body">
                                                    <p id="deleteBody"> Are you sure you want to Remove the Question?</p>
                                                    <button type="submit" name="deleteQuestion" class="btn btn-warning">Remove</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                                </div>
                                            </form>
                                            </div>
                                        </div>
                                    </div>  <!-- End of Delete Button-->                     
                                    <!--End of Modal-->
                                <?php
                                    }
                                ?>
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

