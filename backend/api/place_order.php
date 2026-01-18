<?php
// API: place_order.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../classes/Database.php';
include_once '../classes/Order.php';
include_once '../utils/session.php';
include_once '../utils/validate.php';

// Check Auth
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Unauthorized. Please login."]);
    exit();
}

$database = new Database();
$db = $database->getConnection();
$order = new Order($db);

$data = json_decode(file_get_contents("php://input"), true);
// Note: using true for associative array for easier handling of nested items

if (
    !empty($data['items']) && 
    is_array($data['items']) &&
    !empty($data['payment_method']) &&
    !empty($data['delivery_address'])
) {
    $user_id = $_SESSION['user_id'];
    $items = $data['items'];
    $payment_method = sanitizeInput($data['payment_method']);
    $delivery_address = sanitizeInput($data['delivery_address']);

    // Basic check for items structure
    foreach ($items as $item) {
        if (!isset($item['meal_id']) || !isset($item['quantity'])) {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "Invalid items format."]);
            exit();
        }
    }

    $order_id = $order->createOrder($user_id, $items, $payment_method, $delivery_address);

    if ($order_id) {
        http_response_code(201);
        echo json_encode([
            "success" => true,
            "message" => "Order placed successfully.",
            "data" => ["order_id" => $order_id]
        ]);
    } else {
        http_response_code(503);
        echo json_encode(["success" => false, "message" => "Unable to place order."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Incomplete order data."]);
}
?>
