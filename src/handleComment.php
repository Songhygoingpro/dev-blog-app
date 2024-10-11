<?php
session_start();
require '../config/database.php';
$conn = getDatabaseConnection();

$content = htmlspecialchars($_POST['comment'], ENT_QUOTES, 'UTF-8');
$user_id = $_SESSION['user_id'];
$post_id = $_POST['postID'];

$response = ['success' => false]; // Default response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insert the comment into the database
    $stmt = $conn->prepare('INSERT INTO comments (content, user_id, post_id, created_at) VALUES (?, ?, ?, NOW())');
    $stmt->bind_param('sii', $content, $user_id, $post_id);
    
    if ($stmt->execute()) {
        $comment_id = $stmt->insert_id;
        $stmt->close();

        // Fetch the newly inserted comment with user data
        $stmt = $conn->prepare('SELECT  comments.content, comments.created_at, users.image_path, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE comments.id = ?');
        $stmt->bind_param('i', $comment_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $comment = $result->fetch_assoc();
        
        $response = [
            'success' => true,
            'comment' => [
                'id' => $comment_id,
                'content' => $comment['content'],
                'created_at' => (new DateTime($comment['created_at']))->format('d M Y'),
                'image_path' => $comment['image_path'],
                'username' => $comment['username']
            ]
        ];
    }

    $stmt->close();
}

// Send the response back as JSON
header('Content-Type: application/json');
echo json_encode($response);

