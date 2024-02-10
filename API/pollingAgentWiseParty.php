<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('connection.php');
$response = array();

if ($con) {
    if (isset($_GET['userId'])) {
        $userId = $_GET['userId'];
        $sql = $con->prepare("SELECT ps_id,ps_name,c_id FROM polling_station WHERE u_id = ? AND is_deleted = false");
        $sql->bind_param('i', $userId);
        $sql->execute();
        $result = $sql->get_result();

        $row = $result->fetch_assoc();

        if ($row > 0) {
            $response['success'] = true;
            $response['message'] = 'Successfully fetched data.';
            $response['psId'] = $row['ps_id'];
            $response['psName'] = $row['ps_name'];
            $response['cId'] = $row['c_id'];

            $sql = $con->prepare("SELECT r_id,c_name FROM constituency WHERE c_id = ? AND is_deleted = false");
            $sql->bind_param('i', $response['cId']);
            $sql->execute();
            $result = $sql->get_result();

            $row = $result->fetch_assoc();
            $response['cName'] = $row['c_name'];
            $response['rId'] = $row['r_id'];

            $sql = $con->prepare("SELECT r_name FROM region WHERE r_id = ? AND is_deleted = false");
            $sql->bind_param('i', $response['rId']);
            $sql->execute();
            $result = $sql->get_result();

            $row = $result->fetch_assoc();
            $response['rName'] = $row['r_name'];

            $sql = $con->prepare("SELECT PSP.psp_id,P.p_id,P.p_name,PSP.valid_vote_count,PSP.rejected_vote_count,PSP.no_show_count FROM ps_party AS PSP LEFT JOIN party AS P USING(p_id) WHERE PSP.ps_id = ? AND PSP.is_deleted = false");
            $sql->bind_param('i', $response['psId']);
            $sql->execute();
            $result = $sql->get_result();

            $response['party'] = array(); // Initialize the 'party' array

            while ($row = $result->fetch_assoc()) {
                $party = array();
                $party['pspId'] = $row['psp_id'];
                $party['pId'] = $row['p_id'];
                $party['pName'] = $row['p_name'];
                $party['validCount'] = $row['valid_vote_count'];
                $party['rejectCount'] = $row['rejected_vote_count'];
                $party['unshowCount'] = $row['no_show_count'];
                array_push($response['party'], $party); // Add each party to the 'party' array
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to fetch data.';
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'No user id provided.';
    }

    echo json_encode($response, JSON_PRETTY_PRINT);
} else {
    echo "Failed to connect with database";
}
