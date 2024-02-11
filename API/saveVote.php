<?php


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('connection.php');
$response = array();
$jsonData = file_get_contents('php://input');

if ($jsonData) {

    $data = json_decode($jsonData);
    $psId = (int)$data->psId;

    $sql = $con->prepare("UPDATE ps_party SET valid_vote_count = ?,rejected_vote_count = ?,no_show_count = ? WHERE psp_id = ? AND is_deleted = false");

    foreach ($data->party as $party) {

        $validVote = (int)$party->validCount;
        $rejectedVote = (int)$party->rejectedCount;
        $noShowVote = (int)$party->noShowCount;
        $pspId = (int)$party->pspId;

        $sql->bind_param('iiii', $validVote, $rejectedVote, $noShowVote, $pspId);
        $sql->execute();
    }

    $sql = $con->prepare("UPDATE polling_station SET is_submitted = true WHERE ps_id = ? AND is_deleted = false");
    $sql->bind_param('i', $psId);
    $sql->execute();

    $response['success'] = true;
    $response['message'] = "Successfully submitted data.";
} else {
    $response['success'] = false;
    $response['message'] = "Failed to submit data.";
}
echo json_encode($response, JSON_PRETTY_PRINT);
