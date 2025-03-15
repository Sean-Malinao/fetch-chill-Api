<?php
require_once '../model/admin.php';

class AdminController {
    private $adminModel;

    public function __construct() 
    {
        $this->adminModel = new AdminModel();
    }
    // creating staff
    public function createStaff($input) {
        $name = $input['name'];
        $email = $input['email'];
        $password = $input['password'];
        $role = $input['role'];
        $existingEmail = $this->adminModel->getByEmail($email);
        if($existingEmail){
            echo json_encode(['message' => 'Email already exists']);
            return;
        }
        $this->adminModel->createStaff($name, $email, $password, $role);
        echo json_encode(['message' => 'Staff created', 'staff' => $input]);
    }
    //login staff/admin
    public function loginStaff($input) {
        $email = $input['email'];
        $password = $input['password'];
        $staff = $this->adminModel->loginStaff($email, $password);
        if ($staff) {
            echo json_encode(['message' => 'Login successful']);
        } else {
            echo json_encode(['message' => 'Invalid email or password']);
        }
    }
    
    //getting all staff viewing
    public function getAllStaff(){
        $staff = $this->adminModel->getAllStaff();
        if($staff){
            echo json_encode($staff);
        } else{
            echo json_encode(['message' => 'No staff found'. $staff]);
        }
    }
    //deleting staff
    public function deleteStaff($id) {
        $this->adminModel->deleteStaff($id);
        echo json_encode(['message' => 'Staff deleted']);
    }
}
?>