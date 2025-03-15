<?php
require_once '../config/pet_connection.php';

class AdminModel{
    private $conn;

    public function __construct() {

        $this->conn = PetDatabase::getInstance();
    }
    //creating staff or admin
    public function createstaff ($name, $email, $password, $role) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO admin (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    } 

    //login staff/admin
    public function loginStaff($email, $password) {
        $query = "SELECT * FROM admin WHERE email = ?";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $admin = $result->fetch_assoc();
            $stmt->close();
            if (!$admin) {
                return null; // No user found
            }
            if (password_verify($password, $admin['password'])) {
                return $admin;
            } else {
                return null;
            }
        } else {
            return "Error: " . $this->conn->error;
        }
    }

    //delete staff/admin
    public function deleteStaff($id) {
        $query = "DELETE FROM admin WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    //get all staff/admin
    public function getAllStaff() {
        $query = "SELECT * FROM admin";
        $result = $this->conn->query($query);
        return $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    //check if email exists
    public function getByEmail($email) {
        $sql = "SELECT * FROM admin WHERE email = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $admin = $result->fetch_assoc();
            $stmt->close();
            return $admin === null ? null : $admin;
        } else {
            return "Error: " . $this->conn->error;
        }
       }
}
?>