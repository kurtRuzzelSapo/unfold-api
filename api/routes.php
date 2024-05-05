<?php
header("Access-Control-Allow-Origin: *");
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    http_response_code(200);
    exit;
}
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
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

            case 'studentskill':
                // Return JSON-encoded data for getting jobs
                if (count($request) > 1) {
                    echo json_encode($get->get_skill($request[1]));
                } else {
                    echo json_encode($get->get_skill());
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
                if (count($request) > 1) {
                    echo json_encode($get->get_accomplishment($request[1]));
                } else {
                    echo json_encode($get->get_accomplishment());
                }
                break;

            case 'studentaboutme':
                // Return JSON-encoded data for getting jobs
                if (count($request) > 1) {
                    echo json_encode($get->get_aboutme($request[1]));
                } else {
                    echo json_encode($get->get_aboutme());
                }

                break;
            case 'view-portfolio':
                echo json_encode($get->view_portfolio($request[1]));

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
                echo json_encode($post->add_skill($data));
                break;
            case 'editskill':
                // Return JSON-encoded data for adding employees
                echo json_encode($post->edit_skill($data, $request[1]));
                break;
            case 'deleteskill':
                // Return JSON-encoded data for adding employees
                echo json_encode($post->delete_skill($request[1]));
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

            case 'addaccomplishment':
                // Return JSON-encoded data for adding employees
                echo json_encode($post->add_accomplishments($_POST));
                break;
            case 'editaccomplishment':
                // Return JSON-encoded data for adding employees
                echo json_encode($post->edit_accomplishments($data, $request[1]));
                break;
            case 'deleteaccomplishment':
                // Return JSON-encoded data for adding employees
                echo json_encode($post->delete_accomplishments($request[1]));
                break;

            case 'addaboutme':
                // Return JSON-encoded data for adding employees
                echo json_encode($post->add_aboutme($_POST));
                break;
            case 'editaboutme':
                // Return JSON-encoded data for adding employees
                echo json_encode($post->edit_aboutme($data, $request[1]));
                break;
            case 'deleteaboutme':
                // Return JSON-encoded data for adding employees
                echo json_encode($post->delete_aboutme($request[1]));
                break;
            case 'add-project':
                echo json_encode($post->add_project($_POST));
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
                echo json_encode($delete->delete_student($data));
                break;
        }

        break;

    default:
        // Return a 404 response for unsupported HTTP methods
        echo "Method not available";
        http_response_code(404);
        break;
}