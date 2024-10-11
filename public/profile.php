<?php
session_start();
include "../src/posts.php";
include '../includes/header.php';
$stmts = $conn->prepare('SELECT id FROM users WHERE username = ?');
$stmts->bind_param('s', $username);
$stmts->execute();
$results = $stmts->get_result();
$user = $results->fetch_assoc();

$user_id = isset($_GET['id']) ? $_GET['id'] : $user['id'];

$posts = getAllPosts($conn);

$havePost = false; // Initialize to false

foreach ($posts as $post) {
    if ($post['user_id'] === $user_id) {
        $havePost = true;
        break;
    }
}

$usernameSignup = isset($_SESSION['usernameSignup']) ?  $_SESSION['usernameSignup']  : 'username is not available';
if ($_SESSION['username'] !== $usernameSignup && $havePost === true) {
    $stmt = $conn->prepare('SELECT users.username, COUNT(posts.id) AS total_posts, users.image_path
    FROM users
    JOIN posts ON users.username = posts.author 
    WHERE users.id = ?;');
    $stmt->bind_param("s", $user_id);
} elseif (!empty($_GET['id'])) {
    $stmt = $conn->prepare('SELECT users.username, users.image_path
FROM users
WHERE users.id = ?;');
    $stmt->bind_param("s", $user_id);
} else {
    $stmt = $conn->prepare('SELECT users.username, users.image_path
    FROM users
    WHERE users.id = ?;');
    $stmt->bind_param("s", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_posts = isset($row['total_posts']) ? $row['total_posts'] : 0;
    $usernames = $row['username'];
    $pf_pic_paths = $row['image_path'] !== '' ? $row['image_path'] : '../assets/img/profile-picture.png';
}
$usernameLogin = isset($_SESSION['usernameLogin']) ? $_SESSION['usernameLogin'] : 'username is not available';

$pageTitle = $usernames;


?>

<main>
    <section class="profile px-4 pt-8 flex justify-center">
        <div class="profile__inner w-full relative grid max-w-[990px] after:content-[''] after:rounded-lg after:z-[-1] after:bg-gray-300 after:w-full after:h-[20rem] after:absolute after:top-16">
            <div class="grid h-fit justify-center space-y-8 ">
                <form id="pf-pic-form" action="../src/handlePf_edit.php" enctype="multipart/form-data" method="post" class="rounded-[50%]  group relative border-8 border-[#eff0f1] w-fit mx-auto ">
                    <input type="file" class="hidden" id="upload-pf-pic" name="upload-pf-pic" accept="image/*">

                    <?php if ($usernames === $usernameLogin || !empty($_SESSION['usernameSignup'])) { ?><label for="upload-pf-pic" class="upload-pf-pic">
                            <img class="w-28 h-28 object-cover object-center bg-white rounded-[50%]" id="pf-pic" src="<?= $pf_pic_paths ?>"></label>
                        <button type="button" id="button" class="inset-0 absolute rounded-[50%] bg-[rgba(0,0,0,0.4)] text-center text-white pt-6 opacity-0 group-hover:opacity-100 transition-colors transition-opacity">
                            Upload<br>image
                        </button> <?php } else {  ?>
                        <img class="w-28 h-28 object-cover object-center bg-white rounded-[50%]" id="pf-pic" src="<?= $pf_pic_paths ?>"><?php } ?>
                </form>
                <p class="text-3xl font-bold text-center"><?php echo $usernames ?></p>
                <div id="uploadModal" class="modal hidden fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center">
                    <div class="bg-white rounded-lg p-6 gap-4 flex flex-col items-center">
                        <h2 class="text-lg font-semibold mb-4">Upload Image</h2>
                        <p>Do you want to upload a new profile image?</p>
                        <img class="w-28 h-28 object-cover object-center rounded-[50%]" id="new-pf-pic" src="../assets/img/profile-picture.png">
                        <div class="flex justify-end mt-4">
                            <button id="confirmUpload" class="px-4 py-2 bg-blue-500 text-white rounded-md mr-2">Yes</button>
                            <button id="cancelUpload" class="px-4 py-2 bg-gray-500 text-white rounded-md">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="h-fit pb-32 p-16">
                <p><?= $total_posts ?> posts published</p>
            </div>
        </div>
    </section>
</main>


<script>
 

    const upload_pf_pic = document.getElementById('upload-pf-pic');
    const upload_label = document.querySelector('.upload-pf-pic')
    const new_pf_pic = document.getElementById('new-pf-pic');
    const pf_pic = document.getElementById('pf-pic');

    upload_pf_pic.addEventListener('change', function(e) {

        const file = e.target.files[0];
        const reader = new FileReader();

        if (file) {

            const formData = new FormData();
            formData.append('content_image', file);

            reader.onload = function(e) {
                new_pf_pic.src = e.target.result;
            };

            reader.readAsDataURL(file);

        }

        document.getElementById('uploadModal').classList.toggle('hidden');
    })

    document.getElementById('button').addEventListener('click', function() {
        upload_label.click();
    })

    document.getElementById('cancelUpload').addEventListener('click', function() {
        document.getElementById('uploadModal').classList.toggle('hidden');
    })

    document.getElementById('confirmUpload').addEventListener('click', function() {
        document.getElementById('uploadModal').classList.toggle('hidden');
        document.getElementById('pf-pic-form').submit();
    });
</script>

<?php

include '../includes/footer.php';

?>