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
                    <h1 class="text-3xl font-semibold">Create an account</h1>
                </div>
                <form class="grid gap-6" action=""><input required
                        class="w-96 h-12 rounded-md border-2 border=[#AFAFAF] indent-6" placeholder="Full name"
                        type="text"><input required class="w-96 h-12 rounded-md border-2 border=[#AFAFAF] indent-6"
                        placeholder="Password" type="text"><input required
                        class="w-96 h-12 rounded-md border-2 border=[#AFAFAF] indent-6"
                        placeholder="Confirmation password" type="text">
                    <button class="bg-[#75BDFF] font-medium text-center py-3 rounded-md text-white" type="submit">SIGN
                        UP</button>
                </form>
            </div>
        </section>
    </main>
</body>

</html>