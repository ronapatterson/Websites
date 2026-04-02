<?php
/**
 * Template Name: Gallery
 */
get_header();
?>

<section class="page-header">
    <h1>Gallery</h1>
</section>

<div class="gallery-grid">
    <?php
    $images = get_posts( array(
        'post_type'      => 'attachment',
        'post_mime_type' => 'image',
        'post_parent'    => get_the_ID(),
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ) );

    if ( ! empty( $images ) ) :
        foreach ( $images as $image ) :
            $full_url = wp_get_attachment_url( $image->ID );
            $thumb    = wp_get_attachment_image( $image->ID, 'nbop-card', false, array(
                'class' => 'gallery-img',
                'alt'   => get_post_meta( $image->ID, '_wp_attachment_image_alt', true ),
            ) );
    ?>
        <div class="gallery-item" data-full="<?php echo esc_url( $full_url ); ?>">
            <?php echo $thumb; ?>
        </div>
    <?php
        endforeach;
    else :
    ?>
        <p style="grid-column:1/-1;text-align:center;padding:4rem 0;color:var(--nbop-text-light);">No photos yet. Upload images to this page to build your gallery!</p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
