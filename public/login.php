<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an account</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#F6F6F6]">
    <main>
        <section class="register w-full h-[100vh] flex justify-center items-center">
            <div class="register__inner flex flex-col gap-8">
                <div class="flex flex-col items-center gap-6">
                    <img class="w-20 h-12" src="../assets/img/blog-black-logo.png" alt>
                    <h1 class="text-3xl font-semibold">LOG IN</h1>
                </div>
                <form class="grid gap-6" action="../src/handleLogin.php" method="post" id="loginForm">
                    <input class="w-96 h-12 rounded-md border-2 border=[#AFAFAF] indent-6" placeholder="Full name"
                        type="text" name="usernameLogin" required>
                    <input class="w-96 h-12 rounded-md border-2 border=[#AFAFAF] indent-6" placeholder="Password"
                        type="password" name="pwdLogin" required>
                        <p class="text-red-500" id="warning_message">
                            
                        </p>
                    <button class="bg-[#75BDFF] font-medium text-center py-3 rounded-md text-white" type="submit">LOG IN</button>
                </form>
            </div>
        </section>
</main>

<script>
    document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const warning_message = document.getElementById('warning_message');
        
        if (data.status === 'error') {
            // Display the error message under the password input field
            warning_message.textContent = data.message;
            warning_message.classList.remove = 'hidden';
            warning_message.classList.add = 'block';
        } else {
            // Redirect to another page or take appropriate action
            window.location.href = "../public/index.php";
        }
    })
    .catch(error => console.error('Error:', error));
});
</script>

</body>

</html>