<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Listings</title>
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
                <!-- Populated dynamically -->
            </tbody>
        </table>

        <div id="messageArea" class="message">Saved listings updated</div>

        <a href="heatmap.html" class="button">Back to Heatmap</a>
        <a href="edit listing.html" class="button">Edit a Listing</a>
    </div>

    <script>
        // Example saved listings data (can replace with backend data)
        const savedListings = [
            { title: "Wind Farm", dateAdded: "2024-10-01", saved: true },
            { title: "Solar Panel site", dateAdded: "2024-09-18", saved: true },
            { title: "Tidal Power", dateAdded: "2024-09-25", saved: true },
        ];

        const tableBody = document.getElementById('savedListingsBody');
        const messageArea = document.getElementById('messageArea');

        // Sort by date added (newest first)
        savedListings.sort((a, b) => new Date(b.dateAdded) - new Date(a.dateAdded));

        // Populate table
        savedListings.forEach(listing => addListingRow(listing));

        function addListingRow(listing) {
            const row = document.createElement('tr');
            const titleCell = document.createElement('td');
            const dateCell = document.createElement('td');
            const starCell = document.createElement('td');

            titleCell.textContent = listing.title;
            dateCell.textContent = new Date(listing.dateAdded).toLocaleDateString("en-GB");

            const star = document.createElement('span');
            star.className = listing.saved ? 'star saved' : 'star unsaved';
            star.textContent = listing.saved ? '⭐' : '☆';
            star.addEventListener('click', () => toggleSaved(star, listing));

            starCell.appendChild(star);

            row.appendChild(titleCell);
            row.appendChild(dateCell);
            row.appendChild(starCell);
            tableBody.appendChild(row);
        }

        function toggleSaved(star, listing) {
            listing.saved = !listing.saved;
            star.textContent = listing.saved ? '⭐' : '☆';
            star.className = listing.saved ? 'star saved' : 'star unsaved';
            showMessage(listing.saved ? 'Listing re-saved.' : 'Listing removed from saved.');
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
