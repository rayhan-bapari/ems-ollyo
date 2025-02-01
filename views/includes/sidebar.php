<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Dashboard</div>
                <a class="nav-link <?php echo $pageTitle === 'Dashboard' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/dashboard">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    Dashboard
                </a>
                <div class="sb-sidenav-menu-heading">Event</div>
                <a
                    class="nav-link collapsed <?php echo $pageTitle === 'Create Event' || $pageTitle === 'Events' ? 'active' : ''; ?>"
                    href="#"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseLayouts"
                    aria-expanded="false"
                    aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-columns"></i>
                    </div>
                    Events
                    <div class=" sb-sidenav-collapse-arrow">
                        <i class="fas fa-angle-down"></i>
                    </div>
                </a>
                <div
                    class="collapse <?php echo $pageTitle === 'Create Event' || $pageTitle === 'Events' ? 'show' : ''; ?>"
                    id="collapseLayouts"
                    aria-labelledby="headingOne"
                    data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link <?php echo $pageTitle === 'Create Event' ? 'active' : ''; ?>"
                            href="<?php echo BASE_URL; ?>/events/create">Create Event</a>
                        <a class="nav-link <?php echo $pageTitle === 'Events' ? 'active' : ''; ?>"
                            href="<?php echo BASE_URL; ?>/events">Manage Events</a>
                    </nav>
                </div>

                <a class="nav-link <?php echo $pageTitle === 'Event Reports' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/reports">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    Reports
                </a>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <a href="<?php echo BASE_URL; ?>/logout" class="btn btn-danger w-100">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </div>
    </nav>
</div>
