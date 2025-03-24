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

    
    public function changepassword($id, $oldpassword, $newpassword, $confirmpassword){
        $user = $this->adminModel->getAdminByid($id);
        if(!$user){
            echo json_encode(['message' => 'User not found']);
            return;
        }
        if(password_verify($oldpassword, $user['password'])){
            if($newpassword === $confirmpassword){
                $hashed_password = password_hash($newpassword, PASSWORD_DEFAULT);
                $this->adminModel->Updateadminpassword($id, $hashed_password);
                echo json_encode(['message' => 'Password change successfully']);
            } else{
                echo json_encode(['message' => 'New password and confirm password do not match']);
            }
        } else{
            echo json_encode(['message' => 'Invalid password']);
        }
    }
}
?>