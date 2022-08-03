<?php 
session_start();
include('connect.php');

// Form Submission 
if(isset($_POST['btnsubmit']))
{
  $username = $_POST['txtusername'];
  $password = $_POST['txtpassword'];
  $hashedpassword = md5($password);
  
  // Retrieve Staff
  $select = "SELECT * FROM staff";
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
    $selectstaff = "SELECT * FROM staff WHERE UserName = '$username' AND Password = '$hashedpassword'";
    $runstaff = mysqli_query($connect, $selectstaff);
    $staffcount = mysqli_num_rows($runstaff);

    if ($staffcount == 1)
    {
      $staffarray = mysqli_fetch_array($runstaff);
      $_SESSION['StaffID'] = $staffarray['StaffID'];
      $_SESSION['StaffRole'] = $staffarray['StaffRole'];
      $_SESSION['UserName'] = $staffarray['UserName'];
      $_SESSION['Profile'] = $staffarray['Image'];

      if(!empty($_POST["txtremember"])) 
      {
        $_SESSION['susername'] = $username;
        $_SESSION['spassword'] = $password;
      }

      echo "<script>window.alert('Staff Login Successful')</script>";
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
    <title>C&M | Staff Login</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="../../User/assets/Img/logo.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="../assets/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../assets/lib/animate/animate.min.css" rel="stylesheet">
    <link href="../assets/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <link href="../assets/lib/twentytwenty/twentytwenty.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../../User/assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../../User/assets/css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Contact Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-xl-4 col-lg-6 wow slideInUp" data-wow-delay="0.1s">
                    
                </div>
                <div class="col-xl-4 col-lg-6 wow slideInUp" data-wow-delay="0.3s">
                    <center><label><h1>Staff Log In</h1></label></center>
                    <br>
                    <form action="stafflogin.php" method="POST">
                        <div class="row g-3">
                            <div class="col-12">
                                <label style="color:black;">&nbsp&nbsp&nbsp Username:</label>
                            </div>
                            <div class="col-12">
                                <input type="text" class="form-control border-0 bg-light px-4" name="txtusername" style="height: 55px;" value="<?php if(isset($_COOKIE["susername"])) { echo $_COOKIE["susername"]; } ?>" required>
                            </div>
                            <div class="col-12">
                                <label style="color:black;">&nbsp&nbsp&nbsp Password:</label>
                            </div>
                            <div class="col-12 input-group">
                                <input type="password" class="form-control border-0 bg-light px-4" name="txtpassword" id="pw" style="height: 55px;" value="<?php if(isset($_COOKIE["spassword"])) { echo $_COOKIE["spassword"]; } ?>" required>
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

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/lib/wow/wow.min.js"></script>
    <script src="../assets/lib/easing/easing.min.js"></script>
    <script src="../assets/lib/waypoints/waypoints.min.js"></script>
    <script src="../assets/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="../assets/lib/tempusdominus/js/moment.min.js"></script>
    <script src="../assets/lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="../assets/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="../assets/lib/twentytwenty/jquery.event.move.js"></script>
    <script src="../assets/lib/twentytwenty/jquery.twentytwenty.js"></script>

    <!-- Template Javascript -->
    <script src="../../User/assets/js/main.js"></script>

</body>

</html>