<?php 
$page = 'login';
include('header.php');
include('../../Staff/pages/connect.php');

// Form Submission 
if(isset($_POST['btnsubmit']))
{
  $username = $_POST['txtusername'];
  $password = $_POST['txtpassword'];
  $hashedpassword = md5($password);
  
  // Retrieve Customer
  $select = "SELECT * FROM customer";
  $run = mysqli_query($connect, $select);
  $runcount = mysqli_num_rows($run);

  // Username Checking
  $usernamenotsame = 0;
  for ($i = 0; $i < $runcount; $i++) 
  { 
    $array = mysqli_fetch_array($run);
    if ($username != $array['UserName'])
    {
      $usernamenotsame++;
    }
  }
  if ($usernamenotsame == $runcount)
  {
    echo "<script>window.alert('Username does not exist. Please check your Username and try again.')</script>";
  }
  // Password Checking
  else
  {
    $selectcustomer = "SELECT * FROM customer WHERE UserName = '$username' AND Password = '$hashedpassword'";
    $runcustomer = mysqli_query($connect, $selectcustomer);
    $customercount = mysqli_num_rows($runcustomer);

    if ($customercount == 1)
    {
      $customerarray = mysqli_fetch_array($runcustomer);
      $_SESSION['CustomerID'] = $customerarray['CustomerID'];
      $_SESSION['cUserName'] = $customerarray['UserName'];

      if(!empty($_POST["txtremember"])) 
      {
        $_SESSION['cusername'] = $username;
        $_SESSION['cpassword'] = $password;
      }

      echo "<script>window.alert('Customer Login Successful')</script>";
      echo "<script>window.location='rememberlogin.php'</script>";
    }
    else
    {
      echo "<script>window.alert('Username and Password do not match. Please try again.')</script>";
    }
  }
  
}

 ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>C&M | Login</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">
</head>

<body>
    <!-- Hero Start -->
    <div class="container-fluid bg-primary py-5 hero-header mb-5">
        <div class="row py-3">
            <div class="col-12 text-center">
                <h1 class="display-3 text-white animated zoomIn">Log In</h1>
                <a href="index.php" class="h4 text-white">Home </a>
                <a class="h4 text-white"> > </a>
                <a class="h4 text-primary"> Log In</a>
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
                    <form action="customerlogin.php" method="POST">
                        <div class="row g-3">
                            <div class="col-12">
                                <label style="color:black;">&nbsp&nbsp&nbsp Username:</label>
                            </div>
                            <div class="col-12">
                                <input type="text" class="form-control border-0 bg-light px-4" name="txtusername" style="height: 55px;" value="<?php if(isset($_COOKIE["cusername"])) { echo $_COOKIE["cusername"]; } ?>" required>
                            </div>
                            <div class="col-12">
                                <label style="color:black;">&nbsp&nbsp&nbsp Password:</label>
                            </div>
                            <div class="col-12 input-group">
                                <input type="password" class="form-control border-0 bg-light px-4" name="txtpassword" id="pw" style="height: 55px;" value="<?php if(isset($_COOKIE["cpassword"])) { echo $_COOKIE["cpassword"]; } ?>" required>
                                <div class="input-group-append">
                                  <span class="form-control border-0 bg-light px-4 input-group-text" style="height: 55px;" onclick="showpw()"><i class="far fa-eye" id="showeye"></i><i class="far fa-eye-slash d-none" id="hideeye"></i></span>
                                </div>

                                <script>
                                  function showpw()
                                  {
                                    var pw = document.getElementById("pw");
                                    var showeye = document.getElementById("showeye");
                                    var hideeye = document.getElementById("hideeye");
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
                                </script>
                            </div>
                            <div class="col-12">
                                <input type="checkbox" name="txtremember" checked> Remember me
                            </div>
                            <div class="col-12">
                                <center><label>Not a member? <a href="customerregistration.php">Sign up now!</a></label></center>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" name="btnsubmit" type="submit">Log In</button>
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