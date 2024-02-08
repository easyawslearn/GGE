<?php
session_start();
require_once("connection.php");

if ($_GET['call'] == 'region') {
    if (isset($_POST['edit_submit'])) {
        $Er_id = $_POST['Er_id']; // Get the ID of the region being edited
        $Er_name = $_POST['Er_name']; // Get the new region name from form

        // Create SQL query to check if region name already exists (excluding current region)
        $stmt = $con->prepare("SELECT r_name FROM region WHERE r_name = ? AND r_id != ? AND is_deleted = false");
        $stmt->bind_param("si", $Er_name, $Er_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $resp = "duplicate";
        } else {
            // Proceed with updating the database
            $stmt = $con->prepare("UPDATE region SET r_name = ? WHERE r_id = ?");
            $stmt->bind_param("si", $Er_name, $Er_id);
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
    if (isset($_POST['edit_submit'])) {
        $Eu_id = $_POST['Eu_id']; // Get the ID of the user being edited
        $Eu_name = $_POST['Eusername']; // Get the new user name from form

        // Create SQL query to check if user name already exists (excluding current user)
        $stmt = $con->prepare("SELECT username FROM user WHERE username = ? AND u_id != ? AND is_deleted = false");
        $stmt->bind_param("si", $Eu_name, $Eu_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $resp = "duplicate";
        } else {
            // Proceed with updating the database
            $stmt = $con->prepare("UPDATE user SET username = ? WHERE u_id = ?");
            $stmt->bind_param("si", $Eu_name, $Eu_id);
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
    if (isset($_POST['edit_submit'])) {
        $Ec_id = $_POST['Ec_id']; // Get the ID of the constituency being edited
        $Ec_name = $_POST['Econstituency']; // Get the new constituency name from form
        $Er_id = $_POST['Er_id']; // Get the region ID from form

        // Create SQL query to check if constituency name already exists (excluding current constituency)
        $stmt = $con->prepare("SELECT c_name FROM constituency WHERE c_name = ? AND r_id = ? AND c_id != ? AND is_deleted = false");
        $stmt->bind_param("sii", $Ec_name, $Er_id, $Ec_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $resp = "duplicate";
        } else {
            // Proceed with updating the database
            $stmt = $con->prepare("UPDATE constituency SET c_name = ?, r_id = ? WHERE c_id = ?");
            $stmt->bind_param("sii", $Ec_name, $Er_id, $Ec_id);
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
    if (isset($_POST['edit_submit'])) {
        $Eps_id = $_POST['Eps_id']; // Get the ID of the polling station being edited
        $Eps_name = $_POST['Epolling_station']; // Get the new polling station name from form
        $Ec_id = $_POST['Ec_id']; // Get the constituency ID from form
        $Eu_id = $_POST['Epolling_agent'];

        // Create SQL query to check if polling station name already exists (excluding current polling station)
        $stmt = $con->prepare("SELECT ps_name FROM polling_station WHERE ps_name = ? AND c_id = ? AND ps_id != ? AND is_deleted = false");
        $stmt->bind_param("sii", $Eps_name, $Ec_id, $Eps_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $resp = "duplicate";
        } else {
            // Proceed with updating the database
            $stmt = $con->prepare("UPDATE polling_station SET ps_name = ?, u_id = ? WHERE ps_id = ?");
            $stmt->bind_param("sii", $Eps_name, $Eu_id, $Eps_id);
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
} else if ($_GET['call'] == 'party') {
    if (isset($_POST['edit_submit'])) {
        $Ep_id = $_POST['Ep_id']; // Get the ID of the party being edited
        $Ep_name = $_POST['Eparty']; // Get the new party name from form

        // Create SQL query to check if party name already exists (excluding current party)
        $stmt = $con->prepare("SELECT p_name FROM party WHERE p_name = ? AND p_id != ? AND is_deleted = false");
        $stmt->bind_param("si", $Ep_name, $Ep_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $resp = "duplicate";
        } else {
            // Proceed with updating the database
            $stmt = $con->prepare("UPDATE party SET p_name = ? WHERE p_id = ?");
            $stmt->bind_param("si", $Ep_name, $Ep_id);
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
}
