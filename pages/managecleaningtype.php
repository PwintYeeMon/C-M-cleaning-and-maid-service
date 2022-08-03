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
  echo "<script>window.alert('Only Manager and Administrator can view Cleaning Type List.')</script>";
  echo "<script>window.location='stafflogin.php'</script>";
}

// Retrieve Cleaning Type
$select = "SELECT * FROM cleaningtype";
$run = mysqli_query($connect, $select);
$runcount = mysqli_num_rows($run);

// Entry Form Submission 
if(isset($_POST['btnenter']))
{
  $cleaningtypeid = AutoID('cleaningtype', 'CleaningTypeID', 'CT-', 6);
  $cleaningtype = $_POST['txtcleaningtype'];
  $description = $_POST['txtdescription'];

  $image = $_FILES['txtimage']['name'];
  $file = "../assets/Img/".$image;
  $copied = copy($_FILES['txtimage']['tmp_name'],$file);
  $filename = "Img/".$image;

  $mincleaners = $_POST['txtmincleaners'];
  $maxcleaners = $_POST['txtmaxcleaners'];
  $minduration = $_POST['txtminduration'];
  $maxduration = $_POST['txtmaxduration'];
  $rate = $_POST['txtrate'];
  $commissionrate = $_POST['txtcommissionrate']/100;

  // Insert Cleaning Type 
  $insert = "INSERT INTO cleaningtype(CleaningTypeID, CleaningType, Description, Image, NoofCleaners_min, NoofCleaners_max, Duration_hr_min, Duration_hr_max, Rate, CommissionRate) VALUES ('$cleaningtypeid', '$cleaningtype', '$description', '$filename', '$mincleaners', '$maxcleaners', '$minduration', '$maxduration', '$rate', '$commissionrate')";
  $query = mysqli_query($connect, $insert);

  if ($query)
  {
    echo "<script>alert('Cleaning Type Entered')</script>";
    echo "<script>window.location='managecleaningtype.php'</script>";
  }
  else
  {
    mysqli_error($connect);
  }
}

// Update Form Submission 
if(isset($_POST['btnupdate']))
{
  $cleaningtypeid = $_POST['txtcleaningtypeid'];
  $cleaningtype = $_POST['txtcleaningtype'];
  $description = $_POST['txtdescription'];
  $image = $_FILES['txtimage']['name'];
  $mincleaners = $_POST['txtmincleaners'];
  $maxcleaners = $_POST['txtmaxcleaners'];
  $minduration = $_POST['txtminduration'];
  $maxduration = $_POST['txtmaxduration'];
  $rate = $_POST['txtrate'];
  $commissionrate = $_POST['txtcommissionrate']/100;

  if($image == null)
  {
    // Update Cleaning Type
    $update = "UPDATE cleaningtype
              SET CleaningType = '$cleaningtype', 
              Description = '$description',
              NoofCleaners_min = '$mincleaners', 
              NoofCleaners_max = '$maxcleaners', 
              Duration_hr_min = '$minduration', 
              Duration_hr_max = '$maxduration', 
              Rate = '$rate', 
              CommissionRate = '$commissionrate'
              WHERE CleaningTypeID = '$cleaningtypeid'";
    $query = mysqli_query($connect, $update);
  }
  else
  {
    // Retrieve Cleaning Type to Delete Image
    $select = "SELECT Image FROM cleaningtype WHERE CleaningTypeID = '$cleaningtypeid'";
    $run = mysqli_query($connect, $select);
    $array = mysqli_fetch_array($run);
    $oldimage = "../../User/assets/".$array['Image'];

    unlink("$oldimage");
    $file = "../../User/assets/Img/".$image;
    $copied = copy($_FILES['txtimage']['tmp_name'],$file);
    $filename = "Img/".$image;

    // Update Cleaning Type
    $update = "UPDATE cleaningtype
              SET CleaningType = '$cleaningtype', 
              Description = '$description',
              Image = '$filename',
              NoofCleaners_min = '$mincleaners', 
              NoofCleaners_max = '$maxcleaners', 
              Duration_hr_min = '$minduration', 
              Duration_hr_max = '$maxduration', 
              Rate = '$rate', 
              CommissionRate = '$commissionrate'
              WHERE CleaningTypeID = '$cleaningtypeid'";
    $query = mysqli_query($connect, $update);
  }

  if ($query)
  {
    echo "<script>alert('Cleaning Type Information Update Successful')</script>";
    echo "<script>window.location='managecleaningtype.php'</script>";
  }
  else
  {
    mysqli_error($connect);
  }
}

// Delete 
if(isset($_POST['btndelete']))
{
  $cleaningtypeid = $_POST['txtcleaningtypeid'];

  // Retrieve Cleaning Type to Delete Image
  $select = "SELECT Image FROM cleaningtype WHERE CleaningTypeID = '$cleaningtypeid'";
  $run = mysqli_query($connect, $select);
  $array = mysqli_fetch_array($run);
  $oldimage = "../assets/".$array['Image'];

  unlink("$oldimage");

  // Delete Cleaning Type
  $delete = "DELETE FROM CleaningType
              WHERE CleaningTypeID = '$cleaningtypeid'";
  $query = mysqli_query($connect, $delete);

  if ($query)
  {
    echo "<script>alert('Cleaning Type Information Delete Successful')</script>";
    echo "<script>window.location='managecleaningtype.php'</script>";
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

    <title>C&M | Cleaning Type List</title>

    <!-- Favicon -->
    <link href="../../User/assets/Img/logo.ico" rel="icon">

    <?php include('header.php') ?>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php $page = 'managecleaningtype'; include('sidebar.php'); ?>

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
                                <h6 class="m-0 font-weight-bold text-primary py-2">Cleaning Type List</h6>
                            </div>
                            <div class="p-2">
                            <?php 

                            if($_SESSION['StaffRole'] == 'Manager')
                            {
                            ?>

                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cleaningtypeentry">
                                  Add New Cleaning Type
                                </button>

                            <?php } ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Descr</th>
                                            <th>Image</th>
                                            <th>No of Cleaners</th>
                                            <th>Duration (hr)</th>
                                            <th>Rate (x)</th>
                                            <th>Commission Rate (%)</th>
                                            <?php 
                                            if($_SESSION['StaffRole'] == 'Manager')
                                            {
                                                echo "<th>Action (Edit, Delete)</th>";
                                            }
                                            ?> 
                                        </tr>
                                    </thead>

                                    <tbody>

                                    <?php 

                                    for ($i=0; $i < $runcount; $i++) 
                                    { 
                                      $array = mysqli_fetch_array($run);
                                      $cleaningtypeid = $array['CleaningTypeID'];
                                      $cleaningtype = $array['CleaningType'];
                                      $description = $array['Description'];
                                      $image = "../../User/assets/".$array['Image'];
                                      $mincleaners = $array['NoofCleaners_min'];
                                      $maxcleaners = $array['NoofCleaners_max'];
                                      $minduration = $array['Duration_hr_min'];
                                      $maxduration = $array['Duration_hr_max'];
                                      $rate = $array['Rate'];
                                      $commissionrate = $array['CommissionRate']*100;
                                    
                                     ?>

                                        <tr>
                                            <td><?php echo $cleaningtype; ?></td>
                                            <td><?php echo $description; ?></td>
                                            <td><img src="<?php echo $image; ?>" width="140" height="70" alt="Cleaning Type"></td>
                                            <td><?php echo $mincleaners.' ~ '.$maxcleaners ?></td>
                                            <td><?php echo $minduration.' ~ '.$maxduration; ?></td>
                                            <td><?php echo $rate; ?></td>
                                            <td><?php echo $commissionrate; ?></td>
                                            <?php 
                                            if($_SESSION['StaffRole'] == 'Manager')
                                            {
                                                echo '<td style="color: #4e73df;"><i class="fas fa-pencil-alt fa-sm" data-toggle="modal" data-target="#cleaningtypeupdate'.$i.'"></i>&nbsp&nbsp&nbsp | &nbsp&nbsp&nbsp<i class="fas fa-trash-alt fa-m" data-toggle="modal" data-target="#cleaningtypedelete'.$i.'"></i></td>';
                                            }
                                            ?>   
                                        </tr>

                                        <!-- Cleaning Type Update Modal -->
                                        <?php echo '<div class="modal" id="cleaningtypeupdate'.$i.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">'; ?>
                                          <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalCenterTitle">Cleaning Type Update</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                                </button>
                                              </div>
                                              <div class="modal-body">
                                                <form action="managecleaningtype.php" method="POST" enctype="multipart/form-data">
                                                    <div class="row g-3">
                                                        <div class="col-12">
                                                            <input type="text" class="form-control-plaintext border-0 bg-light px-4" name="txtcleaningtypeid" style="height: 55px;" value="<?php echo $cleaningtypeid; ?>" hidden>
                                                        </div>
                                                        <div class="col-6">
                                                            <label style="color:black;">&nbsp&nbsp&nbsp Cleaning Type:</label>
                                                        </div>
                                                        <div class="col-6">
                                                            <label style="color:black;">&nbsp&nbsp&nbsp Description:</label>
                                                        </div>
                                                        <div class="col-6">
                                                            <input type="text" class="form-control border-0 bg-light px-4" name="txtcleaningtype" style="height: 55px;" autocomplete="off" value="<?php echo $cleaningtype; ?>" required>
                                                        </div>
                                                        <div class="col-6">
                                                            <input type="text" class="form-control border-0 bg-light px-4" name="txtdescription" style="height: 55px;" autocomplete="off" value="<?php echo $description; ?>" required>
                                                        </div>
                                                        <div class="col-6">
                                                            <label style="color:black;">&nbsp&nbsp&nbsp Current Photo:</label>
                                                        </div>
                                                        <div class="col-6">
                                                            <label style="color:black;">&nbsp&nbsp&nbsp New Photo:</label>
                                                        </div>
                                                        <div class="col-6">
                                                            <img class="img-fluid" src="<?php echo $image; ?>" alt="Cleaning Type">
                                                        </div>
                                                        <div class="col-6">
                                                            <input  type="file" accept="image/*" class="form-control-file" name="txtimage">
                                                        </div>
                                                        <div class="col-6">
                                                            <label style="color:black;">&nbsp&nbsp&nbsp Minimum Cleaners:</label>
                                                        </div>
                                                        <div class="col-6">
                                                            <label style="color:black;">&nbsp&nbsp&nbsp Maximum Cleaners:</label>
                                                        </div>
                                                        <div class="col-6">
                                                            <input type="number" class="form-control border-0 bg-light px-4" name="txtmincleaners" id="minc" onchange="check(minc, maxc)" min="1" max="5" style="height: 55px;" autocomplete="off" value="<?php echo $mincleaners; ?>" required>
                                                        </div>
                                                        <div class="col-6">
                                                            <input type="number" class="form-control border-0 bg-light px-4" name="txtmaxcleaners" id="maxc" onchange="check(minc, maxc)" min="2" max="10" style="height: 55px;" autocomplete="off" value="<?php echo $maxcleaners; ?>" required>
                                                        </div>
                                                        <div class="col-6">
                                                            <label style="color:black;">&nbsp&nbsp&nbsp Minimum Duration (hr):</label>
                                                        </div>
                                                        <div class="col-6">
                                                            <label style="color:black;">&nbsp&nbsp&nbsp Maximum Duration (hr):</label>
                                                        </div>
                                                        <div class="col-6">
                                                            <input type="number" class="form-control border-0 bg-light px-4" name="txtminduration" id="mind" onchange="check(mind, maxd)" min="1" max="5" style="height: 55px;" autocomplete="off" value="<?php echo $minduration; ?>" required>
                                                        </div>
                                                        <div class="col-6">
                                                            <input type="number" class="form-control border-0 bg-light px-4" name="txtmaxduration" id="maxd" onchange="check(mind, maxd)" min="2" max="10" style="height: 55px;" autocomplete="off" value="<?php echo $maxduration; ?>" required>
                                                        </div>
                                                        <div class="col-6">
                                                            <label style="color:black;">&nbsp&nbsp&nbsp Rate (multiplier, ×):</label>
                                                        </div>
                                                        <div class="col-6">
                                                            <label style="color:black;">&nbsp&nbsp&nbsp Commission Rate (%):</label>
                                                        </div>
                                                        <div class="col-6">
                                                            <input type="number" class="form-control border-0 bg-light px-4" name="txtrate" step="0.01" min="0.01" style="height: 55px;" autocomplete="off" value="<?php echo $rate; ?>" required>                                
                                                        </div>
                                                        <div class="col-6">
                                                            <input type="number" class="form-control border-0 bg-light px-4" name="txtcommissionrate" max="100" min="1" style="height: 55px;" autocomplete="off"  value="<?php echo $commissionrate; ?>"required>
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

                                        <!-- Cleaning Type Delete Modal-->
                                        <?php echo '<div class="modal" id="cleaningtypedelete'.$i.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">'; ?>
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to Delete?</h5>
                                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Select "Delete" below if you are want to delete this cleaning type.
                                                        <form action="managecleaningtype.php" method="POST">
                                                            <div class="row g-3">
                                                                <div class="col-12">
                                                                    <input type="text" class="form-control-plaintext border-0 bg-light px-4" name="txtcleaningtypeid" style="height: 55px;" value="<?php echo $cleaningtypeid; ?>" hidden>
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

                    <!-- Cleaning Type Entry Modal -->
                    <div class="modal" id="cleaningtypeentry" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Cleaning Type Entry</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <form action="managecleaningtype.php" method="POST" enctype="multipart/form-data">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label style="color:black;">&nbsp&nbsp&nbsp Cleaning Type:</label>
                                    </div>
                                    <div class="col-6">
                                        <label style="color:black;">&nbsp&nbsp&nbsp Image:</label>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" class="form-control border-0 bg-light px-4" name="txtcleaningtype" style="height: 55px;" autocomplete="off" required>
                                    </div>
                                    <div class="col-6">
                                        <input  type="file" accept="image/*" class="form-control-file" name="txtimage" required>
                                    </div>
                                    <div class="col-12">
                                        <label style="color:black;">&nbsp&nbsp&nbsp Description:</label>
                                    </div>
                                    <div class="col-12">
                                        <input type="text" class="form-control border-0 bg-light px-4" name="txtdescription" style="height: 55px;" autocomplete="off" required>
                                    </div>
                                    <div class="col-6">
                                        <label style="color:black;">&nbsp&nbsp&nbsp Minimum Cleaners:</label>
                                    </div>
                                    <div class="col-6">
                                        <label style="color:black;">&nbsp&nbsp&nbsp Maximum Cleaners:</label>
                                    </div>
                                    <div class="col-6">
                                        <input type="number" class="form-control border-0 bg-light px-4" name="txtmincleaners" id="minc" onchange="check(minc, maxc)" min="1" max="5" style="height: 55px;" autocomplete="off" required>
                                    </div>
                                    <div class="col-6">
                                        <input type="number" class="form-control border-0 bg-light px-4" name="txtmaxcleaners" id="maxc" onchange="check(minc, maxc)" min="2" max="10" style="height: 55px;" autocomplete="off" required>
                                    </div>
                                    <div class="col-6">
                                        <label style="color:black;">&nbsp&nbsp&nbsp Minimum Duration (hr):</label>
                                    </div>
                                    <div class="col-6">
                                        <label style="color:black;">&nbsp&nbsp&nbsp Maxmum Duration (hr):</label>
                                    </div>
                                    <div class="col-6">
                                        <input type="number" class="form-control border-0 bg-light px-4" name="txtminduration" id="mind" onchange="check(mind, maxd)" min="1" max="5" style="height: 55px;" autocomplete="off" required>
                                    </div>
                                    <div class="col-6">
                                        <input type="number" class="form-control border-0 bg-light px-4" name="txtmaxduration" id="maxd" onchange="check(mind, maxd)" min="2" max="10" style="height: 55px;" autocomplete="off" required>
                                    </div>
                                    <script>
                                        var minc = document.getElementById("minc");
                                        var maxc = document.getElementById("maxc");
                                        var mind = document.getElementById("mind");
                                        var maxd = document.getElementById("maxd");

                                        function check(min, max) 
                                        {
                                          if (min.value < max.value) 
                                          {
                                            max.setCustomValidity('');
                                          } 
                                          else 
                                          {
                                            max.setCustomValidity('Max should be less than 10 and larger than Min');
                                          }
                                        }
                                      </script>
                                    <div class="col-6">
                                        <label style="color:black;">&nbsp&nbsp&nbsp Rate (multiplier, ×):</label>
                                    </div>
                                    <div class="col-6">
                                        <label style="color:black;">&nbsp&nbsp&nbsp Commission Rate (%):</label>
                                    </div>
                                    <div class="col-6">
                                        <input type="number" class="form-control border-0 bg-light px-4" name="txtrate" step="0.01" min="0.01" style="height: 55px;" autocomplete="off" required>                                
                                    </div>
                                    <div class="col-6">
                                        <input type="number" class="form-control border-0 bg-light px-4" name="txtcommissionrate" max="100" min="1"  style="height: 55px;" autocomplete="off" required>
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