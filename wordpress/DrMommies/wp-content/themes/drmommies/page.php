<?php get_header(); ?>

<section class="page-hero">
    <div class="container">
        <h1><?php the_title(); ?></h1>
    </div>
</section>

<section style="padding:70px 0;background:white;">
    <div class="container">
        <div style="max-width:800px;margin:0 auto;font-size:17px;line-height:1.85;color:#333;">
            <?php while (have_posts()) : the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
