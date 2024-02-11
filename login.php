<?php
session_start();
include 'connection.php';

if (isset($_POST['username']) && isset($_POST['password'])) {

    function validate($data)
    {
        $data = trim($data);
        $data = stripcslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

$username = validate($_POST['username']);
$password = validate($_POST['password']);

if (empty($username)) {
    header("Location: index.php?error=Username cannot be empty!");
    exit();
} else if (empty($password)) {
    header("Location: index.php?error=Password cannot be empty!");
    exit();
}

$sql = $con->prepare("SELECT * FROM admin_panel WHERE username= BINARY ? AND password= BINARY ?");
$sql->bind_param("ss", $username, $password);

$sql->execute();
$result = $sql->get_result();

if (mysqli_num_rows($result) == 1) {
    $row = $result->fetch_assoc();
    if ($row['username'] === $username && $row['password'] === $password) {
        $_SESSION['username'] = $row['username'];
        $_SESSION['password'] = $row['password'];
        $_SESSION['id'] = $row['id'];

        header('Location: home.php');
        exit();
    } else {
        header('Location: index.php?error=Incorrect username or password!');
        exit();
    }
} else {
    header('Location: index.php');
    exit();
}
