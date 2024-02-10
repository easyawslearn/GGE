<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('connection.php');
$response = array();

if ($con) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = $con->prepare("SELECT u_id,user_role FROM user WHERE username = ? AND password = ? AND is_deleted=false");

        $sql->bind_param('ss', $username, $password);
        $sql->execute();
        $result = $sql->get_result();
        $row = $result->fetch_assoc();

        if ($row > 0) {
            $response['success'] = true;
            $response['message'] = 'Login successful';
            $response['userId'] = $row['u_id'];
            $response['userRole'] = $row['user_role'];
        } else {
            $response['success'] = false;
            $response['message'] = 'Login unsuccessful';
        }
    } else {
        $response['message'] = 'No username or password provided';
    }

    echo json_encode($response, JSON_PRETTY_PRINT);
} else {
    echo "Failed to connect with database";
}
