<?php 
session_start();
include('connect.php');

// Check Adminstrator Account
$staffid = $_SESSION['StaffID'];
$role = $_SESSION['StaffRole'];
if (!isset($_SESSION['StaffID']))
{
  echo "<script>window.alert('Please log in first')</script>";
  echo "<script>window.location='stafflogin.php'</script>";
}
elseif ($role != 'House Cleaner')
{
  echo "<script>window.alert('Only Cleaners can have access to their schedule.')</script>";
  echo "<script>window.location='stafflogin.php'</script>";
}

$day = date('D');
$index = 0;

$select = "SELECT * FROM cleanerschedule WHERE StaffID = '$staffid'";
$run = mysqli_query($connect, $select);
$runcountday = mysqli_num_rows($run);

if($runcountday != 0)
{
  for ($i = 0; $i < $runcountday ; $i++) 
  { 
    $array = mysqli_fetch_array($run);                                                
    $workday[$i] = $array['Day'];
  }
}

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

        <?php $page = 'cleanerscheduledisplay'; include('sidebar.php'); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include('topbar.php') ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="row">

                        <div class="col-xl-12 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Schedule</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="profile-box">   
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card schedule-widget mb-0">
                                                
                                                    <!-- Schedule Header -->
                                                    <div class="schedule-header">
                                                    
                                                        <!-- Schedule Nav -->
                                                        <div class="schedule-nav">
                                                            <ul class="nav nav-tabs nav-justified">
                                                                <li class="nav-item">
                                                                    <a class="nav-link <?php if($day == 'Sun'){ echo 'active '; } if (in_array('Sunday', $workday)){ echo 'text-success'; } ?>" data-toggle="tab" href="#slot_sunday">Sunday</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link <?php if($day == 'Mon'){ echo 'active '; } if (in_array('Monday', $workday)){ echo 'text-success'; } ?>" data-toggle="tab" href="#slot_monday">Monday</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link <?php if($day == 'Tue'){ echo 'active '; } if (in_array('Tuesday', $workday)){ echo 'text-success'; } ?>" data-toggle="tab" href="#slot_tuesday">Tuesday</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link <?php if($day == 'Wed'){ echo 'active '; } if (in_array('Wednesday', $workday)){ echo 'text-success'; } ?>" data-toggle="tab" href="#slot_wednesday">Wednesday</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link <?php if($day == 'Thu'){ echo 'active '; } if (in_array('Thursday', $workday)){ echo 'text-success'; } ?>" data-toggle="tab" href="#slot_thursday">Thursday</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link <?php if($day == 'Fri'){ echo 'active '; } if (in_array('Friday', $workday)){ echo 'text-success'; } ?>" data-toggle="tab" href="#slot_friday">Friday</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link <?php if($day == 'Sat'){ echo 'active '; } if (in_array('Saturday', $workday)){ echo 'text-success'; } ?>" data-toggle="tab" href="#slot_saturday">Saturday</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <!-- /Schedule Nav -->
                                                        
                                                    </div>
                                                    <!-- /Schedule Header -->
                                                    
                                                    <!-- Schedule Content -->
                                                    <div class="tab-content schedule-cont">
                                                    
                                                        <!-- Sunday Slot -->
                                                        <div id="slot_sunday" class="modal-body tab-pane fade <?php if($day == 'Sun'){ echo 'show active'; } ?>">
                                                            <!-- Slot List -->
                                                            <div class="row">

                                                              <?php 

                                                              $today = "Sunday";
                                                              include('scheduledisplayquery.php');
                                                                
                                                               ?>

                                                            </div>
                                                        </div>
                                                        <!-- /Sunday Slot -->

                                                        <!-- Monday Slot -->
                                                        <div id="slot_monday" class="modal-body tab-pane fade <?php if($day == 'Mon'){ echo 'show active'; } ?>">
                                                            
                                                            <!-- Slot List -->
                                                            <div class="row">

                                                              <?php 

                                                              $today = "Monday";
                                                              include('scheduledisplayquery.php');
                                                                
                                                               ?>

                                                            </div>
                                                            <!-- /Slot List -->
                                                            
                                                        </div>
                                                        <!-- /Monday Slot -->

                                                        <!-- Tuesday Slot -->
                                                        <div id="slot_tuesday" class="modal-body tab-pane fade <?php if($day == 'Tue'){ echo 'show active'; } ?> <?php if($day == 'Mon'){ echo 'show active'; } ?>">
                                                            <div class="row">
                                                              
                                                              <?php 

                                                              $today = "Tuesday";
                                                              include('scheduledisplayquery.php');
                                                                
                                                               ?>

                                                            </div>
                                                        </div>
                                                        <!-- /Tuesday Slot -->

                                                        <!-- Wednesday Slot -->
                                                        <div id="slot_wednesday" class="modal-body tab-pane fade <?php if($day == 'Wed'){ echo 'show active'; } ?>">
                                                            <div class="row">
                                                              
                                                              <?php 

                                                              $today = "Wednesday";
                                                              include('scheduledisplayquery.php');
                                                                
                                                               ?>

                                                            </div>
                                                        </div>
                                                        <!-- /Wednesday Slot -->

                                                        <!-- Thursday Slot -->
                                                        <div id="slot_thursday" class="modal-body tab-pane fade <?php if($day == 'Thu'){ echo 'show active'; } ?>">
                                                            <div class="row">
                                                              
                                                              <?php 

                                                              $today = "Thursday";
                                                              include('scheduledisplayquery.php');
                                                                
                                                               ?>

                                                            </div>
                                                        </div>
                                                        <!-- /Thursday Slot -->

                                                        <!-- Friday Slot -->
                                                        <div id="slot_friday" class="modal-body tab-pane fade <?php if($day == 'Fri'){ echo 'show active'; } ?>">
                                                            <div class="row">
                                                              
                                                              <?php 

                                                              $today = "Friday";
                                                              include('scheduledisplayquery.php');
                                                                
                                                               ?>

                                                            </div>
                                                        </div>
                                                        <!-- /Friday Slot -->

                                                        <!-- Saturday Slot -->
                                                        <div id="slot_saturday" class="modal-body tab-pane fade <?php if($day == 'Sat'){ echo 'show active'; } ?>">
                                                            <div class="row">
                                                              
                                                              <?php 

                                                              $today = "Saturday";
                                                              include('scheduledisplayquery.php');
                                                                
                                                               ?>

                                                            </div>
                                                        </div>
                                                        <!-- /Saturday Slot -->

                                                    </div>
                                                    <!-- /Schedule Content -->
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>

                        </div>

                    </div>

                </div>
                <!-- /.container-fluid -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="row">

                        <div class="col-xl-12 col-lg-7">
                            <div class="card shadow mb-4">
                                
                                <!-- Card Header -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Leave</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="profile-box">   
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card schedule-widget mb-0">
                                                
                                                    <!-- Schedule Header -->
                                                    <div class="schedule-header">
                                                    
                                                        <!-- Schedule Nav -->
                                                        <div class="schedule-nav">
                                                            <ul class="nav nav-tabs">
                                                                <li class="nav-item">
                                                                    <a class="nav-link active" data-toggle="tab" href="#upcoming">Upcoming</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link" data-toggle="tab" href="#previous">Previous</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <!-- /Schedule Nav -->
                                                        
                                                    </div>
                                                    <!-- /Schedule Header -->
                                                    
                                                    <!-- Schedule Content -->
                                                    <div class="tab-content schedule-cont">
                                                    
                                                        <!-- Upcoming Slot -->
                                                        <div id="upcoming" class="modal-body tab-pane fade show active">

                                                            <?php 

                                                            $today = date('Y-m-d');
                                                            // Retrieve Staff Leave
                                                            $select = "SELECT * FROM staffleave l, staff s WHERE l.StaffID = s.StaffID AND l.EndingDate >= '$today' AND l.StaffID = '$staffid'";
                                                            $run = mysqli_query($connect, $select);
                                                            $runcount = mysqli_num_rows($run);

                                                            if ($runcount == 0)
                                                            {
                                                                echo '<div class="row">
                                                                        <div class="col-12 my-2">
                                                                          <div class="text-dark text-center py-2">
                                                                              <b>No Upcoming Leave Available</b>
                                                                          </div>
                                                                        </div>
                                                                      </div>';
                                                            }
                                                            else
                                                            {
                                                             ?>

                                                            <!-- DataTables -->
                                                            <div class="card shadow mb-4">
                                                                <div class="card-header">
                                                                  <h6 class="m-0 font-weight-bold text-primary py-2">Upcoming Leave List</h6>
                                                                </div>

                                                                <!-- Slot List -->
                                                                <div class="card-body">
                                                                  <div class="table-responsive">
                                                                    <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>No</th>
                                                                                <th>Starting Date</th>
                                                                                <th>Ending Date</th>
                                                                                <th>Reason</th>
                                                                                <?php
                                                                                ?>                                            
                                                                            </tr>
                                                                        </thead>

                                                                        <tbody>

                                                                        <?php 

                                                                        for ($i = 0; $i < $runcount; $i++) 
                                                                        { 
                                                                          $array = mysqli_fetch_array($run);
                                                                          $leavetype = $array['LeaveType'];
                                                                          $startingdate = $array['StartingDate'];
                                                                          $endingdate = $array['EndingDate'];
                                                                          $sdate = date('d M Y', strtotime("$startingdate"));
                                                                          $edate = date('d M Y', strtotime("$endingdate"));
                                                                        
                                                                         ?>

                                                                            <tr>
                                                                                <td><?php echo $i + 1; ?></td>
                                                                                <td><?php echo $sdate; ?></td>
                                                                                <td><?php echo $edate; ?></td>
                                                                                <td style="word-break:break-word;"><?php echo $leavetype; ?></td>
                                                                            </tr>

                                                                        <?php } ?>
                                                                            
                                                                        </tbody>
                                                                    </table>
                                                                  </div>
                                                                </div>
                                                            </div>
                                                          <?php } ?>
                                                        </div>
                                                        <!-- Upcoming Slot -->

                                                        <!-- Previous Slot -->
                                                        <div id="previous" class="modal-body tab-pane fade">

                                                            <?php 

                                                            $today = date('Y-m-d');
                                                            // Retrieve Staff Leave
                                                            $select = "SELECT * FROM staffleave l, staff s WHERE l.StaffID = s.StaffID AND l.EndingDate < '$today' AND l.StaffID = '$staffid'";
                                                            $run = mysqli_query($connect, $select);
                                                            $runcount = mysqli_num_rows($run);

                                                            if ($runcount == 0)
                                                            {
                                                                echo '<div class="row">
                                                                        <div class="col-12 my-2">
                                                                          <div class="text-dark text-center py-2">
                                                                              <b>No Previous Leave Available</b>
                                                                          </div>
                                                                        </div>
                                                                      </div>';
                                                            }
                                                            else
                                                            {
                                                             ?>

                                                            <!-- DataTables -->
                                                            <div class="card shadow mb-4">
                                                                <div class="card-header">
                                                                  <h6 class="m-0 font-weight-bold text-primary py-2">Previous Leave List</h6>
                                                                </div>

                                                               <!-- Slot List -->
                                                                <div class="card-body">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered text-center" id="dataTable1" width="100%" cellspacing="0">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>No</th>
                                                                                    <th>Starting Date</th>
                                                                                    <th>Ending Date</th>
                                                                                    <th>Reason</th>                          
                                                                                </tr>
                                                                            </thead>

                                                                            <tbody>

                                                                            <?php 

                                                                            for ($i = 0; $i < $runcount; $i++) 
                                                                            { 
                                                                              $array = mysqli_fetch_array($run);
                                                                              $leavetype = $array['LeaveType'];
                                                                              $startingdate = $array['StartingDate'];
                                                                              $endingdate = $array['EndingDate'];
                                                                              $sdate = date('d M Y', strtotime("$startingdate"));
                                                                              $edate = date('d M Y', strtotime("$endingdate"));
                                                                            
                                                                             ?>

                                                                                <tr>
                                                                                    <td><?php echo $i + 1; ?></td>
                                                                                    <td><?php echo $sdate; ?></td>
                                                                                    <td><?php echo $edate; ?></td>
                                                                                    <td style="word-break:break-word;"><?php echo $leavetype; ?></td>
                                                                                </tr>

                                                                            <?php } ?>
                                                                                
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                          <?php } ?>
                                                        </div>
                                                        <!-- /Previous Slot -->                                                        

                                                    </div>
                                                    <!-- /Schedule Content -->
                                                    
                                                </div>
                                            </div>
                                        </div>
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