<!DOCTYPE html>
<?php
$listingSaved = false;
include 'connect_db.php';
session_start();
//Database scheme
// landlistings: listing_id, user_id, date_added 
// users: user_id, username, email
// griddata: grid_id
// Connect to database and fetch all land listings with associated user info
$listings = $link->prepare("SELECT l.listing_id, l.time_created, l.title, l.description, u.username, u.email, l.grid_id FROM landlistings l JOIN users u ON l.user_id = u.user_id WHERE l.listing_id = ?");
$listings->bind_param("i", $_GET['listing_id']);

$listings->execute();
$listingsResult = $listings->get_result();  
//Bind results to an array
$listingsArray = [];
while ($row = $listingsResult->fetch_assoc()) {
    $listingsArray[] = $row;
}

if ($listingsResult->num_rows == 0) {
    // listing does not exist
    $_SESSION['status_message'] = 'Listing does not exist.';
    $listings->close();
    header('Location: listings.php');

    exit();
}

// Check if listing is already saved by user
$saveCheck = $link->prepare("SELECT * FROM listing_save WHERE user_id = ? AND listing_id = ?");
$saveCheck->bind_param("ii", $_SESSION['id'], $_GET['listing_id']);
$saveCheck->execute();
$saveCheckResult = $saveCheck->get_result();
if ($saveCheckResult->num_rows > 0) {
    $listingSaved = true;
}   
?>
<html lang = "en">
<head>
    <meta charset = "UTF-8">
    <meta name = "viewport" content = "width = device-width, initial-scale = 1.0">
    <title>Land Listing View</title>
 <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N"
          crossorigin="anonymous">

    <!-- combined stylesheet -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'includes/nav.php'; ?>

    <div class = "page-container">
        
        <div class = "content-wrapper">
            <main class = "listing-detail-main">
                <div class="listing-detail-header" style="display:flex; align-items:center; gap:20px; font-weight:700; margin-bottom:20px;">
                    <h1>Listing at Grid Point <?php echo htmlspecialchars($listingsArray[0]['grid_id']); ?></h1>
                    <?php if (!$listingSaved): ?>
                    <button class="button save-listing-btn">Save Listing</button>
                    <?php else: ?>
                    <button class="button save-listing-btn" style="background-color: red;" disabled>Remove from Saved Listings</button>
                    <?php endif; ?>
                </div>


                <div class = "listing-info" style="margin-top:20px;">
                    <h3>Owner Information</h3>
                    <p><strong>Listed By: </strong><?php echo htmlspecialchars($listingsArray[0]['username']); ?></p>
                    <p><strong>Contact Email:  </strong> <?php echo htmlspecialchars($listingsArray[0]['email']); ?></p>
                    <button class="button contact-owner-btn">Contact Owner</button>
                </div>

                <div class = "listing-info">
                    <h3>Listing Details</h3>
                    <p><strong>Date Added: </strong><?php echo htmlspecialchars(date("d/m/Y", strtotime($listingsArray[0]['time_created']))); ?></p>
                    <p><strong>Description: </strong><?php echo htmlspecialchars($listingsArray[0]['description']); ?></p>
                </div>

                <div class = "listing-documents">
                    <h3>Attached Documents</h3>
                    <table class = "listings-table">
                        <thead>
                            <tr>
                                <th>Document Name</th>
                                <th>Date Uploaded</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Title Sheet</td>
                                <td>12/10/2025</td>
                                <td><a href = "#" class = "view-link">Download</a></td>
                            </tr>
                            <tr>
                                <td>Site Map</td>
                                <td>12/10/2025</td>
                                <td><a href = "#" class = "view-link">Download</a></td>
                            </tr>
                            <tr>
                                <td>Proof of Planning Permission</td>
                                <td>21/10/2025</td>
                                <td><a href = "#" class = "view-link">Download</a></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <a href = "listings.php" class = "button">Back to All Listings</a>
            </main>
        </div>
    </div>
</body>
</html>
<?php include 'includes/footer.php'; ?>