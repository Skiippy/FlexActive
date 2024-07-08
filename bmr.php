<?php
// Start the PHP session to manage user session data
session_start();

header('Content-Type: application/json');

// Check if user is not logged in
if (!isset($_SESSION['emailVar'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in.']);
    exit();
}

// Database Setup
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'fitness';

// Connect to the database
$conn = mysqli_connect($host, $username, $password, $database);

// Check database connection
if (!$conn) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed: ' . mysqli_connect_error()]);
    exit();
}

// Process POST request data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Prevent SQL injection
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $age = mysqli_real_escape_string($conn, $_POST['age']);
    $height = mysqli_real_escape_string($conn, $_POST['height']);
    $weight = mysqli_real_escape_string($conn, $_POST['weight']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);

    // Calculate BMR
    $bmr = (10 * $weight) + (6.25 * $height) - (5 * $age);
    if ($gender == "male") {
        $bmr += 5;
    } elseif ($gender == "female") {
        $bmr -= 161;
    }

    // Calculate activity levels using BMR
    $activityLevels = [
        'sedentary' => $bmr * 1.2,
        'light' => $bmr * 1.375,
        'moderate' => $bmr * 1.55,
        'veryActive' => $bmr * 1.725,
        'superActive' => $bmr * 1.9
    ];

    // SQL query to insert BMR data into the database
    $sql = "INSERT INTO bmr_data (email, age, height, weight, gender, bmr) 
            VALUES ('$email', '$age', '$height', '$weight', '$gender', '$bmr')";

    // Execute the SQL query
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true, 'bmr' => $bmr, 'activityLevels' => $activityLevels]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database error: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}

// Close the database connection
mysqli_close($conn);
?>