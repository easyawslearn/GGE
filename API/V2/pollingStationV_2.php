<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('connection.php');
$response = array();

if ($con) {
    if (isset($_GET['cId'])) {
        $c_id = $_GET['cId'];

        $sql = $con->prepare("SELECT ps_id,ps_name FROM polling_station WHERE c_id = ? AND is_deleted=false");
        $sql->bind_param('i', $c_id);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            $response['success'] = true;
            $response['message'] = 'Successfully fetched polling station.';
            $response['data'] = new stdClass;
            $response['data']->pollingStation = array();
            while ($row = $result->fetch_assoc()) {
                $pollingStation = array();
                $pollingStation['psId'] = $row['ps_id'];
                $pollingStation['psName'] = $row['ps_name'];
                array_push($response['data']->pollingStation, $pollingStation);
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to fetch polling station.';
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Constituency id is not provided.';
    }

    echo json_encode($response, JSON_PRETTY_PRINT);
} else {
    echo "Failed to connect with database";
}
