<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<section class="page-hero" style="padding:60px 0;">
    <div class="container">
        <div style="margin-bottom:12px;">
            <a href="<?php echo esc_url(home_url('/blog')); ?>" style="color:rgba(255,255,255,0.7);font-size:14px;">← Back to Blog</a>
        </div>
        <?php $cats = get_the_category(); ?>
        <?php if ($cats) : ?>
            <span style="display:inline-block;background:rgba(103,61,230,0.4);color:#ebe4ff;padding:5px 16px;border-radius:20px;font-size:12px;font-weight:600;margin-bottom:16px;"><?php echo esc_html($cats[0]->name); ?></span>
        <?php endif; ?>
        <h1 style="max-width:800px;"><?php the_title(); ?></h1>
        <div style="margin-top:16px;color:rgba(255,255,255,0.65);font-size:14px;">
            By <?php the_author(); ?> · <?php echo get_the_date('F j, Y'); ?> · <?php echo ceil(str_word_count(get_the_content()) / 200); ?> min read
        </div>
    </div>
</section>

<section style="padding:60px 0;background:white;">
    <div class="container">
        <div style="display:grid;grid-template-columns:2fr 1fr;gap:60px;max-width:1100px;">
            <article>
                <?php if (has_post_thumbnail()) : ?>
                    <div style="border-radius:20px;overflow:hidden;margin-bottom:36px;">
                        <?php the_post_thumbnail('large', ['style' => 'width:100%;max-height:450px;object-fit:cover;']); ?>
                    </div>
                <?php endif; ?>
                <div class="post-content" style="font-size:17px;line-height:1.85;color:#333;">
                    <?php the_content(); ?>
                </div>
                <div style="margin-top:36px;padding-top:24px;border-top:1px solid #f0e8ff;display:flex;gap:12px;flex-wrap:wrap;">
                    <?php the_tags('<span style="font-size:13px;color:#666;">Tags: </span>', ', ', ''); ?>
                </div>
            </article>

            <aside>
                <div style="background:#f8f5ff;border-radius:16px;padding:24px;margin-bottom:24px;">
                    <h4 style="color:#2f1c6a;margin-bottom:16px;font-size:16px;">Recent Posts</h4>
                    <?php
                    $recent = get_posts(['numberposts' => 4, 'post_status' => 'publish', 'exclude' => [get_the_ID()]]);
                    foreach ($recent as $p) : ?>
                        <div style="margin-bottom:14px;padding-bottom:14px;border-bottom:1px solid #e8e0ff;">
                            <a href="<?php echo get_permalink($p); ?>" style="color:#2f1c6a;font-size:14px;font-weight:600;line-height:1.4;display:block;"><?php echo get_the_title($p); ?></a>
                            <span style="color:#999;font-size:12px;"><?php echo get_the_date('M j, Y', $p); ?></span>
                        </div>
                    <?php endforeach;
                    if (empty($recent)) : ?>
                        <p style="font-size:14px;color:#666;">More posts coming soon!</p>
                    <?php endif; ?>
                </div>

                <div style="background:#2f1c6a;border-radius:16px;padding:24px;color:white;text-align:center;">
                    <div style="font-size:36px;margin-bottom:12px;">🌿</div>
                    <h4 style="color:white;margin-bottom:8px;">Get Weekly Tips</h4>
                    <p style="font-size:13px;opacity:0.75;margin-bottom:16px;">Join our holistic nutrition newsletter.</p>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary" style="width:100%;display:block;font-size:14px;">Subscribe Free</a>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
