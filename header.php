<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

    <!-- DNS Prefetch & Preconnect for speed -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Preload critical above-fold assets -->
    <link rel="preload" as="image"
          href="<?php echo esc_url(get_template_directory_uri() . '/assets/images/apex-logo-nav.webp'); ?>"
          type="image/webp" fetchpriority="high">
    <?php if ( is_front_page() ): ?>
    <link rel="preload" as="image"
          href="<?php echo esc_url(get_template_directory_uri() . '/assets/images/apex-logo-hero.png'); ?>"
          fetchpriority="high">
    <?php endif; ?>

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Toast Notification Container -->
<div id="apex-toasts" aria-live="polite"></div>

<!-- Navigation -->
<nav id="apex-nav" role="navigation" aria-label="Main Navigation">
    <div class="container">
        <div class="nav-inner">

            <!-- Primary Menu -->
            <ul class="nav-menu" id="nav-menu">
                <li><a href="<?php echo home_url('/'); ?>">Home</a></li>
                <li><a href="<?php echo home_url('/products'); ?>">Products</a></li>
                <li><a href="<?php echo home_url('/pmags'); ?>">PMAGs</a></li>
                <li><a href="<?php echo home_url('/services'); ?>">Services</a></li>
                <li><a href="<?php echo home_url('/gallery'); ?>">Gallery</a></li>
                <li><a href="<?php echo home_url('/heroes'); ?>" style="color:var(--apex-gold);">🎖️ Heroes</a></li>
                <li><a href="<?php echo home_url('/contact'); ?>">Contact</a></li>
            </ul>

            <!-- Nav Actions -->
            <div class="nav-actions">
                <a href="<?php echo home_url('/cart'); ?>" class="cart-icon-btn" id="nav-cart-btn" aria-label="Shopping Cart">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                    </svg>
                    <span>Cart</span>
                    <span class="cart-badge" id="nav-cart-count" style="display:none;">0</span>
                </a>

                <!-- Mobile Toggle -->
                <button class="nav-toggle" id="nav-toggle" aria-label="Toggle Menu" aria-expanded="false" aria-controls="nav-menu">
                    <span></span><span></span><span></span>
                </button>
            </div>

        </div>
    </div>
</nav>
