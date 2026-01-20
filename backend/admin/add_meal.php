<?php
// API: admin/add_meal.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../classes/Database.php';
include_once '../classes/Meal.php';
include_once '../utils/session.php';
include_once '../utils/validate.php';

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Unauthorized."]);
    exit();
}

if (!isAdmin()) {
    http_response_code(403);
    echo json_encode(["success" => false, "message" => "Forbidden. Admin access required."]);
    exit();
}

$database = new Database();
$db = $database->getConnection();
$meal = new Meal($db);

$data = json_decode(file_get_contents("php://input"), true);
if (is_null($data)) {
    $data = $_POST;
}

if (
    !empty($data['meal_name']) &&
    !empty($data['price']) &&
    !empty($data['category'])
) {
    // Basic sanitization
    // Handle Image Upload
    $image_filename = '';
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        // Include upload helper
        include_once '../utils/file_upload.php';
        $uploadResult = uploadImage($_FILES['image_file']);
        if ($uploadResult['success']) {
            $image_filename = $uploadResult['filename'];
        } else {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => $uploadResult['message']]);
            exit();
        }
    } else if (!empty($data['image_url'])) {
        // Fallback for manual text entry (backward compatibility)
        $image_filename = sanitizeInput($data['image_url']);
    }

    $meal_data = [
        'meal_name' => sanitizeInput($data['meal_name']),
        'description' => isset($data['description']) ? sanitizeInput($data['description']) : '',
        'price' => $data['price'],
        'category' => sanitizeInput($data['category']),
        'image_url' => $image_filename
    ];

    if ($meal->addMeal($meal_data)) {
        http_response_code(201);
        echo json_encode(["success" => true, "message" => "Meal added."]);
    } else {
        http_response_code(503);
        echo json_encode(["success" => false, "message" => "Unable to add meal."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Incomplete data."]);
}
?>
