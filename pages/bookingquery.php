<?php 

$bookingid = AutoID('booking', 'BookingID', 'B-', 6);
$bookingdate = date("Y-m-d");
$status = 'Processing';
$totalcleaners = (int)$totalcleaners;
$roomcount = 0;
$staffcount = 0;
$starttime = $time;
$endtime = date('H:i:s', strtotime('+'. $totalduration .' hour', strtotime($time)));

// Save booking data
$insert = "INSERT INTO booking(BookingID, CustomerID, CleaningTypeID, CleaningDate, CleaningTime, TotalDuration_hr, TotalPrice, Equipment, Status, BookingDate) VALUES ('$bookingid', '$customerid', '$cleaningtypeid', '$date', '$time', '$totalduration', '$totalprice', '$equipment', '$status', '$bookingdate')";
$query = mysqli_query($connect, $insert);

if ($query)
{
	for ($i = 0; $i < $runcount; $i++) 
  {
    $roomtypeid = $_SESSION['roomtypeid' . $i];
    $quantity = $_SESSION['quantity' . $i];

    if ($quantity != 0)
    {
    	// Save room data
    	$insertroom = "INSERT INTO roomtypedetail(BookingID, RoomTypeID, Quantity) VALUES ('$bookingid', '$roomtypeid', '$quantity')";
	    $queryroom = mysqli_query($connect, $insertroom);
    }
  }

  $day = date('l', strtotime($date));

	// Retrieve Staff and Schedule
	$selectschedule = "SELECT StaffID FROM cleanerschedule cs WHERE NOT EXISTS (SELECT * FROM staffleave sl WHERE cs.StaffID = sl.StaffID AND sl.StartingDate <= '$date' AND sl.EndingDate >= '$date') AND cs.Day = '$day'";
	$runschedule = mysqli_query($connect, $selectschedule);
	$runschedulecount = mysqli_num_rows($runschedule);

	for ($j = 0; $j < $runschedulecount; $j++) 
	{ 
		$array = mysqli_fetch_array($runschedule);
    $staffid = $array['StaffID'];

    // Check reterieved Staff's bookings for that day
    $selectstaff = "SELECT * FROM booking b, bookingdetail bd WHERE b.BookingID = bd.BookingID AND bd.StaffID = '$staffid' AND b.CleaningDate = '$date' AND (b.Status = 'Processing' OR b.Status = 'Approved' OR b.Status = 'Paid')";
    $runstaff = mysqli_query($connect, $selectstaff);
    $runcountstaff = mysqli_num_rows($runstaff);

    if ($runcountstaff != 0)
    {
    	// Check if that staff is available on the booked cleaning time
			for ($k = 0, $countstaff = 0; $k < $runcountstaff; $k++) 
			{ 
				$arraystaff = mysqli_fetch_array($runstaff);
	    	$ctime = $arraystaff['CleaningTime'];
	    	$duration = $arraystaff['TotalDuration_hr'];

				$startingtime = date('H:i:s', strtotime($ctime));
				$endingtime = date('H:i:s', strtotime('+'. $duration .' hour', strtotime($ctime)));

				if (($starttime < $startingtime && $endtime <= $endingtime) || ($starttime >= $endingtime && $endtime > $endingtime))
				{
					$countstaff++;
				}
			}
			if ($countstaff == $runcountstaff)
			{
				$staff[$j] = $staffid;
			}			
    }
  	else
  	{
  		$staff[$j] = $staffid;
  	}		
	}
	
	// check if the number of availble cleaners for chosen date and time is greater than or equal to the required number of cleaners
	if (isset($staff) && count($staff) >= $totalcleaners) 
	{
		// Get the available cleaners in random order
		$keys = array_rand($staff, $totalcleaners);
		for ($k = 0; $k < $totalcleaners; $k++) 
		{ 
			if ($totalcleaners == 1)
			{
				$staffid = $staff[$keys];
			}
			else
			{
				$staffid = $staff[$keys[$k]];
			}

			$insertschedule = "INSERT INTO bookingdetail(BookingID, StaffID) VALUES ('$bookingid', '$staffid')";
			$queryschedule = mysqli_query($connect, $insertschedule);

			if ($queryschedule) 
			{
				$staffcount++;
			}
		}	

		if ($totalcleaners == $staffcount)
		{
			unset($_SESSION['equipment']);
			unset($_SESSION['cleaningtypeid']);
			unset($_SESSION['totalprice']);
			unset($_SESSION['totalcleaners']);
			unset($_SESSION['count']);

			for ($l = 0; $l < $runcount; $l++) 
		  {
		    unset($_SESSION['roomtypeid' . $i]);
		    unset($_SESSION['quantity' . $i]);
		  }
			echo "<script>window.location='booking.php'</script>";
		}  
	}
	else
	{ 
		// Delete saved booking data as there are not enough cleaners for the chosen date and time
		$deleteb = "DELETE FROM booking WHERE BookingID = '$bookingid'";
		$runb = mysqli_query($connect, $deleteb);

		$deleter = "DELETE FROM roomtypedetail WHERE BookingID = '$bookingid'";
		$runr = mysqli_query($connect, $deleter);

		unset($_SESSION['cleaningdate']);
		unset($_SESSION['cleaningtime']);

		echo "<script>alert('Booking full. Please choose another Time or Date.')</script>";
		echo "<script>window.location='dateandtime.php'</script>";
	}
}
else
{
  mysqli_error($connect);
}

 ?>