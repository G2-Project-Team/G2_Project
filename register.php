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
    <title>Sign Up</title>
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
        <img src="logo-placeholder.png" alt="Company Logo" class="logo">
        <h2>Create an Account</h2>

        <form action="registerController.php" method="POST">
            <!-- Full Name -->

            <!-- Contact Information -->
            <input type="email" name="email" placeholder="Email Address" class="input-field" required>


            <!-- Credentials -->
            <input type="text" name="username" placeholder="Username" class="input-field" required>
            <input type="password" name="password" placeholder="Password" class="input-field" required>

            <input type="submit" value="Sign Up" class="button">
        </form>
        <?php
            if (isset($_SESSION['status_message'])) {
                echo '<p class="status-message">' . $_SESSION['status_message'] . '</p>';
                unset($_SESSION['status_message']);
            }
        ?>
        <a href="login.php" class="create-account">Already have an account? Login here</a>
        
    </div>

</body>
</html>
<?php include 'includes/footer.php'; ?>
