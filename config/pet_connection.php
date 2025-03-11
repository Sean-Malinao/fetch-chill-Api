<?php
class PetDatabase{
    private static $instance = null;
    private $conn;
    private $host = 'localhost';
    private $db_name = 'userdb';
    private $db_user= 'root';
    private $db_pass = '';

    // Singleton Pattern - Ensuring single database instance
    private function __construct() {
        // Correcting the order of parameters
        $this->conn = new mysqli($this->host, $this->db_user, $this->db_pass, $this->db_name);
        
        // Check for connection errors
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Get single instance of Database connection
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new PetDatabase();
        }
        return self::$instance->conn;
    }
}
?>
