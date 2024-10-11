<?php

$usernameLogin = isset($_SESSION['usernameLogin']) ? $_SESSION['usernameLogin'] : "";
$usernameSignup = isset($_SESSION['usernameSignup']) ? $_SESSION['usernameSignup'] : "";

$logSucceeded = isset($_SESSION['logSucceeded']) ? $_SESSION['logSucceeded'] : false;
$signupSucceeded = isset($_SESSION['signupSucceeded']) ? $_SESSION['signupSucceeded'] : false;
$username = $usernameLogin === "" ? $usernameSignup : $usernameLogin;
$_SESSION['username'] = $username;
$pf_check = isset($_SESSION['pf_image_path']) ? $_SESSION['pf_image_path'] : '';
$pf_pic_path = $pf_check !== ''  ? $pf_check : '../assets/img/profile-picture.png';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'My App'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="shortcut icon" href="../assets/img/blog-black-favicon.png" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.5.0/styles/atom-one-dark.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prettier/2.8.0/standalone.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prettier/2.8.0/parser-babel.min.js"></script>
    <?php
    // Include additional head content if set
    if (isset($customHeadContent)) {
        echo $customHeadContent;
    }
    ?>
</head>

<body>
    <header class="header bg-black sticky top-0 z-10">
        <div class="header_inner flex justify-between gap-4 py-4 px-4 md:px-8">
            <div class="flex items-center gap-6 flex-1">
                <a href="../public/index.php"><img class="w-16 h-10" src="../assets/img/blog-logo.png" alt></a>
                <form action="../public/search_result.php" class="flex-1" id="search-form" method="get">
                    <div class="relative flex flex-1">
                        <button>
                            <img class="w-6 h-auto absolute left-3 top-2" src="../assets/img/Search.png" alt="Search Icon">
                        </button>
                        <input class="search-post max-w-96 w-full  h-10 rounded-md indent-12 " name="search-query" autocomplete="off" placeholder="Search..." type="text">
                        <div class="post-suggestion absolute top-11 bg-white w-full h-auto hidden shadow-lg border rounded-md z-10"></div>
                    </div>
                </form>

            </div>
            <div class="flex  justify-end items-center gap-6">
                <?php
                if ($logSucceeded || $signupSucceeded) {
                    echo '<a href="../public/create_post.php" class="hover:text-white text-sky-500 border-2 border-sky-500 hover:bg-sky-500 bg-black rounded-md px-6 py-2 font-semibold transition-all md:block hidden" >Create Post</a>
                    <div class="profile-wrapper relative">
                    <button class="profile-pic"><img class="pf_img_header w-10 h-auto rounded-[50%] bg-white" src="'. $pf_pic_path .'"></button>
                    <form action="../src/handleProfileButtons.php" method="post">
                    <ul class="z-10 profile-items absolute right-0 top-12 bg-white border-2 border-gray-400 rounded-md p-6 w-auto transition-all opacity-0 scale-y-0 origin-[100%_0] grid  justify-center items-center  gap-4">
                        <li class="profile-item"><a href="../public/profile.php" class="font-semibold text-[18px]">' . $username .  '</a></li>
                        <hr>
                        <li class="profile-item "><a href="../public/create_post.php" class="whitespace-nowrap">Create post</a></li>
                        <hr>
                        <li class="profile-item"><button href="" type="submit"  class="">Sign out</button></li>
                    </ul></form></div>';
                } else {
                    echo '<a href="../public/login.php" class="text-gray-100">Log in</a>
                <a class="py-2 px-3 border-2 border-[#60B2FF] rounded-md hover:bg-[#60B2FF] transition-all text-center text-[#60B2FF] hover:text-white"
                    href="../public/register.php">Create an account</a>';
                }
                ?>
            </div>
        </div>
    </header>