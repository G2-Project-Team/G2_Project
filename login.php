    <?php
        session_start();
        # Check if user is logged in
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true
        ) {
            # If logged in, redirect to account details page
            header('Location: account-details.php');
            exit();
        }
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

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


    <div class="login-container">
        <img src="logo-placeholder.png" alt="Company Logo" class="logo"> <!-- Placeholder for logo -->
        <h2>Welcome</h2>
        
        <form action="loginController.php" method="POST">
            <input type="text" name="username" placeholder="Username" class="input-field" required>
            <input type="password" name="password" placeholder="Password" class="input-field" required>
            
            <input type="submit" value="Login" class="button">
        </form>

        <!-- Link to direct to forgotten password page -->
        <a href="forgotten-password.php" class="forgot-password">Forgotten Password?</a>
        <a href="register.php" class="create-account">Create an Account</a>

        <!-- Display status message if set in session -->
        <?php
         if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['status_message'])) {
            echo '<div class="message">' . $_SESSION['status_message'] . '</div>';
            unset($_SESSION['status_message']); // Clear the message after displaying
        }   
        ?>
    </div>

</body>
<?php include 'includes/footer.php'; ?>
