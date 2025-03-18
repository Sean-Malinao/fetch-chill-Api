<?php
require_once '../config/pet_connection.php';

class UserModel{
    private $conn;

    public function __construct() {

        $this->conn = PetDatabase::getInstance();
    }
    //creating acc
    public function createUser($name, $email, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $name, $email, $hashed_password);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    //checking if email exists
    public function getUserByEmail($email) {
        $query = "SELECT * FROM users WHERE email = ?";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
            return $user;
        } else {
            return "Error: " . $this->conn->error;
        }
    }
    //logging in user
    public function loginuser($email, $password) {
        $query = "SELECT * FROM users WHERE email = ?";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
            if (!$user) {
                return null; // No user found
            }
            if (password_verify($password, $user['password'])) {
                return $user;
            } else {
                return null;
            }
        } else {
            return "Error: " . $this->conn->error;
        }
    }
    //for updating password
    public function getUserByid($id) {
        $query = "SELECT * FROM users WHERE id = ?";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
            return $user;
        } else {
            return "Error: " . $this->conn->error;
        }
    }
    //for changing password
    public function Updateuserpassword($id, $newpassword){
        $query = "UPDATE users SET password = ? WHERE id = ?";
        if($stmt = $this->conn->prepare($query)){
            $stmt->bind_param("ss", $newpassword, $id);
            $stmt->execute();
            $stmt->close();
            return true;
        } else {
            return "Error: " . $this->conn->error;
        }
    }

}
?>