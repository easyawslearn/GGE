<?php
$isChecked = $_POST['isChecked'];
$partyId = $_POST['partyId'];
$ps_id = $_POST['ps_id'];

require_once("connection.php");

$stmt = $con->prepare("SELECT * FROM ps_party WHERE p_id = ? AND ps_id = ?");
$stmt->bind_param("ii", $partyId, $ps_id);
$stmt->execute();
$result = $stmt->get_result();

if ($isChecked == 'true') {
    if ($result->num_rows > 0) {
        $stmt = $con->prepare("UPDATE ps_party SET is_deleted = false WHERE p_id = ? AND ps_id = ?");
        $stmt->bind_param("ii", $partyId, $ps_id);
        $stmt->execute();
        echo "Record updated successfully Update";
    } else {
        $stmt = $con->prepare("INSERT INTO ps_party (p_id,ps_id,is_deleted) VALUES (?,?, false)");
        $stmt->bind_param("ii", $partyId, $ps_id);
        $stmt->execute();
        echo "Record updated successfully INSERT";
    }
} else {
    $stmt = $con->prepare("UPDATE ps_party SET is_deleted = 1 WHERE p_id = ? AND ps_id = ?");
    $stmt->bind_param("ii", $partyId, $ps_id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo "Record updated successfully Delete";
    } else {
        echo "Error updating record: " . $stmt->error;
    }
}


$stmt->close();
$con->close();
