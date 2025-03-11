<?php
include '../config/pet_connection.php';
class PetRecordModel {
    public $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function insertPetRecord($data) {
        $sql = "INSERT INTO petrecords (ownername, petname, breed, weight, age, gender, diagnosis, treatment, visitdate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssiiisss", $data['ownername'], $data['petname'], $data['breed'], $data['weight'], $data['age'], $data['gender'], $data['diagnosis'], $data['treatment'], $data['visitdate']);
        return $stmt->execute();
    }

    public function updatePetRecord($id, $data) {
        $sql = "UPDATE petrecords SET ownername=?, petname=?, breed=?, weight=?, age=?, gender=?, diagnosis=?, treatment=?, visitdate=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssiiisssi", $data['ownername'], $data['petname'], $data['breed'], $data['weight'], $data['age'], $data['gender'], $data['diagnosis'], $data['treatment'], $data['visitdate'], $id);
        return $stmt->execute();
    }

    public function deletePetRecord($id) {
        $sql = "DELETE FROM petrecords WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute() && $stmt->affected_rows > 0;
    }

    public function getAllPetRecords() {
        $sql = "SELECT * FROM petrecords";
        $result = $this->conn->query($sql);
        $records = array();
        while ($row = $result->fetch_assoc()) {
            $records[] = $row;
        }
        return $records;
    }
}
?>