<?php 
session_start();
include('autoid.php');
include('connect.php');

// Check Manager and Adminstrator Account
if (!isset($_SESSION['StaffID']))
{
  echo "<script>alert('Please log in first')</script>";
  echo "<script>window.location='stafflogin.php'</script>";
}
elseif ($_SESSION['StaffRole'] == 'House Cleaner')
{
  echo "<script>alert('Only Manager and Administrator can view Customer List.')</script>";
  echo "<script>window.location='stafflogin.php'</script>";
}

// Retrieve Cleaning Type
$select = "SELECT * FROM customer";
$run = mysqli_query($connect, $select);
$runcount = mysqli_num_rows($run);

 ?>

 <!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>C&M | Customer List</title>

    <!-- Favicon -->
    <link href="../../User/assets/Img/logo.ico" rel="icon">

    <?php include('header.php') ?>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php $page = 'managecustomer'; include('sidebar.php'); ?>

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
                            <h6 class="m-0 font-weight-bold text-primary">Customer List</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Username</th>
                                            <th>Name</th>
                                            <th>DOB</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <th>Registered Date</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                    <?php 

                                    for ($i = 0; $i < $runcount; $i++) 
                                    { 
                                      $array = mysqli_fetch_array($run);
                                      $customerid = $array['CustomerID'];

                                      if ($array['Image'] == null)
                                      {
                                        $image = "../../User/assets/Img/profile.jpg";
                                      }
                                      else
                                      {
                                        $image = "../../User/assets/".$array['Image'];
                                      }

                                      $username = $array['UserName'];
                                      $firstname = $array['FirstName'];
                                      $lastname = $array['LastName'];
                                      $dob = $array['DOB'];
                                      $phone = $array['Phone'];
                                      $email = $array['Email'];
                                      $housenumber = $array['HouseNumber'];
                                      $street = $array['Street'];
                                      $city = $array['City'];
                                      $state = $array['State'];
                                      $postcode = $array['Postcode'];
                                      $registrationdate = $array['RegistrationDate'];
                                    
                                     ?>

                                        <tr>
                                            <td><?php echo $customerid; ?></td>
                                            <td><img src="<?php echo $image; ?>" width="70" height="70" class="rounded" alt="Profile"></td>
                                            <td style="word-break:break-word;"><?php echo $username; ?></td>
                                            <td><?php echo $firstname." ".$lastname; ?></td>
                                            <td><?php echo $dob; ?></td>
                                            <td style="word-break:break-word;"><?php echo $phone; ?></td>
                                            <td style="word-break:break-word;"><?php echo $email; ?></td>
                                            <td><?php echo $housenumber.", ".$street.", ".$city.", ".$state.", ".$postcode; ?></td>
                                            <td><?php echo $registrationdate; ?></td>
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