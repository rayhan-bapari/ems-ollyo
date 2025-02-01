<?php
require_once '../config/config.php';
require_once '../config/database.php';
require_once '../includes/functions.php';
require_once '../includes/auth.php';

$db = new Database();
$conn = $db->getConnection();
$auth = new Auth($conn);

$request = $_SERVER['REQUEST_URI'];
$basePath = '/event-management';
$request = str_replace($basePath, '', $request);

$urlParts = parse_url($request);
$path = $urlParts['path'];

switch ($path) {
    case '':
    case '/':
        require ROOT_PATH . '/views/home.php';
        break;
    case '/login':
        require ROOT_PATH . '/views/auth/login.php';
        break;
    case '/register':
        require ROOT_PATH . '/views/auth/register.php';
        break;
    case '/logout':
        require ROOT_PATH . '/public/logout.php';
        break;
    case '/event-details':
        if (isset($_GET['id'])) {
            require ROOT_PATH . '/views/event_details.php';
        } else {
            $_SESSION['error'] = "Event ID is required";
            redirect('/');
        }
        break;
    case '/dashboard':
        if ($auth->isLoggedIn()) {
            require ROOT_PATH . '/views/admin/dashboard.php';
        } else {
            redirect('/login');
        }
        break;
    case '/events':
        if ($auth->isLoggedIn()) {
            require ROOT_PATH . '/views/admin/events/list.php';
        } else {
            redirect('/login');
        }
        break;
    case '/events/create':
        if ($auth->isLoggedIn()) {
            require ROOT_PATH . '/views/admin/events/create.php';
        } else {
            redirect('/login');
        }
        break;
    case '/events/edit':
        if ($auth->isLoggedIn()) {
            if (isset($_GET['id'])) {
                $eventId = (int) $_GET['id'];
                require ROOT_PATH . '/views/admin/events/edit.php';
            } else {
                $_SESSION['error'] = "Event ID is required";
                redirect('/events');
            }
        } else {
            redirect('/login');
        }
        break;
    case '/events/delete':
        if ($auth->isLoggedIn()) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
                $eventId = (int) $_POST['id'];
                require ROOT_PATH . '/views/admin/events/delete.php';
            } else {
                $_SESSION['error'] = "Event ID is required";
                redirect('/events');
            }
        } else {
            redirect('/login');
        }
        break;
    case '/events/view':
        if ($auth->isLoggedIn()) {
            if (isset($_GET['id'])) {
                $eventId = (int) $_GET['id'];
                require ROOT_PATH . '/views/admin/events/view.php';
            } else {
                $_SESSION['error'] = "Event ID is required";
                redirect('/events');
            }
        } else {
            redirect('/login');
        }
        break;
    case '/reports':
        if ($auth->isLoggedIn()) {
            require ROOT_PATH . '/views/admin/reports/export.php';
        } else {
            redirect('/login');
        }
        break;

    case '/reports/download':
        if ($auth->isLoggedIn()) {
            if (isset($_GET['event_id'])) {
                require ROOT_PATH . '/views/admin/reports/download.php';
            } else {
                $_SESSION['error'] = "Event ID is required";
                redirect('/reports');
            }
        } else {
            redirect('/login');
        }
        break;
    default:
        http_response_code(404);
        require ROOT_PATH . '/views/404.php';
        break;
}
