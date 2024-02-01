<?php
require_once("connection.php");

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $table = $_POST['table'];

    if ($table == 'user') {
        $stmt = $con->prepare("SELECT * FROM user WHERE u_id = ?");
    } 
    elseif ($table == 'region') {
        $stmt = $con->prepare("SELECT * FROM region WHERE r_id = ?");
    } 
    elseif ($table == 'cons') {
        $stmt = $con->prepare("SELECT c_id, c_name, r_id FROM constituencies WHERE c_id = ?");
    } 
    elseif ($table == 'ps') {
        $stmt = $con->prepare("SELECT PS.ps_id, PS.ps_name,PS.c_id,C.r_id FROM polling_station AS PS INNER JOIN constituencies AS C ON PS.c_id=C.c_id  WHERE ps_id = ?");
    } 
    elseif ($table == 'party') {
        $stmt = $con->prepare("SELECT p_id, p_name, valid_vote_count, rejected_vote_count, no_show_count, ps_name FROM party AS P INNER JOIN polling_station AS PS ON P.ps_id = PS.ps_id WHERE p_id = ?");
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(['status' => false]);
    }

    $stmt->close();
}
