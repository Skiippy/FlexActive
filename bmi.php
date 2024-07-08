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
$host = 'sql106.infinityfree.com';
$username = 'if0_36862596';
$password = 'FlexActiveAdmin';
$database = 'if0_36862596_fitness';

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

    // Calculate BMI
    $height_in_meters = $height / 100;
    $bmi = $weight / ($height_in_meters * $height_in_meters);

    // Determine BMI category
    if ($bmi < 18.5) {
        $category = "Underweight";
    } elseif ($bmi < 24.9) {
        $category = "Normal weight";
    } elseif ($bmi < 29.9) {
        $category = "Overweight";
    } else {
        $category = "Obese";
    }

    // SQL query to insert BMI data into the database
    $sql = "INSERT INTO bmi_data (email, age, height, weight, gender, bmi, category) 
            VALUES ('$email', '$age', '$height', '$weight', '$gender', '$bmi', '$category')";

    // Execute the SQL query
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true, 'bmi' => $bmi, 'category' => $category]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database error: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}

// Close the database connection
mysqli_close($conn);
?>