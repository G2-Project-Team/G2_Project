<?php 
include 'connect_db.php';
session_start();
$uid = $_SESSION['id'];

if (!$uid) {
    // listing does not exist
    $_SESSION['status_message'] = 'Log in to view saved.';
    header('Location: login.php');

    exit();
}

$saves = $link->prepare("SELECT s.user_id, s.listing_id, l.title, l.time_created
FROM listing_save s
INNER JOIN landlistings l ON l.listing_id = s.listing_id
WHERE s.user_id = $uid");

$saves->execute();
$saves->store_result();
$saves->bind_result($userID, $listingID, $title, $time);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Listings</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N"
          crossorigin="anonymous">

    <!-- combined stylesheet -->
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Additional styling to complement existing CSS */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
            color: #2e8b57;
        }

        .star {
            font-size: 20px;
            cursor: pointer;
            user-select: none;
        }

        .star.saved {
            color: #FFD700; /* gold for saved */
        }

        .star.unsaved {
            color: #aaa; /* grey for unsaved */
        }

        .message {
            margin-top: 15px;
            font-weight: bold;
            text-align: center;
            color: #2e8b57;
            opacity: 0;
            transition: opacity 0.5s ease;
        }
    </style>
</head>
<body>
    <?php include 'includes/nav.php'; ?>

    <div class="login-container">
        <img src="logo-placeholder.png" alt="Company Logo" class="logo">
        <h2>Saved Listings</h2>

        <table id="savedListingsTable">
            <thead>
                <tr>
                    <th>Listing Title</th>
                    <th>Date Added</th>
                    <th>Saved</th>
                </tr>
            </thead>
            <tbody id="savedListingsBody">
                <?php while($saves->fetch()) : ?>
                <tr>
                    <td><?= htmlspecialchars($title) ?></td>
                    <td><?= htmlspecialchars(date("d/m/Y", strtotime($time))) ?></td>
                    <td>
                        <span class="star saved" data-listing-id="<?= $listingID ?>">⭐</span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div id="messageArea" class="message">Saved listings updated</div>

        <a href="heatmap.php" class="button">Back to Heatmap</a>
        
    </div>

    <script>
        const messageArea = document.getElementById('messageArea');

        // Add click event listeners to all star elements
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star');
            stars.forEach(star => {
                star.addEventListener('click', () => toggleSaved(star));
            });
        });

        function toggleSaved(star) {
            const listingId = star.getAttribute('data-listing-id');

            // Toggle saved status using saveListingController.php
            fetch(`saveListingController.php?listing_id=${listingId}`)
                .then(response => response.text())
                .then(data => {
                    // Remove the row from the table since it's no longer saved
                    const row = star.closest('tr');
                    row.remove();
                    showMessage('Listing removed from saved.');
                })
                .catch(error => {
                    console.error('Error updating saved status:', error);
                    showMessage('Error updating saved status.');
                });
        }

        function showMessage(text) {
            messageArea.textContent = text;
            messageArea.style.opacity = 1;
            setTimeout(() => {
                messageArea.style.opacity = 0;
            }, 2500);
        }
    </script>

</body>
</html>
<?php include 'includes/footer.php'; ?>
