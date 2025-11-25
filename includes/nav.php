<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-dark mb-3 rounded-bottom"
     style="background-color:#45A583 !important;
            border-bottom: 2px solid #45A583;
            min-height: 6vh;">

    <a class="navbar-brand" href="#">
        <img src="images/logo-placeholder.png" style="max-height:50px;">
    </a>

    <button class="navbar-toggler" type="button"
            data-toggle="collapse"
            data-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <div class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="heatmap.php">Heat Map</a>
            </li>

            <?php if (isset($_SESSION['id'])): ?>
                <li class="nav-item">
                <a class="nav-link" href="listings.php">Listings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="account-details.php">Account Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logoutController.php">Logout</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
            <?php endif; ?>

        </div>
    </div>
</nav>
