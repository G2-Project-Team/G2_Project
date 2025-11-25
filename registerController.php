<?php
include 'connect_db.php';
session_start();

// Input sanitization, taking away any spaces
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);
// $created_at = trim($_POST['created_at']);
// $role = trim($_POST['role']);

// Validate username characters
if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
  $_SESSION['status_message'] = 'Username can only contain letters and numbers';
  header('Location: register.php');
  exit();
}

// Validate password length
if(strlen($password) < 8 || strlen($password) > 16) {
  $_SESSION['status_message'] = 'Password must be between 8 and 16 characters';
  header('Location: register.php');
  exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['status_message'] = 'Invalid email format!';
    header('Location: register.php');
    exit();
}

$stmt = $link->prepare("SELECT user_id FROM users WHERE email = ? OR username = ?");
$stmt->bind_param('ss', $email, $username);

$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
  $_SESSION['status_message'] = 'Email or username already exists';
  header('Location: register.php');
  exit();
} else {
    $stmt-> close();

    // Email doesn't exist. create new account
    $stmt = $link->prepare("INSERT INTO users (Username, Email, Password, Type) VALUES (?, ?, ?, 'user' )");

    // Hash the password for security
    //$hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Bind parameters and execute query 
    if($stmt){
        $stmt->bind_param("sss", $username, $email, $password);
        $stmt->execute();

        // If account creation successful
        if($stmt->affected_rows > 0){
            $_SESSION['status_message'] = 'Account created successfully, Please login';
            header('Location: login.php');           
        } else {
            $_SESSION['status_message'] = 'Error creating account';
            header('Location: register.php');            
        }
        $stmt->close();
    } else {
        $_SESSION['status_message'] = 'Database error';
        header('Location: register.php');
    }

    $link->close();
    exit();

}
?>
<!-- Status Message -->
<?php if (isset($_SESSION['status_message'])) : ?>
    <div><?= $_SESSION['status_message'] ?></div>
<?php unset($_SESSION['status_message']) ?>
<?php endif?>
