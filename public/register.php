<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="bg-[#F6F6F6]">
    <main>
        <section class="register w-full h-[100vh] flex justify-center items-center">
            <div class="register__inner flex flex-col gap-8">
                <div class="flex flex-col items-center gap-6">
                    <img class="w-20 h-12" src="../assets/img/blog-black-logo.png" alt>
                    <h1 class="text-3xl font-semibold">Create an account</h1>
                </div>
                <form class="grid gap-6" id="signUpForm" action="../src/handleSignup.php" method="post">
                    <input required class="w-96 h-12 rounded-md border-2 border=[#AFAFAF] indent-6"
                        placeholder="Full name" name="username" type="text">
                    <input required class="w-96 h-12 rounded-md border-2 border=[#AFAFAF] indent-6"
                        placeholder="Password" name="pwdSignup" id="pwd" type="password">
                    <input required
                        class="w-96 h-12 rounded-md border-2 border=[#AFAFAF] indent-6"
                        placeholder="Confirmation password" id="pwd_confirm" type="password">
                    <p class="text-red-500 text-sm hidden" id="warning_message">Passwords do not match.</p>
                    <button onclick="confirm_pwd();" class="bg-[#75BDFF] font-medium text-center py-3 rounded-md text-white" type="button">SIGN
                        UP</button>
                </form>
            </div>
        </section>
    </main>
    <script>
        const pwd = document.getElementById('pwd');
        const pwd_confirm = document.getElementById('pwd_confirm');
        const signUpForm = document.getElementById('signUpForm');
        const warning_message = document.getElementById('warning_message');

        function confirm_pwd() {
            if (pwd.value === pwd_confirm.value) {

signUpForm.submit();
warning_message.classList.add('hidden');

            } else {

                warning_message.classList.remove('hidden');
                warning_message.classList.add('block');

            }

        }
    </script>
</body>

</html>