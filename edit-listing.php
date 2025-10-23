<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Listing</title>
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

    <div class="login-container">
        <img src="logo-placeholder.png" alt="Company Logo" class="logo">
        <h2>Edit Listing</h2>

        <!-- Listing Information Section -->
        <form id="editListingForm" action="#" method="POST" onsubmit="saveChanges(event)">
            <input type="text" name="listingTitle" placeholder="Listing Title" class="input-field" required>
            <input type="text" name="listingID" placeholder="Listing ID" class="input-field" required>
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
            <div style="margin-top: 20px;">
                <button type="submit" class="button">Save Changes</button>
                <button type="button" class="button" style="background-color: #b22222;" onclick="confirmDelete()">Delete Listing</button>
            </div>

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
            event.preventDefault();
            showMessage('✅ Changes saved successfully!', 'success');
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
