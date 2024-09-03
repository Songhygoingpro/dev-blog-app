<?php
$pageTitle = 'Blog App';
$customHeadContent = '<meta name="description" content="Home page of My App">';
include "../includes/header.php";
include "../src/posts.php";
$posts = getAllPosts($conn);

?>

<main>
    <section class="allPost-seciton py-8 2xl:py-16 px-4 md:px-8 flex justify-center">
        <div class="allPost-section__inner flex flex-col gap-6 max-w-[1200px] w-full">
            <h2 class="text-3xl font-bold">
                All blog posts
            </h2>
            <ul class="posts-container grid grid-cols sm:grid-cols-2 lg:grid-cols-3 gap-y-10 gap-x-6">
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
// Assuming $posts is an array containing the posts fetched from your database

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
    console.log(postTitles);

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

<?php include "../includes/footer.php" ?>