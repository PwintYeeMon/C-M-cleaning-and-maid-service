<?php 
include('header.php');
include('../../Staff/pages/connect.php');

// Change booking status to cancelled
if(isset($_REQUEST['BookingID']))
{
	$bookingid = $_REQUEST['BookingID'];
	$update = "UPDATE booking SET Status = 'Cancelled' WHERE BookingID = '$bookingid'";
	$run = mysqli_query($connect, $update);

	if($run)
	{		
      echo "<script>alert('Booking Cancelled')</script>";
      echo "<script>window.location='bookinghistory.php'</script>";
	}
}

 ?>