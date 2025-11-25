<!--import Header Placeholder-->
<!--php Read Database and assign values to variables-->
<?php
include 'connect_db.php';
include 'includes/nav.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$uid = $_SESSION['id'];

$listings = $link->prepare("SELECT
listing_id,
time_created,
title,
description,
grid_id
FROM landlistings
WHERE user_id = ?
"
);

$listings->bind_param("i", $uid);
$listings->execute();
$listingsResult = $listings->get_result();   // Store the result for later use
//Bind results to an array
$listingsArray = [];
while ($row = $listingsResult->fetch_assoc()) {
    $listingsArray[] = $row;
}    

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account Listings</title>
  <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N"
          crossorigin="anonymous">

    <!-- combined stylesheet -->
    <link rel="stylesheet" href="styles.css">
  <style>
  body {
    margin: 0;
    font-family: Arial, sans-serif;
  }

  /* Main container */
  .account-layout {
    display: flex;
    min-height: 100vh;      /* Full page height */
    width: 100%;            /* Take full width */
  }

  /* Left Panel for links */
  .nav {
    width: 200px;
    background-color: #45A583;
    color: white;
    padding: 20px;
    border-right: 3px solid black;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .nav a {
    color: white;
    text-decoration: none;
    display: block;
    margin-bottom: 50px;
    font-size: 20px;
  }

  .nav a:hover {
    text-decoration: underline;
  }

  /* Right Panel for content */
  .content {
    flex: 1;               /* Fill remaining space */
    padding: 10px;
    background-color: white;
  }

  .logout-btn {
    margin-top: 20px;
    padding: 10px 20px;
    font-size: 16px;
    background: seagreen;
    color: #fff;
    border: none;
    border-radius: 4px;
    width: 100%;
    max-width: 200px;
  }

  .logout-btn:hover {
    opacity: 0.9;
    cursor: pointer;
  }
</style>
</head>

<body>

<!--Navigation Links need to be set up in index?                                 -->
  <div class="account-layout" >
    <div class="nav" style="font-size: 30px; font-weight: bold;">
      <a href="account-details.php" style="margin-top: 250px;">Account Details</a>
      <a href="account-listings.php" style="margin-bottom: 50px;">Listings</a>
      <a href="saved-listings.php" style="margin-bottom: 50px;">Saved Listings</a>

    </div>

    <!--Right Panel-->
    <div class="content">
  <div style="display: flex; justify-content: center; margin-top: 50px;">
    <h1>My Listings</h1>
  </div>

  <!-- Data Table -->
  <div style="margin-top: 50px; display: flex; justify-content: center;">
    <table style="width: 75%; border-collapse: collapse; text-align: center;">
      <thead>
        <tr style="background-color: #f2f2f2;">
          <th style="padding: 12px; border-bottom: 2px solid #000;">Date</th>
          <th style="padding: 12px; border-bottom: 2px solid #000;">Title</th>
          <th style="padding: 12px; border-bottom: 2px solid #000;">Description</th>
          <th style="padding: 12px; border-bottom: 2px solid #000;">Grid ID</th>
          <th style="padding: 12px; border-bottom: 2px solid #000;">Actions</th>

        </tr>
      </thead>
      <tbody>
        <!-- Example rows, replace with database content in foreach loop ? 
         Need delete controller-->
        <?php foreach ($listingsArray as $listing): ?>
      <tr>
        <td class="px-4 py-2 font-medium whitespace-nowrap text-gray-900"><?= htmlspecialchars($listing['time_created']) ?></td>
        <td class="px-4 py-2 whitespace-nowrap text-gray-700"> <?= htmlspecialchars($listing['title']) ?></td>
        <td class="px-4 py-2 whitespace-nowrap text-gray-700"><?= htmlspecialchars($listing['description']) ?></td>
        <td class="px-4 py-2 whitespace-nowrap"><?= htmlspecialchars($listing['grid_id']) ?></td>
        <td>
            
            <a href="edit-listing.php?listing_id=<?php echo $listing['listing_id']; ?>" class="button view-btn">Edit</a>                      
            <a href="javascript:deleteListing(<?php echo $listing['listing_id']; ?>);" onclick="deleteListing(<?php echo $listing['listing_id']; ?>)" class="button view-btn" style="background-color: red;">Delete</a>
          
        
          
      </tbody>
        
      </tr>
      <?php endforeach ?>
    </table>
  </div>



  <?php include 'includes/footer.php'; ?>

  <script>
  

    // delete validation functionality take a listing id as parameter
    function deleteListing(lid) {
      // Confirm delete action
      if (confirm("Are you sure you want to delete this listing?")) {
        // Redirect to delete controller with listing id
        window.location.href = "deleteListingController.php?lid=" + lid; // Example redirect
        alert("Listing has been deleted.");
      }
      
    }
</script>
    
