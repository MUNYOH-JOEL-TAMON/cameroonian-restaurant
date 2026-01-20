<?php
// Session Management Utilities

function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function isLoggedIn() {
    startSession();
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Unauthorized access. Please login."]);
        exit();
    }
}

function loginUser($user) {
    startSession();
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['role'] = isset($user['role']) ? $user['role'] : 'user';
}

function isAdmin() {
    startSession();
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function requireAdmin() {
    if (!isAdmin()) {
        http_response_code(403);
        echo json_encode(["success" => false, "message" => "Unauthorized. Admin access required."]);
        exit();
    }
}

function logoutUser() {
    startSession();
    session_unset();
    session_destroy();
}
?>
