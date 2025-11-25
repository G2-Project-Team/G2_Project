<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgotten Password</title>
  <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N"
          crossorigin="anonymous">

    <!-- combined stylesheet -->
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Additional inline styles for message box */
        .message {
            margin-top: 15px;
            font-size: 14px;
            color: #d9534f; /* Red for errors */
            display: none;
        }

        .message.success {
            color: #2e8b57; /* Green for success */
        }
    </style>
</head>
<body>
    <?php include 'includes/nav.php'; ?>

    <div class="login-container">
        <img src="logo-placeholder.png" alt="Company Logo" class="logo"> <!-- Placeholder for logo -->
        <h2>Reset Your Password</h2>

        <!-- Password reset form -->
        <!-- Redirects to reset-success.php after simulated success -->
        <form id="resetForm" action="reset-success.php" method="GET">
            <input type="email" id="email" name="email" placeholder="Enter your email address" class="input-field" required>
            <button type="submit" class="button">Send Reset Link</button>
        </form>

        <!-- Message area for error or success -->
        <div id="message" class="message">Please enter a valid email address.</div>

        <!-- Link back to the login page -->
        <a href="login.php" class="forgot-password">Back to Login</a>
    </div>

    <!-- Simple validation and feedback script
         NOTE: For testing purposes the exact email "name@caledonian.ac.uk"
         will force a successful redirect to reset-success.html.
         Other behaviour:
           - invalid email format => client-side validation error
           - emails containing "fail" => simulated server-side failure
           - otherwise => simulated success (redirect to reset-success.html)
    -->
    <script>
        const form = document.getElementById('resetForm');
        const emailInput = document.getElementById('email');
        const message = document.getElementById('message');

        form.addEventListener('submit', function(event) {
            event.preventDefault(); // handle navigation manually for simulation

            const emailValue = emailInput.value.trim();
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            // 1) Force-success test case (user requested addition)
            if (emailValue.toLowerCase() === 'name@caledonian.ac.uk') {
                message.textContent = "Sending reset link...";
                message.classList.add('success');
                message.style.display = 'block';

                // short delay so user can see the confirmation text
                setTimeout(() => {
                    window.location.href = form.action;
                }, 700);

                return;
            }

            // 2) Client-side validation: invalid format => show validation error
            if (!emailPattern.test(emailValue)) {
                message.textContent = "Please enter a valid email address.";
                message.classList.remove('success');
                message.style.display = 'block';
                return;
            }

            // 3) Simulated server logic:
            // If email contains "fail"  simulate a server-side failure.
            if (emailValue.toLowerCase().includes('fail')) {
                message.textContent = "Unable to send reset link — please try again later.";
                message.classList.remove('success');
                message.style.display = 'block';
                return;
            }

            // 4) Default simulated success for all other valid emails
            message.textContent = "Sending reset link...";
            message.classList.add('success');
            message.style.display = 'block';

            // simulate redirect after a short delay
            setTimeout(() => {
                window.location.href = form.action;
            }, 700);
        });
    </script>

</body>
</html>
<?php include 'includes/footer.php'; ?>
