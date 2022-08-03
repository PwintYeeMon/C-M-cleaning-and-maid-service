<?php 
$page = 'profile';
include('header.php');
include('../../Staff/pages/connect.php');

// Check Customer Account
if (!isset($_SESSION['CustomerID']))
{
  echo "<script>window.alert('Please log in first')</script>";
  echo "<script>window.location='customerlogin.php'</script>";
}
else
{
	$customerid = $_SESSION['CustomerID'];
}

// Retrieve Customer
$select = "SELECT * FROM customer WHERE CustomerID = '$customerid'";
$run = mysqli_query($connect, $select);
$runcount = mysqli_num_rows($run);

$array = mysqli_fetch_array($run);

if ($array['Image'] == null) 
{
    $image = "../assets/Img/profile.jpg";
}
else
{
    $image = "../assets/".$array['Image'];
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

 ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>C&M | Profile</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">
</head>

<body>
    <!-- Hero Start -->
    <div class="container-fluid bg-primary py-5 hero-header mb-5">
        <div class="row py-3">
            <div class="col-12 text-center">
                <h1 class="display-3 text-white animated zoomIn">Profile</h1>
                <a href="index.php" class="h4 text-white">Home </a>
                <a class="h4 text-white"> > </a>
                <a class="h4 text-primary"> Profile</a>
            </div>
        </div>
    </div>
    <!-- Hero End -->


    <!-- Contact Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-xl-2 col-lg-6 wow slideInUp" data-wow-delay="0.1s">
                    
                </div>
                <div class="col-xl-8 col-lg-6 wow slideInUp" data-wow-delay="0.3s">
                    <form action="customerprofile.php" method="POST">
                        <div class="row g-3">
                            <div class="col-4"></div>
                            <div class="col-3">
                                <img class="img-fluid rounded img-thumbnail" src="<?php echo $image ?>" alt="Profile">
                            </div>                            
                            <div class="col-5"></div>
                            <div class="col-12">
                                <label style="color:black;">&nbsp&nbsp&nbsp Username:</label>
                            </div>
                            <div class="col-12">
                                <input type="text" class="form-control-plaintext border-0 bg-light px-4" style="height: 55px;" value="<?php echo $username ?>" readonly>
                            </div>
                            <div class="col-6">
                                <label style="color:black;">&nbsp&nbsp&nbsp First Name:</label>
                            </div>
                            <div class="col-6">
                                <label style="color:black;">&nbsp&nbsp&nbsp Last Name:</label>
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control-plaintext border-0 bg-light px-4" style="height: 55px;" value="<?php echo $firstname ?>" readonly>
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control-plaintext border-0 bg-light px-4" style="height: 55px;" value="<?php echo $lastname ?>" readonly>
                            </div>
                            <div class="col-4">
                                <label style="color:black;">&nbsp&nbsp&nbsp Date of Birth:</label>
                            </div>
                            <div class="col-4">
                                <label style="color:black;">&nbsp&nbsp&nbsp Phone:</label>
                            </div>
                            <div class="col-4">
                                <label style="color:black;">&nbsp&nbsp&nbsp Email:</label>
                            </div>
                            <div class="col-4">
                                <input type="date" class="form-control-plaintext border-0 bg-light px-4" style="height: 55px;" value="<?php echo $dob ?>" readonly>
                            </div>
                            <div class="col-4">
                                <input type="tel" class="form-control-plaintext border-0 bg-light px-4" style="height: 55px;" value="<?php echo $phone ?>" readonly>
                            </div>
                            <div class="col-4">
                                <input type="email" class="form-control-plaintext border-0 bg-light px-4" style="height: 55px;" value="<?php echo $email ?>" readonly>
                            </div>
                            <div class="col-12">
                                <label style="color:black;">&nbsp&nbsp&nbsp Address:</label>
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control-plaintext border-0 bg-light px-4" style="height: 55px;" value="<?php echo $housenumber ?>" readonly>
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control-plaintext border-0 bg-light px-4" style="height: 55px;" value="<?php echo $street ?>" readonly>
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control-plaintext border-0 bg-light px-4" style="height: 55px;" value="<?php echo $city ?>" readonly>
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control-plaintext border-0 bg-light px-4" style="height: 55px;" value="<?php echo $state ?>" readonly>
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control-plaintext border-0 bg-light px-4" style="height: 55px;" value="<?php echo $postcode ?>" readonly>
                            </div>
                            <div class="col-6">
                                <a class="btn btn-primary w-100 py-3" href="customerupdate.php">Update</a>
                            </div>
                            <div class="col-6">
                                <a class="btn btn-outline-dark w-100 py-3" href="logout.php">Log out</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-xl-2 col-lg-12 wow slideInUp" data-wow-delay="0.6s">
                    
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded back-to-top"><i class="bi bi-arrow-up"></i></a>

</body>

</html>

<?php 
include('footer.php');
 ?>