<?php
require_once("connection.php");

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$id = $_POST['id'];
$table = $_POST['table'];

// sql to delete a record
if ($table == 'user') {
    $sql = "UPDATE user SET is_deleted = true WHERE u_id = $id";
} elseif ($table == 'region') {
    $sql = "UPDATE region SET is_deleted = true WHERE r_id = $id";
} elseif ($table == 'cons') {
    $sql = "UPDATE constituencies SET is_deleted = true WHERE c_id = $id";
} elseif ($table == 'ps') {
    $sql = "UPDATE polling_station SET is_deleted = true WHERE ps_id = $id";
} elseif ($table == 'party') {
    $sql = "UPDATE party SET is_deleted = true WHERE p_id = $id";
}

$con->query($sql);

$con->close();
