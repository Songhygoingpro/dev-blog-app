<?php

require '../config/database.php';
$conn = getDatabaseConnection();
session_start();

// Allowed file types (restrict to images)
$allowedTypes = ['image/jpeg', 'image/png'];
$maxFileSize = 2 * 1024 * 1024; // 2 MB file size limit

$response = []; // Response array to return as JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pf_pic = $_FILES['upload-pf-pic'];

    $image = $pf_pic['tmp_name'];
    $imageName = basename($pf_pic['name']);
    $imageSize = $pf_pic['size'];
    $imageType = mime_content_type($image); // Get MIME type of file

    // Check file type
    if (!in_array($imageType, $allowedTypes)) {
        $response['success'] = false;
        $response['error'] = 'Invalid file type. Only JPG and PNG are allowed.';
        echo json_encode($response);
        exit;
    }

    // Check file size
    if ($imageSize > $maxFileSize) {
        $response['success'] = false;
        $response['error'] = 'File size too large. Max allowed size is 2 MB.';
        echo json_encode($response);
        exit;
    }

    // Generate unique file name to avoid overwriting
    $uniqueImageName = uniqid() . "_" . $imageName;
    $imagePath = '../assets/pf-pic-img/' . $uniqueImageName;
    $_SESSION['pf_image_path'] = $imagePath; // Storing the path in session (optional)

    // Step 1: Fetch the current image path for the user to delete the old image
    $stmt = $conn->prepare("SELECT image_path FROM users WHERE username = ?");
    $stmt->bind_param('s', $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    $currentImage = $result->fetch_assoc();

    // Step 2: Delete the old image file if it exists
    if (!empty($currentImage['image_path'])) {
        $oldImagePath = $currentImage['image_path'];
        if (file_exists($oldImagePath)) {
            unlink($oldImagePath);  // Delete the old image
        }
    }

    // Step 3: Move the new uploaded file to the destination folder
    if (move_uploaded_file($image, $imagePath)) {

        // Step 4: Update the new image path in the database
        $stmt = $conn->prepare("UPDATE users SET image_path = ? WHERE username = ?");
        $stmt->bind_param('ss', $imagePath, $_SESSION['username']);

        if ($stmt->execute()) {
            header('location: ../public/profile.php');
        } else {
            $response['success'] = false;
            $response['error'] = 'Database update failed: ' . $stmt->error;
            echo json_encode($response);
        }
    } else {
        $response['success'] = false;
        $response['error'] = 'Failed to move the uploaded file.';
        echo json_encode($response);
    }
} else {
    $response['success'] = false;
    $response['error'] = 'Invalid request method.';
    echo json_encode($response);
}
