<?php
// Check if it's an OPTIONS request and handle it
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: http://localhost:3000");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Allow-Credentials: true");
    http_response_code(200);
    exit;
}

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

$conn = mysqli_connect('localhost', "root", "", "animesensei");

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case "POST":
        $user_data = json_decode(file_get_contents('php://input'));

        $check_user_query = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $check_user_query->bind_param("s", $user_data->email);

        if ($check_user_query->execute()) {
            $result = $check_user_query->get_result();
            $user = $result->fetch_assoc();

            if ($user) {
                if ($user_data->password == $user['password']) {
                    $response = ['status' => 1, 'user' => $user];
                } else {
                    $response = ['status' => 0, 'error' => 'Incorrect password'];
                }
            } else {
                $response = ['status' => 0, 'error' => 'User not found', 'password' => $user_data->password];
            }
        } else {
            $response = ['status' => 0, 'error' => 'Query execution error'];
        }

        $check_user_query->close();
        $conn->close();

        header('Content-Type: application/json');
        echo json_encode($response);
        break;
    default:
        header("HTTP/1.1 405 Method Not Allowed");
        header("Allow: POST");
        break;
}

?>