<?php 
session_start();
include('autoid.php');
include('connect.php');

// Check Manager and Adminstrator Account
if (!isset($_SESSION['StaffID']))
{
  echo "<script>window.alert('Please log in first')</script>";
  echo "<script>window.location='stafflogin.php'</script>";
}
elseif ($_SESSION['StaffRole'] != 'House Cleaner')
{
  echo "<script>window.alert('Only House Cleaner can view Commission Rate List.')</script>";
  echo "<script>window.location='stafflogin.php'</script>";
}

// Retrieve Cleaning Type
$selectct = "SELECT * FROM cleaningtype";
$runct = mysqli_query($connect, $selectct);
$runcountct = mysqli_num_rows($runct);

// Retrieve Room Type
$selectrt = "SELECT * FROM roomtype";
$runrt = mysqli_query($connect, $selectrt);
$runcountrt = mysqli_num_rows($runrt);

 ?>

 <!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>C&M | Commission Rate List</title>

    <!-- Favicon -->
    <link href="../../User/assets/Img/logo.ico" rel="icon">

    <?php include('header.php') ?>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php $page = 'commissionrate'; include('sidebar.php'); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include('topbar.php') ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- DataTales -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Commission Rate List</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">Room</th>
                                            <th colspan="<?php echo $runcountct; ?>">Price</th>
                                            <th colspan="<?php echo $runcountct; ?>">Duration</th>
                                        </tr>
                                        <tr>
                                          <?php 
                                            for ($i = 0; $i < 2 ; $i++) 
                                            { 
                                              for ($j = 0; $j < $runcountct; $j++) 
                                              { 
                                                $array = mysqli_fetch_array($runct);
                                                $cleaningtype = $array['CleaningType'];
                                                $rate = $array['Rate'];
                                                $commissionrate = $array['CommissionRate'];                                                
                                                $cleaningrate[$j] = $rate;
                                                $cleaningcommissionrate[$j] = $rate * $commissionrate;

                                                echo '<th>'.$cleaningtype.'</th>';
                                              }
                                              $array = mysqli_data_seek($runct, 0);
                                            }
                                           ?>
                                        </tr>
                                    </thead>

                                    <tbody>

                                    <?php 

                                    for ($k = 0; $k < $runcountrt; $k++) 
                                    { 
                                      $array = mysqli_fetch_array($runrt);
                                      $roomname = $array['RoomName'];
                                      $duration = $array['Duration_hr'];
                                      $price = $array['Price'];
                                    
                                     ?>

                                        <tr>
                                            <td><b><?php echo $roomname; ?></b></td>
                                            <?php 
                                            for ($i = 0; $i < $runcountct; $i++) 
                                            { 
                                                echo '<td>'.number_format((float)$price * $cleaningcommissionrate[$i], 2, '.', '').' $</td>';
                                            }
                                            for ($i = 0; $i < $runcountct; $i++) 
                                            { 
                                                $rate = $duration * $cleaningrate[$i];
                                                $mins = ceil($rate * 60) % 60;
                                                echo '<td>'.$mins.' mins</td>';
                                            }
                                             ?>
                                        </tr>

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