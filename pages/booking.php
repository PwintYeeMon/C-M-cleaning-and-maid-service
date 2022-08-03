<?php 
include('header.php');
include('../../Staff/pages/connect.php');

$cleaningdate = strtotime($_SESSION['cleaningdate']);
$date =  date('d M Y', $cleaningdate);
$time =  $_SESSION['cleaningtime'];
$totalduration = $_SESSION['totalduration'];

// Remove saved sessions
unset($_SESSION['booked']);
unset($_SESSION['cleaningdate']);
unset($_SESSION['cleaningtime']);
unset($_SESSION['totalduration']);

$startingtime = date('H:i', strtotime($time));
$endingtime = date('H:i', strtotime('+'. $totalduration .' hour', strtotime($time)));

 ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>C&M | Booking</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">
</head>

<body>
    <!-- Hero Start -->
    <div class="container-fluid bg-primary py-5 hero-header mb-5">
        <div class="row py-3">
            <div class="col-12 text-center">
                <h1 class="display-3 text-white animated zoomIn">Booked !</h1>
                <a href="index.php" class="h4 text-white">Home </a>
                <a class="h4 text-white"> > </a>
                <a href="cleaningtypedisplay.php" class="h4 text-white">Cleaning Type </a>
                <a class="h4 text-white"> > </a>
                <a href="roomtypedisplay.php" class="h4 text-white"> Room Types</a>
                <a class="h4 text-white"> > </a>
                <a href="roomtypedisplay.php" class="h4 text-white"> Date & Time</a>
                <a class="h4 text-white"> > </a>
                <a class="h4 text-primary"> Booking</a>
            </div>
        </div>
    </div>
    <!-- Hero End -->


    <!-- Page Content -->
    <div class="content success-page-cont">
        <div class="container-fluid">
        
            <div class="row justify-content-center">
                <div class="col-lg-6">
                
                    <!-- Success Card -->
                    <div class="card success-card bg-light">
                        <div class="card-body">
                            <div class="success-cont">
                                <i class="fas fa-check"></i>
                                <h3>Cleaning schedule booked Successfully!</h3>
                                <p>Schedule booked on <br> <strong><?php echo $date.', '.$startingtime.' to '.$endingtime ?></strong></p>
                                <a href="bookinghistory.php" class="btn btn-primary py-3 w-25">View Detail</a>
                            </div>
                        </div>
                    </div>
                    <!-- /Success Card -->
                    
                </div>
            </div>
            
        </div>
    </div>      
    <!-- /Page Content -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded back-to-top"><i class="bi bi-arrow-up"></i></a>
<br><br>
</body>

</html>

<?php 
include('footer.php');
 ?>