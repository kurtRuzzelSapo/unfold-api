<?php
header("Access-Control-Allow-Origin: *");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
    header("Access-Control-Allow-Headers: Content-Type");
    http_response_code(200);
    exit;
}
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
http_response_code(200);

require_once "./modules/get.php";
require_once "./modules/post.php";
require_once "./modules/delete.php";
require_once "./config/database.php";

$con = new Connection();
$pdo = $con->connect();

$get = new Get($pdo);
$post = new Post($pdo);
$delete = new Delete($pdo);

if (isset($_REQUEST['request'])) {
    // Split the request into an array based on '/'
    $request = explode('/', $_REQUEST['request']);
} else {
    // If 'request' parameter is not set, return a 404 response
    echo "Not Found";
    http_response_code(404);
}

// Handle requests based on HTTP method
switch ($_SERVER['REQUEST_METHOD']) {
        // Handle GET requests
    case 'GET':
        switch ($request[0]) {
            case 'students':
                // Return JSON-encoded data for getting employees
                if (count($request) > 1) {
                    echo json_encode($get->get_students($request[1]));
                } else {
                    echo json_encode($get->get_students());
                }
                break;
                break;
                case 'studentservice':
                // Return JSON-encoded data for getting jobs
                if (count($request) > 1) {
                    echo json_encode($get->get_service($request[1]));
                } else {
                    echo json_encode($get->get_service());
                }
                break;
            case 'studentinterest':
                // Return JSON-encoded data for getting jobs
                if (count($request) > 1) {
                    echo json_encode($get->get_interest($request[1]));
                } else {
                    echo json_encode($get->get_interest());
                }
                break;

            case 'studentaccomplisments':
                // Return JSON-encoded data for getting jobs
                    echo json_encode($get->view_portfolio($_GET));
                break;

            // case 'studentaboutme':
            //     echo json_encode($get->view_portfolio($_GET));
            //     break;

            // Return JSON-encoded data for getting jobs
                // if (count($request) > 1) {
                //     echo json_encode($get->get_aboutme($request[1]));
                // } else {
                //     echo json_encode($get->get_aboutme());
                // }    
            case 'view-portfolio':
                echo json_encode($get->view_portfolio($_GET));

                break;
            case 'get-project':
                echo json_encode($get->get_project($_GET));

                break;
            case 'get-skill':
                echo json_encode($get->get_skill($_GET));

                break;
            case 'get-contact':
                echo json_encode($get->get_contact($_GET));

                break;
            case 'get-accomplishment':
                echo json_encode($get->get_accomplishment($_GET));

                break;
            case 'get-about':
                echo json_encode($get->get_about($_GET));

                break;

            case 'get-all-students':
                echo json_encode($get->get_all_students());
                break;

            default:
                // Return a 403 response for unsupported requests
                echo "This is forbidden";
                http_response_code(403);
                break;
        }
        break;
        // Handle POST requests    
    case 'POST':
        // Retrieves JSON-decoded data from php://input using file_get_contents
        $data = json_decode(file_get_contents("php://input"));
        switch ($request[0]) {
            case 'addstudent':
                // Return JSON-encoded data for adding employees
                echo json_encode($post->add_students($data));
                break;
            case 'editstudent':
                // Return JSON-encoded data for adding employees
                echo json_encode($post->edit_students($data, $request[1]));
                break;
            case 'deletestudent':
                // Return JSON-encoded data for adding employees
                echo json_encode($post->delete_students($request[1]));
                break;

            case 'addskill':
                // Return JSON-encoded data for adding employees
                echo json_encode($post->add_skill($_POST));
                break;
            case 'edit-skill':
                // Return JSON-encoded data for adding employees
                echo json_encode($post->edit_skill($_POST));
                break;

            case 'addinterest':
                // Return JSON-encoded data for adding employees
                echo json_encode($post->add_interest($data));
                break;
            case 'editinterest':
                // Return JSON-encoded data for adding employees
                echo json_encode($post->edit_interest($data, $request[1]));
                break;
            case 'deleteinterest':
                // Return JSON-encoded data for adding employees
                echo json_encode($post->delete_interest($request[1]));
                break;
            case 'add-contact':
                    echo json_encode($post->add_contact($_POST));
                    break;
            case 'addservice':
                    echo json_encode($post->add_service($_POST));
                    break;
            case 'editservice':
                    echo json_encode($post->edit_service($_POST));
                break;
            case 'addaccomplishment':
                // Return JSON-encoded data for adding employees
                echo json_encode($post->add_accomplishments($_POST));
                break;
            // case 'editaccomplishment':
            //     // Return JSON-encoded data for adding employees
            //     echo json_encode($post->edit_accomplishments($data, $request[1]));
            //     break;
            case 'edit-accomplishment':
                echo json_encode($post->edit_accomplishments($_POST));
                break;

            case 'addaboutme':
                // Return JSON-encoded data for adding employees
                echo json_encode($post->add_aboutme($_POST));
                break;

            case 'edit-about':
                // Return JSON-encoded data for adding employees
                echo json_encode($post->edit_aboutme($_POST));
                
                break;
            case 'deleteaboutme':
                // Return JSON-encoded data for adding employees
                echo json_encode($post->delete_aboutme($request[1]));
                break;
            case 'add-project':
                echo json_encode($post->add_project($_POST));
                break;
               
            case 'edit-project':
                echo json_encode($post->edit_project($_POST));
                break;
            case 'edit-contact':
                echo json_encode($post->edit_contact($_POST));
                break;
            
                
                
            case 'login':
                // Return JSON-encoded data for adding employees
                echo json_encode($post->login($data));
                break;

            default:
                // Return a 403 response for unsupported requests
                echo "This is forbidden";
                http_response_code(403);
                break;
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        switch ($request[0]) {
            case 'delete-student':
                echo json_encode($delete->delete_student($_GET));
                break;
                case 'delete-project':
                    // Check if accomplishment ID is provided in the URL
                    if (isset($request[1])) {
                        // Call the delete_accomplishment method in Post class
                        echo json_encode($post->delete_project($request[1]));
                    } else {
                        // If accomplishment ID is not provided, return error response
                        echo json_encode($post->sendPayload(null, "error", "Skill ID not provided.", null));
                    }
                break;
            case 'delete-accomplishment':
            // Check if accomplishment ID is provided in the URL
            if (isset($request[1])) {
                // Call the delete_accomplishment method in Post class
                echo json_encode($post->delete_accomplishment($request[1]));
            } else {
                // If accomplishment ID is not provided, return error response
                echo json_encode($post->sendPayload(null, "error", "Accomplishment ID not provided.", null));
            }
            break;
            case 'delete-skill':
                // Check if accomplishment ID is provided in the URL
                if (isset($request[1])) {
                    // Call the delete_accomplishment method in Post class
                    echo json_encode($post->delete_skill($request[1]));
                } else {
                    // If accomplishment ID is not provided, return error response
                    echo json_encode($post->sendPayload(null, "error", "Skill ID not provided.", null));
                }
            break;
            case 'delete-contact':
                // Check if accomplishment ID is provided in the URL
                if (isset($request[1])) {
                    // Call the delete_accomplishment method in Post class
                    echo json_encode($post->delete_contact($request[1]));
                } else {
                    // If accomplishment ID is not provided, return error response
                    echo json_encode($post->sendPayload(null, "error", "Contact ID not provided.", null));
                }
            break;
            case 'delete-service':
                // Check if accomplishment ID is provided in the URL
                if (isset($request[1])) {
                    // Call the delete_accomplishment method in Post class
                    echo json_encode($post->delete_service($request[1]));
                } else {
                    // If accomplishment ID is not provided, return error response
                    echo json_encode($post->sendPayload(null, "error", "Service ID not provided.", null));
                }
            break;

    
        }

        break;

    default:
        // Return a 404 response for unsupported HTTP methods
        echo "Method not available";
        http_response_code(404);
        break;
}
