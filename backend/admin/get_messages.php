<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../classes/Database.php';
include_once '../models/Message.php';

$database = new Database();
$db = $database->getConnection();

$message = new Message($db);

$stmt = $message->read();
$num = $stmt->rowCount();

if($num > 0){
    $messages_arr = array();
    $messages_arr["records"] = array();
    $messages_arr["count"] = $num;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);

        $message_item = array(
            "id" => $id,
            "name" => $name,
            "email" => $email,
            "phone" => $phone,
            "subject" => $subject,
            "message" => html_entity_decode($message),
            "created_at" => $created_at
        );

        array_push($messages_arr["records"], $message_item);
    }
    
    http_response_code(200);
    echo json_encode($messages_arr);
} else {
    http_response_code(200); // 200 OK even if empty, just return empty array
    echo json_encode(array("records" => [], "count" => 0));
}
?>
