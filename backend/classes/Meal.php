<?php
class Meal {
    private $conn;
    private $table_name = "meals";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllMeals() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY category, meal_name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMealsByCategory($category) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE category = :category ORDER BY meal_name";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":category", $category);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMealById($meal_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE meal_id = :meal_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":meal_id", $meal_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Admin methods
    public function addMeal($data) {
        $query = "INSERT INTO " . $this->table_name . " (meal_name, description, price, category, image_url) VALUES (:meal_name, :description, :price, :category, :image_url)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":meal_name", $data['meal_name']);
        $stmt->bindParam(":description", $data['description']);
        $stmt->bindParam(":price", $data['price']);
        $stmt->bindParam(":category", $data['category']);
        $stmt->bindParam(":image_url", $data['image_url']);

        return $stmt->execute();
    }

    public function updateMeal($meal_id, $data) {
        $query = "UPDATE " . $this->table_name . " SET meal_name = :meal_name, description = :description, price = :price, category = :category, image_url = :image_url WHERE meal_id = :meal_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":meal_name", $data['meal_name']);
        $stmt->bindParam(":description", $data['description']);
        $stmt->bindParam(":price", $data['price']);
        $stmt->bindParam(":category", $data['category']);
        $stmt->bindParam(":image_url", $data['image_url']);
        $stmt->bindParam(":meal_id", $meal_id);

        return $stmt->execute();
    }

    public function deleteMeal($meal_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE meal_id = :meal_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":meal_id", $meal_id);
        return $stmt->execute();
    }
    
    public function toggleAvailability($meal_id) {
         // First get current status
        $meal = $this->getMealById($meal_id);
        if (!$meal) return false;
        
        $new_status = $meal['is_available'] ? 0 : 1;
        
        $query = "UPDATE " . $this->table_name . " SET is_available = :is_available WHERE meal_id = :meal_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":is_available", $new_status, PDO::PARAM_BOOL);
        $stmt->bindParam(":meal_id", $meal_id);
        
        return $stmt->execute();
    }
}
?>
