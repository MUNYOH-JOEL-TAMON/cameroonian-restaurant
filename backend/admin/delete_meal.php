<?php
// API: admin/delete_meal.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../classes/Database.php';
include_once '../classes/Meal.php';
include_once '../utils/session.php';

if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Unauthorized."]);
    exit();
}

$database = new Database();
$db = $database->getConnection();
$meal = new Meal($db);

$data = json_decode(file_get_contents("php://input"));
if (is_null($data) && isset($_POST['meal_id'])) {
    $data = (object) $_POST;
}

if (!empty($data->meal_id)) {
    if ($meal->deleteMeal($data->meal_id)) {
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "Meal deleted."]);
    } else {
        http_response_code(503);
        echo json_encode(["success" => false, "message" => "Unable to delete meal."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Missing meal_id."]);
}
?>
