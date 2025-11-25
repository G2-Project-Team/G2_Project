<?php
include 'connect_db.php';
session_start();
$uid = $_SESSION['id'];
$title = trim($_POST['listingTitle']);
$desc = trim($_POST['listingDescription']);
$grid_id = isset($_POST['grid_id']) ? trim($_POST['grid_id']) : null;
$lat = isset($_POST['latitude']) ? trim($_POST['latitude']) : null;
$lon = isset($_POST['longitude']) ? trim($_POST['longitude']) : null;


// Validate and sanitise POST content
if (!isset($_POST['listingTitle']) || empty($title)) {
    $_SESSION['status_message'] = "Listing title cannot be empty.";
    header("Location: add-listing.php");
    exit();
}
if (!isset($_POST['listingDescription']) || empty($desc)) {
    $_SESSION['status_message'] = "Listing description cannot be empty.";
    header("Location: add-listing.php");
    exit();
}

// check the listing does not already exist

$stmt = $link->prepare('SELECT listing_id FROM landlistings WHERE title = ?');
$stmt->bind_param('s', $title);

if($stmt){
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // listing already exists
    $_SESSION['status_message'] = 'Listing wasn\'t added!';
    $stmt->close();
    header('Location: add-listing.php');

    exit();
}
elseif(strlen($title) > 255){
    $_SESSION['status_message'] = 'Listing title cannot be longer than 255 characters.';
    $stmt->close();
    header('Location: add-listing.php');

    exit();
}
elseif(strlen($desc) > 65535){
    $_SESSION['status_message'] = 'Listing title cannot be longer than 65535 characters.';
    $stmt->close();
    header('Location: add-listing.php');

    exit();
}
else{
    $stmt->close();
    // add new listing to database
        
        $query = "INSERT INTO `landlistings`(`user_id`, `title`, `description`, `grid_id`) VALUES (?, ?, ?, ?)";
        $stmt = $link->prepare($query);
        $stmt->bind_param("issi", $uid, $title, $desc, $grid_id);
        $stmt->execute();
        $stmt->close();
        $_SESSION['status_message'] = 'Listing added successfully!';
        header('Location: listings.php');
        exit();
}
} else {
        $_SESSION['status_message'] = 'Database error';
        header('Location: add-listing.php');
    }
?>