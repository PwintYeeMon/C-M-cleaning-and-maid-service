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

// Update Form Submission 
if(isset($_POST['txtstatus']))
{
  $bookingid = $_POST['txtbookingid'];
  $bookingstatus = $_POST['txtstatus'];

  // Update Status
  $update = "UPDATE booking SET Status = '$bookingstatus' WHERE BookingID = '$bookingid'";
  $query = mysqli_query($connect, $update);

  if($query)
  {
    echo "<script>alert('Status Update Successful')</script>";
    echo "<script>window.location='managebooking.php'</script>";
  }
  else
  {
    mysqli_error($connect);
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

    <title>C&M | Booking List</title>

    <!-- Favicon -->
    <link href="../../User/assets/Img/logo.ico" rel="icon">

    <?php include('header.php') ?>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php $page = 'managebooking'; include('sidebar.php'); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include('topbar.php') ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                  <!-- Page Heading -->
                  <div class="d-sm-flex align-items-center justify-content-between mb-2">
                      <p class="mb-2 text-danger"> !! Click <b>each cell</b> to see detail information.</p>
                      <form method="POST" action="downloadcsv.php">
                          <button type="submit" name="btndownload" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                              class="fas fa-download fa-sm text-white-50"></i> Generate Report</button>
                      </form>
                      
                  </div>                  

                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                      <a class="nav-link active" id="upcoming-tab" data-toggle="tab" href="#upcoming" role="tab" aria-controls="upcoming" aria-selected="true">Upcoming</a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a class="nav-link" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="false">All</a>
                    </li>
                  </ul>                      

                    <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">

                        <!-- DataTables -->
                        <div class="card shadow mb-4">
                            <div class="card-header">
                              <h6 class="m-0 font-weight-bold text-primary py-2">Upcoming Booking List</h6>
                            </div>
                            
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Customer</th>
                                                <th>Cleaning Date</th>
                                                <th>Cleaning Type</th>
                                                <th>Room</th>
                                                <th>Cleaner</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                        <?php 

                                        // Retrieve Booking
                                        $today = date('Y-m-d');
                                        $select = "SELECT * FROM booking b, cleaningtype ct, customer c WHERE b.CleaningTypeID = ct.CleaningTypeID AND b.CustomerID = c.CustomerID AND b.CleaningDate >= '$today'";
                                        $run = mysqli_query($connect, $select);
                                        $runcount = mysqli_num_rows($run);

                                        for ($i = 0; $i < $runcount; $i++) 
                                        {                                           
                                          $array = mysqli_fetch_array($run);
                                          $bookingid = $array['BookingID'];
                                          $booking = $array['BookingDate'];
                                          $bookingdate = date('d M Y', strtotime($booking));
                                          $equipment = $array['Equipment'];

                                          if ($equipment == 0) 
                                          {
                                            $equipment = "Included";
                                          }
                                          else
                                          {
                                            $equipment = "Not Included";
                                          }

                                          $cdate = $array['CleaningDate'];
                                          $cleaningdate = date('d M Y', strtotime($cdate));
                                          $ctime = $array['CleaningTime'];
                                          $cleaningtime = date('g:i A', strtotime($ctime));
                                          $cleaningtype = $array['CleaningType'];
                                          $rate = $array['Rate'];
                                          $commissionrate = $array['CommissionRate'] * 100;
                                          $duration = $array['TotalDuration_hr'];
                                          $totalprice = $array['TotalPrice'];
                                          $bookingstatus = $array['Status'];
                                          $customername = $array['FirstName'].' '.$array['LastName'];
                                          $phone = $array['Phone'];
                                          $email = $array['Email'];
                                          $address = $array['HouseNumber'].' '.$array['Street'].', '.$array['City'].', '.$array['State'].', '.$array['Postcode'];

                                          //Retrieve Payment
                                          $selectp = "SELECT * FROM payment p, paymentmethod pm WHERE p.PaymentMethodID = pm.PaymentMethodID AND p.BookingID = '$bookingid'";
                                          $runp = mysqli_query($connect, $selectp);
                                          $runcountp = mysqli_num_rows($runp);

                                          //Retrieve Cleaner
                                          $selectc = "SELECT * FROM staff s, bookingdetail bd WHERE s.StaffID = bd.StaffID AND bd.BookingID = '$bookingid'";
                                          $runc = mysqli_query($connect, $selectc);
                                          $runcountc = mysqli_num_rows($runc);

                                          //Retrieve Room Type
                                          $selectrt = "SELECT * FROM roomtype rt, roomtypedetail rd WHERE rt.RoomTypeID = rd.RoomTypeID AND rd.BookingID = '$bookingid'";
                                          $runrt = mysqli_query($connect, $selectrt);
                                          $runcountrt = mysqli_num_rows($runrt);

                                          //Retrieve Total Room Type
                                          $selectrtc = "SELECT SUM(rd.Quantity) AS Rooms FROM roomtype rt, roomtypedetail rd WHERE rt.RoomTypeID = rd.RoomTypeID AND rd.BookingID = '$bookingid' GROUP BY rd.BookingID";
                                          $runrtc = mysqli_query($connect, $selectrtc);
                                          $arrayrtc = mysqli_fetch_array($runrtc);
                                          $rooms = $arrayrtc['Rooms'];
                                         ?>

                                        <tr>
                                            <td><a data-toggle="modal" data-target="#bookingdetail<?php echo $i; ?>"><?php echo $bookingid; ?></a></td>
                                            <td><a data-toggle="modal" data-target="#customerdetail<?php echo $i; ?>"><?php echo $customername; ?></a></td>
                                            <td><a data-toggle="modal" data-target="#timedetail<?php echo $i; ?>"><?php echo $cleaningdate; ?></a></td>
                                            <td><a data-toggle="modal" data-target="#cleaningdetail<?php echo $i; ?>"><?php echo $cleaningtype; ?></a></td>
                                            <td><a data-toggle="modal" data-target="#roomdetail<?php echo $i; ?>"><?php echo $rooms; ?></a></td> 
                                            <td><a data-toggle="modal" data-target="#cleanerdetail<?php echo $i; ?>"><?php echo $runcountc; ?></a></td>
                                            <td>
                                              
                                              <?php 

                                              if ($bookingstatus == "Declined" || $bookingstatus == "Cancelled") 
                                              {
                                                echo "<label class='text-danger'>$bookingstatus</label>";
                                              }
                                              elseif ($bookingstatus == "Done")
                                              {
                                                echo "<label class='text-success'>Done</label>";
                                              }
                                              elseif ($bookingstatus == "Paid") 
                                              {
                                                echo "<label class='text-success'>Paid </label> &nbsp <i class='fas fa-info-circle' data-toggle='modal' data-target='#paymentdetail$i' title='view detail'></i>";
                                              }
                                              elseif ($bookingstatus == "Approved")
                                              {
                                                echo "<label class='text-warning'>Approved</label>";
                                              }
                                              elseif ($bookingstatus == "Processing")
                                              {
                                                if ($role == 'Administrator') 
                                                {
                                                  echo '<form action="managebooking.php" method="POST">
                                                          <input type="text" name="txtbookingid" value="'.$bookingid.'" hidden>
                                                          <select class="form-control bg-light text-warning border-0" name="txtstatus" onchange="this.form.submit()" required>
                                                            <option value="" class="text-warning" disabled selected>Processing</option>
                                                            <option value="Approved" class="text-warning">Approved</option>
                                                            <option value="Declined" class="text-danger">Declined</option>
                                                          </select>
                                                        </form>';
                                                }
                                                else
                                                {
                                                  echo "<label class='text-warning'>Processing</label>";
                                                }
                                              }

                                               ?>
                                            </td>                              
                                        </tr>                                           

                                        <!-- Booking Modal -->
                                        <div class="modal" id="bookingdetail<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                          <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Booking Detail</h5>
                                                <label class="close" data-dismiss="modal" aria-label="Close">
                                                  <span class="font-weight-bold" aria-hidden="true">&times;</span>
                                                </label>
                                              </div>
                                              <div class="modal-body">
                                                <div class="container-fluid">
                                                  <div class="row">
                                                    <div class="col-md-6 text-dark"><label>Booking ID:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $bookingid; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Booking Date:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $bookingdate; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Price:</label></div>
                                                    <div class="col-md-6 ml-auto">$<?php echo $totalprice; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Equipment:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $equipment; ?></div>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <!-- End Booking Modal -->

                                        <!-- Customer Modal -->
                                        <div class="modal" id="customerdetail<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                          <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Customer Detail</h5>
                                                <label class="close" data-dismiss="modal" aria-label="Close">
                                                  <span class="font-weight-bold" aria-hidden="true">&times;</span>
                                                </label>
                                              </div>
                                              <div class="modal-body">
                                                <div class="container-fluid">
                                                  <div class="row">
                                                    <div class="col-md-6 text-dark"><label>Customer Name:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $customername; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Phone:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $phone; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Email:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $email; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Address:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $address; ?></div>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <!-- End Customer Modal -->

                                        <!-- Schedule Modal -->
                                        <div class="modal" id="timedetail<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                          <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Schedule Detail</h5>
                                                <label class="close" data-dismiss="modal" aria-label="Close">
                                                  <span class="font-weight-bold" aria-hidden="true">&times;</span>
                                                </label>
                                              </div>
                                              <div class="modal-body">
                                                <div class="container-fluid">
                                                  <div class="row">
                                                    <div class="col-md-6 text-dark"><label>Cleaning Date:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $cleaningdate; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Cleaning Time:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $cleaningtime; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Duration:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $duration; ?> hr</div>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <!-- End Schedule Modal -->

                                        <!-- Cleaning Detail Modal -->
                                        <div class="modal" id="cleaningdetail<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                          <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Cleaning Detail</h5>
                                                <label class="close" data-dismiss="modal" aria-label="Close">
                                                  <span class="font-weight-bold" aria-hidden="true">&times;</span>
                                                </label>
                                              </div>
                                              <div class="modal-body">
                                                <div class="container-fluid">
                                                  <div class="row">
                                                    <div class="col-md-6 text-dark"><label>Cleaning Type:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $cleaningtype; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Rate:</label></div>
                                                    <div class="col-md-6 ml-auto">x <?php echo $rate; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Commission Rate:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $commissionrate; ?>%</div>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <!-- End Cleaning Detail Modal -->

                                        <!-- Room Type Modal -->
                                        <div class="modal" id="roomdetail<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                          <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Room Detail</h5>
                                                <label class="close" data-dismiss="modal" aria-label="Close">
                                                  <span class="font-weight-bold" aria-hidden="true">&times;</span>
                                                </label>
                                              </div>
                                              <div class="modal-body">
                                                <div class="container-fluid">
                                                  <div class="row">
                                                    <div class="col-md-3 text-dark text-center"><label>Room</label></div>
                                                    <div class="col-md-3 text-dark text-center"><label>Unit Price</label></div>
                                                    <div class="col-md-3 text-dark text-center"><label>Quantity</label></div>
                                                    <div class="col-md-3 text-dark text-center"><label>Total</label></div>
                                                    <hr>
                                                    <?php 
                                                    for ($j = 0; $j < $runcountrt; $j++) 
                                                    { 
                                                      $arrayrt = mysqli_fetch_array($runrt);
                                                      $roomname = $arrayrt['RoomName'];
                                                      $price = $arrayrt['Price'] * $rate;
                                                      $quantity = $arrayrt['Quantity'];
                                                      $total = $price * $quantity;

                                                      echo "<div class='col-md-3 ml-auto text-center'>$roomname</div>
                                                            <div class='col-md-3 ml-auto text-center'>$$price</div>
                                                            <div class='col-md-3 ml-auto text-center'>$quantity</div>
                                                            <div class='col-md-3 ml-auto text-center'>$$total</div>
                                                            <hr>";
                                                    }
                                                     ?>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <!-- End Room Type Modal -->

                                        <!-- Cleaner Modal -->
                                        <div class="modal" id="cleanerdetail<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                          <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Cleaner Detail</h5>
                                                <label class="close" data-dismiss="modal" aria-label="Close">
                                                  <span class="font-weight-bold" aria-hidden="true">&times;</span>
                                                </label>
                                              </div>
                                              <div class="modal-body">
                                                <div class="container-fluid">
                                                  <div class="row">                                                    
                                                    <div class="col-md-2 text-dark text-center"><label>ID</label></div>
                                                    <div class="col-md-3 text-dark text-center"><label>Name</label></div>
                                                    <div class="col-md-3 text-dark text-center"><label>Phone</label></div>
                                                    <div class="col-md-4 text-dark text-center"><label>Email</label></div>
                                                    <?php
                                                    for ($k = 0; $k < $runcountc; $k++) 
                                                    { 
                                                      $arrayc = mysqli_fetch_array($runc);
                                                      $staffid = $arrayc['StaffID'];
                                                      $name = $arrayc['FirstName'].' '.$arrayc['LastName'];
                                                      $phone = $arrayc['Phone'];
                                                      $email = $arrayc['Email'];

                                                      echo "<div class='col-md-2 ml-auto text-center'>$staffid</div>
                                                            <div class='col-md-3 ml-auto text-center'>$name</div>
                                                            <div class='col-md-3 ml-auto text-center'>$phone</div>
                                                            <div class='col-md-4 ml-auto text-center'>$email</div>
                                                            <hr>";
                                                    }
                                                     ?>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <!-- End Cleaner Modal -->

                                        <!-- Payment Modal -->
                                        <div class="modal" id="paymentdetail<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                          <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Payment Detail</h5>
                                                <label class="close" data-dismiss="modal" aria-label="Close">
                                                  <span class="font-weight-bold" aria-hidden="true">&times;</span>
                                                </label>
                                              </div>
                                              <div class="modal-body">
                                                <div class="container-fluid">
                                                  <div class="row">
                                                    <?php 

                                                    $arrayp = mysqli_fetch_array($runp);
                                                    $paymentid = $arrayp['PaymentID'];
                                                    $cardtype = $arrayp['CardType'];
                                                    $cardno = $arrayp['Cardno'];
                                                    $lastno = substr($cardno, -4);
                                                    $pdate = $arrayp['PaymentDate'];
                                                    $paymentdate = date('d M Y', strtotime($pdate)); 
                                                    $ptime = $arrayp['PaymentTime'];
                                                    $paymenttime = date('g:i A', strtotime($ptime));
                                                    $expmonth = $arrayp['Expmonth'];
                                                    $expyear = $arrayp['Expyear'];

                                                     ?>
                                                    <div class="col-md-6 text-dark"><label>Payment ID:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $paymentid; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Payment Date:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $paymentdate; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Payment Time:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $paymenttime; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Card Type:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $cardtype; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Card No:</label></div>
                                                    <div class="col-md-6 ml-auto">XXXXXXXXXXXX-<?php echo $lastno; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Expiration Date:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $expmonth.'/'.$expyear; ?></div>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <!-- End Payment Modal -->

                                        <?php } ?>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                      </div>
                      <div class="tab-pane fade" id="all" role="tabpanel" aria-labelledby="all-tab">

                        <!-- DataTables -->
                        <div class="card shadow mb-4">
                            <div class="card-header">
                              <h6 class="m-0 font-weight-bold text-primary py-2">All Booking List</h6>
                            </div>
                            
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center" id="dataTable1" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Customer</th>
                                                <th>Cleaning Date</th>
                                                <th>Cleaning Type</th>
                                                <th>Room</th>
                                                <th>Cleaner</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                        <?php 

                                        // Retrieve Booking
                                        $select = "SELECT * FROM booking b, cleaningtype ct, customer c WHERE b.CleaningTypeID = ct.CleaningTypeID AND b.CustomerID = c.CustomerID";
                                        $run = mysqli_query($connect, $select);
                                        $runcount = mysqli_num_rows($run);

                                        for ($i = 0; $i < $runcount; $i++) 
                                        {                                           
                                          $array = mysqli_fetch_array($run);
                                          $bookingid = $array['BookingID'];
                                          $booking = $array['BookingDate'];
                                          $bookingdate = date('d M Y', strtotime($booking));
                                          $equipment = $array['Equipment'];

                                          if ($equipment == 0) 
                                          {
                                            $equipment = "Included";
                                          }
                                          else
                                          {
                                            $equipment = "Not Included";
                                          }

                                          $cdate = $array['CleaningDate'];
                                          $cleaningdate = date('d M Y', strtotime($cdate));
                                          $ctime = $array['CleaningTime'];
                                          $cleaningtime = date('g:i A', strtotime($ctime));
                                          $cleaningtype = $array['CleaningType'];
                                          $rate = $array['Rate'];
                                          $commissionrate = $array['CommissionRate'] * 100;
                                          $duration = $array['TotalDuration_hr'];
                                          $totalprice = $array['TotalPrice'];
                                          $bookingstatus = $array['Status'];
                                          $customername = $array['FirstName'].' '.$array['LastName'];
                                          $phone = $array['Phone'];
                                          $email = $array['Email'];
                                          $address = $array['HouseNumber'].' '.$array['Street'].', '.$array['City'].', '.$array['State'].', '.$array['Postcode'];

                                          //Retrieve Payment
                                          $selectp = "SELECT * FROM payment p, paymentmethod pm WHERE p.PaymentMethodID = pm.PaymentMethodID AND p.BookingID = '$bookingid'";
                                          $runp = mysqli_query($connect, $selectp);
                                          $runcountp = mysqli_num_rows($runp);

                                          //Retrieve Cleaner
                                          $selectc = "SELECT * FROM staff s, bookingdetail bd WHERE s.StaffID = bd.StaffID AND bd.BookingID = '$bookingid'";
                                          $runc = mysqli_query($connect, $selectc);
                                          $runcountc = mysqli_num_rows($runc);

                                          //Retrieve Room Type
                                          $selectrt = "SELECT * FROM roomtype rt, roomtypedetail rd WHERE rt.RoomTypeID = rd.RoomTypeID AND rd.BookingID = '$bookingid'";
                                          $runrt = mysqli_query($connect, $selectrt);
                                          $runcountrt = mysqli_num_rows($runrt);

                                          //Retrieve Total Room Type
                                          $selectrtc = "SELECT SUM(rd.Quantity) AS Rooms FROM roomtype rt, roomtypedetail rd WHERE rt.RoomTypeID = rd.RoomTypeID AND rd.BookingID = '$bookingid' GROUP BY rd.BookingID";
                                          $runrtc = mysqli_query($connect, $selectrtc);
                                          $arrayrtc = mysqli_fetch_array($runrtc);
                                          $rooms = $arrayrtc['Rooms'];
                                         ?>

                                        <tr>
                                            <td><a data-toggle="modal" data-target="#allbookingdetail<?php echo $i; ?>"><?php echo $bookingid; ?></a></td>
                                            <td><a data-toggle="modal" data-target="#allcustomerdetail<?php echo $i; ?>"><?php echo $customername; ?></a></td>
                                            <td><a data-toggle="modal" data-target="#alltimedetail<?php echo $i; ?>"><?php echo $cleaningdate; ?></a></td>
                                            <td><a data-toggle="modal" data-target="#allcleaningdetail<?php echo $i; ?>"><?php echo $cleaningtype; ?></a></td>
                                            <td><a data-toggle="modal" data-target="#allroomdetail<?php echo $i; ?>"><?php echo $rooms; ?></a></td> 
                                            <td><a data-toggle="modal" data-target="#allcleanerdetail<?php echo $i; ?>"><?php echo $runcountc; ?></a></td>
                                            <td>
                                              
                                              <?php 

                                              if ($bookingstatus == "Declined" || $bookingstatus == "Cancelled") 
                                              {
                                                echo "<label class='text-danger'>$bookingstatus</label>";
                                              }
                                              elseif ($bookingstatus == "Done")
                                              {
                                                echo "<label class='text-success'>Done</label>";
                                              }
                                              elseif ($bookingstatus == "Paid") 
                                              {
                                                echo "<label class='text-success'>Paid </label> &nbsp <i class='fas fa-info-circle' data-toggle='modal' data-target='#allpaymentdetail$i' title='view detail'></i>";
                                              }
                                              elseif ($bookingstatus == "Approved")
                                              {
                                                echo "<label class='text-warning'>Approved</label>";
                                              }
                                              elseif ($bookingstatus == "Processing")
                                              {
                                                echo "<label class='text-warning'>Processing</label>";
                                              }

                                               ?>
                                            </td>                                     
                                        </tr>                                           

                                        <!-- Booking Modal -->
                                        <div class="modal" id="allbookingdetail<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                          <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Booking Detail</h5>
                                                <label class="close" data-dismiss="modal" aria-label="Close">
                                                  <span class="font-weight-bold" aria-hidden="true">&times;</span>
                                                </label>
                                              </div>
                                              <div class="modal-body">
                                                <div class="container-fluid">
                                                  <div class="row">
                                                    <div class="col-md-6 text-dark"><label>Booking ID:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $bookingid; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Booking Date:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $bookingdate; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Price:</label></div>
                                                    <div class="col-md-6 ml-auto">$<?php echo $totalprice; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Equipment:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $equipment; ?></div>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <!-- End Booking Modal -->

                                        <!-- Customer Modal -->
                                        <div class="modal" id="allcustomerdetail<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                          <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Customer Detail</h5>
                                                <label class="close" data-dismiss="modal" aria-label="Close">
                                                  <span class="font-weight-bold" aria-hidden="true">&times;</span>
                                                </label>
                                              </div>
                                              <div class="modal-body">
                                                <div class="container-fluid">
                                                  <div class="row">
                                                    <div class="col-md-6 text-dark"><label>Customer Name:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $customername; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Phone:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $phone; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Email:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $email; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Address:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $address; ?></div>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <!-- End Customer Modal -->

                                        <!-- Schedule Modal -->
                                        <div class="modal" id="alltimedetail<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                          <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Schedule Detail</h5>
                                                <label class="close" data-dismiss="modal" aria-label="Close">
                                                  <span class="font-weight-bold" aria-hidden="true">&times;</span>
                                                </label>
                                              </div>
                                              <div class="modal-body">
                                                <div class="container-fluid">
                                                  <div class="row">
                                                    <div class="col-md-6 text-dark"><label>Cleaning Date:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $cleaningdate; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Cleaning Time:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $cleaningtime; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Duration:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $duration; ?> hr</div>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <!-- End Schedule Modal -->

                                        <!-- Cleaning Detail Modal -->
                                        <div class="modal" id="allcleaningdetail<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                          <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Cleaning Detail</h5>
                                                <label class="close" data-dismiss="modal" aria-label="Close">
                                                  <span class="font-weight-bold" aria-hidden="true">&times;</span>
                                                </label>
                                              </div>
                                              <div class="modal-body">
                                                <div class="container-fluid">
                                                  <div class="row">
                                                    <div class="col-md-6 text-dark"><label>Cleaning Type:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $cleaningtype; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Rate:</label></div>
                                                    <div class="col-md-6 ml-auto">x<?php echo $rate; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Commission Rate:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $commissionrate; ?>%</div>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <!-- End Cleaning Detail Modal -->

                                        <!-- Room Type Modal -->
                                        <div class="modal" id="allroomdetail<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                          <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Room Detail</h5>
                                                <label class="close" data-dismiss="modal" aria-label="Close">
                                                  <span class="font-weight-bold" aria-hidden="true">&times;</span>
                                                </label>
                                              </div>
                                              <div class="modal-body">
                                                <div class="container-fluid">
                                                  <div class="row">
                                                    <div class="col-md-3 text-dark text-center"><label>Room</label></div>
                                                    <div class="col-md-3 text-dark text-center"><label>Unit Price</label></div>
                                                    <div class="col-md-3 text-dark text-center"><label>Quantity</label></div>
                                                    <div class="col-md-3 text-dark text-center"><label>Total</label></div>
                                                    <hr>
                                                    <?php 
                                                    for ($j = 0; $j < $runcountrt; $j++) 
                                                    { 
                                                      $arrayrt = mysqli_fetch_array($runrt);
                                                      $roomname = $arrayrt['RoomName'];
                                                      $price = $arrayrt['Price'] * $rate;
                                                      $quantity = $arrayrt['Quantity'];
                                                      $total = $price * $quantity;

                                                      echo "<div class='col-md-3 ml-auto text-center'>$roomname</div>
                                                            <div class='col-md-3 ml-auto text-center'>$$price</div>
                                                            <div class='col-md-3 ml-auto text-center'>$quantity</div>
                                                            <div class='col-md-3 ml-auto text-center'>$$total</div>
                                                            <hr>";
                                                    }
                                                     ?>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <!-- End Room Type Modal -->

                                        <!-- Cleaner Modal -->
                                        <div class="modal" id="allcleanerdetail<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                          <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Cleaner Detail</h5>
                                                <label class="close" data-dismiss="modal" aria-label="Close">
                                                  <span class="font-weight-bold" aria-hidden="true">&times;</span>
                                                </label>
                                              </div>
                                              <div class="modal-body">
                                                <div class="container-fluid">
                                                  <div class="row">                                                    
                                                    <div class="col-md-2 text-dark text-center"><label>ID</label></div>
                                                    <div class="col-md-3 text-dark text-center"><label>Name</label></div>
                                                    <div class="col-md-3 text-dark text-center"><label>Phone</label></div>
                                                    <div class="col-md-4 text-dark text-center"><label>Email</label></div>
                                                    <?php
                                                    for ($k = 0; $k < $runcountc; $k++) 
                                                    { 
                                                      $arrayc = mysqli_fetch_array($runc);
                                                      $staffid = $arrayc['StaffID'];
                                                      $name = $arrayc['FirstName'].' '.$arrayc['LastName'];
                                                      $phone = $arrayc['Phone'];
                                                      $email = $arrayc['Email'];

                                                      echo "<div class='col-md-2 ml-auto text-center'>$staffid</div>
                                                            <div class='col-md-3 ml-auto text-center'>$name</div>
                                                            <div class='col-md-3 ml-auto text-center'>$phone</div>
                                                            <div class='col-md-4 ml-auto text-center'>$email</div>
                                                            <hr>";
                                                    }
                                                     ?>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <!-- End Cleaner Modal -->

                                        <!-- Payment Modal -->
                                        <div class="modal" id="allpaymentdetail<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                          <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Payment Detail</h5>
                                                <label class="close" data-dismiss="modal" aria-label="Close">
                                                  <span class="font-weight-bold" aria-hidden="true">&times;</span>
                                                </label>
                                              </div>
                                              <div class="modal-body">
                                                <div class="container-fluid">
                                                  <div class="row">
                                                    <?php 

                                                    $arrayp = mysqli_fetch_array($runp);
                                                    $paymentid = $arrayp['PaymentID'];
                                                    $cardtype = $arrayp['CardType'];
                                                    $cardno = $arrayp['Cardno'];
                                                    $lastno = substr($cardno, -4);
                                                    $pdate = $arrayp['PaymentDate'];
                                                    $paymentdate = date('d M Y', strtotime($pdate)); 
                                                    $ptime = $arrayp['PaymentTime'];
                                                    $paymenttime = date('g:i A', strtotime($ptime));
                                                    $expmonth = $arrayp['Expmonth'];
                                                    $expyear = $arrayp['Expyear'];

                                                     ?>
                                                    <div class="col-md-6 text-dark"><label>Payment ID:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $paymentid; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Payment Date:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $paymentdate; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Payment Time:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $paymenttime; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Card Type:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $cardtype; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Card No:</label></div>
                                                    <div class="col-md-6 ml-auto">XXXXXXXXXXXX-<?php echo $lastno; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Expiration Date:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $expmonth.'/'.$expyear; ?></div>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <!-- End Payment Modal -->

                                        <?php } ?>
                                            
                                        </tbody>
                                    </table>
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

</body>

</html>