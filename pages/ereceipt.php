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
if (isset($_REQUEST['BookingID']))
{
  $_SESSION['BookingID'] = $_REQUEST['BookingID'];
}

$customerid = $_SESSION['CustomerID'];
$bookingid = $_SESSION['BookingID'];

// Retrieve Booking
$select = "SELECT * FROM booking b, cleaningtype ct, customer c, payment p, paymentmethod pm WHERE b.CleaningTypeID = ct.CleaningTypeID AND b.CustomerID = c.CustomerID AND b.BookingID = p.BookingID AND p.PaymentMethodID = pm.PaymentMethodID AND b.CustomerID = '$customerid' AND b.BookingID = '$bookingid'";
$run = mysqli_query($connect, $select);
$runcount = mysqli_num_rows($run);

if($runcount == 1)
{
    $array = mysqli_fetch_array($run);
    $bookingid = $array['BookingID'];
    $totalprice = $array['TotalPrice'];
    $equipment = $array['Equipment'];
    $rate = $array['Rate'];
    $customername = $array['FirstName'].' '.$array['LastName'];
    $housenumber = $array['HouseNumber'];  
    $street = $array['Street'];
    $city = $array['City'];
    $state = $array['State'];
    $postcode = $array['Postcode'];
    $paymentdate = $array['PaymentDate'];
    $date = date('d M Y', strtotime($paymentdate));
    $cardtype = $array['CardType'];
    $cardno = $array['Cardno'];
    $lastno = substr($cardno, -4);

    if ($equipment == 0) 
    {
      $servicefee = $totalprice - 15;
      $equipmentfee = 15;
    }
    else
    {
      $servicefee = $totalprice;
      $equipmentfee = 0;
    }
}   

unset($_SESSION['BookingID']);

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
                <h1 class="display-3 text-white animated zoomIn">E-Receipt</h1>
                <a href="bookinghistory.php" class="h4 text-white">Booking History </a>
                <a class="h4 text-white"> > </a>
                <a class="h4 text-primary"> Receipt</a>
            </div>
        </div>
    </div>
    <!-- Hero End -->

    <!-- Page Content -->
    <div class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="invoice-content">
                        <div class="invoice-item">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="invoice-logo">
                                        <img src="../assets/Img/logo.png" alt="logo">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <p class="invoice-details">
                                        <strong>Order : </strong><?php echo $bookingid ?><br>
                                        <strong>Issued : </strong><?php echo $date ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Invoice Item -->
                        <div class="invoice-item">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="invoice-info">
                                        <strong class="customer-text">Customer Detail</strong>
                                        <p class="invoice-details invoice-details-two">
                                            <?php echo $customername ?> <br>
                                            <?php echo $housenumber.', '.$street ?>,<br>
                                            <?php echo $city.', '.$state.', '.$postcode ?>, Myanmar <br>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Invoice Item -->
                        
                        <!-- Invoice Item -->
                        <div class="invoice-item">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="invoice-info">
                                        <div class="invoice-info"><br>
                                        <strong class="customer-text">Payment Method</strong>
                                        <p class="invoice-details invoice-details-two">
                                            <?php echo $cardtype ?> <br>
                                            XXXXXXXXXXXX-<?php echo $lastno ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Invoice Item -->
                        
                        <!-- Invoice Item -->
                        <div class="invoice-item invoice-table-wrap">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="invoice-table table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Description</th>
                                                    <th class="text-center">Quantity</th>
                                                    <th class="text-center">Unit Cost</th>
                                                    <th class="text-end">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                              <?php 
                                              //Retrieve Room Type
                                              $selectrt = "SELECT * FROM roomtype rt, roomtypedetail rd WHERE rt.RoomTypeID = rd.RoomTypeID AND rd.BookingID = '$bookingid'";
                                              $runrt = mysqli_query($connect, $selectrt);
                                              $runcountrt = mysqli_num_rows($runrt);

                                              for ($i = 0; $i < $runcountrt; $i++) 
                                              { 
                                                $arrayrt = mysqli_fetch_array($runrt);
                                                $roomname = $arrayrt['RoomName'];
                                                $price = $arrayrt['Price'] * $rate;
                                                $quantity = $arrayrt['Quantity'];

                                                echo '<tr>
                                                          <td>'.$roomname.'</td>
                                                          <td class="text-center">'.$quantity.'</td>
                                                          <td class="text-center">$'.$price.'</td>
                                                          <td class="text-end">$'.$quantity * $price.'</td>
                                                      </tr>';
                                              }
                                               ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-4 ms-auto">
                                    <div class="table-responsive">
                                        <table class="invoice-table-two table">
                                            <tbody>
                                            <tr>
                                                <th>Subtotal:</th>
                                                <td><span>$<?php echo $servicefee ?> </span></td>
                                            </tr>
                                            <tr>
                                                <th>Equipment Fee:</th>
                                                <td><span>$<?php echo $equipmentfee ?></span></td>
                                            </tr>
                                            <tr>
                                                <th>Total Amount:</th>
                                                <td><span>$<?php echo $totalprice ?> </span></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Invoice Item -->
                        
                        <!-- Invoice Information -->
                        <div class="other-info">
                            <p class="text-muted mb-0 text-center">Thank you for booking with us!</p>
                        </div>
                        <!-- /Invoice Information -->
                        
                    </div>
                </div>
            </div>

        </div>
        
    </div>      
    <!-- /Page Content -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded back-to-top"><i class="bi bi-arrow-up"></i></a>

</body>

</html>

<?php 
include('footer.php');
 ?>