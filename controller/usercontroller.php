<?php
require_once '../model/user.php';

class UserController{
    private $userModel;

    public function __construct() 
    {
        $this->userModel = new UserModel();
    }

    //creating user
    public function createUser($input) {
        $name = $input['name'];
        $email = $input['email'];
        $password = $input['password'];
        //verify email
        $existingEmail = $this->userModel->getUserByEmail($email);
        if($existingEmail){
            echo json_encode(['message' => 'Email already exists']);
            return;
        }
        $this->userModel->createUser($name, $email, $password);
        echo json_encode(['message' => 'Account created', 'user' => $input]);
    }

    //login user
    public function loginUser($input) {
        $email = $input['email'];
        $password = $input['password'];
        $user = $this->userModel->loginuser($email, $password);
        if(password_verify($password, $user['password'])){
            $token = JwtHelper::encode(array(
                'id' => $user['id'],
                'email' => $user['email']
            ));
            echo json_encode(['message' => 'Login successful', 'token' => $token]);
        } else{
            echo json_encode(['message' => 'Email or password is incorrect']);
        }   
    }

    //change pass
    public function changepassword($id, $oldpassword, $newpassword, $confirmpassword){
        $user = $this->userModel->getUserByid($id);
        if(!$user){
            echo json_encode(['message' => 'User not found']);
            return;
        }
        if(password_verify($oldpassword, $user['password'])){
            if($newpassword === $confirmpassword){
                $hashed_password = password_hash($newpassword, PASSWORD_DEFAULT);
                $this->userModel->Updateuserpassword($id, $hashed_password);
                echo json_encode(['message' => 'Password change successfully']);
            } else{
                echo json_encode(['message' => 'New password and confirm password do not match']);
            }
        } else{
            echo json_encode(['message' => 'Invalid password']);
        }
    }

    
    public function logoutUser(){
        echo json_encode(['message' => 'Logout successful']);
    }
}
?>