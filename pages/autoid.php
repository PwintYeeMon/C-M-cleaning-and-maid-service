<?php
function AutoID($tablename, $columnname, $prefix, $noofleadingzeros)
{ 
	include('connect.php');

	$newID = "";
	$value = 1;
	
	// Retrieve ID
	$select = "SELECT " . $columnname . " FROM " . $tablename . " ORDER BY " . $columnname . " DESC";	
	$run = mysqli_query($connect, $select);
	$runcount = mysqli_num_rows($run);

	$array = mysqli_fetch_array($run);		
	
	if ($runcount < 1)
	{		
		return $prefix . "000001";
	}
	else
	{
		$oldID = $array[$columnname];	// Reading Last ID
		$oldID = str_replace($prefix,"",$oldID);	// Removing "Prefix"
		$value = (int)$oldID;	// Convert to Integer
		$value++;	// Increment		
		$newID = $prefix . NumberFormatter($value, $noofleadingzeros);			
		return $newID;		
	}
}

function NumberFormatter($number,$n) 
{	
	return str_pad((int) $number,$n,"0",STR_PAD_LEFT);
}
?>
