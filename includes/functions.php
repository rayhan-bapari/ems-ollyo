<?php
function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function redirect($path)
{
    header("Location: " . BASE_URL . $path);
    exit();
}
