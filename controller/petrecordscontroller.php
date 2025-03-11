<?php
include '../config/pet_connection.php';
include '../model/petrecords.php';

class PetRecordController {
    public $model;

    public function __construct($conn) {
        $this->model = new PetRecordModel($conn);
    }

    public function handleRequest() {
        $request_uri = explode("/", $_SERVER['REQUEST_URI']);
        $id = end($request_uri);
        $input = json_decode(file_get_contents('php://input'), true);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $result = $this->model->insertPetRecord($input);
            echo json_encode(["message" => $result ? "Pet record added successfully!" : "Error"]);
        } elseif ($_SERVER["REQUEST_METHOD"] == "PUT" && is_numeric($id)) {
            $result = $this->model->updatePetRecord($id, $input);
            echo json_encode(["message" => $result ? "Record updated successfully!" : "Error"]);
        } elseif ($_SERVER["REQUEST_METHOD"] == "DELETE" && is_numeric($id)) {
            $result = $this->model->deletePetRecord($id);
            echo json_encode(["message" => $result ? "Record deleted successfully!" : "Record does not exist"]);
        } else {
            $records = $this->model->getAllPetRecords();
            echo json_encode($records);
        }
    }
}
?>