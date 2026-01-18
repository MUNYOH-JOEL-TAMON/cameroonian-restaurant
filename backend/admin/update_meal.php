<?php
// API: admin/update_meal.php
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

$database = new Database();
$db = $database->getConnection();
$meal = new Meal($db);

$data = json_decode(file_get_contents("php://input"), true);
if (is_null($data)) {
    $data = $_POST;
}

if (!empty($data['meal_id'])) {
    $meal_id = $data['meal_id'];
    
    // Check if meal exists first? Or just update.
    
    $meal_data = [
        'meal_name' => sanitizeInput($data['meal_name']),
        'description' => isset($data['description']) ? sanitizeInput($data['description']) : '',
        'price' => $data['price'],
        'category' => sanitizeInput($data['category']),
        'image_url' => isset($data['image_url']) ? sanitizeInput($data['image_url']) : ''
    ];

    if ($meal->updateMeal($meal_id, $meal_data)) {
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "Meal updated."]);
    } else {
        http_response_code(503);
        echo json_encode(["success" => false, "message" => "Unable to update meal."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Missing meal_id."]);
}
?>
