<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a post</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <header class="header grid w-full h-auto">
        <div class="header__inner flex justify-between items-center px-4 md:px-8 py-4 w-full h-auto">
            <div class="flex gap-4">
                <img class="w-20 h-12" src="../assets/img/blog-black-logo.png" alt>
                <h1 class="text-3xl font-bold">Create post</h1>
            </div>
            <button><img class="w-6 h-6" src="../assets/img/Close-icon.png" alt></button>
        </div>
    </header>
    <main>
        <section class="creat-post-section flex justify-center items-center p-4">
            <div class="create-post-section__inner flex flex-col gap-4">
                <div class="px-12 py-10 w-[990px] h-[80vh] bg-white flex flex-col gap-6 rounded-md">
                    <div class="file-upload">
                        <input type="file" id="file-input" class="hidden" accept="image/*">
                        <label for="file-input" class="px-4 py-2 border-2 rounded-md border-[#D9D9D9] cursor-pointer">Add a cover image</label>
                    </div>
                    <input name="title" type="text" class="text-4xl font-black focus:outline-none" placeholder="New post title...">
                    <input name="tags" type="text" class="focus:outline-none" placeholder="Add tags...">
                    <textarea name="content" type="text" class="focus:outline-none" placeholder="Write your post content..."></textarea>
                </div>
                <div><button class="px-4 py-2 rounded-md text-white font-bold bg-sky-400">Publish</button></div>
            </div>
        </section>
    </main>
</body>

</html>