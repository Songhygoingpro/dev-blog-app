<?php
require "../config/database.php";

session_start();

// Retrieve form data
$usernameLogin = isset($_POST['usernameLogin']) ? $_POST['usernameLogin'] : '';
$pwdLogin = $_POST['pwdLogin'];

$_SESSION['usernameLogin'] = $usernameLogin;

// Prepare and execute the query
$stmt = $conn->prepare('SELECT password_hash FROM users WHERE username = ?');
$stmt->bind_param("s", $usernameLogin);
$stmt->execute();
$result = $stmt->get_result();

// Initialize response array
$response = [];

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $pwdHashFromDb = $row['password_hash'];

    // Verify the password
    if (password_verify($pwdLogin, $pwdHashFromDb)) {
        $response['status'] = 'success'; // Password is correct
        $_SESSION['logSucceeded'] = true;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Invalid password.'; // Invalid password
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'No user found with that username.'; // Username not found
}

// Set the Content-Type header to JSON
header('Content-Type: application/json');

// Encode the response array to JSON and send it
echo json_encode($response);

