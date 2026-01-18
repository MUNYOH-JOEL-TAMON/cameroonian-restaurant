<?php
class Database {
    public $conn;

    public function getConnection() {
        $this->conn = null;

        // Note: Ideally we require the config file here, but since this class might be instantiated
        // from different depths (api/ or root), we should handle the include path carefully
        // or assume the configuration is loaded before this class is used.
        // For robustness in this specific project structure, we will look for the config file.
        
        $configPath = __DIR__ . '/../config/database.php';
        if (file_exists($configPath)) {
            include_once $configPath;
        }

        try {
            // Check if constants are defined, if not, use defaults or fail
            $host = defined('DB_HOST') ? DB_HOST : 'localhost';
            $db_name = defined('DB_NAME') ? DB_NAME : 'cameroonian_restaurant';
            $username = defined('DB_USER') ? DB_USER : 'root';
            // DB_PASS might be empty string which is falsy
            $password = defined('DB_PASS') ? DB_PASS : '';

            $this->conn = new PDO("mysql:host=" . $host . ";dbname=" . $db_name, $username, $password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
