<?php
// API: get_order.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../classes/Database.php';
include_once '../classes/Order.php';
include_once '../utils/session.php';

// Check Auth
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Unauthorized."]);
    exit();
}

$database = new Database();
$db = $database->getConnection();
$orderObj = new Order($db);

$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : null;

if ($order_id) {
    $order = $orderObj->getOrderById($order_id);

    if ($order) {
        // Security check: ensure order belongs to logged-in user
        // (Unless admin, but simple check for now)
        if ($order['user_id'] != $_SESSION['user_id']) {
            http_response_code(403);
            echo json_encode(["success" => false, "message" => "Access denied."]);
            exit();
        }

        echo json_encode([
            "success" => true,
            "data" => $order
        ]);
    } else {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Order not found."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Missing order ID."]);
}
?>
