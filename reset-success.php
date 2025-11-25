<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Successful</title>
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
        <h2>Check Your Email</h2>

        <p>
            If the email address you entered is associated with an account, 
            you will receive a password reset link shortly.
        </p>

        <!-- Link back to login page -->
        <a href="login.php" class="forgot-password">Back to Login</a>
    </div>

</body>
</html>
<?php include 'includes/footer.php'; ?>
