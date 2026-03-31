<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="header-inner">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
            <div class="logo-icon">🌿</div>
            <div class="logo-text">Doctor <span>Mommies</span></div>
        </a>

        <nav class="main-nav" id="main-nav" aria-label="Primary Navigation">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'menu_id'        => 'primary-menu',
                'container'      => false,
                'fallback_cb'    => function() {
                    echo '<ul>';
                    echo '<li><a href="' . home_url('/') . '">Home</a></li>';
                    echo '<li><a href="' . home_url('/blog') . '">Blog</a></li>';
                    echo '<li class="nav-cta"><a href="' . home_url('/recipes') . '">Recipes</a></li>';
                    echo '</ul>';
                },
            ]);
            ?>
        </nav>

        <button class="hamburger" id="hamburger" aria-label="Toggle Menu" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</header>
