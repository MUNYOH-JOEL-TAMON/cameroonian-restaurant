<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../classes/Database.php';
include_once '../models/Message.php';

$database = new Database();
$db = $database->getConnection();

$message = new Message($db);

$data = json_decode(file_get_contents("php://input"));

// Make sure data is not empty
if(
    !empty($data->name) &&
    !empty($data->email) &&
    !empty($data->subject) &&
    !empty($data->message)
){
    $message->name = $data->name;
    $message->email = $data->email;
    $message->phone = $data->phone ?? ''; // Optional
    $message->subject = $data->subject;
    $message->message = $data->message;

    if($message->create()){
        http_response_code(201);
        echo json_encode(array("message" => "Message sent successfully."));
    } else{
        http_response_code(503);
        echo json_encode(array("message" => "Unable to send message."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to send message. Data is incomplete."));
}
?>
