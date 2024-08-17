<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
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
            <a href="index.php"><img class="w-6 h-6" src="../assets/img/Close-icon.png" alt></a>
        </div>
    </header>

    <main>
        <section class="creat-post-section flex justify-center items-center p-4 py-8">
            <div class="create-post-section__inner flex flex-col gap-4">
                <form action="../src/handlePostSubmission.php" class="flex flex-col gap-4" method="post">
                    <div class="px-12 py-10 w-[990px] min-h-[80vh] bg-white flex flex-col gap-6 rounded-md">
                        <div class="file-upload">
                            <input type="file" id="file-input" name="cover_image" class="hidden" accept="image/*">
                            <label for="file-input" id="img-upload-button" class="px-4 py-2 border-2 rounded-md border-[#D9D9D9] cursor-pointer">Add a cover image</label>
                            <div id="image-preview" class="hidden">
                                <div class="flex gap-4 items-center">
                                    <img id="uploaded-image" src="" alt="Cover Image" class="max-w-40 h-auto">
                                    <div id="loader" class="hidden">Loading...</div>
                                    <button class="px-4 py-2 border-2 border-gray-400 rounded-md text-center font-semibold" id="change-image-button">Change</button><button type="button" class="text-red-500 font-semibold" id="remove-image-button">Remove</button>
                                </div>
                            </div>
                        </div>
                        <input name="title" type="text" class="text-4xl font-black focus:outline-none" placeholder="New post title...">
                        <input name="tags" type="text" class="focus:outline-none" placeholder="Add tags...">
                        <textarea name="content" type="text" class="focus:outline-none" placeholder="Write your post content..."></textarea>
                    </div>
                    <div><button class="px-4 py-2 rounded-md text-white font-bold bg-sky-400" type="submit">Publish</button></div>
                </form>
            </div>
        </section>
    </main>

    <script>
    const imgUploadButton = document.getElementById('img-upload-button');
const imagePreview = document.getElementById('image-preview');
const uploadedImage = document.getElementById('uploaded-image');
const loader = document.getElementById('loader');
const fileInput = document.getElementById('file-input');

fileInput.addEventListener('change', function(event) {
    const file = event.target.files[0];
    const reader = new FileReader();

    if (file) {
        // Show the loader and hide the upload button
        imgUploadButton.classList.add('hidden');
        loader.classList.remove('hidden');
        imagePreview.classList.remove('hidden');

        // Set up the file reader onload event
        reader.onload = function(e) {
            uploadedImage.src = e.target.result; // Display the image
            loader.classList.add('hidden'); // Hide the loader once the image is loaded
        };

        // Set up error handling for file reading
        reader.onerror = function() {
            alert('Error loading image');
            loader.classList.add('hidden'); // Hide the loader
            imgUploadButton.classList.remove('hidden'); // Show the upload button again
            imagePreview.classList.add('hidden'); // Hide the image preview if there's an error
        };

        // Start reading the image file
        reader.readAsDataURL(file);
    }
});


document.getElementById('remove-image-button').addEventListener('click', function() {

    fileInput.value = ''; 
    // Hide the image preview and show the upload button again
    imagePreview.classList.add('hidden');
    imgUploadButton.classList.remove('hidden');
});

    </script>

    <?php include '../includes/footer.php'; ?>