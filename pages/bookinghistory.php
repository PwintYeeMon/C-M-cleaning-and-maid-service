<?php 
$page = 'bookinghistory';
include('header.php');
include('../../Staff/pages/connect.php');

// Check Customer Account
if (!isset($_SESSION['CustomerID']))
{
  echo "<script>alert('Please log in first')</script>";
  echo "<script>window.location='customerlogin.php'</script>";
}
else
{
    $customerid = $_SESSION['CustomerID'];
}

if(isset($_SESSION['BookingID']))
{
    unset($_SESSION['BookingID']);
}

// Retrieve Booking
$select = "SELECT * FROM booking b, cleaningtype ct WHERE b.CleaningTypeID = ct.CleaningTypeID AND b.CustomerID = '$customerid'";
$run = mysqli_query($connect, $select);
$runcount = mysqli_num_rows($run);

// Form Submission 
if(isset($_POST['btnsubmit']))
{
  $feedback = addslashes($_POST['txtfeedback']);
  $bookingid = $_POST['txtbookingid'];

  if(!empty($_POST["txtfeature"])) 
  {
    $status = "To be featured";
  }
  else
  {
    $status = "To be reviewed";
  }
  
  // Add Feedback
  $update = "UPDATE booking SET Feedback = '$feedback', FeedbackStatus = '$status' WHERE BookingID = '$bookingid'";
  $query = mysqli_query($connect, $update);

  if ($query)
  {
    echo "<script>alert('Feedback Saved')</script>";
    echo "<script>window.location='bookinghistory.php'</script>";
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
                <h1 class="display-3 text-white animated zoomIn">Booking History</h1>
                <a href="index.php" class="h4 text-white">Home </a>
                <a class="h4 text-white"> > </a>
                <a class="h4 text-primary">History</a>
            </div>
        </div>
    </div>
    <!-- Hero End -->


    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- DataTales -->
                    <div class="card shadow mb-4">
                        <div class="card-header d-flex justify-content-between">
                            <div class="p-2">
                                <h6 class="m-0 font-weight-bold text-primary py-2">Booking History</h6>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped text-center" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Cleaning Date</th>
                                            <th>Cleaning Time</th>
                                            <th>Cleaning Type</th>
                                            <th>Duration (hr)</th>
                                            <th>Price ($)</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                    <?php 

                                    for ($i = 0; $i < $runcount; $i++) 
                                    { 
                                      $array = mysqli_fetch_array($run);
                                      $bookingid = $array['BookingID'];
                                      $booking = $array['BookingDate'];
                                      $bookingdate = date('d M Y', strtotime($booking));
                                      $equipment = $array['Equipment'];
                                      $runcountp = 0;

                                      if ($equipment == 0) 
                                      {
                                        $equipment = "Included";
                                      }
                                      else
                                      {
                                        $equipment = "Not Included";
                                      }

                                      $cleaningdate = $array['CleaningDate'];
                                      $date = date('d M Y', strtotime($cleaningdate));
                                      $cleaningtime = $array['CleaningTime'];
                                      $time = date('g:i A', strtotime($cleaningtime));
                                      $cleaningtype = $array['CleaningType'];
                                      $duration = $array['TotalDuration_hr'];
                                      $price = $array['TotalPrice'];
                                      $status = $array['Status'];

                                      //Retrieve Cleaner
                                      $selectc = "SELECT * FROM staff s, bookingdetail bd WHERE s.StaffID = bd.StaffID AND bd.BookingID = '$bookingid'";
                                      $runc = mysqli_query($connect, $selectc);
                                      $runcountc = mysqli_num_rows($runc);

                                      //Retrieve Room Type
                                      $selectrt = "SELECT * FROM roomtype rt, roomtypedetail rd WHERE rt.RoomTypeID = rd.RoomTypeID AND rd.BookingID = '$bookingid'";
                                      $runrt = mysqli_query($connect, $selectrt);
                                      $runcountrt = mysqli_num_rows($runrt);

                                     ?>

                                        <tr>
                                            <td><?php echo $bookingid; ?></td>
                                            <td><?php echo $date; ?></td>
                                            <td><?php echo $time; ?></td>
                                            <td><?php echo $cleaningtype; ?></td>
                                            <td><?php echo $duration; ?></td>
                                            <td><?php echo $price; ?></td>

                                            <?php 

                                            echo '<td class="text-';

                                            if ($status == "Declined" || $status == "Cancelled") 
                                            {
                                              echo "danger";
                                            }
                                            elseif ($status == "Processing" || $status == "Approved")
                                            {
                                              echo "warning";
                                            }
                                            elseif ($status == "Done" || $status == "Paid")
                                            {
                                              echo "success";
                                            }

                                            echo '">'.$status.'</td>
                                                  <td><a data-toggle="modal" data-target="#bookingdetail'.$i.'"><i class="fas fa-ellipsis-h text-primary" title="View Detail"></i></a>';

                                            if ($status == "Processing") 
                                            {
                                              echo '&nbsp&nbsp&nbsp | &nbsp&nbsp&nbsp<a href="cancelbooking.php?BookingID='.$bookingid.'" onclick="return confirm(\'Click OK if you are sure you are cancelling this booking\')"><i class="fas fa-window-close text-primary" title="Cancel"></i></a></td>';
                                            }
                                            elseif ($status == "Declined" || $status == "Cancelled")
                                            {
                                              echo '</td>';
                                            }
                                            else
                                            {
                                              //Retrieve Payment
                                              $selectp = "SELECT * FROM payment p, paymentmethod pm WHERE p.PaymentMethodID = pm.PaymentMethodID AND p.BookingID = '$bookingid'";
                                              $runp = mysqli_query($connect, $selectp);
                                              $runcountp = mysqli_num_rows($runp);
                                              if ($runcountp == 0) 
                                              { 
                                                echo '&nbsp&nbsp&nbsp | &nbsp&nbsp&nbsp<a href="payment.php?BookingID='.$bookingid.'"><i class="fas fa-hand-holding-usd text-primary" title="Pay"></i></a></td>';
                                              }
                                              else
                                              {
                                                $arrayp = mysqli_fetch_array($runp);                                                
                                                $cardtype = $arrayp['CardType'];
                                                $cardno = $arrayp['Cardno'];
                                                $lastno = substr($cardno, -4);
                                                $pdate = $arrayp['PaymentDate'];
                                                $paymentdate = date('d M Y', strtotime($pdate)); 
                                                $ptime = $arrayp['PaymentTime'];
                                                $paymenttime = date('g:i A', strtotime($ptime));

                                                echo '&nbsp&nbsp&nbsp | &nbsp&nbsp&nbsp<a data-toggle="modal" data-target="#feedback'.$i.'"><i class="fas fa-comment-dots text-primary" title="Review"></i></a>&nbsp&nbsp&nbsp | &nbsp&nbsp&nbsp<a href="ereceipt.php?BookingID='.$bookingid.'"><i class="fas fa-file-invoice-dollar text-primary" title="E-recipt"></i></a></td>';
                                              }
                                            }
                                             ?>
                                        </tr>

                                        <!-- Modal -->
                                        <div class="modal" id="bookingdetail<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                          <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Booking Detail</h5>
                                                <label class="close" data-dismiss="modal" aria-label="Close">
                                                  <span class="font-weight-bold" aria-hidden="true">&#10006;</span>
                                                </label>
                                              </div>
                                              <div class="modal-body">
                                                <div class="container-fluid">
                                                  <div class="row">
                                                    <div class="col-md-6 text-dark"><label>Booking ID:</label></div>
                                                    <div class="col-md-6 ml-auto text-primary"><?php echo $bookingid; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Booking Date:</label></div>
                                                    <div class="col-md-6 ml-auto text-success"><?php echo $bookingdate; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Status:</label></div>
                                                    <div class="col-md-6 ml-auto text-secondary text-uppercase"><b><?php echo $status; ?></b></div>
                                                    <div class="col-md-12">&nbsp</div>

                                                    <?php if ($runcountp == 1){ ?>

                                                    <hr>

                                                    <div class="col-md-6 text-dark"><label>Payment Method:</label></div>
                                                    <div class="col-md-6 ml-auto text-primary"><?php echo $cardtype; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Card Number:</label></div>
                                                    <div class="col-md-6 ml-auto text-primary">XXXXXXXXXXXX-<?php echo $lastno; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Payment Date:</label></div>
                                                    <div class="col-md-6 ml-auto text-success"><?php echo $paymentdate; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Payment Time:</label></div>
                                                    <div class="col-md-6 ml-auto text-success"><?php echo $paymenttime; ?></div>
                                                    <div class="col-md-12">&nbsp</div>

                                                  <?php } ?>

                                                    <hr>

                                                    <div class="col-md-6 text-dark"><label>Cleaning Date:</label></div>
                                                    <div class="col-md-6 ml-auto text-success"><?php echo $date; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Cleaning Time:</label></div>
                                                    <div class="col-md-6 ml-auto text-success"><?php echo $time; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Duration:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $duration; ?> hr</div>
                                                    <div class="col-md-6 text-dark"><label>Price:</label></div>
                                                    <div class="col-md-6 ml-auto text-danger">$<?php echo $price; ?></div>
                                                    <div class="col-md-6 text-dark"><label>Equipment:</label></div>
                                                    <div class="col-md-6 ml-auto"><?php echo $equipment; ?></div>
                                                    <div class="col-md-12">&nbsp</div>

                                                    <div class="col-md-6 text-dark"><label>Rooms:</label></div>
                                                    <?php 
                                                    for ($j = 0; $j < $runcountrt; $j++) 
                                                    { 
                                                      $arrayrt = mysqli_fetch_array($runrt);
                                                      $roomname = $arrayrt['RoomName'];
                                                      $quantity = $arrayrt['Quantity'];

                                                      echo "<div class='col-md-6 ml-auto'>$quantity $roomname(s)</div>";

                                                      if ($j != $runcountrt - 1) 
                                                      {
                                                          echo "<div class='col-md-6'>&nbsp</div>";
                                                      }
                                                    }

                                                    echo '<div class="col-md-12">&nbsp</div>
                                                          <div class="col-md-6 text-dark"><label>Cleaner(s):</label></div>';

                                                    for ($k = 0; $k < $runcountc; $k++) 
                                                    { 
                                                      $arrayc = mysqli_fetch_array($runc);
                                                      $staffname = $arrayc['FirstName'].' '.$arrayc['LastName'];

                                                      echo "<div class='col-md-6 ml-auto'>$staffname</div>";

                                                      if ($k != $runcountc - 1) 
                                                      {
                                                          echo "<div class='col-md-6'>&nbsp</div>";
                                                      }
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
                                        <!-- End Modal -->

                                        <!-- Modal -->
                                        <div class="modal" id="feedback<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                          <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Feedback</h5>
                                                <label class="close" data-dismiss="modal" aria-label="Close">
                                                  <span class="font-weight-bold" aria-hidden="true">&#10006;</span>
                                                </label>
                                              </div>
                                              <div class="modal-body">
                                                <div class="container-fluid">
                                                  <form action="bookinghistory.php" method="POST">
                                                    <div class="row g-3">
                                                        <input class="form-check-input" type="text" name="txtbookingid" value="<?php echo $bookingid; ?>" hidden>
                                                        <?php
                                                        //Retrieve Feedback
                                                        $selectf = "SELECT Feedback FROM booking WHERE BookingID = '$bookingid'";
                                                        $runf = mysqli_query($connect, $selectf);
                                                        $arrayf = mysqli_fetch_array($runf);
                                                        $feedback = $arrayf['Feedback'];
                                                        if ($feedback == null){ ?>
                                                            <div class="col-12">
                                                                <h5 class="text-dark">&nbsp&nbsp&nbsp Please leave your feedback below.</h5>
                                                            </div>
                                                            <div class="col-12">
                                                                <textarea class="form-control border-0 bg-light px-4 py-3" name="txtfeedback" autocomplete="off" rows="5" required></textarea>
                                                            </div>
                                                            <div class="col-12">
                                                                <input type="checkbox" name="txtfeature"> I want my feedback to be featured on home page <br> &nbsp (Profile photo must be uploaded to be featured)
                                                            </div>
                                                            <div class="col-6"></div>
                                                            <div class="col-3">
                                                                <button class="btn btn-primary w-100" name="btnsubmit" type="submit">Save</button>
                                                            </div>
                                                            <div class="col-3">
                                                                <button class="btn btn-outline-primary w-100" data-dismiss="modal" type="reset">Close</button>
                                                            </div>
                                                        <?php }else{ ?>
                                                            <div class="col-12">
                                                                <h5 class="text-dark">&nbsp&nbsp&nbsp Your Feedback:</h5>
                                                            </div>
                                                            <div class="col-12">
                                                                <textarea class="form-control border-0 bg-light px-4 py-3" readonly><?php echo $feedback; ?></textarea>
                                                            </div>
                                                            <div class="col-6"></div>
                                                            <div class="col-3"></div>
                                                            <div class="col-3">
                                                                <button class="btn btn-outline-primary w-100" data-dismiss="modal" type="reset">Close</button>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                  </form>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                        <!-- End Modal -->

                                    <?php } ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>                  

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded back-to-top"><i class="bi bi-arrow-up"></i></a>
<br><br>
</body>

</html>

<?php 
include('footer.php');
 ?>