<?php
include_once 'classes/Database.php';

$database = new Database();
$db = $database->getConnection();

$sql = "CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

try {
    $db->exec($sql);
    echo "Table 'messages' created successfully.";
} catch(PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
?>
