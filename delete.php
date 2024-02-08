<?php
require_once("connection.php");
session_start();

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$id = $_POST['id'];
$table = $_POST['table'];

// sql to delete a record
if ($table == 'user') {
    // Check if the user is linked to a polling station
    $checkSql = "SELECT COUNT(*) AS count FROM polling_station WHERE u_id = $id";
    $result = $con->query($checkSql);
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        $_SESSION['message'] = 'no_delete';
    } else {
        $sql = "UPDATE user SET is_deleted = true WHERE u_id = $id";
        $con->query($sql);
    }
} elseif ($table == 'region') {
    $sql = "UPDATE region SET is_deleted = true WHERE r_id = $id";
} elseif ($table == 'cons') {
    $sql = "UPDATE constituency SET is_deleted = true WHERE c_id = $id";
} elseif ($table == 'ps') {
    $sql = "UPDATE polling_station SET is_deleted = true WHERE ps_id = $id";
} elseif ($table == 'party') {
    $sql = "UPDATE party SET is_deleted = true WHERE p_id = $id";
}

$con->query($sql);

$con->close();
