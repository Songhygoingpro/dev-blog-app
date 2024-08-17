<?php
require '../config/database.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usernameSignup = $_POST['username'];
    $pwdSignup = $_POST['pwdSignup'];

    $pwdSignup_hashed =  password_hash($pwdSignup, PASSWORD_DEFAULT);

    $_SESSION['usernameSignup'] = $usernameSignup;

    $stmtSignup = $conn->prepare('INSERT INTO users (username, password_hash) VALUES (?,?)');

    if ($stmtSignup === false) {
        die('Error preparing the statement: ' . $conn->error);
    }

    $stmtSignup->bind_param("ss", $usernameSignup, $pwdSignup_hashed);

    if ($stmtSignup->execute()) {
        echo "New record created successfully";
        header("Location: ../public/index.php");
        $_SESSION['signupSucceeded'] = true;
    } else {
        echo "Error: " . $stmtSignup->error;
    }

    $stmtSignup->close();
    $conn->close();
}
