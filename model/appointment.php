<?php
require_once '../config/pet_connection.php';

class Appointment{
    private $conn;

    public function __construct()
    {
        $this->conn = PetDatabase::getInstance();
    }

    // get appointment of specific owner
    public function GetAppointment($id) {
        $stmt = $this->conn->prepare("SELECT * FROM appointments WHERE id = ?");
        if ($stmt === false) {
            throw new mysqli_sql_exception("Prepare statement failed: " . $this->conn->error);
        }
        $stmt->bind_param('i', $id);
        if ($stmt->execute() === false) {
            throw new mysqli_sql_exception("Execute statement failed: " . $stmt->error);
        }
        $result = $stmt->get_result();
        $appointment = $result->fetch_assoc();
        $stmt->close();
        return $appointment;
    }

    //get all appointments of a user
    public function GetAppointmentByUserId($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM appointments WHERE user_id = ?");
        if ($stmt === false) {
            throw new mysqli_sql_exception("Prepare statement failed: " . $this->conn->error);
        }
        $stmt->bind_param('i', $user_id);
        if ($stmt->execute() === false) {
            throw new mysqli_sql_exception("Execute statement failed: " . $stmt->error);
        }
        $result = $stmt->get_result();
        $appointment = $result->fetch_assoc();
        $stmt->close();
        return $appointment;
    }

    //get all appointments of a user
    public function GetAllConfirmedAppointments($user_id){
        $query = "SELECT * FROM appointments WHERE user_id = ? AND status = ?";
        $stmt = $this->conn->prepare($query);
        $confirmed = "Confirmed";
        $stmt->bind_param("is", $user_id, $confirmed);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    //get all appointments of a user
    public function GetAllCancelledAppointments($user_id){
        $query = "SELECT * FROM appointments WHERE user_id = ? AND status = ?";
        $stmt = $this->conn->prepare($query);
        $cancelled = "Cancelled";
        $stmt->bind_param("is", $user_id, $cancelled);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    //for website
    public function GetAllAppointments() {
        $query = "SELECT a.*, u.id AS user_id, u.name AS name FROM appointments a JOIN users u ON a.user_id = u.id";
        $result = $this->conn->query($query);
        return $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }
    

    //Creating new appointments
    public function CreateAppointments($user_id, $service_type, $appointment_date, $appointment_time){ 
        $query = "INSERT INTO appointments (user_id, service_type, appointment_date, appointment_time) VALUES (?, ?, ?,?)";
        if($stmt = $this->conn->prepare($query)){
            $stmt->bind_param("isss", $user_id, $service_type, $appointment_date, $appointment_time);
            if($stmt->execute()){
                echo json_encode(['message' => 'Appointment created successfully']);
            } else{
                echo json_encode(['message' => 'Error' . $this->conn->error]);
            }
            $stmt->close();
        }
    }
    //check if user id exists
    public function ifuserexists($user_id){
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
        if ($stmt === false) {
            throw new mysqli_sql_exception("Prepare statement failed: " . $this->conn->error);
        }
        $stmt->bind_param('i', $user_id);
        if ($stmt->execute() === false) {
            throw new mysqli_sql_exception("Execute statement failed: " . $stmt->error); // It helps in debugging by providing detailed error messages.Ensures that execution stops immediately when an error occurs.
        }
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    //update appointment status
    public function UpdateStatus($status, $id) {
        $stmt = $this->conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
        if ($stmt === false) {
            throw new mysqli_sql_exception("Prepare statement failed: " . $this->conn->error);
        }
        $stmt->bind_param('si', $status, $id);
        if ($stmt->execute() === false) {
            throw new mysqli_sql_exception("Execute statement failed: " . $stmt->error);
        }
        $stmt->close();
    }
    //delete appointment
    public function DeleteAppointment($id){
        $stmt = $this->conn->prepare("DELETE FROM appointments WHERE id = ?");
        if ($stmt === false) {
            throw new mysqli_sql_exception("Prepare statement failed: " . $this->conn->error);
        }
        $stmt->bind_param('i', $id);
        if ($stmt->execute() === false) {
            throw new mysqli_sql_exception("Execute statement failed: " . $stmt->error);
        }
        $stmt->close();
    }

    //delete appointment by user
    public function DeletePendingAppointment($id){
        $stmt = $this->conn->prepare("DELETE FROM appointments WHERE id = ? AND status = 'Pending'");
        if ($stmt === false) {
            throw new mysqli_sql_exception("Prepare statement failed: " . $this->conn->error);
        }
        $stmt->bind_param('i', $id);
        if ($stmt->execute() === false) {
            throw new mysqli_sql_exception("Execute statement failed: " . $stmt->error);
        }
        $stmt->close();
    }
}
?>
