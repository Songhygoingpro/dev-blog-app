<?php

session_start();

$logSucceeded = isset($_SESSION['logSucceeded']) ? $_SESSION['logSucceeded'] : false;
$signupSucceeded = isset($_SESSION['signupSucceeded']) ? $_SESSION['signupSucceeded'] : false;
$username = isset($_SESSION['usernameSignup']) ? $_SESSION['usernameSignup'] : $_SESSION['usernameLogin'] ;


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'My App'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
    <?php
    // Include additional head content if set
    if (isset($customHeadContent)) {
        echo $customHeadContent;
    }
    ?>
</head>

<body>
    <header class="header bg-black">
        <div class="header_inner flex justify-between py-4 px-4 md:px-8">
            <div class="flex items-center gap-6"><img class="w-16 h-10" src="../assets/img/blog-logo.png" alt>
                <div class="hidden relative md:flex">
                    <button>
                        <img class="w-6 h-auto absolute left-3 top-2"
                            src="../assets/img/Search.png" alt>
                    </button>
                    <input class="w-96 h-10 rounded-md indent-12"
                        placeholder="Search..." type="text">
                </div>
            </div>
            <div class="flex items-center gap-6">
                <?php
                if ($logSucceeded || $signupSucceeded) {
                    // session_destroy();
                    echo '<a href="../public/create_post.php" class="hover:text-white text-sky-500 border-2 border-sky-500 hover:bg-sky-500 bg-black rounded-md px-6 py-2 font-semibold transition-all" >Create Post</a>
                    <div class="profile-wrapper relative">
                    <button class="profile-pic"><img class="w-8 h-auto" src="../assets/img/profile-picture.png"></button>
                    <ul class="profile-items absolute right-0 top-10 hidden bg-white border-2 border-gray-400 rounded-md p-6 w-36 grid justify-center items-center">
                        <li class="profile-item"><a href="" class="font-semibold text-[18px]">' . $username . '</a></li>
                        <li class="profile-item"><a class="">Create post</a></li>
                        <li class="profile-item"><a>Sign out</a></li>
                    </ul></div>';
                } else {
                    echo '<a href="../public/login.php" class="text-gray-100">Log in</a>
                <a class="py-2 px-3 border-2 border-[#60B2FF] rounded-md hover:bg-[#60B2FF] transition-all text-center text-[#60B2FF] hover:text-white"
                    href="../public/register.php">Create an account</a>';
                }
                ?>
            </div>
        </div>
    </header>