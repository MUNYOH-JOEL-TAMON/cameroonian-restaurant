<?php
// API: register.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../classes/Database.php';
include_once '../classes/User.php';
include_once '../utils/validate.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

// Map legacy POST fields if JSON is null (form-data fallback)
if (is_null($data)) {
    $data = (object) $_POST;
}

if (
    !empty($data->full_name) &&
    !empty($data->email) &&
    !empty($data->phone) &&
    !empty($data->password)
) {
    // Sanitization
    $full_name = sanitizeInput($data->full_name);
    $email = sanitizeInput($data->email);
    $phone = sanitizeInput($data->phone);
    $password = $data->password; // Don't htmlspecialchars password if hashing
    $address = isset($data->address) ? sanitizeInput($data->address) : "";

    // Validation
    if (!validateEmail($email)) {
        http_response_code(400); 
        echo json_encode(["success" => false, "message" => "Invalid email format."]);
        exit();
    }

    if (!validatePhone($phone)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Invalid phone number."]);
        exit();
    }

    if (!validatePassword($password)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Password must be at least 8 characters."]);
        exit();
    }

    if ($user->emailExists($email)) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Email already exists."]);
        exit();
    }

    if ($user->register($full_name, $email, $phone, $password, $address)) {
        http_response_code(201);
        echo json_encode(["success" => true, "message" => "User registered successfully."]);
    } else {
        http_response_code(503);
        echo json_encode(["success" => false, "message" => "Unable to register user."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Incomplete data."]);
}
?>
