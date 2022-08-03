<?php 
session_start();
include('connect.php');

// Check Administrator Account
$staffid = $_SESSION['StaffID'];
$role = $_SESSION['StaffRole'];
if (!isset($_SESSION['StaffID']))
{
  echo "<script>window.alert('Please log in first')</script>";
  echo "<script>window.location='stafflogin.php'</script>";
}
elseif ($role != 'Administrator')
{
  echo "<script>window.alert('Only Administrator can have access to staff leaves.')</script>";
  echo "<script>window.location='stafflogin.php'</script>";
}

// Update Form Submission 
if(isset($_POST['txtstatus']))
{
  $bookingid = $_POST['txtbookingid'];
  $feedbackstatus = $_POST['txtstatus'];

  // Update Status
  $update = "UPDATE booking SET FeedbackStatus = '$feedbackstatus' WHERE BookingID = '$bookingid'";
  $query = mysqli_query($connect, $update);

  if($query)
  {
    echo "<script>alert('Status Update Successful')</script>";
    echo "<script>window.location='managefeedback.php'</script>";
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

    <title>C&M | Feedback List</title>

    <!-- Favicon -->
    <link href="../../User/assets/Img/logo.ico" rel="icon">

    <?php include('header.php') ?>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php $page = 'managefeedback'; include('sidebar.php'); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include('topbar.php') ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                      <a class="nav-link active" id="new-tab" data-toggle="tab" href="#new" role="tab" aria-controls="new" aria-selected="true">New</a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a class="nav-link" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="false">All</a>
                    </li>
                  </ul>                      

                    <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade show active" id="new" role="tabpanel" aria-labelledby="new-tab">

                        <!-- DataTables -->
                        <div class="card shadow mb-4">
                            <div class="card-header">
                              <h6 class="m-0 font-weight-bold text-primary py-2">New Feedback List</h6>
                            </div>
                            
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Image</th>
                                                <th>Customer</th>
                                                <th>Feedback</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                        <?php 

                                        // Retrieve Feedback
                                        $select = "SELECT * FROM booking b, customer c WHERE b.CustomerID = c.CustomerID AND (b.FeedbackStatus = 'To be featured' OR b.FeedbackStatus = 'To be reviewed')";
                                        $run = mysqli_query($connect, $select);
                                        $runcount = mysqli_num_rows($run);

                                        for ($i = 0; $i < $runcount; $i++) 
                                        {                                           
                                          $array = mysqli_fetch_array($run);
                                          $bookingid = $array['BookingID'];
                                          $customerid = $array['CustomerID'];

                                          $selectfeatured = "SELECT * FROM booking b, customer c WHERE b.CustomerID = c.CustomerID AND c.CustomerID = '$customerid' AND b.FeedbackStatus = 'Featured'";
                                          $runfeatured = mysqli_query($connect, $selectfeatured);
                                          $runcountfeatured = mysqli_num_rows($runfeatured);

                                          if ($array['Image'] == null)
                                          {
                                            $image = "../../User/assets/Img/profile.jpg";
                                          }
                                          else
                                          {
                                            $image = "../../User/assets/".$array['Image'];
                                          }

                                          $customername = $array['FirstName'].' '.$array['LastName'];
                                          $username = $array['UserName'];
                                          $feedback = $array['Feedback'];
                                          $status = $array['FeedbackStatus'];
                                         ?>

                                            <tr>
                                                <td><?php echo $bookingid; ?></td>
                                                <td><img src="<?php echo $image; ?>" width="70" height="70" class="rounded" alt="Profile"></td>
                                                <td style="word-break:break-word;"><?php echo $customername; ?></td>
                                                <td style="word-break:break-word;"><?php echo $feedback; ?></td>
                                                <td width="19%">
                                              
                                              <?php 

                                              if ($status == "To be featured" && $array['Image'] != null && $runcountfeatured == 0)
                                              {
                                                $selectstatus = "SELECT COUNT(FeedbackStatus) FROM booking b, customer c WHERE b.CustomerID = c.CustomerID AND c.CustomerID = '$customerid' AND b.FeedbackStatus = 'Featured'";
                                                echo '<form action="managefeedback.php" method="POST">
                                                        <input type="text" name="txtbookingid" value="'.$bookingid.'" hidden>
                                                        <select class="form-control bg-light text-warning border-0" name="txtstatus" onchange="this.form.submit()" required>
                                                          <option value="" class="text-warning" disabled selected>To be featured</option>
                                                          <option value="Checked" class="text-success">Checked</option>
                                                          <option value="Featured" class="text-primary">Feature</option>
                                                        </select>
                                                      </form>';
                                              }
                                              else
                                              {
                                                echo '<form action="managefeedback.php" method="POST">
                                                        <input type="text" name="txtbookingid" value="'.$bookingid.'" hidden>
                                                        <select class="form-control bg-light text-warning border-0" name="txtstatus" onchange="this.form.submit()" required>
                                                          <option value="" class="text-warning" disabled selected>'.$status.'</option>
                                                          <option value="Checked" class="text-success">Checked</option>
                                                        </select>
                                                      </form>';
                                              }

                                               ?>
                                                </td>                                     
                                            </tr>

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
                              <h6 class="m-0 font-weight-bold text-primary py-2">All Feedback List</h6>
                            </div>
                            
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center" id="dataTable1" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Image</th>
                                                <th>Customer</th>
                                                <th>Feedback</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                        <?php 

                                        // Retrieve Feedback
                                        $select = "SELECT * FROM booking b, customer c WHERE b.CustomerID = c.CustomerID AND b.Feedback <> ''";
                                        $run = mysqli_query($connect, $select);
                                        $runcount = mysqli_num_rows($run);

                                        for ($i = 0; $i < $runcount; $i++) 
                                        {                                           
                                          $array = mysqli_fetch_array($run);
                                          $bookingid = $array['BookingID'];
                                          $customerid = $array['CustomerID']; 

                                          if ($array['Image'] == null)
                                          {
                                            $image = "../../User/assets/Img/profile.jpg";
                                          }
                                          else
                                          {
                                            $image = "../../User/assets/".$array['Image'];
                                          }

                                          $customername = $array['FirstName'].' '.$array['LastName'];
                                          $username = $array['UserName'];
                                          $feedback = $array['Feedback'];
                                          $feedbackstatus = $array['FeedbackStatus'];

                                          if ($feedbackstatus == 'To be featured' || $feedbackstatus == 'To be reviewed') 
                                          {
                                            $colour = 'warning';
                                          }
                                          elseif ($feedbackstatus == 'Featured')
                                          {
                                            $colour = 'primary';
                                          }
                                          elseif ($feedbackstatus == 'Checked')
                                          {
                                            $colour = 'success';
                                          }
                                         ?>

                                            <tr>
                                                <td><?php echo $bookingid; ?></td>
                                                <td><img src="<?php echo $image; ?>" width="70" height="70" class="rounded" alt="Profile"></td>
                                                <td style="word-break:break-word;"><?php echo $customername; ?></td>
                                                <td style="word-break:break-word;"><?php echo $feedback; ?></td>
                                                <td class="text-<?php echo $colour; ?>"><?php echo $feedbackstatus; ?></td>
                                            </tr>

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