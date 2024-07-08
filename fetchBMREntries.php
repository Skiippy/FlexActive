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

$email = mysqli_real_escape_string($conn, $_SESSION['emailVar']);

// SQL query to retrieve BMR data for the specified email, ordered by creation date descending
$sql = "SELECT * FROM bmr_data WHERE email = '$email' ORDER BY created_at DESC";
// Execute the SQL query
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(['success' => false, 'error' => 'Query error: ' . mysqli_error($conn)]);
    exit();
}

$entries = [];
while ($row = mysqli_fetch_assoc($result)) {
    $entries[] = [
        'created_at' => date('Y-m-d H:i:s', strtotime($row['created_at'])),
        'age' => $row['age'],
        'height' => $row['height'],
        'weight' => $row['weight'],
        'gender' => $row['gender'],
        'bmr' => floatval($row['bmr'])
    ];
}

echo json_encode(['success' => true, 'entries' => $entries]);

// Close the database connection
mysqli_close($conn);
?>