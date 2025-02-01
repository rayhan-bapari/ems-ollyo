<?php
require_once ROOT_PATH . '/config/database.php';

$database = new Database();
$db = $database->getConnection();

$sql = "SELECT * FROM events";
$stmt = $db->prepare($sql);
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php require_once ROOT_PATH . '/views/includes/frontend/header.php'; ?>

<section class="hero" id="home">
    <div>
        <h1>Make Your Events Memorable</h1>
        <p>We organize amazing events tailored to your needs.</p>
        <a href="#events" class="btn btn-primary">View Events</a>
    </div>
</section>

<section class="container py-5" id="events">
    <h2 class="text-center mb-4">Upcoming Events</h2>
    <div class="container">
        <div class="row">
            <?php foreach ($events as $event): ?>
                <div class="col-lg-4">
                    <div class="card card-margin">
                        <div class="card-body">
                            <div class="widget-49">
                                <div class="widget-49-title-wrapper">
                                    <div class="widget-49-date-primary">
                                        <?php $date = new DateTime($event['date']); ?>
                                        <span class="widget-49-date-day"><?php echo $date->format('d'); ?></span>
                                        <span class="widget-49-date-month"><?php echo $date->format('M'); ?></span>
                                    </div>
                                    <div class="widget-49-meeting-info">
                                        <span class="widget-49-pro-title"><?php echo $event['title']; ?></span>
                                        <span class="widget-49-meeting-time"><?php echo $date->format('H:i A'); ?></span>
                                    </div>
                                </div>
                                <ol class="widget-49-meeting-points">
                                    <li class="widget-49-meeting-item"><span><?php echo $event['location']; ?></span></li>
                                    <li class="widget-49-meeting-item"><span>Max Capacity: <?php echo $event['max_capacity']; ?></span></li>
                                </ol>
                                <div class="widget-49-meeting-action">
                                    <a href="<?php echo BASE_URL; ?>/event-details?id=<?php echo $event['id']; ?>" class="btn btn-sm btn-flash-border-primary">View Event</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="bg-light py-5" id="contact">
    <div class="container text-center">
        <h2>Contact Us</h2>
        <p>Have an event in mind? Let's make it happen.</p>
        <a href="#" class="btn btn-dark">Get in Touch</a>
    </div>
</section>

<?php require_once ROOT_PATH . '/views/includes/frontend/footer.php'; ?>
