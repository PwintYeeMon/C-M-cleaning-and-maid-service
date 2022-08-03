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
  echo "<script>window.alert('Only Manager and Administrator can view Room Type List.')</script>";
  echo "<script>window.location='stafflogin.php'</script>";
}

// Retrieve Room Type
$select = "SELECT * FROM roomtype";
$run = mysqli_query($connect, $select);
$runcount = mysqli_num_rows($run);

// Entry Form Submission 
if(isset($_POST['btnenter']))
{
  $roomtypeid = AutoID('roomtype', 'RoomTypeID', 'RT-', 6);
  $roomname = $_POST['txtroomname'];

  $image = $_FILES['txtimage']['name'];
  $file = "../../User/assets/Img/".$image;
  $copied = copy($_FILES['txtimage']['tmp_name'],$file);
  $filename = "Img/".$image;

  $duration = $_POST['txtduration'];
  $price = $_POST['txtprice'];

  // Insert Room Type 
  $insert = "INSERT INTO roomtype(RoomTypeID, RoomName, Image, Duration_hr, Price) VALUES ('$roomtypeid', '$roomname', '$filename', '$duration', '$price')";
  $query = mysqli_query($connect, $insert);

  if ($query)
  {
    echo "<script>alert('Room Type Entered')</script>";
    echo "<script>window.location='manageroomtype.php'</script>";
  }
  else
  {
    mysqli_error($connect);
  }
}

// Update Form Submission 
if(isset($_POST['btnupdate']))
{
  $roomtypeid = $_POST['txtroomtypeid'];
  $roomname = $_POST['txtroomname'];
  $image = $_FILES['txtimage']['name'];
  $duration = $_POST['txtduration'];
  $price = $_POST['txtprice'];

  if($image == null)
  {
    // Update Room Type
  $update = "UPDATE roomtype
            SET RoomName = '$roomname',
            Duration_hr = '$duration',
            Price = '$price'
            WHERE RoomTypeID = '$roomtypeid'";
    $query = mysqli_query($connect, $update);
  }
  else
  {
    // Retrieve Room to Delete Image
    $select = "SELECT Image FROM roomtype WHERE RoomTypeID = '$roomtypeid'";
    $run = mysqli_query($connect, $select);
    $array = mysqli_fetch_array($run);
    $oldimage = "../assets/".$array['Image'];

    unlink("$oldimage");
    $file = "../../User/assets/Img/".$image;
    $copied = copy($_FILES['txtimage']['tmp_name'],$file);
    $filename = "Img/".$image;

    // Update Room Type
    $update = "UPDATE roomtype
            SET RoomName = '$roomname',
            Image = '$filename'
            Duration_hr = '$duration',
            Price = '$price'
            WHERE RoomTypeID = '$roomtypeid'";
    $query = mysqli_query($connect, $update);
  }

  if ($query)
  {
    echo "<script>alert('Room Type Information Update Successful')</script>";
    echo "<script>window.location='manageroomtype.php'</script>";
  }
  else
  {
    mysqli_error($connect);
  }
}

// Delete 
if(isset($_POST['btndelete']))
{
  $roomtypeid = $_POST['txtroomtypeid'];

  // Retrieve Room to Delete Image
  $select = "SELECT Image FROM roomtype WHERE RoomTypeID = '$roomtypeid'";
  $run = mysqli_query($connect, $select);
  $array = mysqli_fetch_array($run);
  $oldimage = "../../User/assets/".$array['Image'];

  unlink("$oldimage");

  // Delete Room Type
  $delete = "DELETE FROM RoomType
            WHERE RoomTypeID = '$roomtypeid'";
  $query = mysqli_query($connect, $delete);

  if ($query)
  {
    echo "<script>alert('Room Type Information Delete Successful')</script>";
    echo "<script>window.location='manageroomtype.php'</script>";
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

    <title>C&M | Room Type List</title>

    <!-- Favicon -->
    <link href="../../User/assets/Img/logo.ico" rel="icon">

    <?php include('header.php') ?>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php  $page = 'manageroomtype'; include('sidebar.php'); ?>

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
                                <h6 class="m-0 font-weight-bold text-primary py-2">Room Type List</h6>
                            </div>
                            <div class="p-2">
                            <?php 

                            if($_SESSION['StaffRole'] == 'Manager')
                            {
                            ?>

                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#roomtypeentry">
                                  Add New Room Type
                                </button>

                            <?php } ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Duration (hr)</th>
                                            <th>Price ($)</th>
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
                                      $roomtypeid = $array['RoomTypeID'];
                                      $roomname = $array['RoomName'];
                                      $image = "../../User/assets/".$array['Image'];
                                      $duration = $array['Duration_hr'];
                                      $price = $array['Price'];
                                    
                                     ?>

                                        <tr>
                                            <td><img src="<?php echo $image; ?>"class="mx-auto rounded d-block" width="70" height="70" alt="Room Type"></td>
                                            <td><?php echo $roomname; ?></td>
                                            <td><?php echo $duration; ?></td>
                                            <td><?php echo $price; ?></td>
                                            <?php 
                                            if($_SESSION['StaffRole'] == 'Manager')
                                            {
                                                echo '<td style="color: #4e73df;"><i class="fas fa-pencil-alt fa-sm" data-toggle="modal" data-target="#roomtypeupdate'.$i.'"></i>&nbsp&nbsp&nbsp | &nbsp&nbsp&nbsp<i class="fas fa-trash-alt fa-m" data-toggle="modal" data-target="#roomtypedelete'.$i.'"></i></td>';
                                            }
                                            ?>   
                                        </tr>

                                        <!-- Room Type Update Modal -->
                                        <?php echo '<div class="modal" id="roomtypeupdate'.$i.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">'; ?>
                                          <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                              <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalCenterTitle">Room Type Update</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                  <span aria-hidden="true">&times;</span>
                                                </button>
                                              </div>
                                              <div class="modal-body">
                                                <form action="manageroomtype.php" method="POST" enctype="multipart/form-data">
                                                    <div class="row g-3">
                                                        <div class="col-12">
                                                            <input type="text" class="form-control-plaintext border-0 bg-light px-4" name="txtroomtypeid" style="height: 55px;" value="<?php echo $roomtypeid; ?>" hidden>
                                                        </div>
                                                        <div class="col-12">
                                                            <label style="color:black;">&nbsp&nbsp&nbsp Room Name:</label>
                                                        </div>
                                                        <div class="col-12">
                                                            <input type="text" class="form-control border-0 bg-light px-4" name="txtroomname" style="height: 55px;" autocomplete="off" value="<?php echo $roomname; ?>" required>
                                                        </div>
                                                        <div class="col-6">
                                                            <label style="color:black;">&nbsp&nbsp&nbsp Current Photo:</label>
                                                        </div>
                                                        <div class="col-6">
                                                            <label style="color:black;">&nbsp&nbsp&nbsp New Photo:</label>
                                                        </div>
                                                        <div class="col-6">
                                                            <img class="img-fluid" src="<?php echo $image; ?>" alt="Room Type">
                                                        </div>
                                                        <div class="col-6">
                                                            <input  type="file" accept="image/*" class="form-control-file" name="txtimage">
                                                        </div>
                                                        <div class="col-12">
                                                            <label style="color:black;">&nbsp&nbsp&nbsp Duration (hr):</label>
                                                        </div>
                                                        <div class="col-12">
                                                            <input type="number" class="form-control border-0 bg-light px-4" name="txtduration" step="0.01" min="0.01" max="5" style="height: 55px;" autocomplete="off" value="<?php echo $duration; ?>" required>
                                                        </div>
                                                        <div class="col-12">
                                                            <label style="color:black;">&nbsp&nbsp&nbsp Price ($):</label>
                                                        </div>
                                                        <div class="col-12">
                                                            <input type="number" class="form-control border-0 bg-light px-4" name="txtprice" step="0.01" min="1" style="height: 55px;" autocomplete="off" value="<?php echo $price; ?>" required>
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

                                        <!-- Room Type Delete Modal-->
                                        <?php echo '<div class="modal" id="roomtypedelete'.$i.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">'; ?>
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Are you sure you want to Delete?</h5>
                                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Select "Delete" below if you want to delete this room type.
                                                        <form action="manageroomtype.php" method="POST">
                                                            <div class="row g-3">
                                                                <div class="col-12">
                                                                    <input type="text" class="form-control-plaintext border-0 bg-light px-4" name="txtroomtypeid" style="height: 55px;" value="<?php echo $roomtypeid; ?>" hidden>
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

                    <!-- Room Type Entry Modal -->
                    <div class="modal" id="roomtypeentry" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Room Type Entry</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <form action="manageroomtype.php" method="POST" enctype="multipart/form-data">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label style="color:black;">&nbsp&nbsp&nbsp Room Name:</label>
                                    </div>
                                    <div class="col-12">
                                        <input type="text" class="form-control border-0 bg-light px-4" name="txtroomname" style="height: 55px;" autocomplete="off" required>
                                    </div>
                                    <div class="col-12">
                                        <label style="color:black;">&nbsp&nbsp&nbsp Image:</label>
                                    </div>
                                    <div class="col-12">
                                        <input  type="file" accept="image/*" class="form-control-file" name="txtimage" required>
                                    </div>
                                    <div class="col-12">
                                        <label style="color:black;">&nbsp&nbsp&nbsp Duration (hr):</label>
                                    </div>
                                    <div class="col-12">
                                        <input type="number" class="form-control border-0 bg-light px-4" name="txtduration" step="0.01" min="0.01" max="5" style="height: 55px;" autocomplete="off" required>
                                    </div>
                                    <div class="col-12">
                                        <label style="color:black;">&nbsp&nbsp&nbsp Price ($):</label>
                                    </div>
                                    <div class="col-12">
                                        <input type="number" class="form-control border-0 bg-light px-4" name="txtprice" step="0.01" min="1" style="height: 55px;" autocomplete="off" required>
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