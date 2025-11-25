<?php
include 'connect_db.php';
session_start();
$uid = $_SESSION['id'];
$lid = $_GET['lid'];

        $query = "DELETE FROM landlistings WHERE listing_id = ?";
        $stmt = $link->prepare($query);
        $stmt->bind_param("i", $lid);
        $stmt->execute();
        $stmt->close();
        $_SESSION['status_message'] = 'Listing deleted successfully!';
        header("Location: account-listings.php");   
        exit();
?>