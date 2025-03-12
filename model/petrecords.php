<?php
require_once '../config/pet_connection.php';
class PetModel {
    private $conn;

    public function __construct() {
        $this->conn = PetDatabase::getInstance();
    }

    public function insertPet($data) {
        $sql = "INSERT INTO petrecords (ownername, petname, species, breed, weight, age, gender, diagnosis, treatment, vaccine, veterinarian, visitdate) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssiiisssss", $data['ownername'], $data['petname'], $data['species'], 
        $data['breed'], $data['weight'], $data['age'], $data['gender'], $data['diagnosis'], $data['treatment'], $data['vaccine'], $data['veterinarian'], $data['visitdate']);
        return $stmt->execute();
    }

    public function updatePet($id, $data) {
        $sql = "UPDATE petrecords SET ownername=?, petname=?, species=?, breed=?, weight=?, age=?, gender=?, diagnosis=?, treatment=?, 
        vaccine=?, veterinarian=?, visitdate=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssiiisssssi", $data['ownername'], $data['petname'], $data['species'],
        $data['breed'], $data['weight'], $data['age'], $data['gender'], $data['diagnosis'], $data['treatment'], $data['vaccine'], $data['veterinarian'], $data['visitdate'], $id);
        return $stmt->execute();
    }

    public function deletePet($id) {
        $sql = "DELETE FROM petrecords WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function searchPetByOwner($ownername) {
        $sql = "SELECT * FROM petrecords WHERE ownername LIKE ?";
        $stmt = $this->conn->prepare($sql);
        $search_term = "%" . $ownername . "%";
        $stmt->bind_param("s", $search_term);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllPets() {
        $sql = "SELECT * FROM petrecords";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>