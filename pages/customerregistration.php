<?php 
include('header.php');
include('../../Staff/pages/autoid.php');
include('../../Staff/pages/connect.php');

// Form Submission 
if(isset($_POST['btnsubmit']))
{
  $customerid = AutoID('customer', 'CustomerID', 'C-', 6);
  $username = $_POST['txtusername'];
  $firstname = $_POST['txtfirstname'];
  $lastname = $_POST['txtlastname'];
  $dob = $_POST['txtdob'];
  $phone = $_POST['txtphone'];
  $email = $_POST['txtemail'];
  $housenumber = $_POST['txthousenumber'];  
  $street = $_POST['txtstreet'];
  $city = $_POST['txtcity'];
  $state = $_POST['txtstate'];
  $postcode = $_POST['txtpostcode'];
  $password = $_POST['txtpassword'];
  $hashedpassword = md5($password);
  $registrationdate = date("Y-m-d");
  
  // Retrieve Customer
  $select = "SELECT * FROM customer";
  $run = mysqli_query($connect, $select);
  $runcount = mysqli_num_rows($run);

  // Username and Email Duplication Checking
  $usernamenotsame = 0;
  $emailnotsame = 0;
  for ($i=0; $i < $runcount; $i++) 
  { 
    $array = mysqli_fetch_array($run);
    if ($username != $array['UserName'])
    {
      $usernamenotsame++;
    }
    if ($email != $array['Email'])
    {
      $emailnotsame++;
    }
  }

  // Insert Customer
  if($usernamenotsame == $runcount && $emailnotsame == $runcount)
  {
    $insert = "INSERT INTO customer(CustomerID, UserName, FirstName, LastName, DOB, Phone, Email, HouseNumber, Street, City, State, Postcode, Password, RegistrationDate) VALUES ('$customerid', '$username', '$firstname', '$lastname', '$dob', '$phone', '$email', '$housenumber', '$street', '$city', '$state', '$postcode', '$hashedpassword', '$registrationdate')";
    $query = mysqli_query($connect, $insert);

    if ($query)
    {
      echo "<script>alert('Account Registration Successful')</script>";
      echo "<script>window.location='customerlogin.php'</script>";
    }
    else
    {
      mysqli_error($connect);
    }
  }
  if ($usernamenotsame < $runcount)
  {
    echo "<script>window.alert('Username already exists. Please try again with a different username.')</script>";
    echo "<script>window.location='customerregistration.php'</script>";
  }
  if ($emailnotsame < $runcount)
  {
    echo "<script>window.alert('Email already exists. Please try again with a different email.')</script>";
    echo "<script>window.location='customerregistration.php'</script>";
  }
}

 ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>C&M | Registration</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">
</head>

<body>
    <!-- Hero Start -->
    <div class="container-fluid bg-primary py-5 hero-header mb-5">
        <div class="row py-3">
            <div class="col-12 text-center">
                <h1 class="display-3 text-white animated zoomIn">Registration</h1>
                <a href="index.php" class="h4 text-white">Home </a>
                <a class="h4 text-white"> > </a>
                <a class="h4 text-primary"> Registration</a>
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
                    <form action="customerregistration.php" method="POST">
                        <div class="row g-3">
                            <div class="col-12">
                                <label style="color:black;">&nbsp&nbsp&nbsp Username:</label>
                            </div>
                            <div class="col-12">
                                <input type="text" class="form-control border-0 bg-light px-4" name="txtusername" style="height: 55px;" pattern="[a-zA-Z0-9-]+" maxlength="15" minlength="5" title="No special characters are allowed" autocomplete="off" required>
                            </div>
                            <div class="col-6">
                                <label style="color:black;">&nbsp&nbsp&nbsp First Name:</label>
                            </div>
                            <div class="col-6">
                                <label style="color:black;">&nbsp&nbsp&nbsp Last Name:</label>
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control border-0 bg-light px-4" name="txtfirstname" style="height: 55px;" autocomplete="off" required>
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control border-0 bg-light px-4" name="txtlastname" style="height: 55px;" autocomplete="off" required>
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
                                <input type="date" class="form-control border-0 bg-light px-4" name="txtdob" style="height: 55px;" required>
                            </div>
                            <div class="col-4">
                                <input type="tel" class="form-control border-0 bg-light px-4" name="txtphone" style="height: 55px;" pattern="[0-9]*" title="Please Enter a Valid Phone Number" autocomplete="off" required>
                            </div>
                            <div class="col-4">
                                <input type="email" class="form-control border-0 bg-light px-4" name="txtemail" style="height: 55px;" autocomplete="off" oninvalid="this.setCustomValidity('Please Enter valid email')" oninput="setCustomValidity('')" required>
                            </div>
                            <div class="col-12">
                                <label style="color:black;">&nbsp&nbsp&nbsp Address:</label>
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control border-0 bg-light px-4" name="txthousenumber" placeholder="House no./ Building, Floor no./ Unit" style="height: 55px;" autocomplete="off" required>
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control border-0 bg-light px-4" name="txtstreet" placeholder="Street" style="height: 55px;" autocomplete="off" required>
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control border-0 bg-light px-4" name="txtcity" placeholder="City/ Town/ Village" style="height: 55px;" autocomplete="off" required>
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control border-0 bg-light px-4" name="txtstate" placeholder="State/ District" style="height: 55px;" autocomplete="off" required>
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control border-0 bg-light px-4" name="txtpostcode" placeholder="Postcode" style="height: 55px;" autocomplete="off" required>
                            </div>
                            <div class="col-6">
                                <label style="color:black;">&nbsp&nbsp&nbsp Password:</label>
                            </div>
                            <div class="col-6">
                                <label style="color:black;">&nbsp&nbsp&nbsp Confirm Password:</label>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="password" class="form-control border-0 bg-light px-4" name="txtpassword" id="pw" style="height: 55px;" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" onchange="checkpassword();" required>
                                    <div class="input-group-append">
                                      <span class="form-control border-0 bg-light px-4 input-group-text" style="height: 55px;" onclick="showpw(1)"><i class="far fa-eye" id="showeye1"></i><i class="far fa-eye-slash d-none" id="hideeye1"></i></span>
                                    </div> 
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <input type="password" class="form-control border-0 bg-light px-4" id="pw0" style="height: 55px;" onchange="checkpassword();" required>
                                    <div class="input-group-append">
                                      <span class="form-control border-0 bg-light px-4 input-group-text" style="height: 55px;" onclick="showpw(2)"><i class="far fa-eye" id="showeye2"></i><i class="far fa-eye-slash d-none" id="hideeye2"></i></span>
                                    </div>
                                </div>
                                <script>
                                  function showpw(i)
                                  {
                                    if(i == 1)
                                    {
                                      var pw = document.getElementById("pw");
                                      var showeye = document.getElementById("showeye1");
                                      var hideeye = document.getElementById("hideeye1");
                                    }
                                    else if(i == 2)
                                    {
                                      var pw = document.getElementById("pw0");
                                      var showeye = document.getElementById("showeye2");
                                      var hideeye = document.getElementById("hideeye2");
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
                                <center><label>Already have an account? <a href="customerlogin.php">Sign in now!</a></label></center>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" name="btnsubmit" type="submit">Register</button>
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