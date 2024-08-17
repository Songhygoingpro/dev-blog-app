<?php

require "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$cover_image = $_FILES['cover_image'];
$post_title = htmlspecialchars($_POST['title']);
$tags = htmlspecialchars($_POST['tags']);
$content = htmlspecialchars($_POST['content']);

$image = $_FILES['image']['tmp_name'];
$imagePath = '../asset/post-img/' . basename($_FILES['image']['name']);

// Move the uploaded file to the desired directory
move_uploaded_file($image, $imagePath);

// Insert the post with the image path
$stmt = $conn->prepare("INSERT INTO posts (title, content, image_path) VALUES (?, ?, ?)");
$stmt->bind_param("ssb", $post_title, $content, $imagePath);
$stmt->execute();

header('location: ../public/index.php');

}