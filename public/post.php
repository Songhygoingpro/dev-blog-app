<?php
session_start();

include "../src/posts.php";

// Connect to the database
// Get the post ID from the URL
$postId = $_GET['id'];

// Fetch the post from the database
$sql = "
    SELECT 
        posts.*, 
        GROUP_CONCAT(tags.name SEPARATOR ', ') AS tags,
        users.image_path AS pf_pic,
        users.id AS user_id,
        count(post_id) as total_comments
    FROM 
        posts
    LEFT JOIN 
        post_tags ON posts.id = post_tags.post_id
    LEFT JOIN 
        tags ON post_tags.tag_id = tags.id
    LEFT JOIN 
        users on users.username = posts.author
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

$sqlComment = "
   SELECT comments.id, comments.parent_comment_id, comments.content, comments.user_id, comments.created_at, users.image_path, users.username
FROM comments
JOIN users ON comments.user_id = users.id
WHERE comments.post_id = ?
ORDER BY comments.created_at ASC;
    ";

$commentstmt = $conn->prepare($sqlComment);
$commentstmt->bind_param('i', $postId);
$commentstmt->execute();
$resultComment = $commentstmt->get_result();


$pageTitle = $post['title'];
$profile_url = 'profile.php?id=' . $post['user_id'];
$posts = getAllPosts($conn);

$addedTags = explode(', ', $post['tags']);
$pf_pic_path = isset($_SESSION['pf_image_path']) ? $_SESSION['pf_image_path'] : '../assets/img/profile-picture.png';
foreach ($posts as $postss) {
    $postUrl = 'post.php?id=' . $postss['id'];
}
$pf_pic = isset($post['pf_pic']) ? $post['pf_pic'] : '../assets/img/profile-picture.png';
include '../includes/header.php';
?>

<main>
    <section class="blog-post-section flex justify-center items-center py-4">
        <div class="blog-post-section__inner flex justify-center container px-4">
            <div class="blog-post bg-white rounded-md max-w-[950px] w-full">
                <?php if ($post): ?>
                    <div class='w-full max-h-[350px] h-full'>
                        <img class='w-full h-full object-cover object-center rounded-t-md'
                            src='../assets/post-img/<?= $post['image_path'] ?>'>
                    </div>
                    <div class='p-16 flex flex-col gap-8'>
                        <div class='blog-post-profile flex gap-4'>
                            <a href='<?= $profile_url ?>' class="flex gap-2"><img class='w-12 h-auto rounded-[50%]'
                                    src='<?= $pf_pic  ?>'>
                                <div>
                                    <p class='font-semibold text-xl'><?= $post['author'] ?></p>
                                    <p class='text-sm'><?= (new DateTime($post['created_at']))->format('d M Y') ?></p>
                                </div>
                            </a>
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
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </section>
    <section class="comment mx-auto py-8">
        <div class="comment__inner grid gap-8 mx-auto max-w-[950px] w-full px-16">
            <h1 class="text-3xl font-bold">Top Comments (<span class="total_comments"></span>)</h1>
            <div class="add-comment-form flex gap-4">
                <img src="<?= $pf_pic_path ?>" class="w-12 rounded-[50%] h-fit">
                <form class="comment-form grid gap-4 w-full" action="../src/handleComment.php" method="post"
                    enctype="multipart/form-data">
                    <input type="number" class="hidden" name="postID" value="<?= $postId ?>">
                    <textarea class="w-full  pt-2 pl-2 rounded-md" name="comment" id="comment-input"
                        placeholder="Add to the discussion" rows="3"></textarea>
                    <button
                        class="comment-submit-button rounded-md bg-sky-500 text-center font-semibold text-white cursor-not-allowed opacity-50 px-6 py-2 w-fit"
                        type="button">Submit</button>
                </form>
            </div>
            <ul class="comments flex flex-col-reverse gap-8">
                <?php while ($parentComment = $resultComment->fetch_assoc()) {
                    // Check if the comment is a parent (i.e., no parent_comment_id or it's NULL)
                    if (is_null($parentComment['parent_comment_id'])) { ?>
                        <li class="comment-<?= $parentComment['id'] ?> grid gap-6 root">
                            <div class="comment--inner flex gap-4">
                                <a class="h-fit" href="<?= 'profile.php?id=' . $parentComment['user_id'] ?>">
                                    <img class='w-12 h-fit rounded-[50%]' src="<?= $parentComment['image_path'] ?>">
                                </a>
                                <div class="w-full grid gap-2 relative">
                                    <div class="space-y-4 p-4 rounded-md border border-gray-500 w-full">
                                        <div class="flex gap-4 items-center">
                                            <p class="font-semibold"><?= $parentComment['username'] ?></p>
                                            <span class="text-sm text-gray-500"> • <?php
                                                                                    $createdAt = new DateTime($parentComment['created_at']);
                                                                                    $currentYear = (new DateTime())->format('Y');
                                                                                    $commentYear = $createdAt->format('Y');

                                                                                    // Display formatted date
                                                                                    echo ($commentYear === $currentYear)
                                                                                        ? $createdAt->format('d M')
                                                                                        : $createdAt->format('d M Y');
                                                                                    ?>
                                            </span>
                                        </div>
                                        <p><?= $parentComment['content'] ?></p>
                                    </div>

                                    <form class="reply-comment-form" action="../src/handleReply.php" method="post" enctype="multipart/form-data">
                                        <input class="hidden parent_comment_id" name='parentCommentID' type='number' value="<?= $parentComment['id'] ?>" />
                                        <input type="number" class="hidden" name="postID" value="<?= $postId ?>">
                                        <div class="likes-comments flex gap-4 transition duration-300 ease-in-out">
                                            <button class="like flex gap-2 items-center" type="button">
                                                <img class="w-4 h-[20px]" src="../assets/img/like.png" alt>
                                                <p><span class="like-amount"></span>likes</p>
                                            </button>
                                            <button class="reply flex gap-2 items-center" type="button">
                                                <img class="w-4 h-[20px]" src="../assets/img/reply.png" alt>
                                                <p>reply</p>
                                            </button>
                                        </div>
                                        <div class="reply-section grid gap-2 w-full hidden transition duration-300 ease-in-out">
                                            <textarea class="reply-textarea-input pt-2 pl-2 rounded-md" name="comment" placeholder="Add to the discussion" rows="3"></textarea>
                                            <div class="reply-actions flex gap-2">
                                                <button class="reply-submit-btn rounded-md bg-sky-500 text-center font-semibold text-white cursor-not-allowed opacity-50 px-6 py-2 w-fit" type="button">Submit</button>
                                                <button class="reply-dismiss-btn rounded-md text-gray-600 hover:text-black bg-gray-300 hover:bg-gray-400 text-center font-semibold opacity-50 px-6 py-2 w-fit" type="button">Dismiss</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Replies Section -->
                            <ul class="reply-comments-<?= $parentComment['id'] ?> grid gap-4  reply-comments hidden">
                                <?php
                                // Query for replies to this parent comment
                                $replyQuery = " SELECT comments.id, comments.parent_comment_id, comments.content, comments.user_id, comments.created_at, users.image_path, users.username
                                FROM comments
                                JOIN users ON comments.user_id = users.id
                                WHERE comments.parent_comment_id = ?
                                ORDER BY comments.created_at ASC;";
                                $replystmt = $conn->prepare($replyQuery);
                                $replystmt->bind_param('i', $parentComment['id']);
                                $replystmt->execute();
                                $resultReply = $replystmt->get_result();

                                // Loop through replies
                                while ($reply = $resultReply->fetch_assoc()) {

                                ?>
                                    <li class="reply-comment flex gap-4 w-[96%] justify-self-end">
                                        <a class="h-fit" href="profile.php?id=<?= $reply['user_id'] ?>">
                                            <img class="w-12 h-fit rounded-[50%]" src="<?= $reply['image_path'] ?>" alt="Profile Image">
                                        </a>
                                        <div class="w-full grid gap-2 relative">
                                            <div class="space-y-4 p-4 rounded-md border border-gray-500 w-full">
                                                <div class="flex gap-4 items-center">
                                                    <p class="font-semibold"><?= $reply['username'] ?></p>
                                                    <span class="text-sm text-gray-500"> • <?php
                                                                                            $replyCreatedAt = new DateTime($reply['created_at']);
                                                                                            $replyYear = $replyCreatedAt->format('Y');
                                                                                            echo ($replyYear === $currentYear)
                                                                                                ? $replyCreatedAt->format('d M')
                                                                                                : $replyCreatedAt->format('d M Y');
                                                                                            ?>
                                                    </span>
                                                </div>
                                                <p><?= $reply['content'] ?></p>
                                            </div>
                                        </div>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>
                <?php }
                } ?>
            </ul>

            <?php $commentstmt->close(); ?>
        </div>
    </section>
    <div class="login-model grid justify-center items-center fixed inset-0 bg-black bg-opacity-50 hidden">
        <div class="login-model-container rounded-xl grid gap-6 w-96 h-auto bg-white p-6">
            <div class="login-model-header flex  justify-between h-fit">
                <h2 class="text-2xl font-semibold ">Log in to continue</h2>
                <button class="modal-close rounded-md hover:bg-gray-200 p-2"><img class="w-4 h-auto" src="../assets/img/Close-icon.png"></button>
            </div>
            <hr class="w-auto h-[1px] bg-gray-400">
            <div class="login-model-btns grid gap-2">
                <a href="../public/login.php" class="text-center py-2 font-semibold hover:bg-sky-600 bg-sky-500 rounded-md text-white w-full">Log in</a>
                <a href="../public/register.php" class="text-sky-500 py-2 font-semibold rounded-md hover:bg-gray-200 text-center w-full">Create an account</a>
            </div>
        </div>
    </div>
</main>
<?php
// Assuming $posts is an array containing the posts fetched from your database

echo '<script>';
echo 'let postTitles = [];'; // Initialize the JavaScript array

// Only if login/signup failed, show login modal when user clicks
if (!$logSucceeded && !$signupSucceeded) {

    echo '
   document.querySelector(".modal-close").addEventListener("click", function(){
        document.querySelector(".login-model").classList.toggle("hidden");
});

    document.getElementById("comment-input").addEventListener("focus", function() {
        document.querySelector(".login-model").classList.toggle("hidden");
    });

    document.querySelectorAll(".reply").forEach(replyBtn => replyBtn.addEventListener("click", function() {
        document.querySelector(".login-model").classList.toggle("hidden");
    }));

    document.querySelectorAll(".reply-section").forEach(replySection => replySection.classList.add("hidden"));
    ';
} else {

    echo '   document.querySelectorAll(".reply").forEach(replyBtn => {

    replyBtn.addEventListener("click", function() {
    const scrollPosition = window.scrollY;
       const replySection = replyBtn.closest("form").querySelector(".reply-section");
        replySection.classList.toggle("hidden");
        replyBtn.closest(".likes-comments").classList.toggle("hidden");
        window.scrollTo(0, scrollPosition);
    });

});';
}

// Loop through the posts and add each title and post URL to the JavaScript array
foreach ($posts as $postss) {
    $postUrl = 'post.php?id=' . $postss['id']; // Generate the URL
    $title_and_postUrl = ['title' => $postss['title'], 'post_url' => $postUrl];

    // Encode each title and post URL as a JavaScript object and push to the array
    echo 'postTitles.push(' . json_encode($title_and_postUrl) . ');';
}

echo '</script>';
?>


<link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.25.0/themes/prism-tomorrow.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.25.0/prism.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.28.0/prism.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.28.0/components/prism-javascript.min.js"></script>
<script>
    const comment_input = document.getElementById('comment-input');
    const comment_submit_button = document.querySelector('.comment-submit-button');
    const comment_form = document.querySelector('.comment-form');

    comment_input.addEventListener('input', function() {
        if (comment_input.value.trim() !== '') {
            comment_submit_button.classList.remove('opacity-50');
            comment_submit_button.classList.remove('cursor-not-allowed');
            comment_submit_button.setAttribute('type', 'submit');
        } else {
            comment_submit_button.classList.add('opacity-50');
            comment_submit_button.classList.add('cursor-not-allowed');
            comment_submit_button.setAttribute('type', 'button');

        }
    });

    document.querySelector('.total_comments').textContent = document.querySelectorAll('.comments li').length;
    comment_form.addEventListener('submit', function(event) {
        event.preventDefault();

        const commentInput = document.getElementById('comment-input');
        const postID = document.querySelector('input[name="postID"]').value;

        if (commentInput.value.trim() === '') return; // Don't submit empty comments

        const formData = new FormData();
        formData.append('comment', commentInput.value);
        formData.append('postID', postID);

        fetch('../src/handleComment.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Get the current date and format it
                    const createdAt = new Date(data.comment.created_at);
                    const currentYear = new Date().getFullYear();
                    const commentYear = createdAt.getFullYear();
                    let formattedDate;

                    // If the comment year matches the current year, show only day and month
                    if (commentYear === currentYear) {
                        formattedDate = createdAt.toLocaleDateString(undefined, {
                            day: '2-digit',
                            month: 'short'
                        });
                    } else {
                        formattedDate = createdAt.toLocaleDateString(undefined, {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric'
                        });
                    }

                    // Create the new comment HTML element
                    const commentItem = document.createElement('li');
                    commentItem.classList.add(`comment-${data.comment.id}`, 'grid', 'gap-6');
                    commentItem.innerHTML = `
    <div class="comment--inner flex gap-4">
        <a class="h-fit" href="profile.php?id=${data.comment.user_id}">
            <img class="w-12 h-fit rounded-[50%]" src="${data.comment.image_path}" alt="Profile Image">
        </a>
        <div class="w-full grid gap-2 relative">
            <div class="space-y-4 p-4 rounded-md border border-gray-500 w-full">
                <div class="flex gap-4 items-center">
                    <p class="font-semibold">${data.comment.username}</p>
                    <span class="text-sm text-gray-500"> • ${formattedDate}</span>
                </div>
                <p>${data.comment.content}</p>
            </div>

            <form class="reply-comment-form" enctype="multipart/form-data">
                <input class="hidden parent_comment_id" name='parentCommentID' type='number' value="${data.comment.id}" />
                <div class="likes-comments flex gap-4 transition duration-300 ease-in-out">
                    <button class="like flex gap-2 items-center" type="button">
                        <img class="w-4 h-[20px]" src="../assets/img/like.png" alt="Like">
                        <p><span class="like-amount"></span> likes</p>
                    </button>
                    <button class="reply flex gap-2 items-center" type="button">
                        <img class="w-4 h-[20px]" src="../assets/img/reply.png" alt="Reply">
                        <p>reply</p>
                    </button>
                </div>
                <div class="reply-section grid gap-2 w-full hidden transition duration-300 ease-in-out">
                    <textarea class="reply-textarea-input pt-2 pl-2 rounded-md" name="comment" placeholder="Add to the discussion" rows="3"></textarea>
                    <div class="reply-actions flex gap-2">
                        <button class="reply-submit-btn rounded-md bg-sky-500 text-center font-semibold text-white cursor-not-allowed opacity-50 px-6 py-2 w-fit" type="button">Submit</button>
                        <button class="reply-dismiss-btn rounded-md text-gray-600 hover:text-black bg-gray-300 hover:bg-gray-400 text-center font-semibold opacity-50 px-6 py-2 w-fit" type="button">Dismiss</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
     <ul class="reply-comments-${data.comment.id} grid gap-4 justify-end reply-comments hidden">

                        </ul>
`;

                    // Append the new comment to the list
                    document.querySelector('.comments').appendChild(commentItem);
                    document.querySelector('.total_comments').textContent = document.querySelectorAll('.comments li').length;
                    // Optionally reset the form after submission
                    document.querySelector('.comment-form').reset();

                } else {
                    console.log('Error adding comment:', data.error);
                }
            })
            .catch(error => console.error('Error:', error));
    });

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
        },
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
        },
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

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.reply-comments').forEach(el => {
            if (el.childElementCount > 0) {
                el.classList.toggle('hidden');
            }
        });
    });

    document.querySelector('.comments').addEventListener('click', function(event) {
        const commentItem = event.target.closest('li');
        if (!commentItem) return;

        const replySection = commentItem.querySelector('.reply-section');
        const likesComments = commentItem.querySelector('.likes-comments');
        const replySubmitBtn = commentItem.querySelector('.reply-submit-btn');
        const replyInput = commentItem.querySelector('.reply-textarea-input');
        const replyCommentForms = commentItem.querySelectorAll('.reply-comment-form');
        const replyContainer = commentItem.querySelector('.reply-comments');

        // Reply button logic
        if (event.target.closest('.reply')) {
            replyInput.addEventListener('input', function() {
                if (replyInput.value.trim() !== '') {
                    replySubmitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    replySubmitBtn.setAttribute('type', 'submit');
                } else {
                    replySubmitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    replySubmitBtn.setAttribute('type', 'button');
                }
            });
        }

        // Dismiss reply button logic
        if (event.target.closest('.reply-dismiss-btn')) {
            replySection.classList.toggle('hidden');
            likesComments.classList.toggle('hidden');
        }
    });

    // Attach form submit listener only once to prevent duplication
    document.querySelectorAll('.reply-comment-form').forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const replyInput = form.querySelector('.reply-textarea-input');
            const replySection = form.querySelector('.reply-section');
            const likesComments = form.querySelector('.likes-comments');
            const postID = form.querySelector('input[name="postID"]').value;
            const parentCommentID = form.querySelector('.parent_comment_id').value;

            if (replyInput.value.trim() === '') return; // Prevent empty comment submission

            const formData = new FormData();
            formData.append('comment', replyInput.value);
            formData.append('postID', postID);
            formData.append('parentCommentID', parentCommentID);

            // Send data using fetch
            fetch('../src/handleReply.php', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json()) // Parse JSON response
                .then(data => {
                    if (data.success) {
                        // Format the date
                        const createdAt = new Date(data.comment.created_at);
                        const currentYear = new Date().getFullYear();
                        const commentYear = createdAt.getFullYear();
                        const formattedDate = commentYear === currentYear ?
                            createdAt.toLocaleDateString(undefined, {
                                day: '2-digit',
                                month: 'short'
                            }) :
                            createdAt.toLocaleDateString(undefined, {
                                day: '2-digit',
                                month: 'short',
                                year: 'numeric'
                            });

                        // Create the new reply element
                        const replyItem = document.createElement('li');
                        replyItem.classList.add(`comment-${data.comment.id}`, 'grid', 'gap-6', 'w-[96%]', 'justify-self-end');
                        replyItem.innerHTML = `
                    <div class="comment--inner flex gap-4">
                        <a class="h-fit" href="profile.php?id=${data.comment.user_id}">
                            <img class="w-12 h-fit rounded-[50%]" src="${data.comment.image_path}" alt="Profile Image">
                        </a>
                        <div class="w-full grid gap-2 relative">
                            <div class="space-y-4 p-4 rounded-md border border-gray-500 w-full">
                                <div class="flex gap-4 items-center">
                                    <p class="font-semibold">${data.comment.username}</p>
                                    <span class="text-sm text-gray-500"> • ${formattedDate}</span>
                                </div>
                                <p>${data.comment.content}</p>
                            </div>
                        </div>
                    </div>
                `;

                        // Append the new comment to the list
                        document.querySelector(`.reply-comments-${parentCommentID}`).appendChild(replyItem);

                        // Hide reply section and reset input
                        replySection.classList.toggle('hidden');
                        likesComments.classList.toggle('hidden');
                        replyInput.value = ''; // Clear input after submission

                    } else {
                        console.log('Error adding comment:', data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });
</script>

<?php

include "../includes/footer.php";

?>