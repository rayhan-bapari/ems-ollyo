<?php
$pageTitle = "Event Reports";
require_once ROOT_PATH . '/views/includes/header.php';
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/includes/auth.php';
require_once ROOT_PATH . '/includes/event.php';
require_once ROOT_PATH . '/includes/attendee.php';

if (!$auth->isLoggedIn()) {
    redirect('/login');
}

$database = new Database();
$db = $database->getConnection();
$event = new Event($db);
$attendee = new Attendee($db);

$startDate = $_GET['start_date'] ?? '';
$endDate = $_GET['end_date'] ?? '';
$searchTerm = $_GET['search'] ?? '';

$filters = [
    'start_date' => $startDate,
    'end_date' => $endDate,
    'search' => $searchTerm
];

$events = $event->getAllWithAttendeeCount($filters);
?>

<?php require_once ROOT_PATH . '/views/includes/navbar.php'; ?>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php require_once ROOT_PATH . '/views/includes/sidebar.php'; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Event Reports</h2>
                </div>

                <form class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date"
                            value="<?php echo htmlspecialchars($startDate); ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date"
                            value="<?php echo htmlspecialchars($endDate); ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="search" class="form-label">Search Events</label>
                        <input type="text" class="form-control" id="search" name="search"
                            placeholder="Event name..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-secondary w-100">Apply Filters</button>
                    </div>
                </form>

                <!-- Events Table -->
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-table me-1"></i>
                        Event Attendance Reports
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Event Name</th>
                                        <th>Date</th>
                                        <th>Venue</th>
                                        <th>Capacity</th>
                                        <th>Current Attendees</th>
                                        <th>Registration Rate</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($events)): ?>
                                        <?php foreach ($events as $evt): ?>
                                            <?php
                                            $registrationRate = ($evt['max_capacity'] > 0)
                                                ? round(($evt['attendee_count'] / $evt['max_capacity']) * 100, 1)
                                                : 0;
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($evt['title']); ?></td>
                                                <td><?php echo date('M d, Y H:i A', strtotime($evt['date'])); ?></td>
                                                <td><?php echo htmlspecialchars($evt['location']); ?></td>
                                                <td><?php echo htmlspecialchars($evt['max_capacity']); ?></td>
                                                <td><?php echo $evt['attendee_count']; ?></td>
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar" role="progressbar"
                                                            style="width: <?php echo $registrationRate; ?>%"
                                                            aria-valuenow="<?php echo $registrationRate; ?>"
                                                            aria-valuemin="0" aria-valuemax="100">
                                                            <?php echo $registrationRate; ?>%
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="<?php echo BASE_URL; ?>/reports/download?event_id=<?php echo $evt['id']; ?>"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="fas fa-download me-1"></i> Download CSV
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No events found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <?php require_once ROOT_PATH . '/views/includes/dashboard_footer.php'; ?>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (!document.getElementById('start_date').value) {
            const lastMonth = new Date();
            lastMonth.setMonth(lastMonth.getMonth() - 1);
            document.getElementById('start_date').value = lastMonth.toISOString().split('T')[0];
        }

        if (!document.getElementById('end_date').value) {
            const today = new Date();
            document.getElementById('end_date').value = today.toISOString().split('T')[0];
        }
    });
</script>
<?php require_once ROOT_PATH . '/views/includes/footer.php'; ?>
