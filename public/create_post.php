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
                <a href="../public/index.php"><img class="w-20 h-12" src="../assets/img/blog-black-logo.png" alt></a>
                <h1 class="text-3xl font-bold">Create post</h1>
            </div>
            <a href="index.php"><img class="w-6 h-6" src="../assets/img/Close-icon.png" alt></a>
        </div>
    </header>

    <main>
        <section class="creat-post-section flex justify-center items-center p-4 py-8">
            <div class="create-post-section__inner flex flex-col gap-4">
                <form action="../src/handlePostSubmission.php" class="flex flex-col gap-4" method="post" id="post-form"
                    enctype="multipart/form-data">
                    <div class="px-12 py-10 w-[990px] min-h-[80vh] bg-white flex flex-col gap-6 rounded-md">
                        <div class="file-upload">
                            <input type="file" id="file-input" name="cover_image" class="hidden" accept="image/*">
                            <label for="file-input" id="img-upload-button"
                                class="px-4 py-2 border-2 rounded-md border-[#D9D9D9] cursor-pointer">Add a cover
                                image</label>
                            <div id="image-preview" class="hidden">
                                <div class="flex gap-4 items-center">
                                    <img id="uploaded-image" src="" alt="Cover Image" class="max-w-40 h-auto">
                                    <div id="loader" class="hidden">Loading...</div>
                                    <button
                                        class="px-4 py-2 border-2 border-gray-400 rounded-md text-center font-semibold"
                                        type="button" id="change-image-button">Change</button>
                                    <button type="button" class="text-red-500 font-semibold"
                                        id="remove-image-button">Remove</button>
                                </div>
                            </div>
                        </div>
                        <textarea name="title" id="title"
                            class=" text-4xl h-12 font-black focus:outline-none placeholder:text-4xl w-full resize-none overflow-hidden"
                            placeholder="New post title..." oninput="adjustHeight(this)"></textarea>
                        <div id="tag-container">
                            <input type="text" id="tag-input" placeholder="Add up to 4 tags..." autocomplete="off" />
                            <input type="hidden" name="tags" id="tags-input">
                            <div id="suggestions" class="suggestions"></div>
                        </div>
                        <div class="toolbar sticky top-0">
                            <button type="button" onclick="formatText('**', '**')"><b>B</b></button>
                            <button type="button" onclick="formatText('_', '_')"><i>I</i></button>
                            <button type="button" onclick="formatText('## ', '\n')">H</button>
                            <button type="button" onclick="formatText('```\n', '\n```')">&lt;/&gt;</button>
                            <button type="button" onclick="formatText('`', '`')">
                                <>
                            </button>
                            <button type="button" class="imageFile-content-upload">
                                <input type="file" id="imageFile-content-upload"
                                    name="content_image" class="hidden" accept="image/*">
                                <label for="imageFile-content-upload"><img src="../assets/img/image-icon.png"
                                        class="w-8 h-auto cursor-pointer"></label>
                            </button>
                        </div>
                        <textarea name="content" oninput="adjustHeight(this)" type="text"
                            class="resize-none focus:outline-none contentField"
                            placeholder="Write your post content..."></textarea>
                    </div>
                    <div><button class="px-4 py-2 rounded-md text-white font-bold bg-sky-400"
                            type="submit">Publish</button></div>
                </form>
            </div>
        </section>
    </main>

    <script>
        //Handle Image upload
        const imgUploadButton = document.getElementById('img-upload-button');
        const imagePreview = document.getElementById('image-preview');
        const uploadedImage = document.getElementById('uploaded-image');
        const loader = document.getElementById('loader');
        const fileInput = document.getElementById('file-input');
        const changeImageButton = document.getElementById('change-image-button');
        let content_image_url = "";

        document.getElementById('file-input').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const reader = new FileReader();

            if (file) {
                //? Hide the label and show the loader
                imgUploadButton.classList.add('hidden');
                loader.classList.remove('hidden');
                imagePreview.classList.remove('hidden');

                //? Load the image
                reader.onload = function(e) {
                    //? When the image is loaded, display it
                    uploadedImage.src = e.target.result;
                    loader.classList.add('hidden'); //? Hide the loader
                };

                reader.readAsDataURL(file); //? Start reading the image file
            }
        });

        //? Trigger file input click event
        changeImageButton.addEventListener('click', function() {

            fileInput.value = '';
            fileInput.click(); //? Trigger file input click event
        });

        document.getElementById('remove-image-button').addEventListener('click', function() {
            uploadedImage.src = '';
            fileInput.value = '';
            //? Hide the image preview and show the upload button again
            imagePreview.classList.add('hidden');
            imgUploadButton.classList.remove('hidden');
        });

        function adjustHeight(element) {
            element.style = ' height: 48px;'; //? Reset the height to auto
            element.style.height = (element.scrollHeight) + 'px'; //? Set it to the scroll height
        }



        // Handle image contet path
        document.getElementById('imageFile-content-upload').addEventListener('change', function(event) {
            const file = event.target.files[0]; // Get the selected file

            if (file) {
                const formData = new FormData();
                formData.append('content_image', file); // Append the file to form data

                // Send the file to the server using Fetch API
                fetch('../src/handlePostSubmission.php', { // Replace with your server upload URL
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text()) // Change to .text() to see the raw response
                    .then(data => {
                        console.log(data); // Log the raw response to see what is being returned
                        try {
                            const jsonData = JSON.parse(data); // Attempt to parse JSON
                            if (jsonData.url) {
                                content_image_url = jsonData.url; // URL of the uploaded image from the server
                                formatText('!(', ')')
                            } else {
                                console.error('Upload failed:', jsonData.error);
                            }
                        } catch (error) {
                            console.error('Error parsing JSON:', error); // Catch JSON parse errors
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        });

        // Tags
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('tag-input');
            const tagContainer = document.getElementById('tag-container');
            const suggestionsContainer = document.getElementById('suggestions');
            const tagsInput = document.getElementById('tags-input');

            let tags = []; // Array to store the selected tags

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

            // Sort the available tags alphabetically by name when the page loads
            availableTags.sort((a, b) => a.name.localeCompare(b.name));

            // Show all available tags as suggestions when the input field is focused
            input.addEventListener('focus', function() {
                showSuggestions(availableTags.filter(tag => !tags.includes(tag.name)));
            });

            // Filter and show suggestions based on user input
            input.addEventListener('input', function() {
                const query = input.value.trim().toLowerCase();

                const filteredTags = availableTags
                    .filter(tag => tag.name.toLowerCase().includes(query) && !tags.includes(tag.name))
                    .sort((a, b) => {
                        const aStartsWithQuery = a.name.toLowerCase().startsWith(query);
                        const bStartsWithQuery = b.name.toLowerCase().startsWith(query);

                        // Prioritize tags that start with the query
                        if (aStartsWithQuery && !bStartsWithQuery) return -1;
                        if (!aStartsWithQuery && bStartsWithQuery) return 1;

                        // For tags that both start with or don't start with the query, sort alphabetically
                        return a.name.localeCompare(b.name);
                    });

                showSuggestions(filteredTags);
            });

            // Add the tag when the Enter key is pressed
            input.addEventListener('keypress', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault(); // Prevent form submission or default behavior
                    addTagAndHideSuggestions(); // Add the tag and hide the suggestions
                }
            });

            // Add the tag and hide suggestions when the input field loses focus
            input.addEventListener('blur', function() {
                setTimeout(addTagAndHideSuggestions, 100); // Delay to allow clicking on suggestions
            });

            // Add the clicked suggestion as a tag
            suggestionsContainer.addEventListener('click', function(event) {
                if (event.target.classList.contains('suggestion')) {
                    const tagText = event.target.textContent.trim().toLowerCase();

                    // Add the tag if it's valid and not already in the selected tags
                    if (tagText && tags.length < 4 && !tags.includes(tagText)) {
                        addTag(tagText);
                        input.value = ''; // Clear the input field
                        hideSuggestions(); // Hide the suggestions after adding the tag
                    }
                }
            });

            // Add the tag and hide suggestions
            function addTagAndHideSuggestions() {
                const tagText = input.value.trim().toLowerCase();
                if (tagText && tags.length <= 4 && !tags.includes(tagText)) {
                    addTag(tagText);
                }
                input.value = ''; // Clear the input field
                hideSuggestions(); // Hide the suggestions
            }

            // Add a new tag to the tag container
            function addTag(text) {
                // Add the tag to the tags array
                if (text.includes('#')) {
                    tags.push(text);
                } else {
                    tags.push('#' + text);
                }

                const tagElement = document.createElement('span'); // Create a span element for the tag
                const tagData = availableTags.find(tag => tag.name === text.slice(1));
                const tagColor = tagData ? tagData.color : 'lightgray'; // Default color is 'gray'
                tagElement.style = `background-color: ${tagColor}; color: black;`;

                tagElement.className = 'tag'; // Add the 'tag' class
                if (!text.includes('#')) {
                    tagElement.textContent = '#' + text
                } else {
                    tagElement.textContent = text;
                }

                const removeBtn = document.createElement('span'); // Create a span element for the remove button
                removeBtn.className = 'remove-tag'; // Add the 'remove-tag' class
                removeBtn.textContent = 'x'; // Set the button's text content
                removeBtn.onclick = function() {
                    removeTag(tagElement, text); // Remove the tag when the button is clicked
                };

                tagElement.appendChild(removeBtn); // Add the remove button to the tag element
                tagContainer.insertBefore(tagElement, input); // Insert the tag before the input field
            }

            // Remove a tag from the tag container and the tags array
            function removeTag(element, text) {
                tagContainer.removeChild(element); // Remove the tag element from the DOM
                tags = tags.filter(tag => tag !== text); // Remove the tag from the tags array
            }

            // Show the suggestions in the suggestions container
            function showSuggestions(tags) {
                suggestionsContainer.innerHTML = ''; // Clear any previous suggestions
                tags.forEach(tag => {
                    const suggestion = document.createElement(
                        'div'); // Create a div element for each suggestion
                    suggestion.className = 'suggestion'; // Add the 'suggestion' class
                    suggestion.textContent = `#${tag.name}`; // Set the suggestion's text content
                    suggestionsContainer.appendChild(
                        suggestion); // Add the suggestion to the suggestions container
                });
                suggestionsContainer.style.display = 'block'; // Show the suggestions container
            }

            // Hide the suggestions container
            function hideSuggestions() {
                suggestionsContainer.style.display = 'none'; // Hide the suggestions container
            }

            document.getElementById('post-form').addEventListener('submit', function() {
                tagsInput.value = tags.join(',');
            });

        });

        //Format selected text in textarea for content
        function formatText(before, after) {
            const textarea = document.querySelector('.contentField');
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = textarea.value;
            const selectedText = text.slice(start, end);

            // Insert the format before and after the selected text
            let newText = text.slice(0, start) + before + selectedText + after + text.slice(end);

            // Check if the format is for an image
            if (before === '!(' && after === ')') {
                // Insert the uploaded image URL between the 'before' and 'after'
                if (typeof content_image_url !== 'undefined' && content_image_url !== '') {
                    newText = text.slice(0, start) + before + content_image_url + after + text.slice(end);
                } else {
                    console.error('Image URL is not defined or empty.');
                }
            }

            // Update the textarea value
            textarea.value = newText;

            // Reset cursor position to after the inserted format
            textarea.focus();
            textarea.selectionStart = textarea.selectionEnd = start + before.length + selectedText.length;
            adjustHeight(textarea);
        }

    </script>

    <?php include '../includes/footer.php'; ?>