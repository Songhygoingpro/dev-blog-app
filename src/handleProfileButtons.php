<?php
session_start();
session_unset();

// Destroy the session
session_destroy();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Sign out</title>
</head>
<body>
    <main>
        <section class="signOut-section h-[100vh] container flex justify-center items-center">
            <div class="signOUt-section__inner flex flex-col items-center justify-center gap-4  px-16 h-auto py-8 bg-gray-300"><h2 class="text-3xl text-sky-500">Sign Out Successfull</h2><a href="../public/index.php" class="px-6 py-4 text-white bg-sky-500">Back to blog</a></div>
        </section>
    </main>
</body>
</html>