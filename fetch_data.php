<?php
require_once("connection.php");

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $table = $_POST['table'];

    if ($table == 'user') {
        $stmt = $con->prepare("SELECT * FROM user WHERE u_id = ? AND is_deleted = false");
    } 
    elseif ($table == 'region') {
        $stmt = $con->prepare("SELECT * FROM region WHERE r_id = ? AND is_deleted = false");
    } 
    elseif ($table == 'cons') {
        $stmt = $con->prepare("SELECT c_id, c_name, r_id FROM constituencies WHERE c_id = ? AND is_deleted = false");
    } 
    elseif ($table == 'ps') {
        $stmt = $con->prepare("SELECT PS.ps_id, PS.ps_name,PS.c_id,C.r_id FROM polling_station AS PS INNER JOIN constituencies AS C ON PS.c_id=C.c_id  WHERE ps_id = ? AND PS.is_deleted = false");
    } 
    elseif ($table == 'party') {
        $stmt = $con->prepare("SELECT P.p_id, P.p_name, P.ps_id,PS.c_id,C.r_id FROM party AS P INNER JOIN polling_station AS PS ON P.ps_id = PS.ps_id INNER JOIN constituencies AS C ON PS.c_id=C.C_id WHERE p_id = ? AND P.is_deleted = false");
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
