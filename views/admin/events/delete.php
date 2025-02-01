<?php
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/includes/auth.php';
require_once ROOT_PATH . '/includes/event.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "You must be logged in to delete an event.";
    redirect('/events');
}

$db = new Database();
$conn = $db->getConnection();
$event = new Event($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $eventId = (int) $_POST['id'];

    $eventData = $event->getById($eventId);
    if (!$eventData || $eventData['user_id'] != $_SESSION['user_id']) {
        $_SESSION['error'] = "You don't have permission to delete this event.";
        redirect('/events');
        exit;
    }

    $result = $event->delete($eventId);
    if ($result) {
        $_SESSION['success'] = "Event deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting event.";
    }

    redirect('/events');
} else {
    $_SESSION['error'] = "Invalid request.";
    redirect('/events');
}
