<?php 
session_start();
include('autoid.php');
include('connect.php');

// Check Manager and Adminstrator Account
if (!isset($_SESSION['StaffID']))
{
  echo "<script>window.alert('Please log in first')</script>";
  echo "<script>window.location='stafflogin.php'</script>";
}
elseif ($_SESSION['StaffRole'] == 'House Cleaner')
{
  echo "<script>window.alert('Only Manager and Administrator can view Cleaner Schedule List.')</script>";
  echo "<script>window.location='stafflogin.php'</script>";
}

$Day = date('D');

// Retrieve Cleaner Schedule and Staff
$select = "SELECT cs.*, s.StaffID, s.FirstName, s.LastName FROM cleanerschedule cs, staff s WHERE cs.StaffID = s.StaffID";
$run = mysqli_query($connect, $select);
$runcount = mysqli_num_rows($run);

// Entry Form Submission 
if(isset($_POST['btnenter']))
{
  $cleanerscheduleid = AutoID('cleanerschedule', 'CleanerScheduleID', 'CS-', 6);
  $staffid = $_POST['txtstaffid'];
  $day = $_POST['txtday'];

  // Check Existing Schedule
  $selectc = "SELECT * FROM cleanerschedule WHERE StaffID = '$staffid' AND Day = '$day'";
  $runc = mysqli_query($connect, $selectc);
  $runcountc = mysqli_num_rows($runc);

  if ($runcountc == 0) 
  {
    // Insert Schedule
    $insert = "INSERT INTO cleanerschedule(CleanerScheduleID, StaffID, Day) VALUES ('$cleanerscheduleid', '$staffid', '$day')";
    $query = mysqli_query($connect, $insert);

    if ($query)
    {
      echo "<script>alert('Cleaner Schedule Entered')</script>";
      echo "<script>window.location='managecleanerschedule.php'</script>";
    }
  }
  else
  {
    echo "<script>alert('Cleaner Schedule Already Exist')</script>";
    echo "<script>window.location='managecleanerschedule.php'</script>";
  }
}

// Update Form Submission 
if(isset($_POST['btnupdate']))
{
  $cleanerscheduleid = $_POST['txtcleanerscheduleid'];
  $staffid = $_POST['txtstaffid'];
  $day = $_POST['txtday'];

  // Check Existing Schedule
  $selectc = "SELECT * FROM cleanerschedule WHERE StaffID = '$staffid' AND Day = '$day'";
  $runc = mysqli_query($connect, $selectc);
  $runcountc = mysqli_num_rows($runc);

  if ($runcountc == 0) 
  {
    // Update Schedule
    $update = "UPDATE cleanerschedule
            SET StaffID = '$staffid',
            Day = '$day'
            WHERE CleanerScheduleID = '$cleanerscheduleid'";
    $query = mysqli_query($connect, $update);

    if ($query)
    {
      echo "<script>alert('Cleaner Schedule Entered')</script>";
      echo "<script>window.location='managecleanerschedule.php'</script>";
    }
  }
  else
  {
    echo "<script>alert('Cleaner Schedule Already Exist')</script>";
    echo "<script>window.location='managecleanerschedule.php'</script>";
  }
}

// Delete 
if(isset($_POST['btndelete']))
{
  $cleanerscheduleid = $_POST['txtcleanerscheduleid'];

  // Delete Room Type
  $delete = "DELETE FROM cleanerschedule
            WHERE CleanerScheduleID = '$cleanerscheduleid'";
  $query = mysqli_query($connect, $delete);

  if ($query)
  {
    echo "<script>alert('Cleaner Schedule Delete Successful')</script>";
    echo "<script>window.location='managecleanerschedule.php'</script>";
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

    <title>C&M | Cleaner Schedule List</title>

    <!-- Favicon -->
    <link href="../../User/assets/Img/logo.ico" rel="icon">

    <?php include('header.php') ?>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php $page = 'managecleanerschedule'; include('sidebar.php'); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include('topbar.php') ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                  
                  <?php if($_SESSION['StaffRole'] == 'Manager'){ ?>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="manage-tab" data-toggle="tab" href="#manage" role="tab" aria-controls="manage" aria-selected="true">Manage</a>
                      </li>
                      <li class="nav-item" role="presentation">
                        <a class="nav-link" id="view-tab" data-toggle="tab" href="#view" role="tab" aria-controls="view" aria-selected="false">View</a>
                      </li>
                    </ul>
                  <?php } elseif($_SESSION['StaffRole'] == 'Administrator'){ ?>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="manage-tab" data-toggle="tab" href="#manage" role="tab" aria-controls="manage" aria-selected="true">List</a>
                      </li>
                      <li class="nav-item" role="presentation">
                        <a class="nav-link" id="view-tab" data-toggle="tab" href="#view" role="tab" aria-controls="view" aria-selected="false">DaybyDay</a>
                      </li>
                    </ul>
                  <?php } ?>

                    <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade show active" id="manage" role="tabpanel" aria-labelledby="manage-tab">

                        <!-- DataTales -->
                        <div class="card shadow mb-4">
                            <div class="card-header d-flex justify-content-between">
                                <div class="p-2">
                                    <h6 class="m-0 font-weight-bold text-primary py-2">Cleaner Schedule List</h6>
                                </div>
                                <div class="p-2">
                                <?php 

                                if($_SESSION['StaffRole'] == 'Manager')
                                {
                                ?>
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cleanerscheduleentry">
                                      Add New Cleaner Schedule
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
                                                <th>Staff Name</th>
                                                <th>Day</th>
                                                <?php 
                                                if($_SESSION['StaffRole'] == 'Manager')
                                                {
                                                    echo "<th>Action <br> (Edit, Delete)</th>";
                                                }
                                                ?> 
                                            </tr>
                                        </thead>

                                        <tbody>

                                        <?php 

                                        for ($i=0; $i < $runcount; $i++) 
                                        { 
                                          $array = mysqli_fetch_array($run);
                                            $cleanerscheduleid = $array['CleanerScheduleID'];
                                            $staffid = $array['StaffID'];
                                            $firstname = $array['FirstName'];
                                            $lastname = $array['LastName'];
                                            $day = $array['Day'];
                                        
                                         ?>

                                            <tr>
                                                <td><?php echo $cleanerscheduleid; ?></td>
                                                <td><?php echo $firstname.' '.$lastname ?></td>
                                                <td><?php echo $day; ?></td>
                                                <?php 
                                                if($_SESSION['StaffRole'] == 'Manager')
                                                {
                                                    echo '<td style="color: #4e73df;"><i class="fas fa-pencil-alt fa-sm" data-toggle="modal" data-target="#cleanerscheduleupdate'.$i.'"></i>&nbsp&nbsp&nbsp | &nbsp&nbsp&nbsp<i class="fas fa-trash-alt fa-m" data-toggle="modal" data-target="#cleanerscheduledelete'.$i.'"></i></td>';
                                                }
                                                ?>   
                                            </tr>

                                            <!-- Cleaner Schedule Update Modal -->
                                            <?php echo '<div class="modal" id="cleanerscheduleupdate'.$i.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">'; ?>
                                              <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalCenterTitle">Cleaner Schedule Update</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                  <div class="modal-body">
                                                    <form action="managecleanerschedule.php" method="POST">
                                                        <div class="row g-3">
                                                          <input type="text" class="form-control border-0 bg-light px-4" name="txtcleanerscheduleid" style="height: 55px;" value="<?php echo $cleanerscheduleid ?>" hidden> 
                                                          <input type="text" class="form-control border-0 bg-light px-4" name="txtstaffid" style="height: 55px;" value="<?php echo $staffid ?>" hidden> 
                                                          <div class="col-12">
                                                              <label style="color:black;">&nbsp&nbsp&nbsp Staff Name:</label>
                                                          </div>
                                                          <div class="col-12">
                                                              <input type="text" class="form-control border-0 bg-light px-4" style="height: 55px;" value="<?php echo $firstname.' '.$lastname ?>" readonly>
                                                          </div>
                                                          <div class="col-12">
                                                              <label style="color:black;">&nbsp&nbsp&nbsp Day:</label>
                                                          </div>
                                                          <div class="col-12">
                                                              <select class="form-control bg-light border-0" name="txtday" style="height: 55px;" required>
                                                                  <option value="Sunday" <?php if($day == 'Sunday'){ echo "selected"; } ?>>&nbsp Sunday</option>
                                                                  <option value="Monday" <?php if($day == 'Monday'){ echo "selected"; } ?>>&nbsp Monday</option>
                                                                  <option value="Tuesday" <?php if($day == 'Tuesday'){ echo "selected"; } ?>>&nbsp Tuesday</option>
                                                                  <option value="Wednesday" <?php if($day == 'Wednesday'){ echo "selected"; } ?>>&nbsp Wednesday</option>
                                                                  <option value="Thursday" <?php if($day == 'Thursday'){ echo "selected"; } ?>>&nbsp Thursday</option>
                                                                  <option value="Friday" <?php if($day == 'Friday'){ echo "selected"; } ?>>&nbsp Friday</option>
                                                                  <option value="Saturday" <?php if($day == 'Saturday'){ echo "selected"; } ?>>&nbsp Saturday</option>
                                                              </select>
                                                          </div>
                                                          <div class="col-12"><label></label></div>
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

                                            <!-- Cleaner Schedule Delete Modal-->
                                            <?php echo '<div class="modal" id="cleanerscheduledelete'.$i.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">'; ?>
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to Delete?</h5>
                                                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Select "Delete" below if you want to delete this cleaner schedule.
                                                            <form action="managecleanerschedule.php" method="POST">
                                                                <div class="row g-3">
                                                                    <div class="col-12">
                                                                        <input type="text" class="form-control-plaintext border-0 bg-light px-4" name="txtcleanerscheduleid" style="height: 55px;" value="<?php echo $cleanerscheduleid; ?>" hidden>
                                                                    </div>
                                                                    <div class="col-12"><label></label></div>
                                                                    <div class="col-4"></div>
                                                                    <div class="col-4">
                                                                        <button class="btn btn-outline-dark w-100" name="btndelete" type="submit">Delete</button>
                                                                    </div>
                                                                    <div class="col-4">
                                                                        <button class="btn btn-primary w-100" data-dismiss="modal" type="reset">Cancel</button>
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

                        <!-- Cleaner Schedule Entry Modal -->
                        <div class="modal" id="cleanerscheduleentry" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalCenterTitle">Cleaner Schedule Entry</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <form action="managecleanerschedule.php" method="POST">
                                    <div class="row g-3">
                                      <div class="col-12">
                                          <label style="color:black;">&nbsp&nbsp&nbsp Staff Name:</label>
                                      </div>
                                      <div class="col-12">
                                        <select class="form-control bg-light border-0" name="txtstaffid" style="height: 55px;" required>
                                            <option value="" disabled selected hidden>&nbsp -- Select --</option>
                                            
                                            <?php 

                                            // Retrieve House Cleaner
                                            $select = "SELECT * FROM staff WHERE StaffRole = 'House Cleaner'";
                                            $run = mysqli_query($connect, $select);
                                            $runcount = mysqli_num_rows($run);

                                            for ($i=0; $i < $runcount; $i++) 
                                            { 
                                              $array = mysqli_fetch_array($run);
                                              $staffid = $array['StaffID'];
                                              $firstname = $array['FirstName'];
                                              $lastname = $array['LastName'];
                                              
                                              echo "<option value='$staffid'>&nbsp&nbsp&nbsp $firstname $lastname</option>";
                                            }

                                             ?>
                                        </select>
                                      </div>
                                      <div class="col-12">
                                          <label style="color:black;">&nbsp&nbsp&nbsp Day:</label>
                                      </div>
                                      <div class="col-12">
                                          <select class="form-control bg-light border-0" name="txtday" style="height: 55px;" required>
                                              <option value="" disabled selected hidden>&nbsp -- Select --</option>
                                              <option value="Sunday">&nbsp&nbsp&nbsp Sunday</option>
                                              <option value="Monday">&nbsp&nbsp&nbsp Monday</option>
                                              <option value="Tuesday">&nbsp&nbsp&nbsp Tuesday</option>
                                              <option value="Wednesday">&nbsp&nbsp&nbsp Wednesday</option>
                                              <option value="Thursday">&nbsp&nbsp&nbsp Thursday</option>
                                              <option value="Friday">&nbsp&nbsp&nbsp Friday</option>
                                              <option value="Saturday">&nbsp&nbsp&nbsp Saturday</option>
                                          </select>
                                      </div>
                                      <div class="col-12"><label></label></div>
                                      <div class="col-4"></div>
                                      <div class="col-4">
                                          <button class="btn btn-primary w-100" name="btnenter" type="submit">Enter</button>
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
                      <div class="tab-pane fade" id="view" role="tabpanel" aria-labelledby="view-tab">

                        <div class="row">

                          <div class="col-xl-12 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header -->
                                <div class="card-header d-flex justify-content-between">
                                    <div class="p-2">
                                      <h6 class="m-0 font-weight-bold text-primary py-2">Day by Day Cleaner Schedule</h6>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="profile-box">   
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card schedule-widget mb-0">
                                                
                                                    <!-- Schedule Header -->
                                                    <div class="schedule-header">
                                                    
                                                        <!-- Schedule Nav -->
                                                        <div class="schedule-nav">
                                                            <ul class="nav nav-tabs nav-justified">
                                                                <li class="nav-item">
                                                                    <a class="nav-link <?php if($Day == 'Sun'){ echo 'active'; } ?>" data-toggle="tab" href="#slot_sunday">Sunday</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link <?php if($Day == 'Mon'){ echo 'active'; } ?>" data-toggle="tab" href="#slot_monday">Monday</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link <?php if($Day == 'Tue'){ echo 'active'; } ?>" data-toggle="tab" href="#slot_tuesday">Tuesday</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link <?php if($Day == 'Wed'){ echo 'active'; } ?>" data-toggle="tab" href="#slot_wednesday">Wednesday</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link <?php if($Day == 'Thu'){ echo 'active'; } ?>" data-toggle="tab" href="#slot_thursday">Thursday</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link <?php if($Day == 'Fri'){ echo 'active'; } ?>" data-toggle="tab" href="#slot_friday">Friday</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link <?php if($Day == 'Sat'){ echo 'active'; } ?>" data-toggle="tab" href="#slot_saturday">Saturday</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <!-- /Schedule Nav -->
                                                        
                                                    </div>
                                                    <!-- /Schedule Header -->
                                                    
                                                    <!-- Schedule Content -->
                                                    <div class="tab-content schedule-cont">
                                                    
                                                        <!-- Sunday Slot -->
                                                        <div id="slot_sunday" class="modal-body tab-pane fade <?php if($Day == 'Sun'){ echo 'show active'; } ?>">
                                                            <!-- Slot List -->
                                                            <div class="row">

                                                              <?php 

                                                              // Retrieve Schedule
                                                              $today = 'Sunday';
                                                              $select = "SELECT * FROM cleanerschedule c, staff s WHERE c.StaffID = s.StaffID AND c.Day = '$today'";
                                                              $run = mysqli_query($connect, $select);
                                                              $runcount = mysqli_num_rows($run);

                                                              if($runcount == 0)
                                                              {

                                                                echo '<div class="col-12 my-2">
                                                                        <div class="text-dark text-center py-2">
                                                                            <b>No Schedule Available</b>
                                                                        </div>
                                                                      </div>';
                                                              }
                                                              else
                                                              {
                                                                for ($i = 0; $i < $runcount ; $i++) 
                                                                { 

                                                                  $array = mysqli_fetch_array($run);
                                                                  $firstname = $array['FirstName'];
                                                                  $lastname = $array['LastName'];

                                                                  echo '<div class="col-lg-3 my-2">
                                                                          <div class="card text-primary border border-primary">
                                                                            <div class="card-text text-center py-2">
                                                                              <div class="text-primary-50 small">'.$firstname.' '.$lastname.'</div>
                                                                            </div>
                                                                          </div>
                                                                        </div>';
                                                                }
                                                              }
                                                               ?>

                                                            </div>
                                                        </div>
                                                        <!-- /Sunday Slot -->

                                                        <!-- Monday Slot -->
                                                        <div id="slot_monday" class="modal-body tab-pane fade <?php if($Day == 'Mon'){ echo 'show active'; } ?>">
                                                            
                                                            <!-- Slot List -->
                                                            <div class="row">
                                                              <?php 

                                                              // Retrieve Schedule
                                                              $today = 'Monday';
                                                              $select = "SELECT * FROM cleanerschedule c, staff s WHERE c.StaffID = s.StaffID AND c.Day = '$today'";
                                                              $run = mysqli_query($connect, $select);
                                                              $runcount = mysqli_num_rows($run);

                                                              if($runcount == 0)
                                                              {

                                                                echo '<div class="col-12 my-2">
                                                                        <div class="text-dark text-center py-2">
                                                                            <b>No Schedule Available</b>
                                                                        </div>
                                                                      </div>';
                                                              }
                                                              else
                                                              {
                                                                for ($i = 0; $i < $runcount ; $i++) 
                                                                { 

                                                                  $array = mysqli_fetch_array($run);
                                                                  $firstname = $array['FirstName'];
                                                                  $lastname = $array['LastName'];

                                                                  echo '<div class="col-lg-3 my-2">
                                                                          <div class="card text-primary border border-primary">
                                                                            <div class="card-text text-center py-2">
                                                                              <div class="text-primary-50 small">'.$firstname.' '.$lastname.'</div>
                                                                            </div>
                                                                          </div>
                                                                        </div>';
                                                                }
                                                              }
                                                               ?>
                                                            </div>
                                                            <!-- /Slot List -->
                                                            
                                                        </div>
                                                        <!-- /Monday Slot -->

                                                        <!-- Tuesday Slot -->
                                                        <div id="slot_tuesday" class="modal-body tab-pane fade <?php if($Day == 'Tue'){ echo 'show active'; } ?> <?php if($day == 'Mon'){ echo 'show active'; } ?>">
                                                            <div class="row">
                                                              <?php 

                                                              // Retrieve Schedule
                                                              $today = 'Tuesday';
                                                              $select = "SELECT * FROM cleanerschedule c, staff s WHERE c.StaffID = s.StaffID AND c.Day = '$today'";
                                                              $run = mysqli_query($connect, $select);
                                                              $runcount = mysqli_num_rows($run);

                                                              if($runcount == 0)
                                                              {

                                                                echo '<div class="col-12 my-2">
                                                                        <div class="text-dark text-center py-2">
                                                                            <b>No Schedule Available</b>
                                                                        </div>
                                                                      </div>';
                                                              }
                                                              else
                                                              {
                                                                for ($i = 0; $i < $runcount ; $i++) 
                                                                { 

                                                                  $array = mysqli_fetch_array($run);
                                                                  $firstname = $array['FirstName'];
                                                                  $lastname = $array['LastName'];

                                                                  echo '<div class="col-lg-3 my-2">
                                                                          <div class="card text-primary border border-primary">
                                                                            <div class="card-text text-center py-2">
                                                                              <div class="text-primary-50 small">'.$firstname.' '.$lastname.'</div>
                                                                            </div>
                                                                          </div>
                                                                        </div>';
                                                                }
                                                              }
                                                               ?>
                                                            </div>
                                                        </div>
                                                        <!-- /Tuesday Slot -->

                                                        <!-- Wednesday Slot -->
                                                        <div id="slot_wednesday" class="modal-body tab-pane fade <?php if($Day == 'Wed'){ echo 'show active'; } ?>">
                                                            <div class="row">
                                                              <?php 

                                                              // Retrieve Schedule
                                                              $today = 'Wednesday';
                                                              $select = "SELECT * FROM cleanerschedule c, staff s WHERE c.StaffID = s.StaffID AND c.Day = '$today'";
                                                              $run = mysqli_query($connect, $select);
                                                              $runcount = mysqli_num_rows($run);

                                                              if($runcount == 0)
                                                              {

                                                                echo '<div class="col-12 my-2">
                                                                        <div class="text-dark text-center py-2">
                                                                            <b>No Schedule Available</b>
                                                                        </div>
                                                                      </div>';
                                                              }
                                                              else
                                                              {
                                                                for ($i = 0; $i < $runcount ; $i++) 
                                                                { 

                                                                  $array = mysqli_fetch_array($run);
                                                                  $firstname = $array['FirstName'];
                                                                  $lastname = $array['LastName'];

                                                                  echo '<div class="col-lg-3 my-2">
                                                                          <div class="card text-primary border border-primary">
                                                                            <div class="card-text text-center py-2">
                                                                              <div class="text-primary-50 small">'.$firstname.' '.$lastname.'</div>
                                                                            </div>
                                                                          </div>
                                                                        </div>';
                                                                }
                                                              }
                                                               ?>
                                                            </div>
                                                        </div>
                                                        <!-- /Wednesday Slot -->

                                                        <!-- Thursday Slot -->
                                                        <div id="slot_thursday" class="modal-body tab-pane fade <?php if($Day == 'Thu'){ echo 'show active'; } ?>">
                                                            <div class="row">
                                                              <?php 

                                                              // Retrieve Schedule
                                                              $today =  'Thursday';
                                                              $select = "SELECT * FROM cleanerschedule c, staff s WHERE c.StaffID = s.StaffID AND c.Day = '$today'";
                                                              $run = mysqli_query($connect, $select);
                                                              $runcount = mysqli_num_rows($run);

                                                              if($runcount == 0)
                                                              {

                                                                echo '<div class="col-12 my-2">
                                                                        <div class="text-dark text-center py-2">
                                                                            <b>No Schedule Available</b>
                                                                        </div>
                                                                      </div>';
                                                              }
                                                              else
                                                              {
                                                                for ($i = 0; $i < $runcount ; $i++) 
                                                                { 

                                                                  $array = mysqli_fetch_array($run);
                                                                  $firstname = $array['FirstName'];
                                                                  $lastname = $array['LastName'];

                                                                  echo '<div class="col-lg-3 my-2">
                                                                          <div class="card text-primary border border-primary">
                                                                            <div class="card-text text-center py-2">
                                                                              <div class="text-primary-50 small">'.$firstname.' '.$lastname.'</div>
                                                                            </div>
                                                                          </div>
                                                                        </div>';
                                                                }
                                                              }
                                                               ?>
                                                            </div>
                                                        </div>
                                                        <!-- /Thursday Slot -->

                                                        <!-- Friday Slot -->
                                                        <div id="slot_friday" class="modal-body tab-pane fade <?php if($Day == 'Fri'){ echo 'show active'; } ?>">
                                                            <div class="row">
                                                              <?php 

                                                              // Retrieve Schedule
                                                              $today =  'Friday';
                                                              $select = "SELECT * FROM cleanerschedule c, staff s WHERE c.StaffID = s.StaffID AND c.Day = '$today'";
                                                              $run = mysqli_query($connect, $select);
                                                              $runcount = mysqli_num_rows($run);

                                                              if($runcount == 0)
                                                              {

                                                                echo '<div class="col-12 my-2">
                                                                        <div class="text-dark text-center py-2">
                                                                            <b>No Schedule Available</b>
                                                                        </div>
                                                                      </div>';
                                                              }
                                                              else
                                                              {
                                                                for ($i = 0; $i < $runcount ; $i++) 
                                                                { 

                                                                  $array = mysqli_fetch_array($run);
                                                                  $firstname = $array['FirstName'];
                                                                  $lastname = $array['LastName'];

                                                                  echo '<div class="col-lg-3 my-2">
                                                                          <div class="card text-primary border border-primary">
                                                                            <div class="card-text text-center py-2">
                                                                              <div class="text-primary-50 small">'.$firstname.' '.$lastname.'</div>
                                                                            </div>
                                                                          </div>
                                                                        </div>';
                                                                }
                                                              }
                                                               ?>
                                                            </div>
                                                        </div>
                                                        <!-- /Friday Slot -->

                                                        <!-- Saturday Slot -->
                                                        <div id="slot_saturday" class="modal-body tab-pane fade <?php if($Day == 'Sat'){ echo 'show active'; } ?>">
                                                            <div class="row">
                                                              <?php 

                                                              // Retrieve Schedule
                                                              $today =  'Saturday';
                                                              $select = "SELECT * FROM cleanerschedule c, staff s WHERE c.StaffID = s.StaffID AND c.Day = '$today'";
                                                              $run = mysqli_query($connect, $select);
                                                              $runcount = mysqli_num_rows($run);

                                                              if($runcount == 0)
                                                              {

                                                                echo '<div class="col-12 my-2">
                                                                        <div class="text-dark text-center py-2">
                                                                            <b>No Schedule Available</b>
                                                                        </div>
                                                                      </div>';
                                                              }
                                                              else
                                                              {
                                                                for ($i = 0; $i < $runcount ; $i++) 
                                                                { 

                                                                  $array = mysqli_fetch_array($run);
                                                                  $firstname = $array['FirstName'];
                                                                  $lastname = $array['LastName'];

                                                                  echo '<div class="col-lg-3 my-2">
                                                                          <div class="card text-primary border border-primary">
                                                                            <div class="card-text text-center py-2">
                                                                              <div class="text-primary-50 small">'.$firstname.' '.$lastname.'</div>
                                                                            </div>
                                                                          </div>
                                                                        </div>';
                                                                }
                                                              }
                                                               ?>
                                                            </div>
                                                        </div>
                                                        <!-- /Saturday Slot -->

                                                    </div>
                                                    <!-- /Schedule Content -->
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
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