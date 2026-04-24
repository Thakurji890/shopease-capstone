<?php
// api/check_auth.php
require_once 'config.php';
session_start();

if (isset($_SESSION['user_id'])) {
    echo json_encode([
        "authenticated" => true,
        "user" => [
            "id" => $_SESSION['user_id'],
            "name" => $_SESSION['user_name'],
            "role" => $_SESSION['user_role']
        ]
    ]);
} else {
    echo json_encode(["authenticated" => false]);
}
?>
