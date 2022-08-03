<?php 
session_start();
include('autoid.php');
include('connect.php');

// Check Staff Account
$staffid = $_SESSION['StaffID'];
$role = $_SESSION['StaffRole'];
if (!isset($_SESSION['StaffID']))
{
  echo "<script>alert('Please log in first')</script>";
  echo "<script>window.location='stafflogin.php'</script>";
}
elseif ($role == 'House Cleaner')
{
  echo "<script>alert('Only Manager and Administrator can have access to employee leaves.')</script>";
  echo "<script>window.location='stafflogin.php'</script>";
}

// Entry Form Submission 
if(isset($_POST['btnenter']))
{
  $leaveid = AutoID('staffleave', 'LeaveID', 'SL-', 6);
  $staffid = $_POST['txtstaffid'];
  $leavetype = $_POST['txtleavetype'];
  $startingdate = $_POST['txtstartingdate'];
  $endingdate = $_POST['txtendingdate'];

  // Insert Staff Leave
  $insert = "INSERT INTO staffleave(LeaveID, StaffID, LeaveType, StartingDate, EndingDate) VALUES ('$leaveid', '$staffid', '$leavetype', '$startingdate', '$endingdate')";
  $query = mysqli_query($connect, $insert);

  if ($query)
  {
    echo "<script>alert('Staff Leave Information Entered')</script>";
    echo "<script>window.location='managestaffleave.php'</script>";
  }
  else
  {
    mysqli_error($connect);
  }
}

// Update Form Submission 
if(isset($_POST['btnupdate']))
{
  $leaveid = $_POST['txtleaveid'];
  $leavetype = $_POST['txtleavetype'];
  $startingdate = $_POST['txtstartingdate'];
  $endingdate = $_POST['txtendingdate'];

  // Update Staff
  $update = "UPDATE staffleave 
            SET LeaveType = '$leavetype',
            StartingDate = '$startingdate', 
            EndingDate = '$endingdate'
            WHERE LeaveID = '$leaveid'";
  $query = mysqli_query($connect, $update);

  if($query)
  {
    echo "<script>alert('Leave Information Update Successful')</script>";
    echo "<script>window.location='managestaffleave.php'</script>";
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

    <title>C&M | Staff Leave List</title>

    <!-- Favicon -->
    <link href="../../User/assets/Img/logo.ico" rel="icon">

    <?php include('header.php') ?>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php $page = 'managestaffleave'; include('sidebar.php'); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include('topbar.php') ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">
                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                      <a class="nav-link active" id="upcoming-tab" data-toggle="tab" href="#upcoming" role="tab" aria-controls="upcoming" aria-selected="true">Upcoming</a>
                    </li>
                    <li class="nav-item" role="presentation">
                      <a class="nav-link" id="previous-tab" data-toggle="tab" href="#previous" role="tab" aria-controls="previous" aria-selected="false">Previous</a>
                    </li>
                    <?php 

                      if($role == 'Administrator')
                      {
                      ?>
                    <li class="ml-auto">
                      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staffleaveentry">
                        Add Staff Leave
                      </button>
                    </li>
                    <?php } ?>
                  </ul>                      

                    <div class="tab-content" id="myTabContent">
                      <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">

                        <!-- DataTables -->
                        <div class="card shadow mb-4">
                            <div class="card-header">
                              <h6 class="m-0 font-weight-bold text-primary py-2">Upcoming Staff Leave List</h6>
                            </div>
                            
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Staff Name</th>
                                                <th>Starting Date</th>
                                                <th>Ending Date</th>
                                                <th>Reason</th>
                                                <?php 
                                                if($role == 'Administrator')
                                                {
                                                    echo "<th>Action (Edit)</th>";
                                                }
                                                ?>                                            
                                            </tr>
                                        </thead>

                                        <tbody>

                                        <?php 

                                        // Retrieve Staff Leave
                                        $today = date('Y-m-d');
                                        $select = "SELECT * FROM staffleave l, staff s WHERE l.StaffID = s.StaffID AND l.EndingDate >= '$today'";
                                        $run = mysqli_query($connect, $select);
                                        $runcount = mysqli_num_rows($run);

                                        for ($i = 0; $i < $runcount; $i++) 
                                        { 
                                          $array = mysqli_fetch_array($run);
                                          $staffid = $array['StaffID'];
                                          $firstname = $array['FirstName'];
                                          $lastname = $array['LastName'];
                                          $leaveid = $array['LeaveID'];
                                          $leavetype = $array['LeaveType'];
                                          $startingdate = $array['StartingDate'];
                                          $endingdate = $array['EndingDate'];
                                        
                                         ?>

                                            <tr>
                                                <td><?php echo $leaveid; ?></td>
                                                <td><?php echo $firstname." ".$lastname ?></td>
                                                <td><?php echo $startingdate; ?></td>
                                                <td><?php echo $endingdate; ?></td>
                                                <td style="word-break:break-word;"><?php echo $leavetype; ?></td>
                                                <?php 
                                                if($role == 'Administrator')
                                                {
                                                    echo '<td style="color: #4e73df;"><i class="fas fa-pencil-alt fa-sm" data-toggle="modal" data-target="#staffleaveupdate'.$i.'"></td>';
                                                }
                                                ?>                                              
                                            </tr>

                                            <!-- Staff Leave Update Modal -->
                                            <?php echo '<div class="modal" id="staffleaveupdate'.$i.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">'; ?>
                                              <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                  <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalCenterTitle">Staff Leave Update</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                    </button>
                                                  </div>
                                                  <div class="modal-body">
                                                    <form action="managestaffleave.php" method="POST">
                                                        <div class="row g-3">
                                                            <div class="col-12">
                                                                <input type="text" class="form-control-plaintext border-0 bg-light px-4" name="txtleaveid" style="height: 55px;" value="<?php echo $leaveid; ?>" hidden>
                                                            </div>
                                                            <div class="col-12">
                                                                <label style="color:black;">&nbsp&nbsp&nbsp Staff Name:</label>
                                                            </div>
                                                            <div class="col-12">
                                                              <input type="text" class="form-control border-0 bg-light px-4" style="height: 55px;" autocomplete="off" value="<?php echo $firstname.' '.$lastname; ?>" readonly>
                                                            </div>
                                                            <div class="col-12">
                                                                <label style="color:black;">&nbsp&nbsp&nbsp Leave Type/ Reason:</label>
                                                            </div>
                                                            <div class="col-12">
                                                                <input type="text" class="form-control border-0 bg-light px-4" name="txtleavetype"  style="height: 55px;" autocomplete="off" value="<?php echo $leavetype ?>" required>
                                                            </div>
                                                            <div class="col-6">
                                                                <label style="color:black;">&nbsp&nbsp&nbsp Starting Date:</label>
                                                            </div>
                                                            <div class="col-6">
                                                                <label style="color:black;">&nbsp&nbsp&nbsp Ending Date:</label>
                                                            </div>
                                                            <div class="col-6">
                                                                <input type="date" class="form-control border-0 bg-light px-4" id="sdate" name="txtstartingdate" style="height: 55px;" value="<?php echo $startingdate ?>" autocomplete="off" onchange="check(sdate, edate)" required>                                
                                                            </div>
                                                            <div class="col-6">
                                                                <input type="date" class="form-control border-0 bg-light px-4" id="edate" name="txtendingdate" min="<?php echo $today; ?>" style="height: 55px;" value="<?php echo $endingdate ?>" autocomplete="off" onchange="check(sdate, edate)" required>  
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

                                        <?php } ?>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                      </div>
                      <div class="tab-pane fade" id="previous" role="tabpanel" aria-labelledby="previous-tab">

                        <!-- DataTables -->
                        <div class="card shadow mb-4">
                            <div class="card-header">
                              <h6 class="m-0 font-weight-bold text-primary py-2">Previous Staff Leave List</h6>
                            </div>
                            
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center" id="dataTable1" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Staff Name</th>
                                                <th>Starting Date</th>
                                                <th>Ending Date</th>
                                                <th>Reason</th>                          
                                            </tr>
                                        </thead>

                                        <tbody>

                                        <?php 

                                        // Retrieve Staff Leave
                                        $select = "SELECT * FROM staffleave l, staff s WHERE l.StaffID = s.StaffID AND l.EndingDate < '$today'";
                                        $run = mysqli_query($connect, $select);
                                        $runcount = mysqli_num_rows($run);

                                        for ($i=0; $i < $runcount; $i++) 
                                        { 
                                          $array = mysqli_fetch_array($run);
                                          $staffid = $array['StaffID'];
                                          $firstname = $array['FirstName'];
                                          $lastname = $array['LastName'];
                                          $leaveid = $array['LeaveID'];
                                          $leavetype = $array['LeaveType'];
                                          $startingdate = $array['StartingDate'];
                                          $endingdate = $array['EndingDate'];
                                        
                                         ?>

                                            <tr>
                                                <td><?php echo $leaveid; ?></td>
                                                <td><?php echo $firstname." ".$lastname ?></td>
                                                <td><?php echo $startingdate; ?></td>
                                                <td><?php echo $endingdate; ?></td>
                                                <td style="word-break:break-word;"><?php echo $leavetype; ?></td>
                                            </tr>

                                        <?php } ?>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                      </div>
                    </div>

                    <!-- Staff Leave Entry Modal -->
                    <div class="modal" id="staffleaveentry" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Add Staff Leave</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <form action="managestaffleave.php" method="POST">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label style="color:black;">&nbsp&nbsp&nbsp Staff Name:</label>
                                    </div>
                                    <div class="col-12">
                                        <select class="form-control bg-light border-0" name="txtstaffid" style="height: 55px;" required>
                                            <option value="" disabled selected hidden>&nbsp -- Select --</option>

                                            <?php 

                                            // Retrieve Staff
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
                                        <label style="color:black;">&nbsp&nbsp&nbsp Leave Type/ Reason:</label>
                                    </div>
                                    <div class="col-12">
                                        <textarea class="form-control border-0 bg-light px-4 py-3" rows="3" name="txtleavetype" required></textarea>
                                    </div>
                                    <div class="col-6">
                                        <label style="color:black;">&nbsp&nbsp&nbsp Starting Date:</label>
                                    </div>
                                    <div class="col-6">
                                        <label style="color:black;">&nbsp&nbsp&nbsp Ending Date:</label>
                                    </div>
                                    <div class="col-6">
                                        <input type="date" class="form-control border-0 bg-light px-4" id="sdate" name="txtstartingdate" min="<?php echo $today; ?>" style="height: 55px;" autocomplete="off" onchange="check(sdate, edate)" required>
                                    </div>
                                    <div class="col-6">
                                        <input type="date" class="form-control border-0 bg-light px-4" id="edate" name="txtendingdate" min="<?php echo $today; ?>" style="height: 55px;" autocomplete="off" onchange="check(sdate, edate)" required>                                
                                    </div>
                                    <script>
                                        var sdate = document.getElementById("sdate");
                                        var edate = document.getElementById("edate");

                                        function check(sdate, edate) 
                                        {
                                          var start = new Date(sdate.value);                                        
                                          var end = new Date(edate.value);

                                          if (start.getTime() <= end.getTime()) 
                                          {
                                            edate.setCustomValidity('');
                                          } 
                                          else 
                                          {
                                            edate.setCustomValidity('Starting Date should be equal to or less than Ending Date');
                                          }
                                        }
                                    </script>
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
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <?php include('footer.php') ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

</body>

</html>