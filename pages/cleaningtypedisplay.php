<?php 
$page = 'cleaningtype';
include('header.php');
include('../../Staff/pages/connect.php');

// Retrieve Cleaning Type
$select = "SELECT * FROM cleaningtype";
$run = mysqli_query($connect, $select);
$runcount = mysqli_num_rows($run);

 ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>C&M | Cleaning Type</title>
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
                <a class="h4 text-primary"> Cleaning Types</a>
            </div>
        </div>
    </div>
    <!-- Hero End -->

    <!-- Pricing Start -->
    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-5">
                    <div class="section-title mb-4">
                        <h5 class="position-relative d-inline-block text-primary text-uppercase">Cleaning Plan</h5>
                        <h1 class="display-5 mb-0">We Offer Fair Prices for House Cleaning</h1>
                    </div>
                    <p class="mb-4">The duration and prices shown here are just estimations. (Only for one bedroom, one bathroom, one living room and a kitchen).</p>
                    <p> <a style="color: red;">Actual price and duration will differ according to your choices.</a></p>
                    <h5 class="text-uppercase text-primary wow fadeInUp" data-wow-delay="0.3s">Want to know the Details of each service? Call Us!</h5>
                    <h1 class="wow fadeInUp" data-wow-delay="0.6s">09 955 132 836</h1>
                </div>
                <div class="col-lg-7">
                    <div class="owl-carousel price-carousel wow zoomIn" data-wow-delay="0.9s">

                        <?php 

                        for ($i=0; $i < $runcount; $i++) 
                        { 
                          $array = mysqli_fetch_array($run);
                          $cleaningtypeid = $array['CleaningTypeID'];
                          $cleaningtype = $array['CleaningType'];
                          $description = $array['Description'];
                          $image = "../assets/".$array['Image'];
                          $cleaners = $array['NoofCleaners_min']."〜".$array['NoofCleaners_max'];
                          $duration = $array['Duration_hr_min']."〜".$array['Duration_hr_max'];
                          $rate = 150*$array['Rate'];
                        
                         ?>
                        
                        <div class="price-item pb-4">
                            <div class="position-relative">
                                <img class="img-fluid rounded" src="<?php echo $image; ?>" alt="Cleaning Type">
                                <div class="d-flex align-items-center justify-content-center bg-light rounded pt-2 px-3 position-absolute top-100 start-50 translate-middle" style="z-index: 2;">
                                    <h2 class="text-primary m-0">$<?php echo $rate; ?></h2>
                                </div>
                            </div>
                            <div class="position-relative text-center bg-light border-bottom border-primary py-5 p-4">
                                <h4><?php echo $cleaningtype; ?></h4>
                                <hr class="text-primary w-50 mx-auto mt-0">
                                <div class="d-flex justify-content-between mb-3"><span><?php echo $description; ?></span><i class="fa fa-check text-primary pt-1"></i></div>
                                <div class="d-flex justify-content-between mb-3"><span><?php echo $cleaners; ?> cleaners</span><i class="fa fa-check text-primary pt-1"></i></div>
                                <div class="d-flex justify-content-between mb-3"><span><?php echo $duration; ?> hours</span><i class="fa fa-check text-primary pt-1"></i></div>
                                <a href="roomtypedisplay.php?cleaningtypeid=<?php echo $cleaningtypeid ?>" class="btn btn-primary py-2 px-4 position-absolute top-100 start-50 translate-middle">Book Now</a>
                            </div>
                        </div>

                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Pricing End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded back-to-top"><i class="bi bi-arrow-up"></i></a>

</body>

</html>

<?php 
include('footer.php');
 ?>