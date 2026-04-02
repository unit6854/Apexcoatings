<?php
/**
 * Apex Coatings & Engraving — functions.php
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ============================================================
   CONTENT WIDTH (required by WordPress theme standards)
   ============================================================ */
if ( ! isset( $content_width ) ) $content_width = 1200;

/* ============================================================
   THEME SETUP
   ============================================================ */
function apex_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'gallery', 'caption', 'script', 'style' ] );
    add_theme_support( 'custom-logo', [
        'height'      => 120,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ] );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'wp-block-styles' );

    // Register custom image sizes for responsive images
    add_image_size( 'apex-card',      600,  400, true );
    add_image_size( 'apex-gallery',   800,  600, true );
    add_image_size( 'apex-hero',     1400,  800, true );

    register_nav_menus( [
        'primary' => __( 'Primary Menu', 'apex-theme' ),
        'footer'  => __( 'Footer Menu', 'apex-theme' ),
    ] );
}
add_action( 'after_setup_theme', 'apex_setup' );

/* ============================================================
   PERFORMANCE: REMOVE BLOAT
   ============================================================ */
// Remove emoji scripts (saves ~30KB)
remove_action( 'wp_head',             'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles',     'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles',  'print_emoji_styles' );

// Disable XML-RPC
add_filter( 'xmlrpc_enabled', '__return_false' );

// Remove WP generator version tag (security hardening)
remove_action( 'wp_head', 'wp_generator' );

// Remove oEmbed discovery links
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'wp_oembed_add_host_js' );

// Remove shortlink
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

// Remove RSD link
remove_action( 'wp_head', 'rsd_link' );

// Remove wlwmanifest link
remove_action( 'wp_head', 'wlwmanifest_link' );

// Google Fonts preconnect hints (added via wp_head)
function apex_resource_hints() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action( 'wp_head', 'apex_resource_hints', 1 );

/* ============================================================
   ENQUEUE STYLES & SCRIPTS
   ============================================================ */
function apex_enqueue_assets() {
    $ver = '1.2.0';
    $uri = get_template_directory_uri();

    // Google Fonts
    wp_enqueue_style( 'apex-fonts', 'https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700&family=Barlow+Condensed:wght@400;600;700;800;900&display=swap', [], null );

    // Theme CSS
    wp_enqueue_style( 'apex-theme', $uri . '/style.css', [ 'apex-fonts' ], $ver );

    // Cart JS (loads everywhere for cart badge count)
    wp_enqueue_script(
        'apex-cart',
        $uri . '/assets/js/cart.js',
        [],
        $ver,
        true
    );

    // Main JS — deferred via in_footer
    wp_enqueue_script(
        'apex-main',
        $uri . '/assets/js/main.js',
        [ 'apex-cart' ],
        $ver,
        true
    );

    // Pass AJAX URL and nonce to JS
    wp_localize_script( 'apex-cart', 'apexAjax', [
        'url'   => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce( 'apex_order_nonce' ),
        'home'  => home_url('/'),
    ] );

    // Checkout-specific JS — deferred
    if ( is_page( 'checkout' ) || is_page( 'order' ) ) {
        wp_enqueue_script(
            'apex-checkout',
            $uri . '/assets/js/checkout.js',
            [ 'apex-cart' ],
            $ver,
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'apex_enqueue_assets' );

/* ============================================================
   ADD defer TO NON-CRITICAL SCRIPTS
   ============================================================ */
function apex_defer_scripts( $tag, $handle, $src ) {
    $defer_handles = [ 'apex-main', 'apex-checkout' ];
    if ( in_array( $handle, $defer_handles, true ) ) {
        return '<script src="' . esc_url( $src ) . '" defer></script>' . "\n";
    }
    return $tag;
}
add_filter( 'script_loader_tag', 'apex_defer_scripts', 10, 3 );


/* ============================================================
   SOCIAL LINKS OPTION
   ============================================================ */
function apex_get_social_links() {
    return [
        'tiktok'   => get_option( 'apex_social_tiktok',   'https://www.tiktok.com/@apexcoatingsandengraving?_r=1&_t=ZP-958ATEtcIjm' ),
        'facebook' => get_option( 'apex_social_facebook', '#' ),
    ];
}

// Initialize defaults on first load
add_action( 'init', function () {
    if ( false === get_option( 'apex_social_tiktok' ) ) {
        add_option( 'apex_social_tiktok',   'https://www.tiktok.com/@apexcoatingsandengraving?_r=1&_t=ZP-958ATEtcIjm' );
        add_option( 'apex_social_facebook', '#' );
    }
} );

/* ============================================================
   SEO META TAGS — Full (Google, AI, Twitter, Facebook, ChatGPT)
   ============================================================ */
function apex_seo_meta() {
    global $post;

    $site_name  = 'Apex Coatings & Engraving';
    $site_url   = 'https://apexcoatingstn.com';
    $theme_uri  = get_template_directory_uri();
    $og_image   = $theme_uri . '/assets/images/apex-logo-hero.png';
    $canonical  = esc_url( get_permalink() ?: home_url('/') );

    // Per-page title and description
    if ( is_front_page() ) {
        $title       = 'Apex Coatings & Engraving — Cerakote & Custom Engraving | Nashville, TN';
        $description = 'Premium Cerakote finishing, laser engraving, and custom metal work in Tennessee. Precision craftsmanship trusted by collectors, competitors, and professionals.';
        $keywords    = 'Cerakote Nashville, laser engraving Tennessee, Cerakote application, custom engraving, AR magazine engraving, 1911 engraving, pistol slide Cerakote';
    } elseif ( is_page( 'gallery' ) ) {
        $title       = 'Gallery — Custom Engraving & Coating Work | Apex Coatings & Engraving';
        $description = 'Browse our gallery of custom engraving, AR magazine art, laser engraving, and Cerakote projects completed by Apex Coatings & Engraving.';
        $keywords    = 'custom engraving gallery, AR magazine engraving, laser engraving portfolio, Cerakote examples Tennessee';
    } elseif ( is_page( 'contact' ) ) {
        $title       = 'Contact Us — Get a Free Quote | Apex Coatings & Engraving';
        $description = 'Contact Apex Coatings & Engraving for a quote on Cerakote, custom engraving, or laser engraving. Call (615) 862-1660.';
        $keywords    = 'contact Apex Coatings, Cerakote quote Nashville, custom engraving quote, laser engraving quote Tennessee';
    } elseif ( is_page( 'heroes' ) ) {
        $title       = 'Heroes Discount — 10% Off for Military & First Responders | Apex Coatings & Engraving';
        $description = 'Apex Coatings & Engraving honors veterans, military, police, firefighters, and EMS with a 10% discount on all services. Thank you for your service.';
        $keywords    = 'military discount engraving, veteran Cerakote, first responder discount Tennessee, police custom engraving';
    } elseif ( is_singular( 'apex_product' ) && $post ) {
        $product_title = get_the_title( $post );
        $excerpt       = get_the_excerpt( $post ) ?: 'Premium custom finishing and engraving by Apex Coatings & Engraving.';
        $title         = esc_html( $product_title ) . ' | Apex Coatings & Engraving';
        $description   = esc_html( $excerpt );
        $keywords      = esc_html( $product_title ) . ', custom engraving Tennessee, Cerakote Nashville';
        if ( has_post_thumbnail( $post ) ) {
            $og_image = get_the_post_thumbnail_url( $post, 'large' );
        }
    } elseif ( is_page( 'products' ) ) {
        $title       = 'Products & Services — Custom Coatings & Engraving | Apex Coatings & Engraving';
        $description = 'Browse Apex Coatings & Engraving products including Cerakote finishing, custom engraving, laser engraving, and custom metalwork. Starting from $75.';
        $keywords    = 'Cerakote Tennessee, custom engraving products, laser engraving services Nashville, custom metalwork, Cerakote slide finishing';
    } else {
        $title       = ( $post ? get_the_title( $post ) . ' | ' : '' ) . $site_name;
        $description = 'Premium Cerakote finishing, custom engraving, and laser engraving services in Tennessee.';
        $keywords    = 'Cerakote, laser engraving, custom engraving Tennessee, Cerakote slide finishing, pistol slide Cerakote';
    }

    $og_url = $canonical;
    ?>

<!-- Apex SEO Meta -->
<meta name="description" content="<?php echo esc_attr( $description ); ?>">
<meta name="keywords" content="<?php echo esc_attr( $keywords ); ?>">
<meta name="author" content="Apex Coatings &amp; Engraving">
<meta name="theme-color" content="#F5831F">
<meta name="msapplication-TileColor" content="#1A1A1A">
<link rel="canonical" href="<?php echo $canonical; ?>">

<!-- Robots — full crawl permissions + rich snippet hints -->
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

<!-- AI Crawler Permissions (ChatGPT, Claude, Perplexity, Google AI) -->
<meta name="googlebot" content="index, follow, max-image-preview:large, max-snippet:-1">
<meta name="bingbot" content="index, follow">
<meta name="GPTBot" content="index, follow">
<meta name="ClaudeBot" content="index, follow">
<meta name="PerplexityBot" content="index, follow">
<meta name="ChatGPT-User" content="index, follow">
<meta name="CCBot" content="index, follow">
<meta name="anthropic-ai" content="index, follow">

<!-- Open Graph (Facebook, LinkedIn, Slack) -->
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?php echo esc_attr( $site_name ); ?>">
<meta property="og:title" content="<?php echo esc_attr( $title ); ?>">
<meta property="og:description" content="<?php echo esc_attr( $description ); ?>">
<meta property="og:url" content="<?php echo esc_url( $og_url ); ?>">
<meta property="og:image" content="<?php echo esc_url( $og_image ); ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:type" content="image/png">
<meta property="og:image:alt" content="<?php echo esc_attr( $site_name ); ?> — Custom Cerakote &amp; Engraving">
<meta property="og:locale" content="en_US">
<meta property="business:contact_data:locality" content="Nashville">
<meta property="business:contact_data:region" content="Tennessee">
<meta property="business:contact_data:country_name" content="United States">
<meta property="business:contact_data:phone_number" content="+16158621660">

<!-- Twitter / X Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@apexcoatingstn">
<meta name="twitter:creator" content="@apexcoatingstn">
<meta name="twitter:title" content="<?php echo esc_attr( $title ); ?>">
<meta name="twitter:description" content="<?php echo esc_attr( $description ); ?>">
<meta name="twitter:image" content="<?php echo esc_url( $og_image ); ?>">
<meta name="twitter:image:alt" content="<?php echo esc_attr( $site_name ); ?>">

<!-- Favicon -->
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( $theme_uri . '/assets/images/favicon-32.png' ); ?>">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url( $theme_uri . '/assets/images/favicon-16.png' ); ?>">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url( $theme_uri . '/assets/images/apple-touch-icon.png' ); ?>">
<link rel="manifest" href="<?php echo esc_url( $theme_uri . '/assets/site.webmanifest' ); ?>">

<?php
    // LocalBusiness JSON-LD Structured Data
    $schema = [
        '@context'           => 'https://schema.org',
        '@type'              => ['LocalBusiness', 'ProfessionalService'],
        'name'               => 'Apex Coatings & Engraving',
        'alternateName'      => 'Apex Coatings and Engraving',
        'description'        => 'Premium Cerakote finishing, custom engraving, and laser engraving services in Nashville, Tennessee. Specializing in pistol slide Cerakote, AR magazine engraving, knife engraving, and custom metal work.',
        'telephone'          => '+16158621660',
        'email'              => 'orders@apexcoatingstn.com',
        'url'                => $site_url,
        'logo'               => $theme_uri . '/assets/images/apex-logo-hero.png',
        'image'              => $theme_uri . '/assets/images/apex-logo-hero.png',
        'priceRange'         => '$$',
        'currenciesAccepted' => 'USD',
        'paymentAccepted'    => 'Cash, Check',
        'areaServed'         => [
            [ '@type' => 'State', 'name' => 'Tennessee' ],
            [ '@type' => 'City',  'name' => 'Nashville, TN' ],
        ],
        'address' => [
            '@type'           => 'PostalAddress',
            'addressLocality' => 'Nashville',
            'addressRegion'   => 'TN',
            'addressCountry'  => 'US',
        ],
        'openingHoursSpecification' => [
            [
                '@type'     => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Monday','Tuesday','Wednesday','Thursday','Friday'],
                'opens'     => '08:00',
                'closes'    => '17:00',
            ],
            [
                '@type'     => 'OpeningHoursSpecification',
                'dayOfWeek' => 'Saturday',
                'opens'     => '09:00',
                'closes'    => '14:00',
            ],
        ],
        'sameAs' => [
            'https://www.tiktok.com/@apexcoatingsandengraving',
        ],
        'hasOfferCatalog' => [
            '@type' => 'OfferCatalog',
            'name'  => 'Cerakote & Engraving Services',
            'itemListElement' => [
                [ '@type' => 'Offer', 'itemOffered' => [ '@type' => 'Service', 'name' => 'Custom Pistol Slide Engraving',  'description' => 'Precision laser engraving on pistol slides starting at $75.' ] ],
                [ '@type' => 'Offer', 'itemOffered' => [ '@type' => 'Service', 'name' => 'Cerakote Slide Finishing',       'description' => 'Single-color Cerakote on pistol slides starting at $100. Multi-color quoted per project.' ] ],
                [ '@type' => 'Offer', 'itemOffered' => [ '@type' => 'Service', 'name' => 'Handgun Cerakoting',            'description' => 'Full Cerakote application on handgun frames starting at $100.' ] ],
                [ '@type' => 'Offer', 'itemOffered' => [ '@type' => 'Service', 'name' => 'AR-15 / AR-10 Cerakoting',     'description' => 'Professional Cerakote on AR-15 and AR-10 lowers starting at $100.' ] ],
                [ '@type' => 'Offer', 'itemOffered' => [ '@type' => 'Service', 'name' => 'Custom Knife Engraving',        'description' => 'Custom laser engraving on knives and blades starting at $30.' ] ],
                [ '@type' => 'Offer', 'itemOffered' => [ '@type' => 'Service', 'name' => 'Laser Engraving',               'description' => 'High-precision laser etching on metal, wood, slate, and more.' ] ],
            ],
        ],
    ];
    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";

    // Product schema for single product pages
    if ( is_singular( 'apex_product' ) && $post ) {
        $price    = get_post_meta( $post->ID, '_apex_price', true );
        $sku      = get_post_meta( $post->ID, '_apex_sku', true );
        $in_stock = get_post_meta( $post->ID, '_apex_in_stock', true );

        $product_schema = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Product',
            'name'        => get_the_title( $post ),
            'description' => get_the_excerpt( $post ),
            'url'         => get_permalink( $post ),
            'brand'       => [ '@type' => 'Brand', 'name' => 'Apex Coatings & Engraving' ],
        ];
        if ( $sku ) $product_schema['sku'] = $sku;
        if ( has_post_thumbnail( $post ) ) {
            $product_schema['image'] = get_the_post_thumbnail_url( $post, 'large' );
        }
        if ( $price ) {
            $product_schema['offers'] = [
                '@type'         => 'Offer',
                'price'         => floatval( $price ),
                'priceCurrency' => 'USD',
                'availability'  => ( $in_stock === '1' )
                    ? 'https://schema.org/InStock'
                    : 'https://schema.org/PreOrder',
                'seller'        => [ '@type' => 'Organization', 'name' => 'Apex Coatings & Engraving' ],
            ];
        }
        echo '<script type="application/ld+json">' . wp_json_encode( $product_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
    }
}
add_action( 'wp_head', 'apex_seo_meta', 1 );

/* ============================================================
   AUTO ALT TEXT FROM FILENAME
   ============================================================ */
function apex_auto_image_alt( $content ) {
    return preg_replace_callback(
        '/<img([^>]*?)alt=""([^>]*?)>/i',
        function ( $matches ) {
            // Try to extract src filename for alt text
            if ( preg_match( '/src=["\']([^"\']+)["\']/', $matches[1] . $matches[2], $src_m ) ) {
                $filename = pathinfo( $src_m[1], PATHINFO_FILENAME );
                $filename = preg_replace( '/^apex-/', '', $filename );
                $filename = preg_replace( '/-\d+[a-z-]*$/', '', $filename );
                $filename = ucwords( str_replace( [ '-', '_' ], ' ', $filename ) );
                return '<img' . $matches[1] . 'alt="' . esc_attr( $filename . ' — Apex Coatings & Engraving' ) . '"' . $matches[2] . '>';
            }
            return $matches[0];
        },
        $content
    );
}
add_filter( 'the_content', 'apex_auto_image_alt' );

/* ============================================================
   XML SITEMAP PING ON SAVE
   ============================================================ */
function apex_ping_sitemap( $post_id ) {
    if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) return;
    $sitemap_url = home_url( '/sitemap_index.xml' );
    // Ping Google
    wp_remote_get( 'https://www.google.com/ping?sitemap=' . rawurlencode( $sitemap_url ), [ 'blocking' => false ] );
}
add_action( 'save_post', 'apex_ping_sitemap' );

/* ============================================================
   CUSTOM POST TYPE — PRODUCTS
   ============================================================ */
function apex_register_products_cpt() {
    $labels = [
        'name'               => 'Products',
        'singular_name'      => 'Product',
        'add_new'            => 'Add New Product',
        'add_new_item'       => 'Add New Product',
        'edit_item'          => 'Edit Product',
        'new_item'           => 'New Product',
        'view_item'          => 'View Product',
        'search_items'       => 'Search Products',
        'not_found'          => 'No products found',
        'not_found_in_trash' => 'No products found in Trash',
        'menu_name'          => 'Products',
    ];

    register_post_type( 'apex_product', [
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'rewrite'            => [ 'slug' => 'products' ],
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
        'menu_icon'          => 'dashicons-tag',
        'show_in_rest'       => true,
    ] );
}
add_action( 'init', 'apex_register_products_cpt' );

/* ============================================================
   FIX: Force page-products.php template for /products/ URL
   ============================================================ */
function apex_force_products_template( $template ) {
    global $wp;
    $request = trim( $wp->request, '/' );

    if ( $request === 'products' || strpos( $request, 'products/page/' ) === 0 ) {
        $custom = locate_template( 'page-products.php' );
        if ( $custom ) return $custom;
    }
    return $template;
}
add_filter( 'template_include', 'apex_force_products_template', 99999 );

/* ============================================================
   CUSTOM TAXONOMY — PRODUCT CATEGORY
   ============================================================ */
function apex_register_product_taxonomy() {
    register_taxonomy( 'product_cat', 'apex_product', [
        'labels'       => [
            'name'              => 'Product Categories',
            'singular_name'     => 'Product Category',
            'search_items'      => 'Search Categories',
            'all_items'         => 'All Categories',
            'edit_item'         => 'Edit Category',
            'update_item'       => 'Update Category',
            'add_new_item'      => 'Add New Category',
            'new_item_name'     => 'New Category Name',
            'menu_name'         => 'Categories',
        ],
        'hierarchical' => true,
        'show_ui'      => true,
        'rewrite'      => [ 'slug' => 'product-category' ],
        'show_in_rest' => true,
    ] );
}
add_action( 'init', 'apex_register_product_taxonomy' );

/* ============================================================
   PRODUCT META BOXES (Price, SKU, In Stock)
   ============================================================ */
function apex_add_product_meta_boxes() {
    add_meta_box(
        'apex_product_details',
        'Product Details',
        'apex_product_details_callback',
        'apex_product',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'apex_add_product_meta_boxes' );

function apex_product_details_callback( $post ) {
    wp_nonce_field( 'apex_product_details_nonce', 'apex_product_details_nonce' );
    $price    = get_post_meta( $post->ID, '_apex_price', true );
    $sku      = get_post_meta( $post->ID, '_apex_sku', true );
    $in_stock = get_post_meta( $post->ID, '_apex_in_stock', true );
    $material = get_post_meta( $post->ID, '_apex_material', true );
    ?>
    <p>
        <label for="apex_price"><strong>Starting Price ($)</strong></label><br>
        <input type="number" id="apex_price" name="apex_price" value="<?php echo esc_attr($price); ?>"
               step="0.01" min="0" style="width:100%;margin-top:4px;">
    </p>
    <p>
        <label for="apex_sku"><strong>SKU / Item Code</strong></label><br>
        <input type="text" id="apex_sku" name="apex_sku" value="<?php echo esc_attr($sku); ?>"
               style="width:100%;margin-top:4px;">
    </p>
    <p>
        <label for="apex_material"><strong>Material</strong></label><br>
        <input type="text" id="apex_material" name="apex_material" value="<?php echo esc_attr($material); ?>"
               placeholder="e.g. Steel, Aluminum" style="width:100%;margin-top:4px;">
    </p>
    <p>
        <label>
            <input type="checkbox" name="apex_in_stock" value="1" <?php checked($in_stock, '1'); ?>>
            <strong>In Stock / Available</strong>
        </label>
    </p>
    <?php
}

function apex_save_product_meta( $post_id ) {
    if ( ! isset($_POST['apex_product_details_nonce']) ) return;
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['apex_product_details_nonce'] ) ), 'apex_product_details_nonce' ) ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can('edit_post', $post_id) ) return;

    if ( isset($_POST['apex_price']) ) {
        update_post_meta( $post_id, '_apex_price', sanitize_text_field( wp_unslash( $_POST['apex_price'] ) ) );
    }
    if ( isset($_POST['apex_sku']) ) {
        update_post_meta( $post_id, '_apex_sku', sanitize_text_field( wp_unslash( $_POST['apex_sku'] ) ) );
    }
    if ( isset($_POST['apex_material']) ) {
        update_post_meta( $post_id, '_apex_material', sanitize_text_field( wp_unslash( $_POST['apex_material'] ) ) );
    }
    $in_stock = isset($_POST['apex_in_stock']) ? '1' : '0';
    update_post_meta( $post_id, '_apex_in_stock', $in_stock );
}
add_action( 'save_post_apex_product', 'apex_save_product_meta' );

/* ============================================================
   AJAX — SUBMIT ORDER (sends email to admin)
   ============================================================ */
function apex_submit_order() {
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ?? '' ) ), 'apex_order_nonce' ) ) {
        wp_send_json_error( [ 'message' => 'Security check failed.' ] );
    }

    $required = [ 'first_name', 'last_name', 'email', 'phone', 'address', 'city', 'state', 'zip' ];
    foreach ( $required as $field ) {
        if ( empty($_POST[$field]) ) {
            wp_send_json_error( [ 'message' => 'Please fill in all required fields.' ] );
        }
    }

    // Sanitize customer info
    $first_name = sanitize_text_field( wp_unslash( $_POST['first_name'] ) );
    $last_name  = sanitize_text_field( wp_unslash( $_POST['last_name'] ) );
    $email      = sanitize_email( wp_unslash( $_POST['email'] ) );
    $phone      = sanitize_text_field( wp_unslash( $_POST['phone'] ) );
    $company    = sanitize_text_field( wp_unslash( $_POST['company'] ?? '' ) );
    $address    = sanitize_text_field( wp_unslash( $_POST['address'] ) );
    $city       = sanitize_text_field( wp_unslash( $_POST['city'] ) );
    $state      = sanitize_text_field( wp_unslash( $_POST['state'] ) );
    $zip        = sanitize_text_field( wp_unslash( $_POST['zip'] ) );
    $country    = sanitize_text_field( wp_unslash( $_POST['country'] ?? 'United States' ) );
    $notes      = sanitize_textarea_field( wp_unslash( $_POST['notes'] ?? '' ) );

    // Cart items (JSON encoded) — wp_unslash instead of stripslashes
    $cart_json  = wp_unslash( $_POST['cart_items'] ?? '[]' );
    $cart_items = json_decode( $cart_json, true );
    if ( ! is_array($cart_items) ) $cart_items = [];
    // Sanitize each cart item to prevent injection
    $cart_items = array_map( function( $item ) {
        return [
            'name'     => sanitize_text_field( $item['name']     ?? '' ),
            'price'    => floatval( $item['price']    ?? 0 ),
            'quantity' => intval(  $item['quantity'] ?? 1 ),
        ];
    }, $cart_items );

    // Generate order reference
    $order_ref  = 'APEX-' . strtoupper( substr(md5(uniqid()), 0, 8) );
    $order_date = current_time( 'F j, Y g:i A' );

    // Calculate total
    $total = 0;
    foreach ( $cart_items as $item ) {
        $total += floatval($item['price']) * intval($item['quantity']);
    }

    // Build email HTML
    $site_name   = get_bloginfo('name') ?: 'Apex Coatings & Engraving';
    $admin_email = 'orders@apexcoatingstn.com';

    ob_start();
    ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
  body { font-family: Arial, sans-serif; color: #333; background: #f5f5f5; margin: 0; padding: 20px; }
  .wrap { max-width: 640px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.12); }
  .header { background: #1A1A1A; color: #fff; padding: 30px 36px; }
  .header h1 { margin: 0; font-size: 22px; letter-spacing: 2px; text-transform: uppercase; }
  .header .ref { color: #F5831F; font-size: 14px; margin-top: 6px; letter-spacing: 1px; }
  .body { padding: 32px 36px; }
  h2 { font-size: 14px; text-transform: uppercase; letter-spacing: 1px; color: #1A1A1A; border-bottom: 2px solid #F5831F; padding-bottom: 8px; margin: 28px 0 16px; }
  h2:first-child { margin-top: 0; }
  .info-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
  .info-table td { padding: 6px 10px 6px 0; font-size: 14px; vertical-align: top; }
  .info-table td.lbl { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #999; white-space: nowrap; padding-right: 14px; width: 1%; }
  table { width: 100%; border-collapse: collapse; }
  th { background: #1A1A1A; color: #fff; padding: 10px 14px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; text-align: left; }
  td { padding: 12px 14px; border-bottom: 1px solid #eee; font-size: 14px; }
  tr:last-child td { border-bottom: none; }
  .total-row td { font-weight: 800; font-size: 16px; color: #1A1A1A; border-top: 2px solid #1A1A1A; }
  .total-row td.amount { color: #F5831F; }
  .notes-box { background: #f9f9f9; border-left: 3px solid #F5831F; padding: 14px 18px; font-size: 14px; color: #555; }
  .footer { background: #f0f0f0; padding: 20px 36px; font-size: 12px; color: #999; text-align: center; }
</style>
</head>
<body>
<div class="wrap">
  <div class="header">
    <h1>&#9679; New Order Received</h1>
    <div class="ref">Order Reference: <?php echo esc_html($order_ref); ?> &nbsp;|&nbsp; <?php echo esc_html($order_date); ?></div>
  </div>
  <div class="body">
    <h2>Customer Information</h2>
    <table class="info-table">
      <tr><td class="lbl">Name</td><td><strong><?php echo esc_html("$first_name $last_name"); ?></strong></td></tr>
      <tr><td class="lbl">Email</td><td><strong><?php echo esc_html($email); ?></strong></td></tr>
      <tr><td class="lbl">Phone</td><td><strong><?php echo esc_html($phone); ?></strong></td></tr>
      <?php if ($company): ?><tr><td class="lbl">Company</td><td><strong><?php echo esc_html($company); ?></strong></td></tr><?php endif; ?>
    </table>

    <h2>Shipping / Delivery Address</h2>
    <table class="info-table">
      <tr><td class="lbl">Street</td><td><strong><?php echo esc_html($address); ?></strong></td></tr>
      <tr><td class="lbl">City</td><td><strong><?php echo esc_html($city); ?></strong></td></tr>
      <tr><td class="lbl">State</td><td><strong><?php echo esc_html($state); ?></strong></td></tr>
      <tr><td class="lbl">ZIP</td><td><strong><?php echo esc_html($zip); ?></strong></td></tr>
      <tr><td class="lbl">Country</td><td><strong><?php echo esc_html($country); ?></strong></td></tr>
    </table>

    <h2>Order Items</h2>
    <table>
      <thead>
        <tr><th>Item</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr>
      </thead>
      <tbody>
        <?php foreach ($cart_items as $item): ?>
        <tr>
          <td><?php echo esc_html($item['name']); ?></td>
          <td><?php echo intval($item['quantity']); ?></td>
          <td>$<?php echo number_format(floatval($item['price']), 2); ?></td>
          <td>$<?php echo number_format(floatval($item['price']) * intval($item['quantity']), 2); ?></td>
        </tr>
        <?php endforeach; ?>
        <tr class="total-row">
          <td colspan="3"><strong>ESTIMATED TOTAL</strong></td>
          <td class="amount"><strong>$<?php echo number_format($total, 2); ?></strong></td>
        </tr>
      </tbody>
    </table>
    <p style="font-size:12px;color:#999;margin-top:8px;">* Final pricing will be confirmed before work begins. Shipping and applicable taxes not included.</p>

    <?php if ($notes): ?>
    <h2>Special Requests / Notes</h2>
    <div class="notes-box"><?php echo nl2br(esc_html($notes)); ?></div>
    <?php endif; ?>
  </div>
  <div class="footer">
    This order was submitted via the <?php echo esc_html($site_name); ?> website.<br>
    Reply to this email to contact the customer at <?php echo esc_html($email); ?>
  </div>
</div>
</body>
</html>
    <?php
    $email_body = ob_get_clean();

    // Send to admin
    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        "Reply-To: {$first_name} {$last_name} <{$email}>",
    ];

    $subject = "[{$site_name}] New Order #{$order_ref} — {$first_name} {$last_name}";

    $sent = wp_mail( $admin_email, $subject, $email_body, $headers );

    // Also send confirmation to customer
    $customer_body = "
<p>Hi {$first_name},</p>
<p>Thank you for your order request! We've received your submission (Reference: <strong>{$order_ref}</strong>) and will review it shortly.</p>
<p>We'll be in touch within <strong>1-2 business days</strong> to confirm your order details and pricing before any work begins.</p>
<p>Questions? Reply to this email or call us directly.</p>
<br>
<p>— The {$site_name} Team</p>
";
    wp_mail(
        $email,
        "Order Request Received — {$order_ref}",
        $customer_body,
        [ 'Content-Type: text/html; charset=UTF-8' ]
    );

    if ( $sent ) {
        // Save as a custom post for records
        wp_insert_post([
            'post_type'   => 'apex_order',
            'post_title'  => $order_ref . ' — ' . $first_name . ' ' . $last_name,
            'post_status' => 'private',
            'meta_input'  => [
                '_order_ref'      => $order_ref,
                '_customer_email' => $email,
                '_order_data'     => $cart_json,
                '_order_total'    => $total,
                '_order_notes'    => $notes,
            ],
        ]);

        wp_send_json_success([
            'message'   => 'Order submitted successfully!',
            'order_ref' => $order_ref,
        ]);
    } else {
        wp_send_json_error([ 'message' => 'There was an issue sending your order. Please call us directly.' ]);
    }
}
add_action( 'wp_ajax_apex_submit_order',        'apex_submit_order' );
add_action( 'wp_ajax_nopriv_apex_submit_order', 'apex_submit_order' );

/* ============================================================
   REGISTER ORDER CPT (for record-keeping in WP Admin)
   ============================================================ */
function apex_register_order_cpt() {
    register_post_type( 'apex_order', [
        'labels'   => [
            'name'          => 'Orders',
            'singular_name' => 'Order',
            'menu_name'     => 'Orders',
        ],
        'public'          => false,
        'show_ui'         => true,
        'supports'        => [ 'title', 'custom-fields' ],
        'menu_icon'       => 'dashicons-clipboard',
        'capability_type' => 'post',
    ] );
}
add_action( 'init', 'apex_register_order_cpt' );

/* ============================================================
   AJAX — GET PRODUCTS (transient-cached to avoid repeated DB hits)
   ============================================================ */
function apex_get_products() {
    $cache_key = 'apex_products_json';
    $cached    = get_transient( $cache_key );

    if ( false !== $cached ) {
        wp_send_json_success( $cached );
        return;
    }

    $products = get_posts([
        'post_type'      => 'apex_product',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'no_found_rows'  => true, // skip COUNT(*) query — faster
        'update_post_term_cache' => false, // skip term cache — not needed here
    ]);

    $data = [];
    foreach ( $products as $p ) {
        $data[] = [
            'id'       => $p->ID,
            'name'     => $p->post_title,
            'desc'     => get_the_excerpt($p),
            'price'    => get_post_meta($p->ID, '_apex_price', true),
            'sku'      => get_post_meta($p->ID, '_apex_sku', true),
            'image'    => get_the_post_thumbnail_url($p->ID, 'apex-card') ?: get_the_post_thumbnail_url($p->ID, 'medium'),
            'in_stock' => get_post_meta($p->ID, '_apex_in_stock', true),
        ];
    }

    // Cache for 12 hours — busted automatically when any product is saved
    set_transient( $cache_key, $data, 12 * HOUR_IN_SECONDS );

    wp_send_json_success( $data );
}
add_action( 'wp_ajax_apex_get_products',        'apex_get_products' );
add_action( 'wp_ajax_nopriv_apex_get_products', 'apex_get_products' );

// Bust product cache whenever a product is saved or deleted
add_action( 'save_post_apex_product',   function() { delete_transient( 'apex_products_json' ); } );
add_action( 'before_delete_post',       function( $id ) {
    if ( get_post_type( $id ) === 'apex_product' ) delete_transient( 'apex_products_json' );
} );

/* ============================================================
   CONTACT FORM AJAX
   ============================================================ */
function apex_submit_contact() {
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ?? '' ) ), 'apex_order_nonce' ) ) {
        wp_send_json_error( [ 'message' => 'Security check failed.' ] );
    }

    $name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
    $email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
    $phone   = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
    $subject = sanitize_text_field( wp_unslash( $_POST['subject'] ?? 'General Inquiry' ) );
    $message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );

    if ( empty($name) || empty($email) || empty($message) ) {
        wp_send_json_error([ 'message' => 'Please fill in all required fields.' ]);
    }

    $admin_email = 'orders@apexcoatingstn.com';
    $site_name   = get_bloginfo('name') ?: 'Apex Coatings & Engraving';

    $body = "
<h2>New Contact Message</h2>
<p><strong>From:</strong> {$name} ({$email})</p>
<p><strong>Phone:</strong> {$phone}</p>
<p><strong>Subject:</strong> {$subject}</p>
<hr>
<p>" . nl2br(esc_html($message)) . "</p>
";

    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        "Reply-To: {$name} <{$email}>",
    ];

    $sent = wp_mail( $admin_email, "[{$site_name}] {$subject}", $body, $headers );

    if ( $sent ) {
        wp_send_json_success([ 'message' => 'Message sent! We\'ll get back to you within 1-2 business days.' ]);
    } else {
        wp_send_json_error([ 'message' => 'Could not send message. Please call us directly.' ]);
    }
}
add_action( 'wp_ajax_apex_submit_contact',        'apex_submit_contact' );
add_action( 'wp_ajax_nopriv_apex_submit_contact', 'apex_submit_contact' );

/* ============================================================
   HELPER — Get Products for Templates
   ============================================================ */
function apex_get_products_query( $limit = -1, $cat = '' ) {
    $args = [
        'post_type'      => 'apex_product',
        'posts_per_page' => $limit,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ];
    if ( $cat ) {
        $args['tax_query'] = [[
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $cat,
        ]];
    }
    return new WP_Query( $args );
}

/* ============================================================
   HELPER — Format Price
   ============================================================ */
function apex_price( $price ) {
    return '$' . number_format( floatval($price), 0, '.', ',' );
}

/* ============================================================
   EXCERPT LENGTH
   ============================================================ */
add_filter( 'excerpt_length', fn() => 20 );
add_filter( 'excerpt_more',   fn() => '...' );

/* ============================================================
   FLUSH REWRITE RULES ON ACTIVATION
   ============================================================ */
function apex_flush_rewrite() {
    apex_register_products_cpt();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'apex_flush_rewrite' );

/* ============================================================
   AUTO-CREATE HEROES PAGE (runs once — option gate prevents repeated DB work)
   ============================================================ */
function apex_create_heroes_page() {
    // Fast exit — option cached in WP object cache, near-zero overhead
    if ( get_option( 'apex_heroes_page_created' ) ) return;

    $existing = get_page_by_path( 'heroes' );
    if ( $existing ) {
        update_option( 'apex_heroes_page_created', 1 );
        return;
    }

    $page_id = wp_insert_post( [
        'post_title'     => 'Heroes Discount',
        'post_name'      => 'heroes',
        'post_content'   => '',
        'post_status'    => 'publish',
        'post_type'      => 'page',
        'page_template'  => 'page-heroes.php',
        'comment_status' => 'closed',
    ] );

    if ( $page_id && ! is_wp_error( $page_id ) ) {
        update_post_meta( $page_id, '_wp_page_template', 'page-heroes.php' );
        update_option( 'apex_heroes_page_created', 1 );
    }
}
add_action( 'init', 'apex_create_heroes_page' );

/* ============================================================
   SMTP MAILER — Namecheap Private Email (SSL port 465)
   Credentials are stored in wp-config.php as constants.
   ============================================================ */
add_action( 'phpmailer_init', 'apex_configure_smtp' );
function apex_configure_smtp( $phpmailer ) {
    if ( ! defined( 'APEX_SMTP_HOST' ) || APEX_SMTP_PASS === 'YOUR_EMAIL_PASSWORD_HERE' ) {
        return; // Skip until password is set
    }

    $phpmailer->isSMTP();
    $phpmailer->Host       = APEX_SMTP_HOST;
    $phpmailer->Port       = APEX_SMTP_PORT;       // 465
    $phpmailer->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS; // SSL
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Username   = APEX_SMTP_USER;
    $phpmailer->Password   = APEX_SMTP_PASS;
    $phpmailer->From       = APEX_SMTP_FROM;
    $phpmailer->FromName   = APEX_SMTP_FROM_NAME;
    $phpmailer->SMTPOptions = [
        'ssl' => [
            'verify_peer'       => true,
            'verify_peer_name'  => true,
            'allow_self_signed' => false,
        ],
    ];
}

// Force From address on every outgoing email
add_filter( 'wp_mail_from',      fn() => defined('APEX_SMTP_FROM')      ? APEX_SMTP_FROM      : 'orders@apexcoatingstn.com' );
add_filter( 'wp_mail_from_name', fn() => defined('APEX_SMTP_FROM_NAME') ? APEX_SMTP_FROM_NAME : 'Apex Coatings & Engraving' );
