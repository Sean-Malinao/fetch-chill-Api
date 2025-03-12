<?php
require_once '../model/appointment.php';
class AppointmentController {
    private $appointment;

    public function __construct()
    {
        $this->appointment = new Appointment();
    }

    public function GetAppointment($id){
        $appointment = $this->appointment->GetAppointment($id);
        if($appointment){
            echo json_encode($appointment);
        } else{
            echo json_encode(['message' => 'No appointment found']);
        }
    }

    public function GetAllAppointments(){
        $appointments = $this->appointment->GetAllAppointments();
        if($appointments){
            echo json_encode($appointments);
        } else{
        echo json_encode(['message' => 'No appointment found'. $appointments]);
        } 
    }

    public function CreateAppointment($input){
        $user_id = $input['user_id'];
        $service_type = $input['service_type'];
        $appointment_date = $input['appointment_date'];
        $appointment_time = $input['appointment_time'];
        $this->appointment->CreateAppointments($user_id, $service_type, $appointment_date, $appointment_time);
    }

    public function UpdateAppointmentsStatus($id, $input){
        $appointment = $this->appointment->GetAppointment($id);
        if(!$appointment){
            echo json_encode(['message' => 'No appointment found']);
            return;
        }
        $status = $input['status'];
        $this->appointment->UpdateStatus($status, $id);
        echo json_encode(['message' => 'Appointment status updated']);
    }

}
?>