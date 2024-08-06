<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <header class="header bg-black">
        <div class="header_inner flex justify-between py-4 px-4 md:px-8">
            <div class="flex items-center gap-6"><img class="w-16 h-10" src="../assets/img/blog-logo.png" alt>
                <div class="flex relative "><button><img class="w-6 h-auto absolute left-3 top-2" src="../assets/img/Search.png" alt></button><input class="w-96 h-10 rounded-md indent-12" placeholder="Search..." type="text"></div>
            </div>
            <div class="flex items-center gap-6"><a href="" class="text-gray-100">Log in</a><a class="py-2 px-3 border-2 border-[#60B2FF] rounded-md hover:bg-[#60B2FF] transition-all text-center text-[#60B2FF] hover:text-white" href="">Create an account</a></div>
        </div>
    </header>
    <main>
        <section class="post-seciton py-16 px-4 md:px-8 flex justify-center">
            <div class="post-section__inner flex flex-col gap-6 max-w-[1200px] w-full">
            <?php if (!empty($cover_image)): ?>
                    <div class="cover-image w-full h-64 bg-cover bg-center rounded-md" style="background-image: url('<?php echo htmlspecialchars($cover_image); ?>');"></div>
                <?php endif; ?>
                <h1 class="text-4xl font-black"><?php echo htmlspecialchars($title); ?></h1>
                <div class="tags text-gray-500"><?php echo htmlspecialchars($tags); ?></div>
                <div class="content mt-4 text-lg"><?php echo nl2br(htmlspecialchars($content)); ?></div>
            </div>
        </section>
    </main>
    <footer></footer>
</body>

</html>