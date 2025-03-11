<?php
header("Content-Type: application/json");

require_once '../controller/appointmentcontroller.php';

$requestMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';

if (strpos($contentType, 'application/json') === false) {
    echo json_encode(['message' => 'Invalid json content type.']);
    exit;
}
$input = json_decode(file_get_contents('php://input'), true);
$appointmentController = new AppointmentController();

function handleappointments($appointmentController, $requestMethod, $uri, $input) {
    switch ($requestMethod) {
        case 'GET':
            if (preg_match('/\/appointment\/(\d+)/', $uri, $matches)) {
                $appointmentController->GetAppointment($matches[1]);
            } elseif (preg_match('/\/appointment/', $uri)) {
                $appointmentController->GetAllAppointments();
            } else {
                echo json_encode(['message' => 'No appointment found']);
            }
            break;
        case 'POST':
            if(preg_match('/\/appointment/', $uri)){
                $appointmentController->CreateAppointment($input);
            } else {
                echo json_encode(['message' => 'Invalid endpoint']);
            }
            break;
        case 'PATCH':
            if (preg_match('/\/appointment\/(\d+)/', $uri, $matches)) {
                $appointmentController->UpdateAppointmentsStatus($matches[1], $input);
            } else {
                echo json_encode(['message' => 'Invalid endpoint']);
            }
            break;
            default:
            echo json_encode(['message' => 'Invalid request method']);
    }
    
}
if (preg_match('/\/appointment/', $uri)) {
    handleappointments($appointmentController, $requestMethod, $uri, $input);
} else {
    echo json_encode(['message' => 'Invalid endpoint']);
}
?>