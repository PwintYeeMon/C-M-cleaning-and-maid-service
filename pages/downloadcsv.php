<?php 
include('connect.php');

if (isset($_POST['btndownload']))
{
	header('Content-Type: text/csv; charset=utf-8');  
    header('Content-Disposition: attachment; filename=bookinghistory.csv');     
	$output = fopen("php://output", "w");  
	fputcsv($output, array('ID', 'Customer', 'Cleaning Type', 'Cleaning Date', 'Cleaning Time', 'Duration', 'Cleaners', 'Rooms', 'Price ($)', 'Equipment', 'BookingDate'));
	$selectcsv = "SELECT *, COUNT(bd.BookingID) AS Cleaners, SUM(rtd.Quantity) AS Rooms FROM booking b, customer c, cleaningtype ct, bookingdetail bd, roomtypedetail rtd WHERE b.CustomerID = c.CustomerID AND b.CleaningTypeID = ct.CleaningTypeID AND b.BookingID = bd.BookingID AND b.BookingID = rtd.BookingID AND b.Status = 'Paid' GROUP BY b.BookingID";
	$runcsv = mysqli_query($connect, $selectcsv);
	$countcsv = mysqli_num_rows($runcsv);

	for ($i = 0; $i < $countcsv; $i++) 
	{ 
		$arraycsv = mysqli_fetch_array($runcsv);
		$bookingid = $arraycsv['BookingID'];
		$customer = $arraycsv['FirstName'].' '.$arraycsv['LastName'];
		$cleaningtype = $arraycsv['CleaningType'];
		$cleaningdate = $arraycsv['CleaningDate'];
		$cleaningtime = $arraycsv['CleaningTime'];
		$duration = $arraycsv['TotalDuration_hr'];
		$cleaners = $arraycsv['Cleaners'];
		$rooms = $arraycsv['Rooms'];
		$price = $arraycsv['TotalPrice'];
		$equipment = $arraycsv['Equipment'];
		$bookingdate = $arraycsv['BookingDate'];

		fputcsv($output, array( $bookingid, $customer, $cleaningtype, $cleaningdate, $cleaningtime, $duration, $cleaners, $rooms, $price, $equipment, $bookingdate));
	}

	fclose($output);
}

 ?>