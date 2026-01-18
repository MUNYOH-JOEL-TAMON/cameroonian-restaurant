<?php
// API: get_meals.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../classes/Database.php';
include_once '../classes/Meal.php';

$database = new Database();
$db = $database->getConnection();
$meal = new Meal($db);

$category = isset($_GET['category']) ? $_GET['category'] : null;

if ($category) {
    $stmt = $meal->getMealsByCategory($category);
} else {
    $stmt = $meal->getAllMeals();
}

// Since getAllMeals and getMealsByCategory return arrays in my implementation (fetchAll), 
// I don't need to loop cursor. But let's check Meal.php implementation.
// Yes, they return fetchAll(PDO::FETCH_ASSOC).

if (count($stmt) > 0) {
    echo json_encode([
        "success" => true,
        "data" => $stmt
    ]);
} else {
    echo json_encode([
        "success" => true,
        "message" => "No meals found.",
        "data" => []
    ]);
}
?>
