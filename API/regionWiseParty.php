<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('connection.php');
$response = array();

if (isset($_GET['rId'])) {
    $r_id = $_GET['rId'];
    $response['party'] = array();
    $response['selectedRegionVote'] = 0;
    $response['totalRegionVote'] = 0;

    $sql = $con->prepare("SELECT c_id FROM constituency WHERE r_id = ? AND is_deleted = false");
    $sql->bind_param('i', $r_id);
    $sql->execute();
    $res = $sql->get_result();
    if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            $sql2 = $con->prepare("SELECT ps_id FROM polling_station WHERE c_id = ? AND is_deleted = false");
            $sql2->bind_param('i', $row['c_id']);
            $sql2->execute();
            $res2 = $sql2->get_result();
            if ($res2->num_rows > 0) {
                while ($row2 = $res2->fetch_assoc()) {
                    $sql3 = $con->prepare("SELECT PSP.psp_id,P.p_id,P.p_name,PSP.valid_vote_count,PSP.rejected_vote_count,PSP.no_show_count FROM ps_party AS PSP LEFT JOIN party AS P USING(p_id) WHERE PSP.ps_id = ? AND PSP.is_deleted=false");
                    $sql3->bind_param('i', $row2['ps_id']);
                    $sql3->execute();
                    $res3 = $sql3->get_result();
                    if ($res3->num_rows > 0) {
                        while ($row3 = $res3->fetch_assoc()) {
                            $party = array();
                            $party['pspId'] = $row3['psp_id'];
                            $party['pId'] = $row3['p_id'];
                            $party['pName'] = $row3['p_name'];
                            $party['validCount'] = $row3['valid_vote_count'];
                            $party['rejectCount'] = $row3['rejected_vote_count'];
                            $party['unshowCount'] = $row3['no_show_count'];
                            array_push($response['party'], $party);

                            $response['selectedRegionVote'] = $response['selectedRegionVote'] + $row3['valid_vote_count'] + $row3['rejected_vote_count'] + $row3['no_show_count'];
                        }
                        $response['success'] = true;
                        $response['message'] = 'Data fetched successfully.';
                    }
                }
            }
        }
    } else {
        $response['success'] = false;
        $response['message'] = 'Failed fetch to selected region data.';
    }

    $sql4 = $con->prepare("SELECT SUM(valid_vote_count + rejected_vote_count + no_show_count) AS totalVoteCount FROM ps_party WHERE is_deleted=false");
    $sql4->execute();
    $res4 = $sql4->get_result();
    $row4 = $res4->fetch_assoc();
    $response['totalRegionVote'] = (int)$row4['totalVoteCount'];

    echo json_encode($response, JSON_PRETTY_PRINT);
} else {
    $response['success'] = false;
    $response['message'] = 'Region id is not provided.';
    echo json_encode($response, JSON_PRETTY_PRINT);
}
