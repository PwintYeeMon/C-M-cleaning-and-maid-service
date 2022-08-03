<?php
session_start();
include('connect.php');

$staffid = $_SESSION['StaffID'];
$page = $_SESSION['page'];

//Retrieve Staff
$select = "SELECT * FROM staff WHERE StaffID = '$staffid'";
$run = mysqli_query($connect, $select);
$runcount = mysqli_num_rows($run);

$array = mysqli_fetch_array($run);
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
  $address = $_POST['txtaddress'];  
  $password0 = $_POST['txtpassword'];
  $hashedpassword = md5($password0);
  
  // Check Password
  if($password != $hashedpassword)
  {    
    echo "<script>alert('Please check your password and try again')</script>";
    echo "<script>window.location='".$page.".php'</script>";
  }
  else
  {
    // Username and Email Duplication Checking
    $usernamenotsame = 0;
    $emailnotsame = 0;

    // Retrieve All Staff
    $selectall = "SELECT * FROM staff WHERE StaffID != '$staffid'";
    $runall = mysqli_query($connect, $selectall);
    $runcountall = mysqli_num_rows($runall);
    for($i=0; $i < $runcountall; $i++) 
    { 
      $arrayall = mysqli_fetch_array($runall);
      if($username != $arrayall['UserName'])
      {
        $usernamenotsame++;
      }
      if($email != $arrayall['Email'])
      {
        $emailnotsame++;
      }
    }

    if($usernamenotsame == $runcountall && $emailnotsame == $runcountall)
    {
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
        // Retrieve Staff
        $select = "SELECT Image FROM staff WHERE StaffID = '$staffid'";
        $run = mysqli_query($connect, $select);
        $array = mysqli_fetch_array($run);
        $oldimage = "../../User/assets/".$array['Image'];

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
        //Retrieve Staff
        $select = "SELECT * FROM staff WHERE StaffID = '$staffid'";
        $run = mysqli_query($connect, $select);
        $runcount = mysqli_num_rows($run);

        $staffarray = mysqli_fetch_array($run);
        $username = $staffarray['UserName'];
        $image = $staffarray['Image'];

        $_SESSION['UserName'] = $username;
        $_SESSION['Profile'] = $image;

        echo "<script>alert('Staff Information Update Successful')</script>";
        echo "<script>window.location='".$page.".php'</script>";
      }
      else
      {
        mysqli_error($connect);
      }
    }
    elseif($usernamenotsame < $runcountall)
    {
      echo "<script>alert('Username already exist.')</script>";
      echo "<script>window.location='".$page.".php'</script>";
    }
    elseif($emailnotsame < $runcountall)
    {
      echo "<script>alert('Email already exist.')</script>";
      echo "<script>window.location='".$page.".php'</script>";
    }
  }
}

// Change Password Form Submission
if(isset($_POST['btnchange']))
{
  $select = "SELECT * FROM staff WHERE StaffID = '$staffid'";
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
      echo "<script>window.location='".$page.".php'</script>";
  }
  else
  {
    // Update Password
    $update = "UPDATE staff
                SET Password = '$hashedpassword'
                WHERE StaffID = '$staffid'";

    $query = mysqli_query($connect, $update);

    if ($query)
    {
        echo "<script>window.alert('Password Changed')</script>";
        echo "<script>window.location='".$page.".php'</script>";
    }
    else
    {
        mysqli_error($connect);
    }
  }   
}

 ?>