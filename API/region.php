<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('connection.php');
$response = array();

if ($con) {
    
    $sql = $con->prepare("SELECT r_id,r_name FROM region WHERE is_deleted=false");
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        $response['success'] = true;
        $response['message'] = 'Successfully fetched region.';
        $response['region'] = array();
        while($row = $result->fetch_assoc()){
            $region = array();
            $region['rId'] = $row['r_id'];
            $region['rName'] = $row['r_name'];
            array_push($response['region'],$region);
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Failed to fetch region.';
    }

    echo json_encode($response, JSON_PRETTY_PRINT);
} else {
    echo "Failed to connect with database";
}
?>
