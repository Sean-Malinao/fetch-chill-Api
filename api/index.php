<?php
header("Content-Type: application/json");

require_once '../controller/appointmentcontroller.php';
require_once '../controller/petrecordscontroller.php';
require_once '../controller/admincontroller.php';

$requestMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';

if (strpos($contentType, 'application/json') === false) {
    echo json_encode(['message' => 'Invalid json content type.']);
    exit;
}
$input = json_decode(file_get_contents('php://input'), true);
$appointmentController = new AppointmentController();
$petrecordsController = new PetController();
$adminController = new AdminController();

//handle appointments
function handleappointments($appointmentController, $requestMethod, $uri, $input) {
    switch ($requestMethod) {
        case 'GET': // Handle GET requests for appointments
            if (preg_match('/\/appointment\/(\d+)/', $uri, $matches)) {
                $appointmentController->GetAppointment($matches[1]);
            } elseif (preg_match('/\/appointment/', $uri)) {
                $appointmentController->GetAllAppointments();
            } else {
                echo json_encode(['message' => 'No appointment found']);
            }
            break;
        case 'POST': // Handle POST requests to create a new appointment
            if(preg_match('/\/appointment/', $uri)){
                $appointmentController->CreateAppointment($input);
            } else {
                echo json_encode(['message' => 'Invalid endpoint']);
            }
            break;
        case 'PATCH': // Handle PATCH requests to update an appointment's status
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
    //handle petrecords
    function handlepetrecords($petrecordsController, $requestMethod, $uri, $input) {
        switch ($requestMethod) {
            case "POST": // Handle POST requests to create a new pet record
                if (preg_match('/\/petrecords/', $uri)) {
                    $petrecordsController->createPet($input);
                } else {
                    echo json_encode(['message' => 'Invalid endpoint']);
                }
                break;
            case "PUT":  // Handle PUT requests to update an existing pet record
                if (preg_match('/\/petrecords\/(\d+)/', $uri, $matches)) {
                    $petrecordsController->updatePet($matches[1], $input);
                } else {
                    echo json_encode(['message' => 'Invalid endpoint']);
                }
                break;
            case "DELETE":   // Handle DELETE requests to delete a pet record
                if (preg_match('/\/petrecords\/(\d+)/', $uri, $matches)) {
                    $petrecordsController->deletePet($matches[1]);
                } else {
                    echo json_encode(['message' => 'Invalid endpoint']);
                }
                break;
            case "GET": //for searching pet by owner name
                if (isset($_GET['ownername'])) {
                    $petrecordsController->searchPetByOwner($_GET['ownername']);
                } elseif (preg_match('/\/petrecords\/(\d+)/', $uri, $matches)) {
                    $petrecordsController->getPet($matches[1]);
                } else {
                    $petrecordsController->getAllPets();
                }
                break;
                default:
                echo json_encode(["message" => "Invalid request method"]);
                break;
            }
        }
    function handlestaff($adminController, $requestMethod, $uri, $input) {
        switch ($requestMethod) {
            case "POST": // Handle POST requests to create a new staff
                if(preg_match('/\/staff\/login/', $uri)) {
                    $adminController->loginStaff($input);
                }elseif (preg_match('/\/staff/', $uri)) {
                    $adminController->createStaff($input);
                }else {
                    echo json_encode(['message' => 'Invalid endpoint']);
                }
                break;
            case "GET": // Handle GET requests to get all staff
                if (preg_match('/\/staff/', $uri)) {
                    $adminController->getAllStaff();
                } else {
                    echo json_encode(['message' => 'Invalid endpoint']);
                }
                break;
            case "DELETE": // Handle DELETE requests to delete a staff
                if (preg_match('/\/staff\/(\d+)/', $uri, $matches)) {
                    $adminController->deleteStaff($matches[1]);
                } else {
                    echo json_encode(['message' => 'Invalid endpoint']);
                }
                break;
                default:
        }
    }
// Determine which endpoint is being accessed and call the appropriate handler function
if (preg_match('/\/appointment/', $uri)) {
    handleappointments($appointmentController, $requestMethod, $uri, $input);
} elseif (preg_match('/\/petrecords/', $uri)) {
    handlepetrecords($petrecordsController, $requestMethod, $uri, $input);
} elseif (preg_match('/\/staff/', $uri)) {
    handlestaff($adminController, $requestMethod, $uri, $input);
}else {
    echo json_encode(['message' => 'Invalid endpoint']);
}
?>