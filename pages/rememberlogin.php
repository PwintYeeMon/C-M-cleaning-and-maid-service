<?php 
session_start();    

if(isset($_SESSION["susername"])) 
{
    setcookie("susername", $_SESSION["susername"], time() + 60*60*24*30);
    setcookie("spassword", $_SESSION["spassword"], time() + 60*60*24*30);

    unset($_SESSION['susername']);
    unset($_SESSION['spassword']);
} 
else 
{
    setcookie("susername","");
    setcookie("spassword","");
}

if ($_SESSION['StaffRole'] == 'Manager')
{        
    echo "<script>window.location='dashboard.php'</script>";
}
elseif ($_SESSION['StaffRole'] == 'Administrator')
{
    echo "<script>window.location='dashboard.php'</script>";
}    
else
{
    echo "<script>window.location='cleanerscheduledisplay.php'</script>";
}

 ?>