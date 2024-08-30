
<?php

// Connect to the database
include '../config/database.php';

// Get the post ID from the URL
$postId = $_GET['id'];

// Fetch the post from the database
$sql = "
    SELECT 
        posts.*, 
        GROUP_CONCAT(tags.name SEPARATOR ', ') AS tags
    FROM 
        posts
    LEFT JOIN 
        post_tags ON posts.id = post_tags.post_id
    LEFT JOIN 
        tags ON post_tags.tag_id = tags.id
    WHERE 
        posts.id = ?
    GROUP BY 
        posts.id
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $postId);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

$pageTitle = $post['title'];
include '../includes/header.php';

$addedTags = explode(', ', $post['tags']);
?>

<main>
    <section class="blog-post-section flex justify-center items-center py-4">
        <div class="blog-post-section__inner flex justify-center container px-4">
            <div class="blog-post bg-white rounded-md max-w-[950px] w-full">
                <?php if ($post): ?>
                    <div class='w-full max-h-[350px] h-full'>
                        <img class='w-full h-full object-cover object-center rounded-t-md' src='../assets/post-img/<?= $post['image_path'] ?>'>
                    </div>
                    <div class='p-16 flex flex-col gap-8'>
                        <div class='blog-post-profile flex gap-4'>
                            <a href=''><img class='w-8 h-auto' src='../assets/img/profile-picture.png'></a>
                            <div>
                                <p class='font-semibold text-xl'><?= $post['author'] ?></p>
                                <p class='text-sm'><?= (new DateTime($post['created_at']))->format('d M Y') ?></p>
                            </div>
                        </div>
                      <h1 class="text-3xl lg:text-5xl font-bold"> <?= $post['title'] ?> </h1>
                        <?php if ($post['tags']) : ?>
                            <ul class="tags flex gap-2">
                                <?php
                                for ($i = 0; $i < count($addedTags); $i++) {
                                    echo '<li class="tag">' . htmlspecialchars($addedTags[$i], ENT_QUOTES, 'UTF-8') . '</li>';
                                }
                                ?>
                            </ul>
                        <?php endif; ?>
                        <div class="flex flex-col gap-4"><?= $post['content'] ?></div>
                        </di>
                    <?php endif; ?>

                    </div>
            </div>
    </section>
</main>
<link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.25.0/themes/prism-tomorrow.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.25.0/prism.min.js"></script>
<script>
    const availableTags = [
  { name: "webdev", color: "lightblue" },
  { name: "javascript", color: "lightgreen" },
  { name: "css", color: "lightyellow" },
  { name: "html", color: "orange" }, // Keep the original color
  { name: "react", color: "lightcoral" },
  { name: "nodejs", color: "lightpurple" },
  { name: "php", color: "lightblue" },
  { name: "python", color: "lightseagreen" },
  { name: "java", color: "limegreen" },
  { name: "c#", color: "lightcyan" },
  { name: "ruby", color: "lightskyblue" },
  { name: "go", color: "lightslategray" },
  { name: "vue.js", color: "lightblue" },
  { name: "angular", color: "lightindigo" },
  { name: "laravel", color: "lightviolet" },
  { name: "django", color: "lightmagenta" },
  { name: "mysql", color: "darkmagenta" },
  { name: "postgresql", color: "deeppink" },
  { name: "mongodb", color: "hotpink" },
  { name: "redis", color: "lightpink" },
  { name: "devops", color: "lightcoral" },
  { name: "cloud computing", color: "lightsalmon" },
  { name: "aws", color: "lightorange" },
  { name: "azure", color: "darkorange" },
  { name: "gcp", color: "chocolate" }, // Keep the original color
  { name: "docker", color: "saddlebrown" },
  { name: "kubernetes", color: "sandybrown" },
  { name: "ai", color: "gold" },
  { name: "ml", color: "yellowgreen" },
  { name: "data science", color: "lightgreen" },
  { name: "blockchain", color: "lawngreen" },
  { name: "cybersecurity", color: "chartreuse" },
  { name: "iot", color: "lightgreen" },
  { name: "ar", color: "lightgreen" },
  { name: "vr", color: "palegreen" }
];

    const tags = document.querySelectorAll('.tag');


    for (let addedTag of tags) {

        const tagData = availableTags.find(tag => tag.name === addedTag.textContent.slice(1));
        const tagColor = tagData ? tagData.color : 'gray';
        addedTag.style = `background-color: ${tagColor}; `;
    }
</script>

<?php

include "../includes/footer.php";

?>
