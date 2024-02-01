<?php

require_once("connection.php");

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (isset($_GET['region_id'])) {
    $regionId = intval($_GET['region_id']);

    $stmt = $con->prepare("SELECT c_id, c_name FROM constituencies WHERE r_id = ? ORDER BY c_name ASC");
    $stmt->bind_param("i", $regionId);

    $stmt->execute();
    $consti = $stmt->get_result();

    while ($row = $consti->fetch_assoc()) {
        echo '<option value="' . $row['c_id'] . '">' . $row['c_name'] . '</option>';
    }
    } elseif (isset($_GET['constituency_id'])) {

    $constituencyId = intval($_GET['constituency_id']);  

    $stmt = $con->prepare("SELECT ps_id, ps_name FROM polling_station WHERE c_id = ? ORDER BY ps_name ASC");
    $stmt->bind_param("i", $constituencyId);

    $stmt->execute();

    $ps = $stmt->get_result();

    while ($row = $ps->fetch_assoc()) {
        echo '<option value="' . $row['ps_id'] . '">' . $row['ps_name'] . '</option>';
    }

    $stmt->close();
}
