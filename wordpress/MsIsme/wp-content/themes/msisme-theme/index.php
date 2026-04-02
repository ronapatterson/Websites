<?php get_header(); ?>

<main>
    <section class="page-hero">
        <div class="container">
            <h1><?php the_title(); ?></h1>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <?php
            while (have_posts()) :
                the_post();
                the_content();
            endwhile;
            ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
