<?php
class Message {
    private $conn;
    private $table_name = "messages";

    public $id;
    public $name;
    public $email;
    public $phone;
    public $subject;
    public $message;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create new message
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    name = :name,
                    email = :email,
                    phone = :phone,
                    subject = :subject,
                    message = :message";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->subject = htmlspecialchars(strip_tags($this->subject));
        $this->message = htmlspecialchars(strip_tags($this->message));

        // Bind
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone", $this->phone);
        $stmt->bindParam(":subject", $this->subject);
        $stmt->bindParam(":message", $this->message);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // Get all messages
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
