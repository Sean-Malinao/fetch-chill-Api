<?php
require_once '../model/petrecords.php';

class PetController {
    private $model;

    public function __construct() {
        $this->model = new PetModel();
    }

    public function createPet($data) {
        if ($this->model->insertPet($data)) {
            echo json_encode(["message" => "Pet record added successfully!"]);
        } else {
            echo json_encode(["message" => "Error"]);
        }
    }

    public function updatePet($id, $data) {
        if ($this->model->updatePet($id, $data)) {
            echo json_encode(["message" => "Record updated successfully!", "result" => $data]);
        } else {
            echo json_encode(["message" => "Error"]);
        }
    }

    public function deletePet($id) {
        if ($this->model->deletePet($id)) {
            echo json_encode(["message" => "Record deleted successfully!"]);
        } else {
            echo json_encode(["message" => "Record not found."]);
        }
    }

    public function searchPetByOwner($ownername) {
        $records = $this->model->searchPetByOwner($ownername);
        if (!empty($records)) {
            echo json_encode($records);
        } else {
            echo json_encode(["message" => "No records found."]);
        }
    }

    public function getAllPets() {
        $records = $this->model->getAllPets();
        if (!empty($records)) {
            echo json_encode($records);
        } else {
            echo json_encode(["message" => "No records found."]);
        }
    }
}
?>