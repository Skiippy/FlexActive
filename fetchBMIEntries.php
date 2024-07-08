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

$email = mysqli_real_escape_string($conn, $_GET['email']);

// Execute the SQL query
$sql = "SELECT created_at, age, height, weight, gender, bmi FROM bmi_data WHERE email = '$email' ORDER BY created_at DESC";

$result = mysqli_query($conn, $sql);

// Return error if query fails
if (!$result) {
    echo json_encode(['success' => false, 'error' => 'Failed to fetch BMI data: ' . mysqli_error($conn)]);
    exit();
}

// Fetch all rows as associative arrays
$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (empty($rows)) {
    echo json_encode(['success' => true, 'entries' => []]);
} else {
    $entries = [];
    foreach ($rows as $row) {
        $row['bmi'] = (float) $row['bmi'];
        $entries[] = $row;
    }
    echo json_encode(['success' => true, 'entries' => $entries]);
}

// Close the database connection
mysqli_close($conn);
?>