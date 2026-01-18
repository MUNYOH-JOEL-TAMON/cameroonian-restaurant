<?php
// Validation Utilities

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePhone($phone) {
    // Basic Cameroonian phone validation (assuming 9 digits, typically starts with 6)
    // Adjust regex as needed for strictness.
    return preg_match('/^237[0-9]{9}$/', $phone) || preg_match('/^[0-9]{9}$/', $phone); // Allowing with or without country code 237
}

function validatePassword($password) {
    // Minimum 8 characters
    return strlen($password) >= 8;
}

function validateRequiredFields($data, $fields) {
    foreach ($fields as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            return false;
        }
    }
    return true;
}
?>
