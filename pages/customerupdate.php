<?php 
$page = 'update';
include('header.php');
include('../../Staff/pages/connect.php');

// Check Customer Account
if (!isset($_SESSION['CustomerID']))
{
  echo "<script>alert('Please log in first')</script>";
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
$password = $array['Password'];

// Update Form Submission 
if(isset($_POST['btnupdate']))
{
  $image = $_FILES['txtimage']['name'];
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
  $password0 = $_POST['txtpassword'];
  $hashedpassword = md5($password0);

  // Check Password
  if($password != $hashedpassword)
  {    
    echo "<script>alert('Please check your password and try again')</script>";
    echo "<script>window.location='customerupdate.php'</script>";
  }
  else
  {

    // Username and Email Duplication Checking
    $usernamenotsame = 0;
    $emailnotsame = 0;

    // Retrieve All Customer
    $selectall = "SELECT * FROM customer WHERE CustomerID != '$customerid'";
    $runall = mysqli_query($connect, $selectall);
    $runcountall = mysqli_num_rows($runall);
    for ($i=0; $i < $runcountall; $i++) 
    { 
      $arrayall = mysqli_fetch_array($runall);
      if ($username != $arrayall['UserName'])
      {
        $usernamenotsame++;
      }
      if ($email != $arrayall['Email'])
      {
        $emailnotsame++;
      }
    }

    // Update Customer
    if($usernamenotsame == $runcountall && $emailnotsame == $runcountall)
    {
      if($image == null)
      {
        // Update Customer
        $update = "UPDATE customer
                  SET UserName = '$username',
                  FirstName = '$firstname',
                  LastName = '$lastname', 
                  DOB = '$dob',
                  Phone = '$phone',
                  Email = '$email',
                  HouseNumber = '$housenumber',
                  Street = '$street', 
                  City = '$city',
                  State = '$state', 
                  Postcode = '$postcode'
                  WHERE CustomerID = '$customerid'";
        $query = mysqli_query($connect, $update);
      }
      else
      {
        if($array['Image'] != null)
        {
          // Retrieve Image
          $select = "SELECT Image FROM customer WHERE CustomerID = '$customerid'";
          $run = mysqli_query($connect, $select);
          $array = mysqli_fetch_array($run);
          $oldimage = "../assets/".$array['Image'];

          // Delete Old Image
          unlink("$oldimage");
        }

        $file = "../assets/Img/".$image;
        $copied = copy($_FILES['txtimage']['tmp_name'],$file);
        $filename = "Img/".$image;

        $update = "UPDATE customer
                    SET Image = '$filename',
                    UserName = '$username',
                    FirstName ='$firstname',
                    LastName = '$lastname',
                    DOB = '$dob',
                    Phone = '$phone',
                    Email = '$email',
                    HouseNumber = '$housenumber',
                    Street = '$street',
                    City = '$city',
                    State = '$state',
                    Postcode = '$postcode'
                    WHERE CustomerID = '$customerid'";
        $query = mysqli_query($connect, $update);
      }

      if ($query)
      {
        //Retrieve Customer
        $select = "SELECT * FROM customer WHERE CustomerID = '$customerid'";
        $run = mysqli_query($connect, $select);
        $runcount = mysqli_num_rows($run);

        $customerarray = mysqli_fetch_array($run);
        $username = $customerarray['UserName'];

        $_SESSION['UserName'] = $username;

        echo "<script>window.alert('Profile Update Successful')</script>";
        echo "<script>window.location='customerprofile.php'</script>";
      }
      else
      {
        mysqli_error($connect);
      }
    }
    elseif ($usernamenotsame < $runcountall)
    {
      echo "<script>window.alert('Username already exist.')</script>";
      echo "<script>window.location='customerupdate.php'</script>";
    }
    elseif ($emailnotsame < $runcountall)
    {
      echo "<script>window.alert('Email already exist.')</script>";
      echo "<script>window.location='customerupdate.php'</script>";
    }
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
                <a class="h4 text-primary"> Update</a>
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
                    <form action="customerupdate.php" method="POST" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-6">
                                <label style="color:black;">&nbsp&nbsp&nbsp Current Photo:</label>
                            </div>
                            <div class="col-6">
                                <label style="color:black;">&nbsp&nbsp&nbsp New Photo:</label>
                            </div>
                            <div class="col-2"></div>
                            <div class="col-3">
                                <img class="img-fluid rounded img-thumbnail" src="<?php echo $image ?>" alt="Profile">
                            </div>
                            <div class="col-1"></div>
                            <div class="col-6">
                                <input type="file" accept="image/*" class="form-control-file" name="txtimage">
                            </div>
                            <div class="col-12">
                                <label style="color:black;">&nbsp&nbsp&nbsp Username:</label>
                            </div>
                            <div class="col-12">
                                <input type="text" class="form-control border-0 bg-light px-4" name="txtusername" style="height: 55px;" pattern="[a-zA-Z0-9-]+" maxlength="15" minlength="5" title="No special characters are allowed" autocomplete="off" value="<?php echo $username ?>" required>
                            </div>
                            <div class="col-6">
                                <label style="color:black;">&nbsp&nbsp&nbsp First Name:</label>
                            </div>
                            <div class="col-6">
                                <label style="color:black;">&nbsp&nbsp&nbsp Last Name:</label>
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control border-0 bg-light px-4" name="txtfirstname" style="height: 55px;" autocomplete="off" value="<?php echo $firstname ?>" required>
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control border-0 bg-light px-4" name="txtlastname" style="height: 55px;" autocomplete="off" value="<?php echo $lastname ?>" required>
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
                                <input type="date" class="form-control border-0 bg-light px-4" name="txtdob" style="height: 55px;" value="<?php echo $dob ?>" required>
                            </div>
                            <div class="col-4">
                                <input type="tel" class="form-control border-0 bg-light px-4" name="txtphone" style="height: 55px;" pattern="[0-9]*" title="Please Enter a Valid Phone Number" autocomplete="off" value="<?php echo $phone ?>" required>
                            </div>
                            <div class="col-4">
                                <input type="email" class="form-control border-0 bg-light px-4" name="txtemail" style="height: 55px;" autocomplete="off" oninvalid="this.setCustomValidity('Please Enter valid email')" oninput="setCustomValidity('')" value="<?php echo $email ?>" required>
                            </div>
                            <div class="col-12">
                                <label style="color:black;">&nbsp&nbsp&nbsp Address:</label>
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control border-0 bg-light px-4" name="txthousenumber" placeholder="House no./ Building, Floor no./ Unit" style="height: 55px;" autocomplete="off" value="<?php echo $housenumber ?>" required>
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control border-0 bg-light px-4" name="txtstreet" placeholder="Street" style="height: 55px;" autocomplete="off" value="<?php echo $street ?>" required>
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control border-0 bg-light px-4" name="txtcity" placeholder="City/ Town/ Village" style="height: 55px;" autocomplete="off" value="<?php echo $city ?>" required>
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control border-0 bg-light px-4" name="txtstate" placeholder="State/ District" style="height: 55px;" autocomplete="off" value="<?php echo $state ?>" required>
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control border-0 bg-light px-4" name="txtpostcode" placeholder="Postcode" style="height: 55px;" autocomplete="off" value="<?php echo $postcode ?>" required>
                            </div>
                            <div class="col-12">
                                <label style="color:black;">&nbsp&nbsp&nbsp Password:</label>
                            </div>
                            <div class="col-12 input-group">
                                <input type="password" class="form-control border-0 bg-light px-4" name="txtpassword" id="pw" style="height: 55px;" required>
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
                                <button class="btn btn-primary w-100 py-3" name="btnupdate" type="submit">Update</button>
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