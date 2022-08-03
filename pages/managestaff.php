<?php 
session_start();
include('autoid.php');
include('connect.php');

// Check Manager and Adminstrator Account
$staffid = $_SESSION['StaffID'];
$role = $_SESSION['StaffRole'];
if(!isset($staffid))
{
  echo "<script>alert('Please log in first')</script>";
  echo "<script>window.location='stafflogin.php'</script>";
}
elseif($role == 'House Cleaner')
{
  echo "<script>alert('Only Manager and Administrator can have access to staff details.')</script>";
  echo "<script>window.location='stafflogin.php'</script>";
}
elseif($role== 'Manager')
{
    // Retrieve Admin and House Cleaner
    $select = "SELECT * FROM staff WHERE StaffRole <> 'Manager'";
    $run = mysqli_query($connect, $select);
    $runcount = mysqli_num_rows($run);
}
elseif($role == 'Administrator')
{
    // Retrieve House Cleaner
    $select = "SELECT * FROM staff WHERE StaffRole = 'House Cleaner'";
    $run = mysqli_query($connect, $select);
    $runcount = mysqli_num_rows($run);
}

// Register Form Submission 
if(isset($_POST['btnregister']))
{
  $staffid = AutoID('staff', 'StaffID', 'S-', 6);
  $staffrole = $_POST['txtstaffrole'];
  $image = $_FILES['txtimage']['name'];
  $file = "../../User/assets/Img/".$image;
  $copied = copy($_FILES['txtimage']['tmp_name'],$file);
  $filename = "Img/".$image;

  $username = $_POST['txtusername'];
  $firstname = $_POST['txtfirstname'];
  $lastname = $_POST['txtlastname'];
  $dob = $_POST['txtdob'];
  $phone = $_POST['txtphone'];
  $email = $_POST['txtemail'];
  $address = $_POST['txtaddress'];  
  $password = $_POST['txtpassword'];
  $hashedpassword = md5($password);
  
  // Retrieve Staff
  $select = "SELECT * FROM staff";
  $run = mysqli_query($connect, $select);
  $runcount = mysqli_num_rows($run);

  // Username and Email Duplication Checking
  $usernamenotsame = 0;
  $emailnotsame = 0;
  for ($i=0; $i < $runcount; $i++) 
  { 
    $array = mysqli_fetch_array($run);
    if($username != $array['UserName'])
    {
      $usernamenotsame++;
    }
    if($email != $array['Email'])
    {
      $emailnotsame++;
    }
  }

  // Insert Staff
  if($usernamenotsame == $runcount && $emailnotsame == $runcount)
  {
    $insert = "INSERT INTO staff(StaffID, StaffRole, Image, UserName, FirstName, LastName, DOB, Phone, Email, Address, Password) VALUES ('$staffid', '$staffrole', '$filename', '$username', '$firstname', '$lastname', '$dob', '$phone', '$email', '$address', '$hashedpassword')";
    $query = mysqli_query($connect, $insert);

    if($query)
    {
      echo "<script>alert('Staff Registration Successful')</script>";
      echo "<script>window.location='managestaff.php'</script>";
    }
    else
    {
      mysqli_error($connect);
    }
  }
  elseif($usernamenotsame < $runcount)
  {
    echo "<script>alert('Username already exists. Please try again with a different username.')</script>";
    echo "<script>window.location='managestaff.php'</script>";
  }
  elseif($emailnotsame < $runcount)
  {
    echo "<script>alert('Email already exists. Please try again with a different email.')</script>";
    echo "<script>window.location='managestaff.php'</script>";
  }
}

// Update Form Submission 
if(isset($_POST['btnupdate']))
{
  $staffid = $_POST['txtstaffid'];
  $image = $_FILES['txtimage']['name'];
  $username = $_POST['txtusername'];
  $firstname = $_POST['txtfirstname'];
  $lastname = $_POST['txtlastname'];
  $dob = $_POST['txtdob'];
  $phone = $_POST['txtphone'];
  $email = $_POST['txtemail'];
  $address = $_POST['txtaddress'];

  if($image == null)
  {
    // Update Staff
    $update = "UPDATE staff 
              SET UserName = '$username',
              FirstName = '$firstname', 
              LastName = '$lastname', 
              DOB = '$dob', 
              Phone = '$phone', 
              Email = '$email', 
              Address = '$address'
              WHERE StaffID = '$staffid'";
    $query = mysqli_query($connect, $update);
  }
  else
  {
    // Retrieve Image
    $select = "SELECT Image FROM staff WHERE StaffID = '$staffid'";
    $run = mysqli_query($connect, $select);
    $array = mysqli_fetch_array($run);
    $oldimage = "../../User/assets/".$array['Image'];

    // Delete Old Image
    unlink("$oldimage");
    $file = "../../User/assets/Img/".$image;
    $copied = copy($_FILES['txtimage']['tmp_name'],$file);
    $filename = "Img/".$image;
    
    // Update Staff
    $update = "UPDATE staff 
              SET UserName = '$username',
              Image = '$filename', 
              FirstName = '$firstname', 
              LastName = '$lastname', 
              DOB = '$dob', 
              Phone = '$phone', 
              Email = '$email', 
              Address = '$address'
              WHERE StaffID = '$staffid'";
    $query = mysqli_query($connect, $update);
  }

  if($query)
  {
    echo "<script>alert('Staff Information Update Successful')</script>";
    echo "<script>window.location='managestaff.php'</script>";
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>C&M | Staff List</title>

    <!-- Favicon -->
    <link href="../../User/assets/Img/logo.ico" rel="icon">

    <?php include('header.php') ?>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php $page = 'managestaff'; include('sidebar.php'); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include('topbar.php') ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- DataTales -->
                    <div class="card shadow mb-4">
                        <div class="card-header d-flex justify-content-between">
                            <div class="p-2">
                                <h6 class="m-0 font-weight-bold text-primary py-2">Staff List</h6>
                            </div>
                            <div class="p-2">
                            <?php 

                            if($role == 'Manager')
                            {
                            ?>

                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staffregistration">
                                  Create New Staff Account
                                </button>

                            <?php } ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Profile</th>
                                            <th>Name</th>
                                            <?php 
                                            if($role == 'Manager')
                                            {
                                                echo "<th>Role</th>";
                                            }
                                            ?> 
                                            <th>DOB</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                            <?php 
                                            if($role == 'Manager')
                                            {
                                                echo "<th>Action (Edit)</th>";
                                            }
                                            ?>                                            
                                        </tr>
                                    </thead>

                                    <tbody>

                                    <?php 

                                    for ($i=0; $i < $runcount; $i++) 
                                    { 
                                      $array = mysqli_fetch_array($run);
                                      $staffid = $array['StaffID'];
                                      $staffrole = $array['StaffRole'];
                                      $image = "../../User/assets/".$array['Image'];
                                      $username = $array['UserName'];
                                      $firstname = $array['FirstName'];
                                      $lastname = $array['LastName'];
                                      $dob = $array['DOB'];
                                      $phone = $array['Phone'];
                                      $email = $array['Email'];
                                      $address = $array['Address'];
                                    
                                     ?>

                                        <tr>
                                            <td><?php echo $staffid ?></td>
                                            <td><img src="<?php echo $image; ?>" width="70" height="70" class="rounded" alt="Staff Profile"></td>
                                            <td><?php echo $firstname." ".$lastname ?></td>
                                            <?php 
                                            if($role == 'Manager')
                                            {
                                                echo '<td style="word-break:break-word;">'.$staffrole.'</td>';
                                            }
                                            ?>
                                            <td><?php echo $dob; ?></td>
                                            <td style="word-break:break-word;"><?php echo $phone; ?></td>
                                            <td style="word-break:break-word;"><?php echo $email; ?></td>
                                            <td><?php echo $address; ?></td>
                                            <?php 
                                            if($role == 'Manager')
                                            {
                                                echo '<td style="color: #4e73df;"><i class="fas fa-pencil-alt fa-sm" data-toggle="modal" data-target="#staffupdate'.$i.'"></i></td>';
                                            }
                                            ?>                                              
                                        </tr>

                                        <!-- Staff Update Modal -->
                                        <?php echo '<div class="modal" id="staffupdate'.$i.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">'; ?>
                                          <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalCenterTitle">Staff Update</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                                </button>
                                              </div>
                                              <div class="modal-body">
                                                <form action="managestaff.php" method="POST" enctype="multipart/form-data">
                                                    <div class="row g-3">
                                                        <div class="col-12">
                                                            <input type="text" class="form-control-plaintext border-0 bg-light px-4" name="txtstaffid" style="height: 55px;" value="<?php echo $staffid; ?>" hidden>
                                                        </div>
                                                        <div class="col-12">
                                                            <label style="color:black;">&nbsp&nbsp&nbsp Role:</label>
                                                        </div>
                                                        <div class="col-12">
                                                            <input type="text" class="form-control-plaintext border-0 bg-light px-4" style="height: 55px;" value="<?php echo $staffrole; ?>" readonly>
                                                        </div>
                                                        <div class="col-6">
                                                            <label style="color:black;">&nbsp&nbsp&nbsp Current Photo:</label>
                                                        </div>
                                                        <div class="col-6">
                                                            <label style="color:black;">&nbsp&nbsp&nbsp New Photo:</label>
                                                        </div>
                                                        <div class="col-1"></div>
                                                        <div class="col-4">
                                                            <img class="img-fluid rounded img-thumbnail" src="<?php echo $image; ?>" alt="Staff Profile">
                                                        </div>
                                                        <div class="col-1"></div>
                                                        <div class="col-6">
                                                            <input type="file" accept="image/*" class="form-control-file" name="txtimage">
                                                        </div>
                                                        <div class="col-12">
                                                            <label style="color:black;">&nbsp&nbsp&nbsp Username:</label>
                                                        </div>
                                                        <div class="col-12">
                                                            <input type="text" class="form-control border-0 bg-light px-4" name="txtusername" style="height: 55px;" pattern="[a-zA-Z0-9-]+" maxlength="15" minlength="5" title="No special characters are allowed" autocomplete="off" value="<?php echo $username; ?>" required>
                                                        </div>
                                                        <div class="col-6">
                                                            <label style="color:black;">&nbsp&nbsp&nbsp First Name:</label>
                                                        </div>
                                                        <div class="col-6">
                                                            <label style="color:black;">&nbsp&nbsp&nbsp Last Name:</label>
                                                        </div>
                                                        <div class="col-6">
                                                            <input type="text" class="form-control border-0 bg-light px-4" name="txtfirstname" style="height: 55px;" autocomplete="off" value="<?php echo $firstname; ?>" required>
                                                        </div>
                                                        <div class="col-6">
                                                            <input type="text" class="form-control border-0 bg-light px-4" name="txtlastname" style="height: 55px;" autocomplete="off" value="<?php echo $lastname; ?>" required>
                                                        </div>
                                                        <div class="col-6">
                                                            <label style="color:black;">&nbsp&nbsp&nbsp Date of Birth:</label>
                                                        </div>
                                                        <div class="col-6">
                                                            <label style="color:black;">&nbsp&nbsp&nbsp Phone:</label>
                                                        </div>
                                                        <div class="col-6">
                                                            <input type="date" class="form-control border-0 bg-light px-4" name="txtdob" style="height: 55px;" autocomplete="off" value="<?php echo $dob; ?>" required>
                                                        </div>
                                                        <div class="col-6">
                                                            <input type="tel" class="form-control border-0 bg-light px-4" name="txtphone" style="height: 55px;" pattern="[0-9]*" title="Please Enter a Valid Phone Number" value="<?php echo $phone; ?>" autocomplete="off" required>
                                                        </div>
                                                        <div class="col-12">
                                                            <label style="color:black;">&nbsp&nbsp&nbsp Email:</label>
                                                        </div>
                                                        <div class="col-12">
                                                            <input type="email" class="form-control border-0 bg-light px-4" name="txtemail" style="height: 55px;" autocomplete="off" oninvalid="this.setCustomValidity('Please Enter valid email')" oninput="setCustomValidity('')" value="<?php echo $email; ?>" required>
                                                        </div>
                                                        <div class="col-12">
                                                            <label style="color:black;">&nbsp&nbsp&nbsp Address:</label>
                                                        </div>
                                                        <div class="col-12">
                                                            <textarea class="form-control border-0 bg-light px-4 py-3" name="txtaddress" placeholder="House Number/ Building, Floor Number/ Unit,
                    Street,
                    City/ Town/ Village, 
                    State/ District, 
                    Postcode" autocomplete="off" required><?php echo $address; ?></textarea>
                                                        </div>
                                                        <div class="col-12"><label> </label></div>
                                                        <div class="col-4"></div>
                                                        <div class="col-4">
                                                            <button class="btn btn-primary w-100" name="btnupdate" type="submit">Update</button>
                                                        </div>
                                                        <div class="col-4">
                                                            <button class="btn btn-outline-dark w-100" data-dismiss="modal" type="reset">Cancel</button>
                                                        </div>
                                                    </div>
                                                </form>
                                              </div>
                                            </div>
                                          </div>
                                        </div>

                                    <?php } ?>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Staff Registration Modal -->
                    <div class="modal" id="staffregistration" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Staff Registration</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <form action="managestaff.php" method="POST" enctype="multipart/form-data">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label style="color:black;">&nbsp&nbsp&nbsp Staff Role:</label>
                                    </div>
                                    <div class="col-6">
                                        <label style="color:black;">&nbsp&nbsp&nbsp Image:</label>
                                    </div>
                                    <div class="col-6">
                                        <select class="form-control bg-light border-0" name="txtstaffrole" style="height: 55px;" autocomplete="off" required>
                                            <option value="" disabled selected hidden>&nbsp -- Select --</option>
                                            <option value="Manager">&nbsp&nbsp&nbsp Manager</option>
                                            <option value="Administrator">&nbsp&nbsp&nbsp Administrator</option>
                                            <option value="House Cleaner">&nbsp&nbsp&nbsp House Cleaner</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <input  type="file" accept="image/*" class="form-control-file" name="txtimage" required>
                                    </div>
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
                                    <div class="col-6">
                                        <label style="color:black;">&nbsp&nbsp&nbsp Date of Birth:</label>
                                    </div>
                                    <div class="col-6">
                                        <label style="color:black;">&nbsp&nbsp&nbsp Phone:</label>
                                    </div>
                                    <div class="col-6">
                                        <input type="date" class="form-control border-0 bg-light px-4" name="txtdob" style="height: 55px;" autocomplete="off" required>
                                    </div>
                                    <div class="col-6">
                                        <input type="tel" class="form-control border-0 bg-light px-4" name="txtphone" style="height: 55px;" pattern="[0-9]*" title="Please Enter a Valid Phone Number" autocomplete="off" required>
                                    </div>
                                    <div class="col-12">
                                        <label style="color:black;">&nbsp&nbsp&nbsp Email:</label>
                                    </div>
                                    <div class="col-12">
                                        <input type="email" class="form-control border-0 bg-light px-4" name="txtemail" style="height: 55px;" autocomplete="off" oninvalid="this.setCustomValidity('Please Enter valid email')" oninput="setCustomValidity('')" required>
                                    </div>
                                    <div class="col-12">
                                        <label style="color:black;">&nbsp&nbsp&nbsp Address:</label>
                                    </div>
                                    <div class="col-12">
                                        <textarea class="form-control border-0 bg-light px-4 py-3" name="txtaddress" rows="5" placeholder="House Number/ Building, Floor Number/ Unit,
Street,
City/ Town/ Village, 
State/ District, 
Postcode" autocomplete="off" required></textarea>
                                    </div>
                                    <div class="col-6">
                                        <label style="color:black;">&nbsp&nbsp&nbsp Password:</label>
                                    </div>
                                    <div class="col-6">
                                        <label style="color:black;">&nbsp&nbsp&nbsp Confirm Password:</label>
                                    </div>
                                    <div class="col-6 input-group">
                                        <input type="password" class="form-control border-0 bg-light px-4" name="txtpassword" id="pwold" style="height: 55px;" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" onchange="checkpassword(1);" required>
                                        <div class="input-group-append">
                                          <span class="form-control border-0 bg-light px-4 input-group-text" style="height: 55px;" onclick="showpw(4)"><i class="far fa-eye" id="showeyeold"></i><i class="far fa-eye-slash d-none" id="hideeyeold"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-6 input-group">
                                        <input type="password" class="form-control border-0 bg-light px-4" id="pwnew" style="height: 55px;" onchange="checkpassword(1);" required>
                                        <div class="input-group-append">
                                          <span class="form-control border-0 bg-light px-4 input-group-text" style="height: 55px;" onclick="showpw(5)"><i class="far fa-eye" id="showeyenew"></i><i class="far fa-eye-slash d-none" id="hideeyenew"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-12"><br></div>
                                    <div class="col-4"></div>
                                    <div class="col-4">
                                        <button class="btn btn-primary w-100" name="btnregister" type="submit">Register</button>
                                    </div>
                                    <div class="col-4">
                                        <button class="btn btn-outline-dark w-100" data-dismiss="modal" type="reset">Cancel</button>
                                    </div>
                                </div>
                            </form>
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

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

</body>

</html>