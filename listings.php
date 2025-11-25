<?php 
require_once 'connect_db.php';

session_start();
//Database scheme
// landlistings: listing_id, user_id, date_added 
// users: user_id, username, email
// griddata: grid_id, lat, lon, avg_sun, avg_wind
// Connect to database and fetch all land listings with associated user info
$listings = $link->prepare("SELECT l.listing_id, l.time_created, u.username, l.grid_id, l.title FROM landlistings l JOIN users u ON l.user_id = u.user_id ORDER BY l.time_created DESC");
$listings->execute();
$listingsResult = $listings->get_result();  
//Bind results to an array
$listingsArray = [];
while ($row = $listingsResult->fetch_assoc()) {
    $listingsArray[] = $row;
}   

// Get list of saved listings for the logged-in user
$savedListings = [];
if (isset($_SESSION['id'])) {
    $uid = $_SESSION['id'];
    $savedStmt = $link->prepare("SELECT listing_id FROM listing_save WHERE user_id = ?");
    $savedStmt->bind_param("i", $uid);
    $savedStmt->execute();
    $savedResult = $savedStmt->get_result();
    while ($row = $savedResult->fetch_assoc()) {
        $savedListings[] = $row['listing_id'];
    }
    $savedStmt->close();
}
?>

<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset = "UTF-8">
    <meta name = "viewport" content = "width = device-width, initial-scale = 1.0">
    <title>Land Listings</title>
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
<!-- Align container     to center -->
    <div class = "page-container" style ="justify-content: center; align-items: center;">

        <div class = "content-wrapper" >

            <main class = "listings-main" style="margin-bottom:20px;"> 
                <div class = "listings-header">
                    <h2>Available Land Listings</h2>
                    <a href = "heatmap.php" class = "button add-lisitng-btn">Add new Listing</a>
                </div>

                <table class = "listings-table" style="margin-bottom:20px;">
                    <thead>
                        <!-- Display all users listings -->
                        <tr>
                            <th>Location (Grid Ref)</th>
                            <th>Title</th>
                            <th>Listed By</th>
                            <th>Date Added</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody >
                        <?php foreach ($listingsArray as $listing): ?>
                            <tr>
                                
                                <?php $grid_id = $listing['grid_id']; ?>
                                <td><a href="Listing_View.php?listing_id=<?php echo $listing['listing_id']; ?>"><?php echo htmlspecialchars($grid_id); ?></a></td>
                                <td><?php echo htmlspecialchars($listing['title']); ?></td>
                                <td><?php echo htmlspecialchars($listing['username']); ?></td>
                                <td><?php echo htmlspecialchars(date("d/m/Y", strtotime($listing['time_created']))); ?></td>
                                <td>
                                    <a href="Listing_View.php?listing_id=<?php echo $listing['listing_id']; ?>" class="button view-btn">View Full Listing</a>
                                    <a href="#" class="button edit-btn">Contact</a>
                                    
                                    
                                    <?php if (in_array($listing['listing_id'], $savedListings)): ?>
                                        <a href="saveListingController.php?listing_id=<?php echo $listing['listing_id']; ?>" class="button save-btn" style="background-color: #b22222;">Unsave</a>
                                    <?php else: ?>
                                        <a href="saveListingController.php?listing_id=<?php echo $listing['listing_id']; ?>" class="button save-btn">Save</a>
                                    <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                        
                    </tbody>
                </table>
            </main>
        </div>
    </div>
    
</body>
</html>
<?php include 'includes/footer.php'; ?>
