<?php
session_start();
include 'connect_db.php';
$uid = $_SESSION['id'];
$lid = $_GET['listing_id'];

// Check if the listing is already saved
$stmt = $link->prepare('SELECT * FROM listing_save WHERE user_id = ? AND listing_id = ?');
$stmt->bind_param('ii', $uid, $lid);
$stmt->execute();
$stmt->store_result();
$saved = false;
if ($stmt->num_rows > 0) {
    // Listing already saved
    $saved = true;
}
$stmt->close();

// Determine which page to redirect back to
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'listings.php';

// Extract just the filename from the referer URL
$redirect_page = basename(parse_url($referer, PHP_URL_PATH));

// If no valid referer or coming from external site, default to listings.php
if (empty($redirect_page) || strpos($referer, $_SERVER['HTTP_HOST']) === false) {
    $redirect_page = 'listings.php';
}

if ($saved) {
    // Delete the saved listing
        $query = "DELETE FROM listing_save WHERE user_id = ? AND listing_id = ?";
        $stmt = $link->prepare($query);
        $stmt->bind_param("ii", $uid, $lid);
        $stmt->execute();
    $stmt->close();
    header("Location: " . $redirect_page);
    exit();

}
else {
        // Save the listing
        $query = "INSERT INTO `listing_save`(`user_id`, `listing_id`) VALUES (?, ?)";
        $stmt = $link->prepare($query);
        $stmt->bind_param("ii", $uid, $lid);
        $stmt->execute();
        $stmt->close();
        header("Location: " . $redirect_page);
        exit();
}
?>
