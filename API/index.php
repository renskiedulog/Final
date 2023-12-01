<?php
// Check if it's an OPTIONS request and handle it
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Allow-Headers: Content-Type");
    header("Access-Control-Allow-Credentials: true");
    http_response_code(200);
    exit;
}

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");

$conn = mysqli_connect('localhost', "root", "", "animesensei");

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case "POST":
        if (isset($_GET['user'])) {
            $action = $_GET['user'];
            if ($action === 'login') {
                authLogin($conn);
            } else if ($action === 'register') {
                authRegister($conn);
            }
        }
        if (isset($_GET['bookmark'])) {
            $action = $_GET['bookmark'];
            if ($action === 'add') {
                addBookmark($conn);
            }
        }
        break;
    default:
        header("HTTP/1.1 405 Method Not Allowed");
        header("Allow: POST");
        break;
}
function authLogin($conn)
{
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
}

function authRegister($conn)
{
    $user_data = json_decode(file_get_contents('php://input'));
    $checkEmailQuery = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $checkEmailQuery->bind_param("s", $user_data->email);
    $checkEmailQuery->execute();
    $existingUser = $checkEmailQuery->get_result()->fetch_assoc();
    $checkEmailQuery->close();
    if ($existingUser) {
        $response = ['status' => 0, 'error' => 'Email already exists.'];
    } else {
        $insertQuery = $conn->prepare("INSERT INTO users(firstname,lastname,email,password) VALUES(?, ?, ?, ?)");
        $insertQuery->bind_param("ssss", $user_data->firstname, $user_data->lastname, $user_data->email, $user_data->password);
        if ($insertQuery->execute()) {
            $seekUser = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
            $seekUser->bind_param("ss", $user_data->email, $user_data->password);
            if ($seekUser->execute()) {
                $result = $seekUser->get_result();
                $user = $result->fetch_assoc();
                $response = ['status' => 1, 'user' => $user];
                $seekUser->close();
            } else {
                $response = ['status' => 0, 'error' => 'Something went wrong. Please try again.'];
            }
        } else {
            $response = ['status' => 0, 'error' => 'Something went wrong. Please try again.'];
        }
        $insertQuery->close();
    }

    $conn->close();
    header('Content-Type: application/json');
    echo json_encode($response);
}

function addbookmark($conn)
{
    $bookmark_data = json_decode(file_get_contents('php://input'));

    $checkBookmarkQuery = $conn->prepare("SELECT * FROM bookmarks WHERE name = ? AND email = ?");
    $checkBookmarkQuery->bind_param("ss", $bookmark_data->name, $bookmark_data->email);
    $checkBookmarkQuery->execute();
    $existingBookmark = $checkBookmarkQuery->get_result()->fetch_assoc();
    $checkBookmarkQuery->close();

    if ($existingBookmark) {
        $response = ['status' => 0, 'message' => 'Bookmark already exists.'];
    } else {
        $insertBookmarkQuery = $conn->prepare("INSERT INTO bookmarks(name, email) VALUES(?, ?)");
        $insertBookmarkQuery->bind_param("ss", $bookmark_data->name, $bookmark_data->email);

        if ($insertBookmarkQuery->execute()) {
            $response = ['status' => 1, 'message' => 'Bookmark added successfully.'];
        } else {
            $response = ['status' => 0, 'message' => 'Something went wrong. Please try again.'];
        }

        $insertBookmarkQuery->close();
    }

    $conn->close();
    header('Content-Type: application/json');
    echo json_encode($response);
}


?>