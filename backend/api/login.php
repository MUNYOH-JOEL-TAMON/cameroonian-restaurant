<?php
// API: login.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../classes/Database.php';
include_once '../classes/User.php';
include_once '../utils/validate.php';
include_once '../utils/session.php';

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$data = json_decode(file_get_contents("php://input"));
if (is_null($data)) {
    $data = (object) $_POST;
}

if (!empty($data->email) && !empty($data->password)) {
    $email = sanitizeInput($data->email);
    $password = $data->password;

    $loggedInUser = $user->login($email, $password);

    if ($loggedInUser) {
        loginUser($loggedInUser); // Start session

        http_response_code(200);
        echo json_encode([
            "success" => true,
            "message" => "Login successful.",
            "data" => $loggedInUser
        ]);
    } else {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Invalid credentials."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Incomplete data."]);
}
?>
