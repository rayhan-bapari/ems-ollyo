<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="<?php echo BASE_URL; ?>/dashboard">
        Event Management
    </a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar-->
    <ul class="navbar-nav d-md-block ms-auto">
        <li class="nav-item">
            <a
                class="nav-link d-flex align-items-center"
                href="#">
                <img
                    class="me-2"
                    src="https://ui-avatars.com/api/?name=<?php echo $_SESSION['username']; ?>&color=7F9CF5&background=EBF4FF"
                    alt="<?php echo $_SESSION['username']; ?>"
                    width="30"
                    height="30"
                    style="border-radius: 50%;">
                <span></span><?php echo $_SESSION['username']; ?></span>
            </a>
        </li>
    </ul>
</nav>
