<?php

include('../database.php');
include('session.php');

//kapag nakalogged in na then nagtry iredirect sa ibang pages na hindi naman nila role
if ($_SESSION['role']  == 'Guard') {
    echo "<script>window.location.href = 'scanAccountQR.php'</script>";
} elseif ($_SESSION['role']  == 'Visitor' || $_SESSION['role']  == 'Guest') {
    echo "<script>window.location.href = 'userProfile.php'</script>";
}
//end

date_default_timezone_set("Asia/Manila");
$dateToday = date("Y-m-d");


$role = $_SESSION['role'];

//start sidebar infos
if ($role == 'Guard') {
    $accountEmail = $_SESSION['email'];

    $queryGInfo = "SELECT firstName, middleName, lastName, suffixName, pic2x2 FROM guardInfo WHERE email='{$accountEmail}'";
    $sqlGInfo = mysqli_query($connection, $queryGInfo);

    $array = array();

    // look through query
    while ($row = mysqli_fetch_array($sqlGInfo)) {
        // add each row returned into an array
        $array = $row;
    }
    /*
        echo "<pre>";
        echo print_r($array);
        echo "</pre>";
        */
}
//end sidebar infos


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>VYSMO</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta content="" name="description">

    <script src="js/nodevtool.js"></script>
    <!-- Favicon -->
    <link href="../images/vysmoprintlogo.png" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link type="text/css" href="css/style2.php" rel="stylesheet">
</head>

<body class="body">
    <div class=" position-relative">


        <!-- Spinner Start -->

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
            <nav class="navbar navbar-light justify-content-center">
                <div class=" justify-content-center">
                    <div class="col">
                        <a class="navbar-brand mx-0 ">
                            <h3 id="head" class="text-primary">VYSMO</h3>
                        </a>

                    </div>

                </div>
                <div class="row ms-2 mb-2">

                    <div class="row pe-0 justify-content-center">
                        <?php
                        if ($role == 'Admin') {
                        ?>
                            <img src="../images/adminprofile.png" alt="" style="width: 120px; height: 90px;">
                        <?php
                        } elseif ($role == 'Guard') {
                        ?>
                            <img class="rounded-circle" src="guard2x2/<?php echo $array['pic2x2'] ?>" alt="" style="width: 120px; height: 90px;">
                        <?php
                        }
                        ?>

                    </div>
                    <div class="row pe-0 mt-3">
                        <?php
                        if ($role == 'Admin') {
                            echo '<h5 class="mb-0 text-center">Admin</h5>';
                        } elseif ($role == 'Guard') {
                        ?>

                            <?php if (isset($array['suffixName'])) {
                                if ($array['suffixName'] == 'Jr' or $array['suffixName'] == 'Sr') {
                                    $suffix = $array['suffixName'] . '.';
                                } else {
                                    $suffix = $array['suffixName'];
                                }
                            }
                            ?>
                            <h5 class="mb-0 text-center" id="lastName"><?php if ($_SESSION['role'] == 'Guard') {
                                                                            echo $array['lastName'];
                                                                        } ?> </h5>
                            <h6 class="mb-0 text-center" id="firstName"><?php if (isset($array['firstName'])) {
                                                                            echo $array['firstName'] . ' ' . $array['middleName'] . ' ' . $suffix;
                                                                        } ?></h6>
                            <span class="text-center" style="color: #5A5A5A;"><?php echo $_SESSION['role']; ?></span>
                        <?php
                        }
                        ?>

                    </div>


                </div>
                <div class="navbar-nav w-100">
                    <?php
                    if ($_SESSION['role'] == 'Admin') {
                        echo '
                                <a href="dashboard.php" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                                <a href="addDestination.php" class="nav-item nav-link "><i class="bi bi-building"></i> Destination List</a>
                                <a href="addGuard.php" class="nav-item nav-link "><i class="bi bi-shield-lock"></i> Guard List</a>
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
            <!-- Visitors Start -->
            <nav class="navbar justify-content-center head1">
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <div class="position-relative">
                    <img class="rounded-circle guardLogo" src="../images/logo.png" alt="">
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
            <div class="row justify-content-between">
                <div class="col">
                    <div class="lgScreen"> <!--For Desktop--> <!--From Date-->
                        <div class="container-fluid">
                            <div class="row mt-2 mb-1">
                                <div class="col-auto ">
                                    <label for="fromDate" class="col-form-label labelBlend">From:</label>
                                </div>
                                <div class="col-auto">
                                    <input type="date" class="form-control" class="fromDate" id="fromDateDashboard" value="<?php echo $dateToday; ?>" max="<?php echo $dateToday; ?>" onKeyDown="disableArrowKeys(event)">
                                </div>
                                <div class="col-auto">
                                    <label for="toDate" class="col-form-label labelBlend">To:</label>
                                </div>
                                <div class="col-auto">
                                    <input type="date" class="form-control" class="toDate" id="toDateDashboard" value="<?php echo $dateToday; ?>" min="<?php echo $dateToday; ?>" max="<?php echo $dateToday; ?>" onKeyDown="disableArrowKeys(event)">
                                </div>
                            </div>
                        </div>
                    </div><!--For Desktop-->
                    <script>
                        function disableArrowKeys(event) {
                            // List of key codes for arrow keys
                            const arrowKeys = [37, 38, 39, 40];

                            if (arrowKeys.includes(event.keyCode)) {
                                event.preventDefault();
                            }
                        }

                        // Get references to the date inputs
                        const fromDateInput = document.getElementById('fromDateDashboard');
                        const toDateInput = document.getElementById('toDateDashboard');

                        // Add an event listener to the "From" date input
                        fromDateInput.addEventListener('change', function() {
                            // Get the selected date from the "From" date input
                            const selectedFromDate = new Date(this.value);

                            // Check if the selected "From" date is greater than the current value of the "To" date
                            const selectedToDate = new Date(toDateInput.value);
                            if (selectedFromDate > selectedToDate) {
                                // If "From" date is greater, set "To" date to the same date as "From" date
                                toDateInput.value = this.value;
                            }

                            // Increment the selected date by one day to set the minimum date for "To" date input
                            selectedFromDate.setDate(selectedFromDate.getDate());

                            // Set the minimum date for the "To" date input
                            toDateInput.min = selectedFromDate.toISOString().split('T')[0];
                        });
                    </script>

                    <!--For Mobile-->
                    <div class="mx-1 smScreen">
                        <div class="container-fluid">
                            <div class="row mb-1">
                                <div class="col-6">
                                    <label for="fromDate" class="col-form-label labelBlend">From:</label>
                                    <input type="date" class="form-control" id="fromDateDashboard" name="fromDate" max="<?php echo $dateToday; ?>" value="<?php echo $dateToday; ?>" onKeyDown="disableArrowKeys(event)">
                                </div>
                                <div class="col-6 ">
                                    <label for="toDate" class="col-form-label labelBlend">To:</label>
                                    <input type="date" class="form-control" id="toDateDashboard" name="toDate" min="<?php echo $dateToday; ?>" max="<?php echo $dateToday; ?>" value="<?php echo $dateToday; ?>" onKeyDown="disableArrowKeys(event)">
                                </div>
                            </div>
                        </div>
                    </div><!--For Mobile--> <!--To Date-->
                </div>

                <div class="col-auto">
                    <div class=""> <!--Real Date and Time-->
                        <div class="container-fluid">
                            <div class="row justify-content-end mt-1 mx-0 ">
                                <div class="col-auto">
                                    <div class="row border bg-dark shadow-lg rounded py-2">
                                        <div class="col-auto">
                                            <div class="display-date text-primary">
                                                <span id="day">day</span>,
                                                <span id="month">month</span>
                                                <span id="daynum">00</span>,
                                                <span id="year">0000</span>
                                            </div>
                                        </div>
                                        <div class="col-auto text-success">
                                            <div class="display-time"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!--Real Date and Time-->
                </div>
            </div>

            <div class="dashBorder bg-light m-3 mt-0 ">
                <nav class="navbar navbar-light justify-content-center visitInfo">
                    <span class="navbar-brand mb-0 h1 text-white">Visitation</span>
                </nav>
                <div class="container-fluid pt-4 px-4 ">
                    <div class="row g-4 justify-content-center">
                        <div class="col col-sm-auto">
                            <div class="expected rounded d-flex align-items-center justify-content-between p-4 border border-success">
                                <i class="fa fa-chart-line fa-3x text-dark"></i>
                                <div class="ms-3">
                                    <h1 class="mb-0 text-end" id="expectedVisits"></h1>
                                    <p class="mb-2 text-dark">Expected Visit</p>
                                </div>
                            </div>
                        </div>
                        <div class="col col-sm-auto">
                            <div class="successful rounded d-flex align-items-center justify-content-between p-4 border border-success">
                                <i class="bi bi-person-check-fill fa-3x text-dark"></i>
                                <div class="ms-3">
                                    <h1 class="mb-0  text-end" id="successfulVisits"></h1>
                                    <p class="mb-2 text-dark">Successful Visit</p>
                                </div>
                            </div>
                        </div>

                        <div class="col col-sm-auto">
                            <div class="totalVisitors rounded d-flex align-items-center justify-content-between p-4 border border-success">
                                <i class="bi bi-people-fill fa-3x text-dark"></i>
                                <div class="ms-3">
                                    <h1 class="mb-0  text-end" id="totalVisitors"></h1>
                                    <p class="mb-2 text-dark">Total Number of Visitor</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Visitors End -->


                <!-- Visitors Chart Start -->
                <div class="container-fluid pt-4 px-4 pb-4">
                    <div class="col-auto bargraph">
                        <div class="bg-light text-center rounded border border-success">
                            <div class="mt-2">
                                <h6 class="">Number of Visit Per Destination</h6>
                            </div>
                            <canvas id="bargraph" style="max-height: 400px;"></canvas>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class=" linegraph">
                            <div class="bg-light text-center rounded border border-success">
                                <div class="mt-2">
                                    <h6 class="">Visitation Status</h6>
                                </div>
                                <canvas id="linegraph" style="max-height: 350px;"></canvas>
                            </div>
                        </div>

                        <div class=" piegraph">
                            <div class="bg-light text-center rounded border border-success">
                                <div class="mt-2">
                                    <h6 class="">Total Number Of Visitor by Gender</h6>
                                </div>
                                <canvas id="piegraph" style="max-height: 400px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Visitors Chart End -->
            </div>

            <div class="dashBorder1 bg-light m-3 mt-0">
                <nav class="navbar navbar-light justify-content-center visitInfo1">
                    <span class="navbar-brand mb-0 h1 text-white">Open Event</span>
                </nav>


                <!-- Visitors Chart Start -->
                <div class="container-fluid pt-4 px-4 pb-4">
                    <div class="col-auto bargraph">
                        <div class="bg-light text-center rounded border border-success">
                            <div class="mt-2">
                                <h6 class="">Number of Visitor Per Day</h6>
                            </div>
                            <canvas id="bargraph1" style="max-height: 400px;"></canvas>
                        </div>
                        <br>
                    </div>
                </div>
                <!-- Visitors Chart End -->
            </div>

        </div>
        <!-- Content End -->

    </div>

    <!-- start chart js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.0.2/chart.min.js"></script>

    <!-- start highcharts js -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <!-- start amcharts -->
    <script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
    <script src="https://www.amcharts.com/lib/3/ammap.js"></script>
    <script src="https://www.amcharts.com/lib/3/maps/js/worldLow.js"></script>
    <script src="https://www.amcharts.com/lib/3/serial.js"></script>
    <script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
    <script src="https://www.amcharts.com/lib/3/themes/light.js"></script>


    <!--
    <script src="lib/dash_assets/js/line-chart.js"></script>
    <script src="lib/dash_assets/js/pie-chart.js"></script>
    <script src="lib/dash_assets/js/bar-chart.js"></script>
    <script src="dash_assets/js/maps.js"></script>
    <script src="dash_assets/js/plugins.js"></script>
    <script src="dash_assets/js/scripts.js"></script>
    -->


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->

    <script src="js/main.js"></script>
</body>

</html>


<script>
    function updateExpectedVisit(expected) {
        // Find the element by its ID
        console.log("updateExpectedVisit function called with expected value:", expected);
        var expectedVisitsElement = document.getElementById('expectedVisits');

        expectedVisitsElement.innerHTML = expected;
        console.log(expected);

    }




    function updateSuccessfulVisit(successfulVisit) {
        // Find the element by its ID
        var successfulVisitElement = document.getElementById('successfulVisits');

        successfulVisitElement.innerHTML = successfulVisit;

    }


    function updateTotalVisitors(totalVisitors) {
        // Find the element by its ID
        var totalVisitorsElement = document.getElementById('totalVisitors');

        totalVisitorsElement.innerHTML = totalVisitors;

    }

    var ctx = document.getElementById('bargraph').getContext('2d'); //DITO SIMULA NG DASHBOARD VISITATION
    var bargraph = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                    label: 'No. of Successful Visits ',
                    data: [],
                    backgroundColor: 'rgba(0, 128, 0, 0.3)',
                    borderColor: 'rgba(0, 128, 0, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Total Visitors',
                    data: [],
                    backgroundColor: 'rgba(207,248,0, 0.4)',
                    borderColor: 'rgba(207,248,0, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return Number.isInteger(value) ? value : '';
                        }
                    }
                }
            }
        }
    });

    function updateChart1(destinationData) {
        // Filter out destinations with zero counts
        const filteredData = destinationData.filter(item => item.count > 0);

        // Extract destination names and counts from the filtered data
        const labels = filteredData.map(item => item.destinationName);
        const data = filteredData.map(item => item.count);
        const totalVisitorData = filteredData.map(item => item.totalVisitor);

        // Update the bar chart data for both datasets
        bargraph.data.labels = labels; // Set labels as destination names
        bargraph.data.datasets[0].data = data; // Set data as counts
        bargraph.data.datasets[1].data = totalVisitorData; // Set data for the total visitors dataset
        bargraph.update();
    }



    var sce = document.getElementById('linegraph').getContext('2d');
    var linegraph = new Chart(sce, {
        type: "line",
        data: {
            labels: [], // Leave this empty initially
            datasets: [{
                    label: 'Successful Visits',
                    fill: false,
                    lineTension: 0,
                    backgroundColor: "rgba(75, 192, 192, 0.2)",
                    borderColor: "rgba(75, 192, 192, 1)",
                    data: [],
                    borderWidth: 1
                },
                {
                    label: 'Expired Visits',
                    fill: false,
                    lineTension: 0,
                    backgroundColor: "rgb(128, 128, 128, 0.3)",
                    borderColor: "rgb(128, 128, 128)",
                    data: [],
                    borderWidth: 1
                }
            ]
        },
        options: {
            scales: {
                yAxes: {
                    ticks: {
                        min: 0,
                        callback: function(value) {
                            return Number.isInteger(value) ? value : '';
                        }
                    }
                }
            }
        }
    });

    function updateChart2(line) {
        // Update the line graph data
        linegraph.data.datasets[0].data = line.SuccessfulVisit;
        linegraph.data.datasets[1].data = line.ExpiredVisit;
        linegraph.data.labels = line.Labels; // Set labels dynamically
        linegraph.update();
    }



    var fml = document.getElementById('piegraph').getContext('2d');
    var piechart = new Chart(fml, {
        type: 'pie',
        data: {
            labels: ['Male', 'Female', 'Guest'],
            datasets: [{
                label: 'My First Dataset',
                data: [], // Initialize with zeros
                backgroundColor: ['rgb(51, 153, 255)', 'rgb(255, 102, 178)', 'rgb(128, 128, 128)'],
                hoverOffset: 4
            }]
        }
    });

    // Function to update chart 3
    function updateChart3(pie) {
        // Update the pie chart data
        piechart.data.datasets[0].data = pie;
        piechart.update();
    } //DITO HULI NG DASHBOARD VISITATION


    // Create the bar graph
    var ctx1 = document.getElementById('bargraph1').getContext('2d');
    var bargraph1 = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: [], // Event names will be filled dynamically
            datasets: [{
                label: 'No. of Visitors',
                data: [], // Total visitor counts will be filled dynamically
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return Number.isInteger(value) ? value : '';
                        }
                    }
                }
            }
        }
    });

    function updateChartOpenEvent(openEventDetails) {
        var eventNames = openEventDetails.map(function(event) {
            return event.dayCounter;
        });

        var totalVisitors = openEventDetails.map(function(event) {
            return event.totalNumberOfVisitor;
        });

        // Update the chart labels and data with the retrieved data
        bargraph1.data.labels = eventNames;
        bargraph1.data.datasets[0].data = totalVisitors;

        // Trigger an update to redraw the chart
        bargraph1.update();
    }

    //DITO HULI NG OPEN EVENT DASHBOARD



    // Function to fetch data from the server
    function fetchData() {
        console.log("AJAX Request Sent");
        var startDate = $('#fromDateDashboard').val();
        var endDate = $('#toDateDashboard').val();

        console.log("startDate: " + startDate); // Debugging statement
        console.log("endDate: " + endDate);
        $.ajax({
            type: 'POST',
            url: 'dashboard_data.php',
            data: {
                start_date: startDate,
                end_date: endDate
            },
            dataType: 'json',
            success: function(data) {
                console.log(data);
                // Update the pie chart with the retrieved data
                updateExpectedVisit(data.expectedVisit);
                updateSuccessfulVisit(data.successfulVisit);
                updateTotalVisitors(data.totalVisitors);
                updateChart1(data.chart1);
                updateChart2(data.chart2);
                updateChart3(data.chart3);
                updateChartOpenEvent(data.chartOpenEvent1);

            },
            error: function(xhr, status, error) {
                console.log("ayaw tangina");
                console.error("AJAX error:", status, error); // Debugging statement
            }
        });
    }

    // Attach the fetchData function to the date inputs' change event
    $(document).ready(function() {
        $('#fromDateDashboard, #toDateDashboard').change(fetchData);
        setInterval(fetchData, 3000);

        fetchData(); // Initial data fetch
    });




    //
</script>