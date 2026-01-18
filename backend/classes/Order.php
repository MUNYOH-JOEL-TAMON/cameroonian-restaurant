<?php
class Order {
    private $conn;
    private $table_name = "orders";
    private $items_table = "order_items";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createOrder($user_id, $items, $payment_method, $delivery_address) {
        try {
            $this->conn->beginTransaction();

            // 1. Calculate total amount and validate items
            $total_amount = 0;
            $processed_items = [];

            // We need to fetch meal details for price 
            // Ideally we inject Meal class or just query directly. Querying directly for speed here.
            $meal_stmt = $this->conn->prepare("SELECT price, is_available FROM meals WHERE meal_id = :meal_id");

            foreach ($items as $item) {
                $meal_stmt->execute([':meal_id' => $item['meal_id']]);
                $meal_data = $meal_stmt->fetch(PDO::FETCH_ASSOC);

                if (!$meal_data || !$meal_data['is_available']) {
                    throw new Exception("Meal ID " . $item['meal_id'] . " is invalid or unavailable.");
                }

                $subtotal = $meal_data['price'] * $item['quantity'];
                $total_amount += $subtotal;

                $processed_items[] = [
                    'meal_id' => $item['meal_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $meal_data['price'],
                    'subtotal' => $subtotal
                ];
            }

            // 2. Insert Order
            $query = "INSERT INTO " . $this->table_name . " (user_id, total_amount, payment_method, delivery_address) VALUES (:user_id, :total_amount, :payment_method, :delivery_address)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->bindParam(":total_amount", $total_amount);
            $stmt->bindParam(":payment_method", $payment_method);
            $stmt->bindParam(":delivery_address", $delivery_address);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to create order record.");
            }

            $order_id = $this->conn->lastInsertId();

            // 3. Insert Order Items
            $item_query = "INSERT INTO " . $this->items_table . " (order_id, meal_id, quantity, unit_price, subtotal) VALUES (:order_id, :meal_id, :quantity, :unit_price, :subtotal)";
            $item_stmt = $this->conn->prepare($item_query);

            foreach ($processed_items as $p_item) {
                $item_stmt->execute([
                    ':order_id' => $order_id,
                    ':meal_id' => $p_item['meal_id'],
                    ':quantity' => $p_item['quantity'],
                    ':unit_price' => $p_item['unit_price'],
                    ':subtotal' => $p_item['subtotal']
                ]);
            }

            $this->conn->commit();
            return $order_id;

        } catch (Exception $e) {
            $this->conn->rollBack();
            // In a real app, propagate error or log it. 
            // returning false to signal failure, or handle exception in API
            return false;
        }
    }

    public function getOrderById($order_id) {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE order_id = :order_id LIMIT 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":order_id", $order_id);
            $stmt->execute();
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($order) {
                // Get items
                $item_query = "SELECT Oi.*, M.meal_name, M.image_url 
                               FROM " . $this->items_table . " Oi 
                               JOIN meals M ON Oi.meal_id = M.meal_id 
                               WHERE Oi.order_id = :order_id";
                $item_stmt = $this->conn->prepare($item_query);
                $item_stmt->bindParam(":order_id", $order_id);
                $item_stmt->execute();
                $order['items'] = $item_stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            return $order;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getUserOrders($user_id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = :user_id ORDER BY order_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllOrders() {
        // Admin function
        $query = "SELECT O.*, U.full_name, U.email FROM " . $this->table_name . " O JOIN users U ON O.user_id = U.user_id ORDER BY O.order_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    

    public function updateOrderStatus($order_id, $status) {
        $query = "UPDATE " . $this->table_name . " SET order_status = :status WHERE order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":order_id", $order_id);
        return $stmt->execute();
    }
    
    public function updatePaymentStatus($order_id, $status) {
        $query = "UPDATE " . $this->table_name . " SET payment_status = :status WHERE order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":order_id", $order_id);
        return $stmt->execute();
    }
}
?>
