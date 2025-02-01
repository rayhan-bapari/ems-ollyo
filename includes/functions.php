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
    if (!headers_sent()) {
        header("Location: " . BASE_URL . $path);
        exit();
    } else {
        echo "<script>window.location.href='" . BASE_URL . $path . "';</script>";
        exit();
    }
}