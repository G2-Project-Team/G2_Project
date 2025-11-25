<?php
// Get grid location data from URL parameters
$grid_id = isset($_GET['grid_id']) ? htmlspecialchars($_GET['grid_id']) : null;
$lat = isset($_GET['lat']) ? htmlspecialchars($_GET['lat']) : null;
$lon = isset($_GET['lon']) ? htmlspecialchars($_GET['lon']) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Listing</title>
  <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N"
          crossorigin="anonymous">

    <!-- combined stylesheet -->
    <link rel="stylesheet" href="styles.css">
    <style>
        /* small inline additions for feedback messages */
        .message {
            margin-top: 15px;
            font-weight: bold;
            text-align: center;
            transition: opacity 0.5s ease;
        }
        .message.success {
            color: #2e8b57;
        }
        .message.error {
            color: #b22222;
        }
    </style>
</head>
<body>
    <?php include 'includes/nav.php'; ?>

    <div class="login-container">
        <img src="logo-placeholder.png" alt="Company Logo" class="logo">
        <h2>Add Listing </h2>
        <h3><?php echo $grid_id ? "Grid Location $grid_id ($lat, $lon)" : ""; ?></h3>

        <?php if ($grid_id && $lat && $lon): ?>
        <!-- Grid Location Information -->
        <div class="alert alert-info" role="alert">
            <h5 class="alert-heading">Adding Listing at Grid Location <?php echo $grid_id; ?> (<?php echo $lat; ?>, <?php echo $lon; ?>)</h5>
            <p class="mb-0">You are adding a listing at the selected location from the heatmap.</p>
        </div>
        <?php else: ?>
            <!-- Grid Location Information -->
        <div class="alert alert-info" role="alert">
            <h5 class="alert-heading">No grid location selected.</h5>
            <p class="mb-0">Cannot add listing.</p>
        </div>
        <?php endif; ?>

        <!-- Listing Information Section -->
        <form action="addListingController.php" method="POST" onsubmit="saveChanges(event)">
            <!-- Hidden fields to store grid location data -->
            <?php if ($grid_id && $lat && $lon): ?>
            <input type="hidden" name="grid_id" value="<?php echo $grid_id; ?>">
            <input type="hidden" name="latitude" value="<?php echo $lat; ?>">
            <input type="hidden" name="longitude" value="<?php echo $lon; ?>">
            <?php endif; ?>

            <input type="text" name="listingTitle" placeholder="Listing Title" class="input-field" required>
            <textarea name="listingDescription" placeholder="Listing Description" class="input-field" rows="3" required></textarea>

            <!-- Document Upload -->
            <label for="documentUpload"><strong>Upload New Document:</strong></label>
            <input type="file" id="documentUpload" name="documentUpload" class="input-field" accept=".pdf,.doc,.docx,.jpg,.png">

            <!-- Document Table -->
            <h3>Attached Documents</h3>
            <table id="documentTable" style="width:100%; border-collapse: collapse; text-align:left;">
                <thead>
                    <tr>
                        <th>Document Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="documentTableBody">
                    <!-- Rows dynamically added -->
                </tbody>
            </table>

            <!-- Action Buttons -->

            <?php if(!$grid_id || !$lat || !$lon): ?>
            <div style="margin-top: 20px;">
                <button type="submit" class="button" disabled>Cannot Add Listing</button>
            </div>
            <?php else: ?>
            <div style="margin-top: 20px;">
                <button type="submit" class="button">Add Listing</button>
            </div>
            <?php endif; ?>

             <?php
            if (isset($_SESSION['status_message'])) {
                echo '<p class="status-message">' . $_SESSION['status_message'] . '</p>';
                unset($_SESSION['status_message']);
            }
            ?>

            <!-- Feedback message -->
            <div id="messageArea" class="message" style="opacity:0;"></div>

            <a href="index.php" class="button">Back to Welcome</a>
            <a href="saved-listings.php" class="button">View Saved Listings</a>
        </form>
    </div>

    <script>
        const documentUpload = document.getElementById('documentUpload');
        const documentTableBody = document.getElementById('documentTableBody');
        const messageArea = document.getElementById('messageArea');

        // Add uploaded document to the table automatically
        documentUpload.addEventListener('change', function() {
            const files = Array.from(this.files);
            files.forEach(file => addDocumentRow(file.name));
            this.value = ''; // Reset so user can re-upload same file name if needed
        });

        // Add a document row dynamically
        function addDocumentRow(fileName) {
            const row = document.createElement('tr');
            const nameCell = document.createElement('td');
            const actionCell = document.createElement('td');

            nameCell.textContent = fileName;
            actionCell.innerHTML = '<button type="button" class="button remove-btn" onclick="removeDocument(this)">X</button>';

            row.appendChild(nameCell);
            row.appendChild(actionCell);
            documentTableBody.appendChild(row);
        }

        // Remove a document from the table
        function removeDocument(button) {
            const row = button.closest('tr');
            row.remove();
        }

        // Show feedback messages
        function showMessage(text, type) {
            messageArea.textContent = text;
            messageArea.className = `message ${type}`;
            messageArea.style.opacity = 1;
            setTimeout(() => {
                messageArea.style.opacity = 0;
            }, 3000);
        }

        // Save changes (placeholder functionality)
        function saveChanges(event) {


            document.getElementById('addListingForm').addEventListener('submit', function(event) {
            const title = document.getElementById('listingTitle').value.trim();
            const desc = document.getElementById('listingDesc').value.trim();

            // Basic checks
            if (title.length > 255) {
                alert('Title cannot be longer than 255 characters.');
                event.preventDefault();
            }
            if (desc.length > 65535) {
                alert('Description cannot be longer than 65535 characters.');
                event.preventDefault();
            }
            });

            //showMessage('✅ Changes saved successfully!', 'success');
        }

        // Confirm before deleting listing
        function confirmDelete() {
            const confirmAction = confirm('⚠️ Are you sure you want to delete this listing? This action cannot be undone.');
            if (confirmAction) {
                showMessage('❌ Listing deleted.', 'error');
            }
        }
    </script>

</body>
</html>
<?php include 'includes/footer.php'; ?>
