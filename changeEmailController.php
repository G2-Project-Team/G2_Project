<?php
include 'connect_db.php';
session_start();
$uid = $_SESSION['id'];
$newEmail = $_POST['newEmail'];

        $query = "UPDATE users SET email = ? WHERE user_id = ?";
        $stmt = $link->prepare($query);
        $stmt->bind_param("si", $newEmail, $uid);
        $stmt->execute();
        $stmt->close();
        $_SESSION['email'] = $newEmail;

        exit();
?>
