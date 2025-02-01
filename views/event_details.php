<?php
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/includes/event.php';
require_once ROOT_PATH . '/includes/attendee.php';

$event_id = $_GET['id'] ?? null;
$database = new Database();
$db = $database->getConnection();

if ($event_id) {
    $event = new Event($db);
    $eventDetails = $event->getById($event_id);

    if (!$eventDetails) {
        echo "Event not found.";
        exit;
    }
} else {
    echo "No event ID provided.";
    exit;
}

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
) {

    $response = ['success' => false, 'message' => ''];
    $errors = [];

    if (empty($_POST['name'])) {
        $errors[] = "Name is required";
    }
    if (empty($_POST['email'])) {
        $errors[] = "Email is required";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    if (empty($_POST['phone'])) {
        $errors[] = "Phone number is required";
    }
    if (empty($_POST['event_id'])) {
        $errors[] = "Event ID is required";
    }

    if (empty($errors)) {
        $attendee = new Attendee($db);
        $result = $attendee->register([
            'event_id' => $_POST['event_id'],
            'name' => $_POST['name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone']
        ]);

        $response = $result;
    } else {
        $response['message'] = implode(', ', $errors);
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>

<?php require_once ROOT_PATH . '/views/includes/frontend/header.php'; ?>

<div class="container-fluid p-4 mt-5">
    <h2>Event Registration</h2>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Event Details
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($eventDetails['title']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($eventDetails['description']); ?></p>
                    <p class="card-text">
                        Date: <?php echo date('F j, Y', strtotime($eventDetails['date'])); ?>
                    </p>
                    <p class="card-text">
                        Location: <?php echo htmlspecialchars($eventDetails['location']); ?>
                    </p>
                    <p class="card-text">
                        Max Capacity: <?php echo htmlspecialchars($eventDetails['max_capacity']); ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div id="alertContainer"></div>

            <form id="registrationForm" method="POST">
                <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event_id); ?>">

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    <div class="invalid-feedback">Please enter your name</div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <div class="invalid-feedback">Please enter a valid email</div>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" required>
                    <div class="invalid-feedback">Please enter your phone number</div>
                </div>

                <button type="submit" class="btn btn-primary">Register</button>
            </form>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/views/includes/frontend/footer.php'; ?>

<script>
    document.getElementById('registrationForm').addEventListener('submit', function(event) {
        event.preventDefault();

        document.getElementById('alertContainer').innerHTML = '';

        this.classList.remove('was-validated');

        if (!this.checkValidity()) {
            this.classList.add('was-validated');
            return;
        }

        const formData = new FormData(this);

        fetch(window.location.href, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${data.success ? 'success' : 'danger'} alert-dismissible fade show`;
                alertDiv.role = 'alert';
                alertDiv.innerHTML = `
            ${data.message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

                document.getElementById('alertContainer').appendChild(alertDiv);

                if (data.success) {
                    this.reset();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                alertDiv.role = 'alert';
                alertDiv.innerHTML = `
            An error occurred. Please try again.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
                document.getElementById('alertContainer').appendChild(alertDiv);
            });
    });
</script>
