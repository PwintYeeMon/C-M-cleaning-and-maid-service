<?php 
session_start();
include('connect.php');

// Check Manager Account
$staffid = $_SESSION['StaffID'];
$role = $_SESSION['StaffRole'];
if (!isset($_SESSION['StaffID']))
{
  echo "<script>window.alert('Please log in first')</script>";
  echo "<script>window.location='stafflogin.php'</script>";
}
elseif ($role != 'Manager')
{
  echo "<script>window.alert('Only Manager can have access to staff leaves.')</script>";
  echo "<script>window.location='stafflogin.php'</script>";
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

    <title>C&M | Payment List</title>

    <!-- Favicon -->
    <link href="../../User/assets/Img/logo.ico" rel="icon">

    <?php include('header.php') ?>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php $page = 'managepayment'; include('sidebar.php'); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include('topbar.php') ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                      <a class="nav-link active" id="new-tab" data-toggle="tab" href="#new" role="tab" aria-controls="new" aria-selected="true">This Month</a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a class="nav-link" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="false">Previous</a>
                    </li>
                  </ul>                      

                    <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade show active" id="new" role="tabpanel" aria-labelledby="new-tab">

                        <!-- DataTables -->
                        <div class="card shadow mb-4">
                            <div class="card-header">
                              <h6 class="m-0 font-weight-bold text-primary py-2">This Month Payment List</h6>
                            </div>
                            
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Payment Date</th>
                                                <th>Payment Time</th>
                                                <th>Card Type</th>
                                                <th>Card No</th>
                                                <th>Expiration Date</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                        <?php 

                                        $today = date('Y-m-d');
                                        $firstday = date('Y-m-01', strtotime($today));
                                        $lastday = date('Y-m-t', strtotime($today));
                                        // Retrieve Feedback
                                        $select = "SELECT * FROM booking b, payment p, paymentmethod pm WHERE b.BookingID = p.BookingID AND p.PaymentMethodID = pm.PaymentMethodID AND p.PaymentDate >= '$firstday' AND p.PaymentDate <= '$lastday'";
                                        $run = mysqli_query($connect, $select);
                                        $runcount = mysqli_num_rows($run);

                                        for ($i = 0; $i < $runcount; $i++) 
                                        {                                           
                                          $array = mysqli_fetch_array($run);
                                          $paymentid = $array['PaymentID'];
                                          $paymentdate = $array['PaymentDate'];
                                          $pdate = date('d M Y', strtotime($paymentdate));
                                          $paymenttime = $array['PaymentTime'];
                                          $ptime = date('g:i A', strtotime($paymenttime));
                                          $cardtype = $array['CardType'];
                                          $cardno = $array['Cardno'];
                                          $lastno = substr($cardno, -4);
                                          $expirationdate = $array['Expmonth'].'/'.$array['Expyear'];
                                          $price = $array['TotalPrice'];
                                         ?>

                                            <tr>
                                                <td><?php echo $paymentid; ?></td>
                                                <td><?php echo $pdate; ?></td>
                                                <td><?php echo $ptime; ?></td>
                                                <td><?php echo $cardtype; ?></td>
                                                <td style="word-break:break-word;">XXXXXXXXXXXX-<?php echo $lastno; ?></td>
                                                <td><?php echo $expirationdate; ?></td>
                                                <td>$<?php echo $price; ?></td>   
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
                              <h6 class="m-0 font-weight-bold text-primary py-2">Previous Payment List</h6>
                            </div>
                            
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center" id="dataTable1" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Payment Date</th>
                                                <th>Payment Time</th>
                                                <th>Card Type</th>
                                                <th>Card No</th>
                                                <th>Expiration Date</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                        <?php 

                                        // Retrieve Feedback
                                        $select = "SELECT * FROM booking b, payment p, paymentmethod pm WHERE b.BookingID = p.BookingID AND p.PaymentMethodID = pm.PaymentMethodID AND p.PaymentDate < '$firstday'";
                                        $run = mysqli_query($connect, $select);
                                        $runcount = mysqli_num_rows($run);

                                        for ($i = 0; $i < $runcount; $i++) 
                                        {                                           
                                          $array = mysqli_fetch_array($run);
                                          $paymentid = $array['PaymentID'];
                                          $paymentdate = $array['PaymentDate'];
                                          $pdate = date('d M Y', strtotime($paymentdate));
                                          $paymenttime = $array['PaymentTime'];
                                          $ptime = date('g:i A', strtotime($paymenttime));
                                          $cardtype = $array['CardType'];
                                          $cardno = $array['Cardno'];
                                          $lastno = substr($cardno, -4);
                                          $expirationdate = $array['Expmonth'].'/'.$array['Expyear'];
                                          $price = $array['TotalPrice'];
                                         ?>

                                            <tr>
                                                <td><?php echo $paymentid; ?></td>
                                                <td><?php echo $pdate; ?></td>
                                                <td><?php echo $ptime; ?></td>
                                                <td><?php echo $cardtype; ?></td>
                                                <td style="word-break:break-word;">XXXXXXXXXXXX-<?php echo $lastno; ?></td>
                                                <td><?php echo $expirationdate; ?></td>
                                                <td>$<?php echo $price; ?></td>   
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