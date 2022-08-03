<?php 

if (in_array($today, $workday))
{
  $cleaningdate = date('Y-m-d', strtotime("$today"));
  $select = "SELECT * FROM booking b, bookingdetail bd, customer c, cleaningtype ct WHERE b.BookingID = bd.BookingID AND b.CustomerID = c.CustomerID AND b.CleaningTypeID = ct.CleaningTypeID AND bd.StaffID = '$staffid' AND b.CleaningDate = '$cleaningdate' AND (b.Status = 'Approved' OR b.Status = 'Paid') ORDER BY b.CleaningTime ";
  $run = mysqli_query($connect, $select);
  $runcount = mysqli_num_rows($run);

  if ($runcount == 0)
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
      $bookingid = $array['BookingID'];
      $date = $array['CleaningDate'];      
      $cdate = date('d M Y', strtotime("$date"));
      $cleaningtype = $array['CleaningType'];
      $customername = $array['FirstName'].' '.$array['LastName'];
      $phone = $array['Phone'];
      $address = $array['HouseNumber'].', '.$array['Street'].', '.$array['City'].', '.$array['State'].', '.$array['Postcode'];
      $equipment = $array['Equipment'];
      $duration = $array['TotalDuration_hr'];
      $cstime = strtotime($array['CleaningTime']);
      $cleaningstime = date('g:i A', $cstime);
      $cleaningetime = date('g:i A', strtotime('+'. $duration .' hour', $cstime));

      if ($equipment == 0) 
      {
        $equipment = "Included";
      }
      else
      {
        $equipment = "Not Included";
      }

      //Retrieve Room Type
      $selectrt = "SELECT * FROM roomtype rt, roomtypedetail rd WHERE rt.RoomTypeID = rd.RoomTypeID AND rd.BookingID = '$bookingid'";
      $runrt = mysqli_query($connect, $selectrt);
      $runcountrt = mysqli_num_rows($runrt);

      echo '<div class="col-lg-3 my-2" data-toggle="modal" data-target="#bookingdetail'.$index.'">
              <div class="card text-primary border border-primary">
                <div class="card-text text-center py-2">
                  '.$cleaningstime.' to '.$cleaningetime.'
                </div>
              </div>
            </div>

            <!-- Booking Modal -->
            <div class="modal" id="bookingdetail'.$index.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Booking Detail</h5>
                    <label class="close" data-dismiss="modal" aria-label="Close">
                      <span class="font-weight-bold" aria-hidden="true">&times;</span>
                    </label>
                  </div>
                  <div class="modal-body">
                    <div class="container-fluid">
                      <div class="row">
                        <div class="col-md-6 text-dark"><label>Booking ID:</label></div>
                        <div class="col-md-6 ml-auto">'.$bookingid.'</div>
                        <div class="col-md-6 text-dark"><label>Cleaning Date:</label></div>
                        <div class="col-md-6 ml-auto">'.$cdate.'</div>                        
                        <div class="col-md-6 text-dark"><label>Cleaning Time:</label></div>
                        <div class="col-md-6 ml-auto">'.$cleaningstime.' to '.$cleaningetime.'</div>

                        <div class="col-md-12"><label></label></div>

                        <div class="col-md-6 text-dark"><label>Customer:</label></div>
                        <div class="col-md-6 ml-auto">'.$customername.'</div>
                        <div class="col-md-6 text-dark"><label>Phone:</label></div>
                        <div class="col-md-6 ml-auto">'.$phone.'</div>
                        <div class="col-md-6 text-dark"><label>Address:</label></div>
                        <div class="col-md-6 ml-auto">'.$address.'</div>

                        <div class="col-md-12"><label></label></div>
                        
                        <div class="col-md-6 text-dark"><label>Cleaning Type:</label></div>
                        <div class="col-md-6 ml-auto">'.$cleaningtype.'</div>
                        <div class="col-md-6 text-dark"><label>Equipment:</label></div>
                        <div class="col-md-6 ml-auto">'.$equipment.'</div> 
                        <div class="col-md-6 text-dark">Rooms:</div>';

                        for ($j = 0; $j < $runcountrt; $j++) 
                        { 
                          $arrayrt = mysqli_fetch_array($runrt);
                          $roomname = $arrayrt['RoomName'];
                          $quantity = $arrayrt['Quantity'];

                          echo "<div class='col-md-6 ml-auto'>$quantity $roomname(s)</div>";

                          if ($j != $runcountrt - 1) 
                          {
                              echo "<div class='col-md-6'>&nbsp</div>";
                          }
                        }

                echo '</div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- End Booking Modal -->';

      $index++;
    }
  }
}
else
{
  echo '<div class="col-12 my-2">
            <div class="text-dark text-center py-2">
                <b>Non-Working Day</b>
            </div>
          </div>';
}

?>