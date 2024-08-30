<?php

include "../config/database.php";

//Query order by created date
// src/posts.php
function getAllPosts($conn) {
    $sql = "
        SELECT 
            posts.id, 
            posts.title, 
            posts.content, 
            posts.image_path,
            posts.author,
            posts.created_at,
            GROUP_CONCAT(tags.name SEPARATOR ', ') AS tags
        FROM 
            posts
        LEFT JOIN 
            post_tags ON posts.id = post_tags.post_id
        LEFT JOIN 
            tags ON tags.id = post_tags.tag_id
        GROUP BY 
            posts.id
        ORDER BY 
            posts.created_at DESC";

    $result = $conn->query($sql);

    if ($result === false) {
        // Handle query error
        die('Error: ' . $conn->error);
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}


//Query order by id
function getPostById($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}
