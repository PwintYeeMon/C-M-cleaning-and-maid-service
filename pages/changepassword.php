<?php 
$page = 'changepassword';
include('header.php');
include('../../Staff/pages/connect.php');

// Check Account
if(!isset($_SESSION['CustomerID']))
{
  echo "<script>alert('Please log in first')</script>";
  echo "<script>window.location='customerlogin.php'</script>";
}
else
{
	$customerid = $_SESSION['CustomerID'];
}

// Form Submission
if(isset($_POST['btnchange']))
{
	$select = "SELECT * FROM customer WHERE CustomerID = '$customerid'";
	$run = mysqli_query($connect, $select);
	$runcount = mysqli_num_rows($run);

	$array = mysqli_fetch_array($run);
	$oldpassword = $array['Password'];

	$oldpassword0 = $_POST['txtoldpassword'];
	$hashedoldpassword = md5($oldpassword0);
	$password = $_POST['txtpassword'];
	$hashedpassword = md5($password);

	// Check Password
	if($oldpassword != $hashedoldpassword)
	{    
		echo "<script>alert('Please check your old password and try again')</script>";
		echo "<script>window.location='changepassword.php'</script>";
	}

	// Update Password
    $update = "UPDATE customer
                SET Password = '$hashedpassword'
                WHERE CustomerID = '$customerid'";

    $query = mysqli_query($connect, $update);

	if ($query)
	{
		echo "<script>window.alert('Password Changed')</script>";
		echo "<script>window.location='customerprofile.php'</script>";
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
                <a href="customerprofile.php" class="h4 text-white">Profile </a>
                <a class="h4 text-white"> > </a>
                <a class="h4 text-primary"> Change Password</a>
            </div>
        </div>
    </div>
    <!-- Hero End -->


    <!-- Contact Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-xl-4 col-lg-6 wow slideInUp" data-wow-delay="0.1s">
                    
                </div>
                <div class="col-xl-4 col-lg-6 wow slideInUp" data-wow-delay="0.3s">
                    <form action="changepassword.php" method="POST">
                        <div class="row g-3">
                            <div class="col-12">
                                <label style="color:black;">&nbsp&nbsp&nbsp Old Password:</label>
                            </div>
                            <div class="col-12 input-group">
                                <input type="password" class="form-control border-0 bg-light px-4" name="txtoldpassword" id="oldpw" style="height: 55px;" required>
                                <div class="input-group-append">
                                  <span class="form-control border-0 bg-light px-4 input-group-text" style="height: 55px;" onclick="showpw(1)"><i class="far fa-eye" id="showeye1"></i><i class="far fa-eye-slash d-none" id="hideeye1"></i></span>
                                </div>                          
                            </div>
                            <div class="col-12">
                                <label style="color:black;">&nbsp&nbsp&nbsp New Password:</label>
                            </div>
                            <div class="col-12 input-group">
                                <input type="password" class="form-control border-0 bg-light px-4" name="txtpassword" id="pw" style="height: 55px;" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" onchange="checkpassword();" required>
                                <div class="input-group-append">
                                  <span class="form-control border-0 bg-light px-4 input-group-text" style="height: 55px;" onclick="showpw(2)"><i class="far fa-eye" id="showeye2"></i><i class="far fa-eye-slash d-none" id="hideeye2"></i></span>
                                </div>                                
                            </div>
                            <div class="col-12">
                                <label style="color:black;">&nbsp&nbsp&nbsp Confirm New Password:</label>
                            </div>
                            <div class="col-12 input-group">
                                <input type="password" class="form-control border-0 bg-light px-4" id="pw0" style="height: 55px;" onchange="checkpassword();" required>
                                <div class="input-group-append">
                                  <span class="form-control border-0 bg-light px-4 input-group-text" style="height: 55px;" onclick="showpw(3)"><i class="far fa-eye" id="showeye3"></i><i class="far fa-eye-slash d-none" id="hideeye3"></i></span>
                                </div>

                                <script>
                                  function showpw(i)
                                  {
                                    if(i == 1)
                                    {
                                      var pw = document.getElementById("oldpw");
                                      var showeye = document.getElementById("showeye1");
                                      var hideeye = document.getElementById("hideeye1");
                                    }
                                    else if(i == 2)
                                    {
                                      var pw = document.getElementById("pw");
                                      var showeye = document.getElementById("showeye2");
                                      var hideeye = document.getElementById("hideeye2");
                                    }
                                    else if(i == 3)
                                    {
                                      var pw = document.getElementById("pw0");
                                      var showeye = document.getElementById("showeye3");
                                      var hideeye = document.getElementById("hideeye3");
                                    }
                                    hideeye.classList.remove("d-none");

                                    if (pw.type === "password")
                                    {
                                      pw.type = "text";
                                      showeye.style.display = "none";
                                      hideeye.style.display = "block";
                                    }
                                    else
                                    {
                                      pw.type = "password";
                                      showeye.style.display = "block";
                                      hideeye.style.display = "none";
                                    }
                                  }
                                  function checkpassword() 
                                  {
                                    if (pw.value === pw0.value) 
                                    {
                                      pw0.setCustomValidity('');
                                    } 
                                    else 
                                    {
                                      pw0.setCustomValidity('Passwords do not match');
                                    }
                                  }
                                </script>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" name="btnchange" type="submit">Change Password</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-xl-4 col-lg-12 wow slideInUp" data-wow-delay="0.6s">
                    
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