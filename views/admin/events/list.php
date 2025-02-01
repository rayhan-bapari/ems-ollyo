<?php
$pageTitle = "Events";
require_once ROOT_PATH . '/views/includes/header.php';
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/includes/auth.php';
require_once ROOT_PATH . '/includes/event.php';

if (!$auth->isLoggedIn()) {
    redirect('/login');
}

$successMessage = isset($_SESSION['success']) ? $_SESSION['success'] : false;
unset($_SESSION['success']);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$filters = [
    'search' => $_GET['search'] ?? '',
    'date' => $_GET['date'] ?? ''
];

$database = new Database();
$db = $database->getConnection();
$event = new Event($db);
$events = $event->getAllPaginated($page, 10, $filters);
$totalEvents = $event->getTotalCount($filters);
$totalPages = ceil($totalEvents / 10);
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
                    <h2>Events Dashboard</h2>
                    <a href="<?php echo BASE_URL; ?>/events/create" class="btn btn-sm btn-primary">Create Event</a>
                </div>

                <form class="row g-3 mb-4">
                    <div class="col-auto">
                        <input type="text" class="form-control" name="search" placeholder="Search events..."
                            value="<?php echo htmlspecialchars($filters['search']); ?>">
                    </div>
                    <div class="col-auto">
                        <input type="date" class="form-control" name="date"
                            value="<?php echo htmlspecialchars($filters['date']); ?>">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-secondary">Filter</button>
                    </div>
                </form>

                <?php if ($successMessage): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $successMessage; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Venue</th>
                                <th>Capacity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($events)): ?>
                                <?php foreach ($events as $event): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($event['title']); ?></td>
                                        <td><?php echo date('M d, Y H:i A', strtotime($event['date'])); ?></td>
                                        <td><?php echo htmlspecialchars($event['location']); ?></td>
                                        <td><?php echo htmlspecialchars($event['max_capacity']); ?></td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>/events/view?id=<?php echo $event['id']; ?>"
                                                class="btn btn-sm btn-info">View</a>
                                            <a href="<?php echo BASE_URL; ?>/events/edit?id=<?php echo $event['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                            <form action="<?php echo BASE_URL; ?>/events/delete" method="POST" class="d-inline">
                                                <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No events found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($totalPages > 1): ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo $page === $i ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($filters['search']); ?>&date=<?php echo urlencode($filters['date']); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </main>
        <?php require_once ROOT_PATH . '/views/includes/dashboard_footer.php'; ?>
    </div>
</div>
<?php require_once ROOT_PATH . '/views/includes/footer.php'; ?>
