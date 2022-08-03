<?php 
session_start();
include('connect.php');

// Check Staff Account
$staffid = $_SESSION['StaffID'];
$role = $_SESSION['StaffRole'];
if (!isset($_SESSION['StaffID']))
{
  echo "<script>window.alert('Please log in first')</script>";
  echo "<script>window.location='stafflogin.php'</script>";
}

if ($role == 'House Cleaner')
{
  echo "<script>window.alert('Only Manager and Administrator can have access to employee leaves.')</script>";
  echo "<script>window.location='stafflogin.php'</script>";
}

// Retrieve Cleaner and Booking
$selectcb = "SELECT *, COUNT(b.BookingID) AS NoofCommission FROM booking b, bookingdetail bd, staff s WHERE b.BookingID = bd.BookingID AND bd.StaffID = s.StaffID AND s.StaffRole = 'House Cleaner' GROUP BY s.StaffID";
$runcb = mysqli_query($connect, $selectcb);
$runcountcb = mysqli_num_rows($runcb);

for ($i = 0; $i < $runcountcb; $i++) 
{                                           
  $arraycb = mysqli_fetch_array($runcb);
  $staffname[] = $arraycb['FirstName'].' '.$arraycb['LastName'];
  $commission[] = $arraycb['NoofCommission'];
}

// Retrieve Booking and Cleaning Type
$selectbc = "SELECT c.CleaningType, COUNT(b.BookingID) AS Noofeedbackooking FROM booking b, cleaningtype c WHERE b.CleaningTypeID = c.CleaningTypeID GROUP BY c.CleaningTypeID";
$runbc = mysqli_query($connect, $selectbc);
$runcountbc = mysqli_num_rows($runbc);

for ($j = 0; $j < $runcountbc; $j++) 
{                                           
  $arraybc = mysqli_fetch_array($runbc);
  $cleaningtype[] = $arraybc['CleaningType'];
  $booking[] = $arraybc['Noofeedbackooking'];
}

$startmonth = date('Y-m-01');
$endmonth = date('Y-m-t');
$selectearningm = "SELECT SUM(TotalPrice) AS earning FROM booking WHERE Status = 'Paid' AND BookingDate BETWEEN '$startmonth' AND '$endmonth'";
$runearningm = mysqli_query($connect, $selectearningm);
$arrayearningm = mysqli_fetch_array($runearningm);
$earningm = number_format((float)$arrayearningm['earning'], 2, '.', '');

$startyear = date('Y-01-01');
$endyear = date('Y-12-31');
$selectearninga = "SELECT SUM(TotalPrice) AS earning FROM booking WHERE Status = 'Paid'";
$runearninga = mysqli_query($connect, $selectearninga);
$arrayearninga = mysqli_fetch_array($runearninga);
$earninga = number_format((float)$arrayearninga['earning'], 2, '.', '');

$selectapproval = "SELECT COUNT(BookingID) AS approval FROM booking WHERE Status = 'Processing'";
$runapproval = mysqli_query($connect, $selectapproval);
$arrayapproval = mysqli_fetch_array($runapproval);
$approval = $arrayapproval['approval'];

$selectbooking = "SELECT COUNT(BookingID) AS booking FROM booking";
$runbooking = mysqli_query($connect, $selectbooking);
$arraybooking = mysqli_fetch_array($runbooking);
$book = $arraybooking['booking'];

$today = date('Y-m-d');
$selectcleaning = "SELECT COUNT(BookingID) AS cleaning FROM booking WHERE CleaningDate > '$today' AND (Status = 'Approved' OR Status = 'Paid')";
$runcleaning = mysqli_query($connect, $selectcleaning);
$arraycleaning = mysqli_fetch_array($runcleaning);
$cleaning = $arraycleaning['cleaning'];

$percent = 100 - intval(($cleaning / $book) * 100);

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>C&M | Dashboard</title>

    <!-- Favicon -->
    <link href="../../User/assets/Img/logo.ico" rel="icon">

    <?php include('header.php') ?>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php $page = 'dashboard'; include('sidebar.php'); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include('topbar.php') ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>                        
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Content -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Earnings (This Month)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">$<?php echo $earningm; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Earnings (Annual)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">$<?php echo $earninga; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Bookings Done
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $percent; ?>%</div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                            style="width: <?php echo $percent; ?>%" aria-valuenow="<?php echo $percent; ?>" aria-valuemin="0"
                                                            aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Pending Approval</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $approval; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Number of Commissions per Cleaner</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Manage:</div>
                                            <a class="dropdown-item" href="managebooking.php">Booking</a>
                                            <a class="dropdown-item" href="managecleaningtype.<?php  ?>">Cleaning Type</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myBarChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Booking per Cleaning Type</h6>
                                    <div class="dropdown no-arrow">
                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            <div class="dropdown-header">Manage:</div>
                                            <a class="dropdown-item" href="managebooking.php">Booking</a>
                                            <a class="dropdown-item" href="managecleaningtype.php">Cleaning Type</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <?php include('footer.php') ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

</body>

</html>

<script>
// Bar Chart Example
var ctx = document.getElementById("myBarChart");
var myBarChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: <?php echo json_encode($staffname); ?>,
    datasets: [{
      label: " Commission ",
      backgroundColor: "#4e73df",
      hoverBackgroundColor: "#2e59d9",
      borderColor: "#4e73df",
      data: <?php echo json_encode($commission); ?>,
    }],
  },
  options: {
    maintainAspectRatio: false,
    layout: {
      padding: {
        left: 10,
        right: 25,
        top: 25,
        bottom: 0
      }
    },
    scales: {
      xAxes: [{
        time: {
          unit: 'month'
        },
        gridLines: {
          display: false,
          drawBorder: false
        },
        ticks: {
          maxTicksLimit: 6
        },
        maxBarThickness: 25,
      }],
      yAxes: [{
        ticks: {
          min: 0,
          max: <?php echo max($commission); ?>,
          maxTicksLimit: 5,
          padding: 10,
          // Include a dollar sign in the ticks
          callback: function(value, index, values) {
            return value;
          }
        },
        gridLines: {
          color: "rgb(234, 236, 244)",
          zeroLineColor: "rgb(234, 236, 244)",
          drawBorder: false,
          borderDash: [2],
          zeroLineBorderDash: [2]
        }
      }],
    },
    legend: {
      display: false
    },
  }
});

// Pie Chart Example
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'pie',
  data: {
    labels: <?php echo json_encode($cleaningtype); ?>,
    datasets: [{
      labels: <?php echo json_encode($cleaningtype); ?>,
      data: <?php echo json_encode($booking); ?>,
      backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
      hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
      hoverBorderColor: "rgba(234, 236, 244, 1)",
    }],
  },
  options: {
    maintainAspectRatio: false,
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      caretPadding: 10,
    },
    legend: {
      display: true,
      position: 'bottom'
    },
  },
});
</script>