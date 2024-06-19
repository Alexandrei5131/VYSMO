<?php
    include "../../database.php";

    session_start();
    if(isset($_SESSION['statpage'])){
        if ($_SESSION['statpage'] == 'invalid' || empty($_SESSION['statpage'])) {
            /* Set status to invalid */
            $_SESSION['statpage'] = 'invalid';
    
            unset($_SESSION['accountID']);
            unset($_SESSION['email']);
            unset($_SESSION['role']);
            unset($_SESSION['accountQR']);
            
            echo "<script>window.location.href = '../../'</script>";
    
        }
        else{//meaning valid
            //kapag nakalogged in na then nagtry iredirect sa ibang pages na hindi naman nila role
            if($_SESSION['role']  == 'Guard'){
                echo "<script>window.location.href = '../scanAccountQR.php'</script>";
            }
            elseif($_SESSION['role']  == 'Visitor' || $_SESSION['role']  == 'Guest'){
                echo "<script>window.location.href = '../userProfile.php'</script>";
            }
            //end
        }
    }

    

    $encdata = $_GET['encdata'];

    $decodedData = base64_decode($encdata);

    $destinationData = explode('***', $decodedData);//[0][1][2]

    $destinationID = $destinationData[0];
    $destination = $destinationData[1];
    $destinationName = $destinationData[2];

    //para overwrite nalang sa destinationqr
    $checkBldgQR = mysqli_query($connection, "SELECT bldgQR FROM destination_list WHERE destination_id = '$destinationID'"); 
    if(mysqli_num_rows($checkBldgQR) > 0){
        $bldgQRName = mysqli_fetch_array($checkBldgQR);

        if(empty($bldgQRName[0])){//new generated qr
            $qrName = uniqid('',true).'.png';
        }
        else{
            $qrName = $bldgQRName[0];
        }

    }

    $alpha   = str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZ', 5));//get randomly 5 letters, can be repeated
    $numeric = str_shuffle(str_repeat('0123456789', 5));//get randomly 5 numbers, can be repeated
    $combinedAlphaNumeric = substr($alpha, 0, 5) . substr($numeric, 0, 5); //pagsamahin
    $shuffleKey = str_shuffle($combinedAlphaNumeric);
    $key = md5($shuffleKey);
    
    $updateDestinationKey = "UPDATE destination_list SET bldgQR = '$qrName', keyScan='$key' WHERE destination_id = '$destinationID'";

    if(mysqli_query($connection, $updateDestinationKey)){
        
        $buildingQRDirectory = "../destinationQR/";
    
        $codeContents = "$destinationID"."***"."$key";

        // Save QR 
        $imageUrl = 'https://chart.googleapis.com/chart?chs=500x500&cht=qr&chl='.urlencode($codeContents).'&choe=UTF-8';
        $rawImage = file_get_contents($imageUrl);
        file_put_contents($buildingQRDirectory.$qrName, $rawImage);

    }

   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Building QR Code Printing</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Include jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
     <!-- Include html2canvas -->
     <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>


    <!--SWEET ALERT 2-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
    
    
    <!-- Favicon -->
    <link href="../../images/vysmoprintlogo.png" rel="icon">

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

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../css/style.css" rel="stylesheet">
    <style>
        /* Define a CSS class for the printable area */
        .printable {
            width: 8.5in;
            height: 11in;
            border: 1px solid #000; /* Add a border to simulate the letter size */
        }


        /* Hide everything outside of the .printable div when printing */
        @page {
            size: auto;   /* auto is the initial value */
            margin: -4.3mm;  /* this affects the margin in the printer settings */
        }
        @media print {
            #footerBldg{
            margin-top: 130px !important;
            }
        }
        #footerBldg{
            margin-top: 120px;
            margin-left: 0;
            margin-right: 0;
            background-color: lightblue;
            font-size: 12px;
            font-family: 'Courier New', Courier, monospace;
            color: black;

        }

    
    </style>
</head>
<body class="d-flex justify-content-center pb-3" style="background-color: #cce6ff;">
    <div class="position-relative">
        <div class="contentPrintQR">
            <div class="container-fluid">
                <div class="row d-flex justify-content-center mt-3">
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary print-button" onclick="printContent()">Print QR Code</button>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-secondary pdf-button" onclick="saveAsPDF()">Generate PDF</button>
                    </div> 
                </div>
            
                <div class="printable shadow-lg " id="pdfGenerate">
                    <div class="row justify-content-center mb-5 pb-4 px-5 pt-3 mx-0 border border-dark border-start-0 border-end-0 border-top-0" id="header">
                        
                        <div class="col-1 p-0 logoQRBorder   align-middle">
                            <img src="../../images/logo.png" alt="" class="logoQR">
                        </div>

                        <div class="col ">
                            <div class="text-center">
                                <span class="headerTitle">Republic of the Philippines</span>
                            </div>
                            <div class="text-center">
                                <h5 class="headerTitle">Nueva Ecija University of Science and Technology</h5>
                            </div>
                            <div class="text-center">
                                <span class="headerTitle">Cabanatuan City, Nueva Ecija</span>
                            </div>
                        </div>
                        <div class="col-1 vysLogoBorder  p-0">
                            <img src="../../images/vysmoprintlogo.png" class="vysLogo" alt="" id="">
                        </div>
                        
                    </div>

                    <div id="content" class="px-3">
                        <!-- Your content inside the printable area -->
                        
                        <div class="row">
                            <h1 class="text-center"><?php echo $destination;?></h1>
                        </div>
                        <div class="row">
                            <h1 class="text-center"> Building QR Code</h1>
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            <img class="qrPrint border border-dark" src="../destinationQR/<?php echo $qrName; ?>" alt='QR Code'>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-auto">
                                <span class="text-center p-2 text-white border border-dark bg-dark scanMeLabel"><i class="bi bi-qr-code-scan"></i><i class="bi bi-person"></i>Visitor Scan Me to Record your Visit<i class="bi bi-person"></i><i class="bi bi-qr-code-scan"></i></span>
                            </div>
                        </div>
                    </div>


                    <div class="row border border-dark border-start-0 border-end-0 border-bottom-0" id="footerBldg">
                        <div class="col text-center"  style="background-color: white">
                            <span style="text-transform: uppercase;">This QR Code is intended exclusively for individuals who are visiting the '<i><?php echo $destination;?></i>' Building</span>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>


    <script>
        window.jsPDF = window.jspdf.jsPDF;
        //GENERATE PDF
        function saveAsPDF() {
            document.querySelector('.print-button').style.display = 'none';
            document.querySelector('.pdf-button').style.display = 'none';
            document.querySelector('.printable').style.border = 'none';
            var doc = new jsPDF();

            // Get the pdfGenerate element
            var content = document.getElementById('pdfGenerate');

            // Use html2canvas to capture the element as an image
            html2canvas(content, { scale: 2}).then(function(canvas) {
                // Convert the canvas to a data URL
                var imgData = canvas.toDataURL('image/jpeg', 1.0);

                // Add the image to the PDF, covering the entire page without margins
                doc.addImage(imgData, 'JPEG', 0, 0, 210, 297); // 215.9mm x 279.4mm is equivalent to letter size in mm

                // Save the PDF with a specified file name
                doc.save('<?php echo $destination;?> QR Building.pdf');
            });

            document.querySelector('.print-button').style.display = 'block';
            document.querySelector('.pdf-button').style.display = 'block';
            document.querySelector('.printable').style.border = '1px solid #000 ';
        }


        //PRINTING
        function printContent() {
            // Hide the print button
            document.querySelector('.print-button').style.display = 'none';
            document.querySelector('.pdf-button').style.display = 'none';
            document.querySelector('.printable').style.border = 'none';
            // Trigger the print dialog
            window.print();

        }

        window.onafterprint = function () {
            closeWindow();
        };

        function closeWindow() {
            document.querySelector('.print-button').style.display = 'block';
            document.querySelector('.pdf-button').style.display = 'block';
            document.querySelector('.printable').style.border = '1px solid #000 ';
        }
    </script>

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
    <script src="../js/main.js"></script>
</body>
</html>
