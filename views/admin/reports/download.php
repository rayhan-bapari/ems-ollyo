<?php
require_once ROOT_PATH . '/config/config.php';
require_once ROOT_PATH . '/config/database.php';
require_once ROOT_PATH . '/includes/functions.php';
require_once ROOT_PATH . '/includes/auth.php';
require_once ROOT_PATH . '/includes/attendee.php';
require_once ROOT_PATH . '/includes/event.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

if (!$auth->isLoggedIn()) {
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied');
}

if (!isset($_GET['event_id']) || !is_numeric($_GET['event_id'])) {
    $_SESSION['error'] = "Invalid event ID";
    redirect('/reports');
}

$event_id = (int)$_GET['event_id'];

$event = new Event($db);
$attendee = new Attendee($db);

$eventDetails = $event->getById($event_id);
if (!$eventDetails) {
    $_SESSION['error'] = "Event not found";
    redirect('/reports');
}

$attendees = $attendee->generateAttendeeReport($event_id);

$filename = sanitizeFilename($eventDetails['title']) . '_attendees_' . date('Y-m-d') . '.csv';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');

fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

fputcsv($output, [
    'Name',
    'Email',
    'Phone',
    'Registration Date',
    'Status',
    'Notes'
]);

foreach ($attendees as $row) {
    fputcsv($output, [
        $row['name'],
        $row['email'],
        $row['phone'],
        date('Y-m-d H:i:s', strtotime($row['registration_date'])),
        $row['status'] ?? 'Registered',
        $row['notes'] ?? ''
    ]);
}

fclose($output);
exit();

function sanitizeFilename($filename)
{
    $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $filename);
    $filename = preg_replace('/_+/', '_', $filename);
    return trim($filename, '_');
}
