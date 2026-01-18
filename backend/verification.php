<?php
// backend/verification.php
// verified logic by directly instantiating classes

echo "<h2>Backend Verification Script</h2>";

include_once 'config/database.php';
include_once 'classes/Database.php';
include_once 'classes/User.php';
include_once 'classes/Meal.php';
include_once 'classes/Order.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    if ($db) {
        echo "<p style='color:green'>[PASS] Database Connection</p>";
    } else {
        throw new Exception("Database Connection Failed");
    }

    // 1. Test User Registration
    $user = new User($db);
    $test_email = "test_" . time() . "@example.com";
    $test_pass = "password123";
    
    if ($user->register("Test User", $test_email, "237677889900", $test_pass, "Douala")) {
        echo "<p style='color:green'>[PASS] User Registration</p>";
    } else {
        echo "<p style='color:red'>[FAIL] User Registration</p>";
    }

    // 2. Test Login
    $loggedIn = $user->login($test_email, $test_pass);
    if ($loggedIn) {
        echo "<p style='color:green'>[PASS] User Login (User ID: " . $loggedIn['user_id'] . ")</p>";
    } else {
        echo "<p style='color:red'>[FAIL] User Login</p>";
        exit;
    }

    // 3. Test Meal Listing
    $meal = new Meal($db);
    $meals = $meal->getAllMeals();
    if (count($meals) > 0) {
        echo "<p style='color:green'>[PASS] Get Meals (Found " . count($meals) . " meals)</p>";
    } else {
        echo "<p style='color:orange'>[WARN] No meals found (Did you import database.sql?)</p>";
    }

    // 4. Test Place Order
    $order = new Order($db);
    $items = [
        ['meal_id' => $meals[0]['meal_id'], 'quantity' => 2]
    ];
    $order_id = $order->createOrder($loggedIn['user_id'], $items, 'delivery', 'Yaounde');
    
    if ($order_id) {
        echo "<p style='color:green'>[PASS] Place Order (Order ID: $order_id)</p>";
        
        // 5. Get Order
        $fetched_order = $order->getOrderById($order_id);
        if ($fetched_order && count($fetched_order['items']) > 0) {
            echo "<p style='color:green'>[PASS] Get Order Details</p>";
        } else {
            echo "<p style='color:red'>[FAIL] Get Order Details</p>";
        }
    } else {
        echo "<p style='color:red'>[FAIL] Place Order</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color:red'>[CRITICAL FAIL] " . $e->getMessage() . "</p>";
}
?>
