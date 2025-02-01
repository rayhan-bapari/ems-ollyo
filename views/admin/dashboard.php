<?php
$pageTitle = "Dashboard";
require_once ROOT_PATH . '/views/includes/header.php';
require_once ROOT_PATH . '/includes/event.php';
require_once ROOT_PATH . '/includes/attendee.php';
require_once ROOT_PATH . '/config/database.php';

if (!$auth->isLoggedIn()) {
    redirect('/login');
}

$database = new Database();
$db = $database->getConnection();
$event = new Event($db);
$attendee = new Attendee($db);

// upcoming event count
$upcomingEventCount = $event->getUpcomingEventCount();

// total event count
$totalEventCount = $event->getTotalEventCount();

// total attendee count
$totalAttendeeCount = $attendee->getTotalAttendees();
?>

<?php require_once ROOT_PATH . '/views/includes/navbar.php'; ?>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php require_once ROOT_PATH . '/views/includes/sidebar.php'; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <div class="row mt-4">
                    <div class="col-xl-4 col-md-6">
                        <div class="card bg-primary text-white mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Upcoming Events</h5>
                                <p class="card-text">Total: <?php echo $upcomingEventCount; ?></p>
                                <a class="btn btn-light text-primary" href="<?php echo BASE_URL; ?>/events">View All</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card bg-warning text-white mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Total Events</h5>
                                <p class="card-text">Total: <?php echo $totalEventCount; ?></p>
                                <a class="btn btn-light text-warning" href="<?php echo BASE_URL; ?>/events">View All</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card bg-success text-white mb-4">
                            <div class="card-body">
                                <h5 class="card-title">Total Attendees</h5>
                                <p class="card-text">Total: <?php echo $totalAttendeeCount; ?></p>
                                <a class="btn btn-light text-success" href="<?php echo BASE_URL; ?>/reports">View All</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php require_once ROOT_PATH . '/views/includes/dashboard_footer.php'; ?>
    </div>
</div>
<?php require_once ROOT_PATH . '/views/includes/footer.php'; ?>