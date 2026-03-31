<?php
/**
 * Template Name: Donate Page
 *
 * @package PerfectLoveRestored
 */

get_header(); ?>

<div class="page-header">
    <h1>Donate</h1>
    <p class="subtitle">Partner with us in spreading the message of God's perfect love</p>
</div>

<section class="section">
    <div class="donate-content">
        <div class="donate-scripture">
            <p class="scripture-text" style="font-size: 1.2rem; margin-bottom: 0.5rem;">
                &ldquo;Each of you should give what you have decided in your heart to give,
                not reluctantly or under compulsion, for God loves a cheerful giver.&rdquo;
            </p>
            <span class="scripture-ref">&mdash; 2 Corinthians 9:7 (NIV)</span>
        </div>

        <h2>Your Generosity Makes a Difference</h2>
        <p>
            Your donations help us continue to share the message of God's unconditional love
            and the finished work of the cross with people around the world. Every gift, no matter
            the size, helps us create content, develop resources, and reach more hearts with
            the truth of the Gospel.
        </p>

        <h3 style="margin-top: var(--plr-spacing-lg);">How Your Gift Helps</h3>
        <div class="donate-options">
            <div class="donate-option">
                <h3>Blog & Content</h3>
                <p style="font-size: 0.95rem;">Supporting the creation of free teachings, devotionals, and reflections on God's love.</p>
            </div>
            <div class="donate-option">
                <h3>Resources</h3>
                <p style="font-size: 0.95rem;">Developing books, studies, and materials that help believers grow in the knowledge of His love.</p>
            </div>
            <div class="donate-option">
                <h3>Outreach</h3>
                <p style="font-size: 0.95rem;">Reaching people who have never experienced the reality of God's unconditional, perfect love.</p>
            </div>
        </div>

        <?php
        // Display any page content (for donation plugin forms, etc.)
        while ( have_posts() ) :
            the_post();
            $content = get_the_content();
            if ( ! empty( $content ) ) :
                ?>
                <div style="margin-top: var(--plr-spacing-lg);">
                    <?php the_content(); ?>
                </div>
            <?php endif;
        endwhile;
        ?>

        <div style="margin-top: var(--plr-spacing-lg); padding: var(--plr-spacing-md); background: var(--plr-cream); border-radius: var(--plr-border-radius-lg);">
            <p style="font-size: 0.9rem; color: var(--plr-text-light); margin: 0;">
                For direct donations or questions about giving, please contact us at
                <a href="mailto:hello@perfectloverestored.com">hello@perfectloverestored.com</a>
            </p>
        </div>
    </div>
</section>

<?php get_footer(); ?>
