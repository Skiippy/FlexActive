<?php
session_start();

// Set Variables
$host = 'sql106.infinityfree.com';
$username = 'if0_36862596';
$password = 'FlexActiveAdmin';
$database = 'if0_36862596_fitness';

// Link to Database
$conn = mysqli_connect($host, $username, $password, $database);

// Error Message if Connection Fails
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create and Link Variables
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $encryptedPassword = md5($password); // Consider using a stronger hashing algorithm like bcrypt

    // Retrieve Email and Password from Database
    $sql = "SELECT * FROM login WHERE email = '$email' AND password = '$encryptedPassword'";
    $result = mysqli_query($conn, $sql);

    // If Email and Password Match then Login, otherwise show Error Message
    if (mysqli_num_rows($result) == 1) {
        $_SESSION['emailVar'] = $email;
        header("Location: landing.html");
        exit();
    } else {
        // If the Email and Password Don't Match, Show Error Message
        echo '<script>alert("Incorrect Email or Password, Please try again")</script>';
        // Send User to Login Page
        header("refresh:0;url=index.html");
        exit();
    }
}
?>