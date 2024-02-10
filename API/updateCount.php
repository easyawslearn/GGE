<?php


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('connection.php');
$response = array();
$result = '';

if (isset($_POST['pspId'])) {

    $psp_id = $_POST['pspId'];
    $validVote = $_POST['validCount'];
    $rejectedVote = $_POST['rejectedCount'];
    $noShowVote = $_POST['noShowCount'];

    $sql = $con->prepare("UPDATE ps_party SET valid_vote_count = ?,rejected_vote_count = ?,no_show_count = ? WHERE psp_id = ? AND is_deleted = false");
    $sql->bind_param('iiii', $validVote, $rejectedVote, $noShowVote, $psp_id);
    $sql->execute();

    $response['success'] = true;
    $response['message'] = "Successfully submitted data.";
} else {
    $response['success'] = false;
    $response['message'] = "Failed to submit data.";
}
echo json_encode($response, JSON_PRETTY_PRINT);
