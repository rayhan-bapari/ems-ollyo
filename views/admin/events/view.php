<?php
$pageTitle = "Event Details";
require_once ROOT_PATH . '/views/includes/header.php';
require_once ROOT_PATH . '/includes/auth.php';
require_once ROOT_PATH . '/includes/event.php';
require_once ROOT_PATH . '/config/database.php';

if (!$auth->isLoggedIn()) {
    redirect('/login');
}

$event_id = $_GET['id'] ?? null;

$database = new Database();
$db = $database->getConnection();

if ($event_id) {
    $event = new Event($db);
    $eventDetails = $event->getById($event_id);

    if ($eventDetails) {
    } else {
        echo "Event not found.";
    }
} else {
    echo "No event ID provided.";
}

$user = new Auth($db);
$userDetails = $user->getUserById($eventDetails['user_id']);
?>

<?php require_once ROOT_PATH . '/views/includes/navbar.php'; ?>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php require_once ROOT_PATH . '/views/includes/sidebar.php'; ?>
    </div>
    <div id="layoutSidenav_content" class="bg-light">
        <main>
            <div class="container-fluid p-4">
                <div class="row">
                    <div class="card text-center px-0">
                        <div class="card-header">
                            Event Details
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php echo $eventDetails['title']; ?>
                            </h5>
                            <p class="card-text">
                                <?php echo $eventDetails['description']; ?>
                            </p>
                            <p class="card-text">
                                Date: <?php echo date('F j, Y', strtotime($eventDetails['date'])); ?>
                            </p>
                            <p class="card-text">
                                Location: <?php echo $eventDetails['location']; ?>
                            </p>
                            <p class="card-text">
                                Max Capacity: <?php echo $eventDetails['max_capacity']; ?>
                            </p>

                            <a href="<?php echo BASE_URL; ?>/events/edit?id=<?php echo $eventDetails['id']; ?>" class="btn btn-primary">Edit Event</a>
                        </div>
                        <div class="card-footer text-body-secondary d-flex justify-content-between">
                            <span>
                                Created by: <?php echo $userDetails['username']; ?>
                            </span>
                            <span>
                                Created at: <?php echo date('F j, Y H:i A', strtotime($eventDetails['created_at'])); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php require_once ROOT_PATH . '/views/includes/dashboard_footer.php'; ?>
    </div>
</div>
<?php require_once ROOT_PATH . '/views/includes/footer.php'; ?>
