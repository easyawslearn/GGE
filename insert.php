<?php
session_start();
require_once("connection.php");

if ($_GET['call'] == 'region') {

    if (isset($_POST['submit'])) {
        $r_name = $_POST['r_name'];

        $stmt = $con->prepare("SELECT r_name FROM region WHERE r_name = ? AND is_deleted = false");
        $stmt->bind_param("s", $r_name);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $resp = "duplicate";
        } else {
            $stmt = $con->prepare("INSERT INTO region (r_name) VALUES (?)");
            $stmt->bind_param("s", $r_name);
            $stmt->execute();

            if ($stmt->affected_rows === 0) {
                $resp = "error";
            } else {
                $resp = "success";
            }
        }
        $_SESSION['message'] = $resp;
        header("Location: region.php");
    }
} else if ($_GET['call'] == 'user') {
    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['passwd'];
        $user_role = $_POST['user_role'];

        $stmt = $con->prepare("SELECT username FROM user WHERE username = ? AND is_deleted = false");
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $resp = "duplicate";
        } else {
            $stmt = $con->prepare("INSERT INTO user (username,password,user_role) VALUES (?,?,?)");
            $stmt->bind_param("sss", $username, $password, $user_role);
            $stmt->execute();

            if ($stmt->affected_rows === 0) {
                $resp = "error";
            } else {
                $resp = "success";
            }
        }
        $_SESSION['message'] = $resp;
        header("Location: user.php");
    }
} else if ($_GET['call'] == 'constituency') {
    if (isset($_POST['submit'])) {
        $r_id = $_POST['r_id'];
        $c_name = $_POST['constituency'];

        $stmt = $con->prepare("SELECT c_name FROM constituency WHERE c_name = ? AND r_id = ? AND is_deleted = false");
        $stmt->bind_param("si", $c_name, $r_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $resp = "duplicate";
        } else {
            $stmt = $con->prepare("INSERT INTO constituency (c_name,r_id) VALUES (?,?)");
            $stmt->bind_param("si", $c_name, $r_id);
            $stmt->execute();

            if ($stmt->affected_rows === 0) {
                $resp = "error";
            } else {
                $resp = "success";
            }
        }
        $_SESSION['message'] = $resp;
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
} else if ($_GET['call'] == 'polling_station') {
    if (isset($_POST['submit'])) {
        $c_id = $_POST['c_id'];
        $u_id = $_POST['polling_agent'];
        $ps_name = $_POST['polling_station'];

        $stmt = $con->prepare("INSERT INTO polling_station (ps_name,c_id,u_id) VALUES (?,?,?)");
        $stmt->bind_param("sii", $ps_name, $c_id, $u_id);
        $stmt->execute();

        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
} else if ($_GET['call'] == 'party') {
    if (isset($_POST['submit'])) {
        $p_name = $_POST['party'];

        $stmt = $con->prepare("INSERT INTO party (p_name) VALUES (?)");
        $stmt->bind_param("s", $p_name);
        $stmt->execute();

        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}
