<?php
include 'connect_db.php';
session_start();
$uid = $_SESSION['id'];
$lid = $_POST['listingID'];
$title = trim($_POST['listingTitle']);
$desc = trim($_POST['listingDescription']);
// Validate and sanitise POST content
if (!isset($_POST['listingTitle']) || empty($title)) {
    $_SESSION['status_message'] = "Listing title cannot be empty.";
    header("Location: edit-listing.php?listing_id=" . $lid);
    exit();
}
if (!isset($_POST['listingDescription']) || empty($desc)) {
    $_SESSION['status_message'] = "Listing description cannot be empty.";
    header("Location: edit-listing.php?listing_id=" . $lid);
    exit();
}
        $query = "UPDATE landlistings SET title = ?, description = ? WHERE listing_id = ? AND user_id = ?";
        $stmt = $link->prepare($query);
        $stmt->bind_param("ssii", $title, $desc, $lid, $uid);
        $stmt->execute();
        $stmt->close();
        $_SESSION['status_message'] = 'Listing updated successfully!';
        header("Location: account-listings.php");   
        exit();