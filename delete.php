<?php
require_once("connection.php");

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$id = $_POST['id'];
$table = $_POST['table'];
print_r($id);

// sql to delete a record
if($table == 'user'){
    $sql = "DELETE FROM user WHERE u_id = $id";
}
elseif($table == 'region'){
    $sql = "DELETE FROM region WHERE r_id = $id";
}
elseif($table == 'cons'){
    $sql = "DELETE FROM constituencies WHERE c_id = $id";
}
elseif($table == 'ps'){
    $sql = "DELETE FROM polling_station WHERE ps_id = $id";
}
elseif($table == 'party'){
    $sql = "DELETE FROM party WHERE p_id = $id";
}

$con->query($sql);

$con->close();
