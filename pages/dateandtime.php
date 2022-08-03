<?php 
include('header.php');
include('../../Staff/pages/autoid.php');
include('../../Staff/pages/connect.php');

// Check Cleaning Type and Room Type
if(isset($_SESSION['count']))
{
  $runcount = $_SESSION['count'];
  $cleaningtypeid = $_SESSION['cleaningtypeid'];
  $totalprice = $_SESSION['totalprice'];
  $totalduration = $_SESSION['totalduration'];
  $totalcleaners = $_SESSION['totalcleaners'];
  $equipment = $_SESSION['equipment'];

  // Retrieve Cleaning Type
	$select = "SELECT * FROM cleaningtype WHERE CleaningTypeID = '$cleaningtypeid'";
	$run = mysqli_query($connect, $select);
	$array = mysqli_fetch_array($run);
	$cleaningtype = $array['CleaningType'];
  $image = "../assets/".$array['Image'];
}
else
{
	echo "<script>alert('Please choose Cleaning Type and Rooms first.')</script>";
  echo "<script>window.location='cleaningtypedisplay.php'</script>";
}

if (isset($_SESSION['cleaningdate']) && isset($_SESSION['cleaningtime']))
{
	$date = $_SESSION['cleaningdate'];
	$time = $_SESSION['cleaningtime'];
}
else
{
	$date = null;
	$time = null;
}

// Form Submission
if(isset($_POST['btnbook']))
{
	$date = $_SESSION['cleaningdate'] = $_POST['txtdate'];
	$time = $_SESSION['cleaningtime'] = $_POST['txttime'];

	if(isset($_SESSION['CustomerID']))
	{
		$customerid = $_SESSION['CustomerID'];

		// Retrieve Booking and Payment
	  $selectstatus = "SELECT Status FROM booking WHERE CustomerID = '$customerid' ORDER BY BookingID DESC";
	  $runstatus = mysqli_query($connect, $selectstatus);
	  $runcountstatus = mysqli_num_rows($runstatus);
		
		if ($runcountstatus != 0) 
		{
			$arraystatus = mysqli_fetch_array($runstatus);
		  $status = $arraystatus['Status'];

		  // Check Payment of Last Booking
		  if($status == 'Paid' || $status == 'Cancelled' || $status == 'Declined')
		  {
		  	include('bookingquery.php');
		  }
		  else
		  {	
				$_SESSION['booked'] = true;
				echo "<script>alert('Please pay your latest booking first to proceed.')</script>";
			  echo "<script>window.location='bookinghistory.php'</script>";
		  }
		}
		else
		{
			include('bookingquery.php');
		}
		
	}
	else
	{
		$_SESSION['booked'] = true;
		echo "<script>alert('Please Log In to your account first.')</script>";
	  echo "<script>window.location='customerlogin.php'</script>";
	}
}

 ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>C&M | Booking</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">	
</head>

<body>
    <!-- Hero Start -->
    <div class="container-fluid bg-primary py-5 hero-header mb-5">
        <div class="row py-3">
            <div class="col-12 text-center">
                <h1 class="display-3 text-white animated zoomIn">Our Services</h1>
                <a href="index.php" class="h4 text-white">Home </a>
                <a class="h4 text-white"> > </a>
                <a href="cleaningtypedisplay.php" class="h4 text-white">Cleaning Type </a>
                <a class="h4 text-white"> > </a>
                <a href="roomtypedisplay.php" class="h4 text-white"> Room Types</a>
                <a class="h4 text-white"> > </a>
                <a class="h4 text-primary"> Date & Time</a>
            </div>
        </div>
    </div>
    <!-- Hero End -->

    <!-- Page Content -->
		<div class="content">
			<div class="container">
			
				<div class="row">
					<div class="col-12">
					
						<div class="card">
							<div class="card-body">

								<div class="row">
									<div class="col-3">
										<img class="img-fluid rounded" src="<?php echo $image; ?>" alt="Cleaning Type">
									</div>
									<div class="col-5">
										<h4><a href="cleaningtypedisplay.php"><?php echo $cleaningtype; ?></a></h4>
										<div class="rating">
											<i class="fas fa-money-bill-wave text-dark"></i> <span class="d-inline-block average-rating">Price: <b class="text-secondary">$ <?php echo $totalprice; ?></b></span>
										</div>
										<div class="rating">
											<i class="fas fa-clock text-dark"></i> &nbsp<span class="d-inline-block average-rating">Duration: <b><?php echo $totalduration ?> </b>hr</span>
										</div>													
										<div class="rating">
											<i class="fas fa-user-friends text-dark"></i> <span class="d-inline-block average-rating">Cleaner: <b><?php echo $totalcleaners ?></b></span>
										</div>
									</div>
								</div>

							</div>
						</div>

						<form action="dateandtime.php" method="post">
						
						<!-- Schedule Widget -->
						<div class="card booking-schedule schedule-widget">							

							<!-- Schedule Header -->
							<div class="schedule-header bg-info">
								<div class="row">
									<div class="col-md-12">
									
										<!-- Day Slot -->
										<div class="day-slot text-center text-light">
											<span>CHOOSE DATE</span>
										</div>
										<!-- /Day Slot -->
										
									</div>
								</div>
							</div>
							<!-- /Schedule Header -->
						
							<!-- Schedule Header -->
							<div class="schedule-header">
								<div class="row">
									<div class="col-md-12">
									
										<!-- Day Slot -->
										<div class="day-slot">
											<ul>
												<li>
													<span>Sun</span>
												</li>
												<li>
													<span>Mon</span>
												</li>
												<li>
													<span>Tue</span>
												</li>
												<li>
													<span>Wed</span>
												</li>
												<li>
													<span>Thu</span>
												</li>
												<li>
													<span>Fri</span>
												</li>
												<li>
													<span>Sat</span>
												</li>
											</ul>
										</div>
										<!-- /Day Slot -->
										
									</div>
								</div>
							</div>
							<!-- /Schedule Header -->

							<!-- Schedule Content -->
							<div class="schedule-cont">
								<div class="row">
									<div class="col-md-12">
									
										<!-- Date Slot -->
										<div class="time-slot">
											<ul class="clearfix">
												
												
												<?php 

												$i = $count = 0;
												$today = date("D");

												echo '<li>';

												$startdate = strtotime("Sunday");
												$enddate = strtotime("+4 weeks", $startdate);

												while ($startdate < $enddate) 
												{
												  if ($count == 0 && $today != "Sat")
												  {
												  	echo '<input type="radio" class="btn-check">
															<label class="timing btn-primary">
																<span style="display: inline-block; visibility: hidden;">00 Abc</span>
																<span><small style="display: inline-block; visibility: hidden;">0000</small></span>
															</label>';
												  	$count++;

												  	if ($today == 'Sun')
												  	{												  		
													  	$startdate = strtotime("+1 week", $startdate);
												  	}
												  }
												  else
												  {
													  echo '<input type="radio" class="btn-check" name="txtdate" id="optiondate'.$i.'" value="'.date("Y-m-d", $startdate).'" required '.(($date== date('Y-m-d', $startdate))?'checked':"").'>
															<label class="timing btn-primary" for="optiondate'.$i.'">
																<span>'.date("d M", $startdate).' </span> 
																<span><small class="slot-year">'.date("Y", $startdate).'</small></span>
															</label>';
													  $i++;
													  $startdate = strtotime("+1 week", $startdate);
												  }												  	
												  
												}

												echo '</li><li>';

												$startdate = strtotime("Monday");
												$enddate = strtotime("+4 weeks", $startdate);

												while ($startdate < $enddate) 
												{
												  if ($count == 1 && ($today == "Mon" || $today == "Tue" || $today == "Wed" || $today == "Thu" || $today == "Fri"))
												  {
												  	echo '<input type="radio" class="btn-check">
															<label class="timing btn-primary">
																<span style="display: inline-block; visibility: hidden;">00 Abc</span>
																<span><small style="display: inline-block; visibility: hidden;">0000</small></span>
															</label>';
												  	$count++;

												  	if ($today == 'Mon')
												  	{												  		
													  	$startdate = strtotime("+1 week", $startdate);
												  	}
												  }
												  else
												  {
													  echo '<input type="radio" class="btn-check" name="txtdate" id="optiondate'.$i.'" value="'.date("Y-m-d", $startdate).'" '.(($date== date('Y-m-d', $startdate))?'checked':"").'>
															<label class="timing btn-primary" for="optiondate'.$i.'">
																<span>'.date("d M", $startdate).' </span> 
																<span><small class="slot-year">'.date("Y", $startdate).'</small></span>
															</label>';
													  $i++;
													  $startdate = strtotime("+1 week", $startdate);
												  }
												}

												echo '</li><li>';

												$startdate = strtotime("Tuesday");
												$enddate = strtotime("+4 weeks", $startdate);

												while ($startdate < $enddate) 
												{
												  if ($count == 2 && ($today == "Tue" || $today == "Wed" || $today == "Thu" || $today == "Fri"))
												  {
												  	echo '<input type="radio" class="btn-check">
															<label class="timing btn-primary">
																<span style="display: inline-block; visibility: hidden;">00 Abc</span>
																<span><small style="display: inline-block; visibility: hidden;">0000</small></span>
															</label>';
												  	$count++;

												  	if ($today == 'Tue')
												  	{												  		
													  	$startdate = strtotime("+1 week", $startdate);
												  	}
												  }
												  else
												  {
													  echo '<input type="radio" class="btn-check" name="txtdate" id="optiondate'.$i.'" value="'.date("Y-m-d", $startdate).'" '.(($date== date('Y-m-d', $startdate))?'checked':"").'>
															<label class="timing btn-primary" for="optiondate'.$i.'">
																<span>'.date("d M", $startdate).' </span> 
																<span><small class="slot-year">'.date("Y", $startdate).'</small></span>
															</label>';
													  $i++;
													  $startdate = strtotime("+1 week", $startdate);
												  }
												}

												echo '</li><li>'; 

												$startdate = strtotime("Wednesday");
												$enddate = strtotime("+4 weeks", $startdate);

												while ($startdate < $enddate) 
												{
												  if ($count == 3 && ($today == "Wed" || $today == "Thu" || $today == "Fri"))
												  {
												  	echo '<input type="radio" class="btn-check">
															<label class="timing btn-primary">
																<span style="display: inline-block; visibility: hidden;">00 Abc</span>
																<span><small style="display: inline-block; visibility: hidden;">0000</small></span>
															</label>';
												  	$count++;

												  	if ($today == 'Wed')
												  	{												  		
													  	$startdate = strtotime("+1 week", $startdate);
												  	}
												  }
												  else
												  {
													  echo '<input type="radio" class="btn-check" name="txtdate" id="optiondate'.$i.'" value="'.date("Y-m-d", $startdate).'" '.(($date== date('Y-m-d', $startdate))?'checked':"").'>
															<label class="timing btn-primary" for="optiondate'.$i.'">
																<span>'.date("d M", $startdate).' </span> 
																<span><small class="slot-year">'.date("Y", $startdate).'</small></span>
															</label>';
													  $i++;
													  $startdate = strtotime("+1 week", $startdate);
												  }
												}

												echo '</li><li>'; 

												$startdate = strtotime("Thursday");
												$enddate = strtotime("+4 weeks", $startdate);

												while ($startdate < $enddate) 
												{
												  if ($count == 4 && ($today == "Thu" || $today == "Fri"))
												  {
												  	echo '<input type="radio" class="btn-check">
															<label class="timing btn-primary">
																<span style="display: inline-block; visibility: hidden;">00 Abc</span>
																<span><small style="display: inline-block; visibility: hidden;">0000</small></span>
															</label>';
												  	$count++;

												  	if ($today == 'Thu')
												  	{												  		
													  	$startdate = strtotime("+1 week", $startdate);
												  	}
												  }
												  else
												  {
													  echo '<input type="radio" class="btn-check" name="txtdate" id="optiondate'.$i.'" value="'.date("Y-m-d", $startdate).'" '.(($date== date('Y-m-d', $startdate))?'checked':"").'>
															<label class="timing btn-primary" for="optiondate'.$i.'">
																<span>'.date("d M", $startdate).' </span> 
																<span><small class="slot-year">'.date("Y", $startdate).'</small></span>
															</label>';
													  $i++;
													  $startdate = strtotime("+1 week", $startdate);
												  }
												}

												echo '</li><li>'; 

												$startdate = strtotime("Friday");
												$enddate = strtotime("+4 weeks", $startdate);

												while ($startdate < $enddate) 
												{
												  if ($count == 5 && $today == "Fri")
												  {
												  	echo '<input type="radio" class="btn-check">
															<label class="timing btn-primary">
																<span style="display: inline-block; visibility: hidden;">00 Abc</span>
																<span><small style="display: inline-block; visibility: hidden;">0000</small></span>
															</label>';
												  	$count++;

												  	if ($today == 'Fri')
												  	{												  		
													  	$startdate = strtotime("+1 week", $startdate);
												  	}
												  }
												  else
												  {
													  echo '<input type="radio" class="btn-check" name="txtdate" id="optiondate'.$i.'" value="'.date("Y-m-d", $startdate).'" '.(($date== date('Y-m-d', $startdate))?'checked':"").'>
															<label class="timing btn-primary" for="optiondate'.$i.'">
																<span>'.date("d M", $startdate).' </span> 
																<span><small class="slot-year">'.date("Y", $startdate).'</small></span>
															</label>';
													  $i++;
													  $startdate = strtotime("+1 week", $startdate);
												  }
												}

												echo '</li><li>'; 

												$startdate = strtotime("Saturday");
												$enddate = strtotime("+4 weeks", $startdate);
												while ($startdate < $enddate) 
												{
												  echo '<input type="radio" class="btn-check" name="txtdate" id="optiondate'.$i.'" value="'.date("Y-m-d", $startdate).'" '.(($date== date('Y-m-d', $startdate))?'checked':"").'>
														<label class="timing btn-primary" for="optiondate'.$i.'">
															<span>'.date("d M", $startdate).' </span> 
															<span><small class="slot-year">'.date("Y", $startdate).'</small></span>
														</label>';
												  $i++;
											    $startdate = strtotime("+1 week", $startdate);
												}

												echo '</li>';

												 ?>
												
											</ul>
										</div>										
										<!-- /Date Slot -->
										
									</div>
								</div>
							</div>
							<!-- /Schedule Content -->

						</div>

						<!-- Schedule Widget -->
						<div class="card booking-schedule schedule-widget">

							<!-- Schedule Header -->
							<div class="schedule-header bg-info">
								<div class="row">
									<div class="col-md-12">
									
										<!-- Time Slot -->
										<div class="day-slot text-center text-light">
											<span>CHOOSE STARTING TIME</span>
										</div>
										<!-- /Time Slot -->
										
									</div>
								</div>
							</div>
							<!-- /Schedule Header -->
							
							<!-- Schedule Content -->
							<div class="schedule-cont">
								<div class="row">
									<div class="col-md-12">
									
										<!-- Time Slot -->
										<div class="time-slot">
											<ul class="clearfix">

												<?php 

												$j = 0;

												echo '';

												$starttime = strtotime("9:00");
												$endtime = strtotime("16:00");

												while ($starttime < $endtime) 
												{
												  echo '<li>
																  <input type="radio" class="btn-check" name="txttime" id="optiontime'.$j.'" value="'.date("H:i:s", $starttime).'" required  '.(($time== date('H:i:s', $starttime))?'checked':"").'>
																	<label class="timing btn-primary" for="optiontime'.$j.'">
																		<span>'.date("H:i", $starttime).'</span>
																	</label>
																</li>';
												  $j++;
												  
												  $starttime = strtotime("+60 minutes", $starttime);
												}

												echo '';
												 ?>

											</ul>
										</div>
										<!-- /Time Slot -->
										
									</div>
								</div>
							</div>
							<!-- /Schedule Content -->
							
						</div>
						<!-- /Schedule Widget -->
						
						<!-- Submit Section -->
						<div class="submit-section proceed-btn text-right d-flex justify-content-center">
							<button type="submit" class="btn btn-primary py-3" style="width: 20%;" name="btnbook">Book</button>
						</div>
						<!-- /Submit Section -->

						</form>

					</div>
				</div>
			</div>

		</div>		
		<!-- /Page Content -->

    <!-- Back to Top -->
    <a class="btn btn-lg btn-primary btn-lg-square rounded back-to-top"><i class="bi bi-arrow-up"></i></a>

</body>

</html>

<?php 
include('footer.php');
 ?>