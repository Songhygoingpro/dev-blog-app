<?php

require "../config/database.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $cover_image = $_FILES['cover_image'];
    $post_title = htmlspecialchars($_POST['title']);
    $tags = htmlspecialchars($_POST['tags']);
    $tagsArray = explode(',', $tags);
    $content = $_POST['content'];
    $author = $_SESSION['username'];

    $image = $cover_image['tmp_name'];
    $imageName = basename($cover_image['name']);
    $imagePath = '../assets/post-img/' . $imageName; // Ensure full path for moving the file

    function parseContent($content)
    {
        // Convert Markdown-like syntax to HTML
        $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8'); // Prevent XSS

        // Convert **bold** or __bold__ to <strong>
        $content = preg_replace('/(\*\*|__)(.*?)\1/', '<strong>$2</strong>', $content);

        // Convert _italic_ or *italic* to <em>
        $content = preg_replace('/(\*|_)(.*?)\1/', '<em>$2</em>', $content);

        // Convert ## Heading to <h2 class="font-bold text-2xl">Heading</h2>
        $content = preg_replace('/^\#\# (.*)$/m', '<h2 class="font-bold text-2xl">$1</h2>', $content);

        // Convert ```code block``` to <pre><code>
        $content = preg_replace('/```[\r\n]*(.*?)```/s', '<pre><code class="language-javascript">$1</code></pre>', $content);

        // Convert `code` to <code>
        $content = preg_replace('/(\`|__)(.*?)\1/', '<code>$2</code>', $content);

$content = preg_replace('/<p>\s*<\/p>/', '', $content);


        // Wrap text blocks in <p> tags, but avoid wrapping inside code blocks
        $lines = explode("\n", $content);
        foreach ($lines as &$line) {
            if (!preg_match('/^\s*$/', $line) && !preg_match('/<(h1|h2|h3|pre|ul|ol|li|blockquote)[^>]*>/', $line)) {
                $line = '<p>' . trim($line) . '</p>';
            }
        }
        $content = implode("\n", $lines);

        return $content;
    }

    $formattedContent = parseContent($content);


    // Check if file upload is successful
    if (move_uploaded_file($image, $imagePath)) {
        // Insert the post into the `posts` table
        $stmt = $conn->prepare("INSERT INTO posts (title, content, author, image_path) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $post_title, $formattedContent, $author, $imageName);

        if ($stmt->execute()) {
            $post_id = $conn->insert_id; // Get the ID of the newly inserted post

            // Now handle the tags
            $tagIds = []; // Array to store tag IDs

            // Prepare a statement to insert new tags
            $tagStmt = $conn->prepare("INSERT INTO tags (name) VALUES (?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)");
            if ($tagStmt === false) {
                die('Prepare failed: ' . $conn->error);
            }

            foreach ($tagsArray as $tag) {
                $tag = trim($tag); // Remove any extra spaces
                $tagStmt->bind_param('s', $tag);

                if ($tagStmt->execute()) {
                    // Get the ID of the inserted/updated tag
                    $tagIds[] = $conn->insert_id;
                } else {
                    die('Execute failed: ' . $tagStmt->error);
                }
            }

            $tagStmt->close(); // Close the statement

            // Link tags to the post
            if (!empty($tagIds)) {
                $postTagStmt = $conn->prepare("INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)");
                if ($postTagStmt === false) {
                    die('Prepare failed: ' . $conn->error);
                }

                foreach ($tagIds as $tag_id) {
                    $postTagStmt->bind_param('ii', $post_id, $tag_id);

                    if (!$postTagStmt->execute()) {
                        die('Execute failed: ' . $postTagStmt->error);
                    }
                }

                $postTagStmt->close(); // Close the statement
            }

            // Redirect to the index page after successful insertion
            header('location: ../public/index.php');
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close(); // Close the statement
    } else {
        echo "Failed to upload the image.";
    }
}
