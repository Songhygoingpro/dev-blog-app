<?php
session_start();

include "../config/database.php";

$conn = getDatabaseConnection();
$search_query = '%' . $_GET['search-query'] . '%'; // Add wildcard characters for partial matches

function getPostsBySearch($conn, $search_query) {
    // SQL query with relevance sorting and prepared statements
    $sql = "
        SELECT 
            posts.id, 
            posts.title, 
            posts.image_path,
            posts.author,
            posts.created_at,
            GROUP_CONCAT(tags.name SEPARATOR ', ') AS tags,
            (
                CASE
                    WHEN posts.title LIKE ? THEN 3  -- High relevance if found in title
                    WHEN posts.content LIKE ? THEN 2  -- Medium relevance if found in content
                    ELSE 1  -- Low relevance otherwise
                END
            ) AS relevance
        FROM 
            posts
        LEFT JOIN 
            post_tags ON posts.id = post_tags.post_id
        LEFT JOIN 
            tags ON tags.id = post_tags.tag_id
        WHERE 
            posts.title LIKE ? OR posts.content LIKE ?  -- Filter by search query
        GROUP BY 
            posts.id
        ORDER BY 
            relevance DESC,  -- Sort by relevance first
            posts.created_at DESC";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }

    // Bind parameters to the statement
    $stmt->bind_param('ssss', $search_query, $search_query, $search_query, $search_query);

    // Execute the prepared statement
    $stmt->execute();

    // Get the result set from the executed query
    $result = $stmt->get_result();
    
    // Fetch all results as an associative array
    $posts = $result->fetch_all(MYSQLI_ASSOC);

    // Close the statement
    $stmt->close();

    return $posts;
}


// Fetch posts using the search query
$posts = getPostsBySearch($conn, $search_query);

include '../includes/header.php'; 
?>


<main>
    <section class="search-result py-8 2xl:py-16 px-4 md:px-8 flex justify-center">
        <div class="search-result__inner  flex flex-col gap-6 max-w-[1200px] w-full">
            <h1 class="text-3xl font-bold">Search results</h1>
            <ul class="search-result-container  grid grid-cols sm:grid-cols-2 lg:grid-cols-3 gap-y-10 gap-x-6">

                <?php

                foreach ($posts as $post) {
                    // Format the date
                    $date = new DateTime($post['created_at']);
                    $formattedDate = $date->format('d M Y');
                    $addedTags = explode(', ', $post['tags']);

                    // Create the post URL using the post's ID
                    $postUrl = 'post.php?id=' . $post['id'];

                    // Display the post
                    echo '<li class="grid group">';
                    echo '<a href="' . $postUrl . '" class="flex flex-col gap-2">';
                    echo '<div class="overflow-hidden grid"><img src="../assets/post-img/' . htmlspecialchars($post['image_path']) . '" class="w-full max-h-auto h-48 object-cover object-center group-hover:scale-[1.05] transition-transform duration-300 ease-in-out"></div>';
                    echo '<p class="font-semibold text-sm text-[#6941C6]">' . htmlspecialchars($post['author']) . ' • ' . $formattedDate . '</p>';
                    echo '<h1 class="post-title text-2xl font-bold">' . $post['title'] . '</h1>';

                    if ($post['tags']) {
                        echo '<ul class="flex gap-2" >';
                        for ($i = 0; $i < count($addedTags); $i++) {
                            echo '<li class="tag ">' . $addedTags[$i] . '</li>';
                        }
                        echo '</ul>';
                    }
                    echo '</a>';
                    echo '</li>';
                }
                ?>

            </ul>
        </div>
    </section>
</main>

<?php
// Handle search post suggestions

echo '<script>';
echo 'let postTitles = [];'; // Initialize the JavaScript array

// Loop through the posts and add each title to the JavaScript array
foreach ($posts as $post) {

    $postUrl = 'post.php?id=' . $post['id'];

    $title_and_postUrl = ['title' => $post['title'], 'post_url' => $postUrl];

    echo 'postTitles.push(' . json_encode($title_and_postUrl) . ');'; // Encode each title as a JavaScript string
}

echo '</script>';
?>

<script>

    const availableTags = [{
            name: "webdev",
            color: "lightblue"
        },
        {
            name: "javascript",
            color: "lightgreen"
        },
        {
            name: "css",
            color: "lightyellow"
        },
        {
            name: "html",
            color: "orange"
        }, // Keep the original color
        {
            name: "react",
            color: "lightcoral"
        },
        {
            name: "nodejs",
            color: "lightpurple"
        },
        {
            name: "php",
            color: "lightblue"
        },
        {
            name: "python",
            color: "lightseagreen"
        },
        {
            name: "java",
            color: "limegreen"
        },
        {
            name: "c#",
            color: "lightcyan"
        },
        {
            name: "ruby",
            color: "lightskyblue"
        },
        {
            name: "go",
            color: "lightslategray"
        },
        {
            name: "vue.js",
            color: "lightblue"
        },
        {
            name: "angular",
            color: "lightindigo"
        },
        {
            name: "laravel",
            color: "lightviolet"
        },
        {
            name: "django",
            color: "lightmagenta"
        },
        {
            name: "mysql",
            color: "darkmagenta"
        },
        {
            name: "postgresql",
            color: "deeppink"
        },
        {
            name: "mongodb",
            color: "hotpink"
        },
        {
            name: "redis",
            color: "lightpink"
        },
        {
            name: "devops",
            color: "lightcoral"
        },
        {
            name: "cloud computing",
            color: "lightsalmon"
        },
        {
            name: "aws",
            color: "lightorange"
        },
        {
            name: "azure",
            color: "darkorange"
        },
        {
            name: "gcp",
            color: "chocolate"
        }, // Keep the original color
        {
            name: "docker",
            color: "saddlebrown"
        },
        {
            name: "kubernetes",
            color: "sandybrown"
        },
        {
            name: "ai",
            color: "gold"
        },
        {
            name: "ml",
            color: "yellowgreen"
        },
        {
            name: "data science",
            color: "lightgreen"
        },
        {
            name: "blockchain",
            color: "lawngreen"
        },
        {
            name: "cybersecurity",
            color: "chartreuse"
        },
        {
            name: "iot",
            color: "lightgreen"
        },
        {
            name: "ar",
            color: "lightgreen"
        },
        {
            name: "vr",
            color: "palegreen"
        }
    ];

    const tags = document.querySelectorAll('.tag');


    for (let addedTag of tags) {

        const tagData = availableTags.find(tag => tag.name === addedTag.textContent.slice(1));
        const tagColor = tagData ? tagData.color : 'lightgray';
        addedTag.style = `background-color: ${tagColor}; `;
    }

   
</script>

<?php

include '../includes/footer.php';

?>