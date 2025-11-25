<!--import Header Placeholder-->
<!--php Read Database and assign values to variables-->
<?php
    session_start();  
    // Check if user is logged in
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        // If not logged in, redirect to login page
        header('Location: login.php');
        exit();
    }
  $uid = $_SESSION['id'];
  $username = $_SESSION['username'];
  $email = $_SESSION['email'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Account Details</title>
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
<?php include 'includes/nav.php'; ?>

<div class="account-layout">
  <div class="nav" style="font-size: 30px; font-weight: bold;">
    <a href="account-details.php" style="margin-top: 250px;">Account Details</a>
    <a href="account-listings.php">Listings</a>
    <a href="saved-listings.php">Saved Listings</a>
  </div>

  <div class="content">
    <div style="display: flex; justify-content: center;">
      <img src="profile-pic-placeholder.jpg"
           alt="Profile Picture"
           style="width:200px; height:200px; border-radius:50%; margin-bottom: 100px;">
    </div>

    <div style="display:flex; flex-direction:column; align-items:center;">
      <h2>Email: <?php echo $email; ?></h2>
      <a href="javascript:changeEmail();" style="font-size:16px; color:blue; margin-top:-20px;">Change Email</a>
    </div>

    <div style="display:flex; flex-direction:column; align-items:center;">
      <h2>Username: <?php echo $username; ?></h2>
    </div>

    <div style="display:flex; flex-direction:column; align-items:center; padding:20px;">
      <textarea rows="4" cols="50" placeholder="Account Description..." style="margin-top:50px;"></textarea>
      <!-- logout button -->
      <button onclick="logout()" class="logout-btn">Logout</button>
    </div>
  </div>
</div>

  <script>
    // JavaScript for any interactivity if needed in future
    // JavaScript function to change email
    function changeEmail() {
      var newEmail = prompt("Enter your new email address:");
  if (newEmail) {
    fetch('changeEmailController.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'newEmail=' + encodeURIComponent(newEmail)
    })
    .then(response => response.text())
    .then(data => {
      alert(data); // Server’s response message
    })
    .catch(error => {
      console.error('Error:', error);
      alert('An error occurred while changing your email.');
    });
    // If successful, update session email variable and display message
    <?php if (isset($_SESSION['email'])): ?>
      alert("Your email has been changed!");
    <?php endif; ?>

    // If successful, update displayed email
    document.querySelector('h2').innerText = "Email: " + newEmail;

  }
}

    // JavaScript function to logout
    function logout() {
      // Confirm logout action
      if (confirm("Are you sure you want to logout?")) {
        // Redirect to logout page or perform logout action
        window.location.href = "logoutController.php"; // Example redirect
        alert("You have been logged out.");
      }
      
    }
  </script>

</body>
<?php include 'includes/footer.php'; ?>
</html>


    



    
      


   

