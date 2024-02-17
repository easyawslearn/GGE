<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('connection.php');
$response = array();

if ($con) {
    if (isset($_GET['rId'])) {
        $r_id = $_GET['rId'];

        $sql = $con->prepare("SELECT c_id,c_name FROM constituency WHERE r_id = ? AND is_deleted=false");
        $sql->bind_param('i', $r_id);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            $response['success'] = true;
            $response['message'] = 'Successfully fetched constituency.';
            $response['data'] = new stdClass;
            $response['data']->constituency = array();
            while ($row = $result->fetch_assoc()) {
                $constituency = array();
                $constituency['cId'] = $row['c_id'];
                $constituency['cName'] = $row['c_name'];
                array_push($response['data']->constituency, $constituency);
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to fetch constituency.';
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Region id is not provided.';
    }

    echo json_encode($response, JSON_PRETTY_PRINT);
} else {
    echo "Failed to connect with database";
}
