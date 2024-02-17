<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('connection.php');
$response = array();

if ($con) {
    if (isset($_GET['psId'])) {
        $ps_id = $_GET['psId'];

        $sql = $con->prepare("SELECT PSP.psp_id,P.p_id,P.p_name,PSP.valid_vote_count,PSP.rejected_vote_count,PSP.no_show_count FROM ps_party AS PSP LEFT JOIN party AS P USING(p_id) WHERE PSP.ps_id = ? AND PSP.is_deleted=false");
        $sql->bind_param('i', $ps_id);
        $sql->execute();
        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            $response['success'] = true;
            $response['message'] = 'Successfully fetched party.';
            $response['data'] = new stdClass;
            $response['data']->party = array();
            while ($row = $result->fetch_assoc()) {
                $party = array();
                $party['pspId'] = $row['psp_id'];
                $party['pId'] = $row['p_id'];
                $party['pName'] = $row['p_name'];
                $party['validCount'] = $row['valid_vote_count'];
                $party['rejectCount'] = $row['rejected_vote_count'];
                $party['unShowCount'] = $row['no_show_count'];
                array_push($response['data']->party, $party);
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to fetch party.';
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Polling station id is not provided.';
    }

    echo json_encode($response, JSON_PRETTY_PRINT);
} else {
    echo "Failed to connect with database";
}
