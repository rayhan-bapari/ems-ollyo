<?php
$pageTitle = "Edit Event";
require_once ROOT_PATH . '/views/includes/header.php';
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/includes/event.php';

if (!$auth->isLoggedIn()) {
    redirect('/login');
}

$db = new Database();
$conn = $db->getConnection();
$event = new Event($conn);

$event_id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$event_id) {
    $_SESSION['error'] = "Event ID is required";
    redirect('/events');
}

$eventData = $event->getById($event_id);
if (!$eventData) {
    $_SESSION['error'] = "Event not found";
    redirect('/events');
}

if ($eventData['user_id'] != $_SESSION['user_id']) {
    $_SESSION['error'] = "You don't have permission to edit this event";
    redirect('/events');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    if (empty($_POST['title'])) {
        $errors[] = "Event title is required";
    }
    if (empty($_POST['description'])) {
        $errors[] = "Description is required";
    }
    if (empty($_POST['date'])) {
        $errors[] = "Event date is required";
    }
    if (empty($_POST['location'])) {
        $errors[] = "Location is required";
    }
    if (empty($_POST['max_capacity']) || !is_numeric($_POST['max_capacity']) || $_POST['max_capacity'] < 1) {
        $errors[] = "Valid maximum capacity is required";
    }

    if (empty($errors)) {
        $event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $result = $event->update($event_id, [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'date' => $_POST['date'],
            'location' => $_POST['location'],
            'max_capacity' => $_POST['max_capacity'],
            'status' => $_POST['status']
        ]);

        if ($result) {
            $_SESSION['success'] = "Event updated successfully";
            redirect('/events');
            exit;
        } else {
            $_SESSION['error'] = "Error updating event";
            redirect("/events/edit?id=$event_id");
            exit;
        }
    } else {
        $_SESSION['errors'] = $errors;
        redirect("/events/edit?id=$event_id");
        exit;
    }
}

?>

<?php require_once ROOT_PATH . '/views/includes/navbar.php'; ?>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <?php require_once ROOT_PATH . '/views/includes/sidebar.php'; ?>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid p-4">
                <h2 class="mb-4">Edit Event</h2>
                <?php if (isset($_SESSION['errors'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            <?php foreach ($_SESSION['errors'] as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php unset($_SESSION['errors']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_SESSION['error']; ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
                <form id="editEventForm" action="" method="POST" class="needs-validation" novalidate>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="title" class="form-label">Event Title</label>
                            <input type="text" class="form-control" id="title" name="title"
                                value="<?php echo htmlspecialchars($eventData['title']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="date" class="form-label">Event Date</label>
                            <input type="datetime-local" class="form-control" id="date" name="date"
                                value="<?php echo date('Y-m-d\TH:i', strtotime($eventData['date'])); ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($eventData['description']); ?></textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="location" class="form-label">Venue</label>
                            <input type="text" class="form-control" id="location" name="location"
                                value="<?php echo htmlspecialchars($eventData['location']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="max_capacity" class="form-label">Maximum Capacity</label>
                            <input type="number" class="form-control" id="max_capacity" name="max_capacity"
                                value="<?php echo htmlspecialchars($eventData['max_capacity']); ?>" required min="1">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="active" <?php echo $eventData['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="cancelled" <?php echo $eventData['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                <option value="completed" <?php echo $eventData['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Event</button>
                    <a href="<?php echo BASE_URL; ?>/events" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </main>
        <?php require_once ROOT_PATH . '/views/includes/dashboard_footer.php'; ?>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let editEventForm = document.getElementById('editEventForm');

        editEventForm.addEventListener('submit', function(event) {
            let isValid = true;

            const fields = [{
                    input: document.getElementById('title'),
                    validator: validateRequired
                },
                {
                    input: document.getElementById('date'),
                    validator: validateRequired
                },
                {
                    input: document.getElementById('description'),
                    validator: validateRequired
                },
                {
                    input: document.getElementById('location'),
                    validator: validateRequired
                },
                {
                    input: document.getElementById('max_capacity'),
                    validator: validateMaxCapacity
                }
            ];

            fields.forEach(({
                input,
                validator
            }) => {
                const error = validator(input.value);
                if (error) {
                    showError(input, error);
                    isValid = false;
                } else {
                    clearError(input);
                }
            });

            if (!isValid) {
                event.preventDefault();
            }
        });

        function validateRequired(value) {
            return value ? null : "This field is required";
        }

        function validateMaxCapacity(value) {
            if (!value) return "Maximum capacity is required";
            if (!isNumeric(value) || value < 1) return "Valid maximum capacity is required";
            return null;
        }

        function isNumeric(value) {
            return !isNaN(value) && !isNaN(parseFloat(value));
        }

        function showError(input, message) {
            input.classList.add('is-invalid');
            let errorDiv = input.nextElementSibling;
            if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                input.parentNode.insertBefore(errorDiv, input.nextSibling);
            }
            errorDiv.textContent = message;
        }

        function clearError(input) {
            input.classList.remove('is-invalid');
            const errorDiv = input.nextElementSibling;
            if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                errorDiv.remove();
            }
        }
    });
</script>

<?php require_once ROOT_PATH . '/views/includes/footer.php'; ?>
