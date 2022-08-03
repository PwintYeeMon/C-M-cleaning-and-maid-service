<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion sticky-top h-100" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon">
            C&M
        </div>
        <div class="sidebar-brand-text mx-3" style="text-transform: lowercase;">Company</div>
    </a>

    <?php 
    if($_SESSION['StaffRole'] != 'House Cleaner')
    {        
     ?>

    <!-- Staff List -->
    <li class="nav-item sidebar-margin <?php if($page == 'dashboard'){ echo "active"; } ?>">
        <a class="nav-link" href="dashboard.php">
            <i class="fas fa-fw fa-chart-line"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Staff Lists
    </div>

    <!-- Staff List -->
    <li class="nav-item sidebar-margin <?php if($page == 'managestaff'){ echo "active"; } ?>">
        <a class="nav-link" href="managestaff.php">
            <i class="fas fa-fw fa-user-tie"></i>
            <span>Staff</span></a>
    </li>

    <!-- Schedule -->
    <li class="nav-item sidebar-margin <?php if($page == 'managecleanerschedule'){ echo "active"; } ?>">
        <a class="nav-link" href="managecleanerschedule.php">
            <i class="fas fa-fw fa-calendar-alt"></i>
            <span>Cleaner Schedule</span></a>
    </li>

    <!-- Staff Leave -->
    <li class="nav-item sidebar-margin <?php if($page == 'managestaffleave'){ echo "active"; } ?>">
        <a class="nav-link" href="managestaffleave.php">
            <i class="fas fa-fw fa-sign-out-alt"></i>
            <span>Staff Leave</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Customer Lists
    </div>

    <!-- Customer -->
    <li class="nav-item sidebar-margin <?php if($page == 'managecustomer'){ echo "active"; } ?>">
        <a class="nav-link" href="managecustomer.php">
            <i class="fas fa-fw fa-users"></i>
            <span>Customer</span></a>
    </li>

    <!-- Booking -->
    <li class="nav-item sidebar-margin <?php if($page == 'managebooking'){ echo "active"; } ?>">
        <a class="nav-link" href="managebooking.php">
            <i class="far fa-fw fa-calendar-check"></i>
            <span>Booking</span></a>
    </li>

    <?php if ($_SESSION['StaffRole'] == 'Manager'){ ?>

    <!-- Payment -->
    <li class="nav-item sidebar-margin <?php if($page == 'managepayment'){ echo "active"; } ?>">
        <a class="nav-link" href="managepayment.php">
            <i class="fas fa-fw fa-money-check-alt"></i>
            <span>Payment</span></a>
    </li>

    <?php }elseif ($_SESSION['StaffRole'] == 'Administrator'){ ?>

    <!-- Feedback -->
    <li class="nav-item sidebar-margin <?php if($page == 'managefeedback'){ echo "active"; } ?>">
        <a class="nav-link" href="managefeedback.php">
            <i class="fas fa-fw fa-comment-dots"></i>
            <span>Feedback</span></a>
    </li>

    <?php } ?>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Services
    </div>

    <!-- Cleaning Type -->
    <li class="nav-item sidebar-margin <?php if($page == 'managecleaningtype'){ echo "active"; } ?>">
        <a class="nav-link" href="managecleaningtype.php">
            <i class="fas fa-fw fa-hands-wash"></i>
            <span>Cleaning Type</span></a>
    </li>

    <!-- Room Type -->
    <li class="nav-item sidebar-margin <?php if($page == 'manageroomtype'){ echo "active"; } ?>">
        <a class="nav-link" href="manageroomtype.php">
            <i class="fas fa-fw fa-door-closed"></i>
            <span>Room Type</span></a>
    </li>

    <?php 
    }
    else
    {
     ?>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Lists
    </div>
    
    <!-- Cleaner Schedule -->
    <li class="nav-item sidebar-margin <?php if($page == 'cleanerscheduledisplay'){ echo "active"; } ?>">
        <a class="nav-link" href="cleanerscheduledisplay.php">
            <i class="fas fa-fw fa-calendar-alt"></i>
            <span>Schedule</span></a>
    </li>

    <!-- Booking History -->
    <li class="nav-item sidebar-margin <?php if($page == 'bookinghistory'){ echo "active"; } ?>">
        <a class="nav-link" href="bookinghistory.php">
            <i class="fas fa-fw fa-history"></i>
            <span>Booking History</span></a>
    </li>

    <!-- Commission Rate -->
    <li class="nav-item sidebar-margin <?php if($page == 'commissionrate'){ echo "active"; } ?>">
        <a class="nav-link" href="commissionrate.php">
            <i class="fas fa-fw fa-percentage"></i>
            <span>Commission Rate</span></a>
    </li>

    <?php } ?>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

</ul>
<!-- End of Sidebar -->