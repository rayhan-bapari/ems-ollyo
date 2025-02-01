<?php
require_once '../config/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

$auth = new Auth($conn);
$auth->logout();
redirect('/login');
