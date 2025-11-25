<?php
if (headers_sent($file, $line)) {
    die("⚠️ Headers already sent in $file on line $line");
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Start a session and include the database configuration file to establish a database connection
require 'connect_db.php';
session_start();

// Check if the login form was submitted with both email and password fields filled
if (empty($_POST['username']) || empty($_POST['password'])) {
    // Set a session error message and redirect to the login page if any field is empty
    $_SESSION['status_message'] = 'Please fill both the username and password fields!';
    header('Location: login.php');
    exit();
}

// Prepare an SQL statement to prevent SQL injection when checking user credentials
if ($stmt = $link->prepare('SELECT user_id, email, username, password FROM users WHERE username = ?')) {
    // Bind the input email parameter to the SQL query and execute the statement
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();
    
    // If the email exists, bind the result variables and fetch data
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $email, $username, $password);
        $stmt->fetch();
        
        // Verify the entered password against the hashed password stored in the database
        if ($_POST['password'] === $password) {
            // Password is correct, regenerate session ID for security
            session_regenerate_id();
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $_POST['username']; // Store the username in session
            $_SESSION['id'] = $id; // Store the user ID in session
            $_SESSION['email'] = $email; // store the users email in session

            // Set a secure cookie with the username (HTTP only and Secure flag enabled)
            setcookie("username", $_POST['username'], time() + 86400, "/", "", true, true);

            // Set a debugging message in session for successful login
            $_SESSION['status_message'] = 'Login successful! Welcome, ' . $_SESSION['username'] . '.';
         
            // Direct to account details page after successful login
            header('Location: account-details.php');
            exit();
            
        } else {
            // If password is incorrect, set an error message and redirect back to login page
            $_SESSION['status_message'] = 'Incorrect password!';
            header('Location: login.php');
            exit();
        }
    } else {
        // If email does not exist, set an error message and redirect to login page
        $_SESSION['status_message'] = 'Incorrect username';
        header('Location: login.php');
        exit();
    }
    
    // Close the prepared statement after execution
    $stmt->close();
} else {
    // If preparing the SQL statement fails, set an error message and redirect to login page
    $_SESSION['status_message'] = 'Login system error. Please try again later.';
    header('Location: login.php ');
    exit();
}
?>