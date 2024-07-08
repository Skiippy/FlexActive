<?php
// Connect Variables
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$encryptedPassword = md5($password);
// Set Variables
$host = 'sql106.infinityfree.com';
$dbUsername = 'if0_36862596';
$dbPassword = 'FlexActiveAdmin';
$dbname = 'if0_36862596_fitness';
// Link to Database
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbname);
// Error Message if Connection Fails
if (mysqli_connect_error()) {
    die('Connect Error(' . mysqli_connect_error() . ')' . mysqli_connect_error());
} else {
    // Ensures Only One Email per Person
    $SELECT = "SELECT email FROM login WHERE email = ? LIMIT 1";
    // Insert Data into Database
    $INSERT = "INSERT INTO login (name, email, password) VALUES(?,?,?)";

    $stmt = $conn->prepare($SELECT);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($email);
    $stmt->store_result();
    $rnum = $stmt->num_rows;

    if ($rnum == 0) {
        $stmt->close();
        $stmt = $conn->prepare($INSERT);
        $stmt->bind_param("sss", $name, $email, $encryptedPassword);
        $stmt->execute();
        // Verify Record was Inserted Into the Database
        echo '<script>alert("You have Signed Up Successfully")</script>';
        // Send User to Login Page
        header("refresh:0;url=index.html");
        exit();
    } else {
        // Error that Email has been Taken
        echo '<script>alert("Email has been taken")</script>';
        // Send User to Signup Page
        header("refresh:0;url=signup.html");
    }
    $stmt->close();
    // Close the database connection
    $conn->close();
}
?>