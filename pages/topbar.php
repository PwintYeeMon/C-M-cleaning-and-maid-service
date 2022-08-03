<?php 
 ?>

<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Staff Role -->
    <div class="nav-item">
        <label class="nav-link text-primary">
        <span><?php echo $_SESSION['StaffRole']; ?></span></label>
    </div>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        </li>

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['UserName']; ?></span>
                <img class="img-profile rounded-circle"
                    src="../../User/assets/<?php echo $_SESSION['Profile']; ?>">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" data-toggle="modal" data-target="#staffprofile">
                    <i class="fas fa-address-card fa-sm fa-fw mr-2 text-gray-600"></i>
                    Profile
                </a>
                <a class="dropdown-item" data-toggle="modal" data-target="#staffupdate">
                    <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-600"></i>
                    Update Profile
                </a>
                <a class="dropdown-item" data-toggle="modal" data-target="#changepassword">
                    <i class="fas fa-lock fa-sm fa-fw mr-2 text-gray-600"></i>
                    Change Password
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-600"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>
<!-- End of Topbar -->