<?php
$pageTitle = 'Blog App';
$customHeadContent = '<meta name="description" content="Home page of My App">';
include "../includes/header.php";

?>

<main>
    <section class="allPost-seciton py-8 px-4 md:px-8 flex justify-center">
        <div class="allPost-section__inner flex flex-col gap-6 max-w-[1200px] w-full">
            <h2 class="text-3xl font-bold">
                All blog posts
            </h2>
            <ul class="posts-container grid grid-cols-3 gap-6">
<?php 



echo '<li class="" ></li>';


?>
</ul>
        </div>
    </section>
</main>

<?php include "../includes/footer.php" ?>