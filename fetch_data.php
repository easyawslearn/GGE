<?php
require_once("connection.php");

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $table = $_POST['table'];

    if ($table == 'user') {
        $stmt = $con->prepare("SELECT * FROM user WHERE u_id = ? AND is_deleted = false");
    } elseif ($table == 'region') {
        $stmt = $con->prepare("SELECT * FROM region WHERE r_id = ? AND is_deleted = false");
    } elseif ($table == 'cons') {
        $stmt = $con->prepare("SELECT c_id, c_name, r_id FROM constituency WHERE c_id = ? AND is_deleted = false");
    } elseif ($table == 'ps') {
        $stmt = $con->prepare("SELECT PS.ps_id,PS.ps_name,PS.u_id,U.username FROM polling_station AS PS LEFT JOIN user AS U ON U.u_id=PS.u_id WHERE PS.ps_id = ? AND PS.is_deleted = false");
    } elseif ($table == 'party') {
        $stmt = $con->prepare("SELECT p_id,p_name FROM party WHERE p_id = ? AND is_deleted = false");
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
