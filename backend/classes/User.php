<?php
class User {
    private $conn;
    private $table_name = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($full_name, $email, $phone, $password, $address) {
        $query = "INSERT INTO " . $this->table_name . " (full_name, email, phone, password, address) VALUES (:full_name, :email, :phone, :password, :address)";
        
        $stmt = $this->conn->prepare($query);

        // Sanitize (already done in API usually, but good practice here too or bind param handles it mostly)
        // Bind parameters
        $stmt->bindParam(":full_name", $full_name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":phone", $phone);
        
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt->bindParam(":password", $password_hash);
        
        $stmt->bindParam(":address", $address);

        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch (PDOException $e) {
            // Handle duplicate email etc.
            // For now just return false or error message
            // error_log($e->getMessage());
        }
        return false;
    }

    public function login($email, $password) {
        $query = "SELECT user_id, full_name, email, password, phone, address, role FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password'])) {
                // Remove password from returned array
                unset($row['password']);
                return $row;
            }
        }
        return false;
    }

    public function getUserById($user_id) {
        $query = "SELECT user_id, full_name, email, phone, address, role, created_at FROM " . $this->table_name . " WHERE user_id = :user_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfile($user_id, $data) {
        // Dynamic update query construction
        $fields = [];
        foreach ($data as $key => $value) {
            if (in_array($key, ['full_name', 'phone', 'address'])) {
                $fields[] = "$key = :$key";
            }
        }

        if (empty($fields)) {
            return false;
        }

        $query = "UPDATE " . $this->table_name . " SET " . implode(", ", $fields) . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        
        foreach ($data as $key => $value) {
            if (in_array($key, ['full_name', 'phone', 'address'])) {
                $stmt->bindValue(":$key", $value);
            }
        }
        $stmt->bindValue(":user_id", $user_id);

        return $stmt->execute();
    }
    
    public function emailExists($email) {
        $query = "SELECT user_id FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
?>
