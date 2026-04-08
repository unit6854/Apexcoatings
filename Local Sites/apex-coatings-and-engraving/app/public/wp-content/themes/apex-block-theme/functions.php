<?php
/**
 * Apex Coatings & Engraving — Block Theme functions.php
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* ============================================================
   SITE CONTENT SETTINGS PANEL
   ============================================================ */
require_once get_template_directory() . '/inc/apex-settings.php';

/* ============================================================
   CONTENT WIDTH
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
    add_theme_support( 'editor-styles' );

    // Block theme: align content padding with theme.json
    add_theme_support( 'align-wide' );

    // Register custom image sizes
    add_image_size( 'apex-card',    600,  400, true );
    add_image_size( 'apex-gallery', 800,  600, true );
    add_image_size( 'apex-hero',   1400,  800, true );

    register_nav_menus( [
        'primary' => __( 'Primary Menu', 'apex-block-theme' ),
        'footer'  => __( 'Footer Menu',  'apex-block-theme' ),
    ] );
}
add_action( 'after_setup_theme', 'apex_setup' );

/* ============================================================
   PERFORMANCE: REMOVE BLOAT
   ============================================================ */
remove_action( 'wp_head',             'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles',     'print_emoji_styles' );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles',  'print_emoji_styles' );
add_filter( 'xmlrpc_enabled', '__return_false' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'wp_oembed_add_host_js' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );

function apex_resource_hints() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}
add_action( 'wp_head', 'apex_resource_hints', 1 );

/* ============================================================
   ENQUEUE STYLES & SCRIPTS
   ============================================================ */
function apex_enqueue_assets() {
    $ver = '2.0.0';
    $uri = get_template_directory_uri();

    // Google Fonts
    wp_enqueue_style( 'apex-fonts', 'https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700&family=Barlow+Condensed:wght@400;600;700;800;900&display=swap', [], null );

    // Main theme stylesheet
    wp_enqueue_style( 'apex-theme', $uri . '/assets/css/theme.css', [ 'apex-fonts' ], $ver );

    // Cart JS (loads everywhere for cart badge)
    wp_enqueue_script( 'apex-cart', $uri . '/assets/js/cart.js', [], $ver, true );

    // Main JS — deferred
    wp_enqueue_script( 'apex-main', $uri . '/assets/js/main.js', [ 'apex-cart' ], $ver, true );

    // Pass AJAX URL + nonce to JS
    wp_localize_script( 'apex-cart', 'apexAjax', [
        'url'   => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce( 'apex_order_nonce' ),
        'home'  => home_url('/'),
    ] );

    // Checkout-specific JS
    if ( is_page( 'checkout' ) || is_page( 'order' ) ) {
        wp_enqueue_script( 'apex-checkout', $uri . '/assets/js/checkout.js', [ 'apex-cart' ], $ver, true );
    }
}
add_action( 'wp_enqueue_scripts', 'apex_enqueue_assets' );

/* ============================================================
   DEFER NON-CRITICAL SCRIPTS
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
   EDITOR STYLES — Load theme.css in block editor too
   ============================================================ */
function apex_editor_styles() {
    add_editor_style( 'assets/css/theme.css' );
}
add_action( 'after_setup_theme', 'apex_editor_styles' );

/* ============================================================
   BLOCK PATTERNS — Register all section patterns
   ============================================================ */
function apex_register_block_patterns() {
    register_block_pattern_category( 'apex', [ 'label' => __( 'Apex Sections', 'apex-block-theme' ) ] );
}
add_action( 'init', 'apex_register_block_patterns' );

/* ============================================================
   SOCIAL LINKS OPTION
   ============================================================ */
function apex_get_social_links() {
    if ( get_option( 'apex_social_facebook' ) === '#' ) {
        update_option( 'apex_social_facebook', 'https://www.facebook.com/share/1ApWJAXR7v/?mibextid=wwXIfr' );
    }
    return [
        'tiktok'   => get_option( 'apex_social_tiktok',   'https://www.tiktok.com/@apexcoatingsandengraving?_r=1&_t=ZP-958ATEtcIjm' ),
        'facebook' => get_option( 'apex_social_facebook', 'https://www.facebook.com/share/1ApWJAXR7v/?mibextid=wwXIfr' ),
    ];
}
add_action( 'init', function () {
    if ( false === get_option( 'apex_social_tiktok' ) ) {
        add_option( 'apex_social_tiktok',   'https://www.tiktok.com/@apexcoatingsandengraving?_r=1&_t=ZP-958ATEtcIjm' );
        add_option( 'apex_social_facebook', 'https://www.facebook.com/share/1ApWJAXR7v/?mibextid=wwXIfr' );
    }
} );

/* ============================================================
   SEO META TAGS
   ============================================================ */
function apex_seo_meta() {
    global $post;

    $site_name  = 'Apex Coatings & Engraving';
    $site_url   = 'https://apexcoatingstn.com';
    $theme_uri  = get_template_directory_uri();
    $og_image   = $theme_uri . '/assets/images/og-image.webp';
    $canonical  = esc_url( get_permalink() ?: home_url('/') );

    if ( is_page( [ 'cart', 'checkout', 'order' ] ) ) {
        echo '<meta name="robots" content="noindex, nofollow">' . "\n";
        return;
    }

    if ( is_front_page() ) {
        $title       = 'Apex Coatings & Engraving — Cerakote & Custom Engraving | Nashville, TN';
        $description = 'Premium Cerakote finishing, laser engraving, and custom metal work in Nashville, Tennessee. Trusted by military, veterans, law enforcement, and collectors. 10% off for heroes.';
        $keywords    = 'Cerakote Nashville, laser engraving Tennessee, Cerakote application, custom engraving, AR magazine engraving, 1911 engraving, pistol slide Cerakote, military engraving, veteran Cerakote, police custom engraving, AR-15 Cerakote Nashville';
    } elseif ( is_page( 'gallery' ) ) {
        $title       = 'Gallery — Custom Engraving & Coating Work | Apex Coatings & Engraving';
        $description = 'Browse our gallery of custom engraving, AR magazine art, laser engraving, and Cerakote projects completed by Apex Coatings & Engraving.';
        $keywords    = 'custom engraving gallery, AR magazine engraving, laser engraving portfolio, Cerakote examples Tennessee';
    } elseif ( is_page( 'contact' ) ) {
        $title       = 'Contact Us — Get a Free Quote | Apex Coatings & Engraving';
        $description = 'Contact Apex Coatings & Engraving for a free quote on Cerakote, laser engraving, or custom metalwork. Call (615) 862-1660 or fill out our quick form.';
        $keywords    = 'contact Apex Coatings, Cerakote quote Nashville, custom engraving quote Tennessee, laser engraving quote';
    } elseif ( is_page( 'heroes' ) ) {
        $title       = 'Military & First Responder Discount — 10% Off | Apex Coatings & Engraving Nashville';
        $description = 'Apex Coatings & Engraving honors active military, veterans, Army, Navy, Marines, Air Force, Coast Guard, Space Force, law enforcement, firefighters, EMTs, and healthcare workers with a 10% discount.';
        $keywords    = 'military discount engraving Tennessee, veteran Cerakote discount, Army engraving discount, first responder discount, police custom engraving Nashville';
    } elseif ( is_singular( 'apex_product' ) && $post ) {
        $product_title = get_the_title( $post );
        $excerpt       = get_the_excerpt( $post ) ?: 'Premium custom finishing and engraving by Apex Coatings & Engraving.';
        $title         = esc_html( $product_title ) . ' | Apex Coatings & Engraving';
        $description   = esc_html( $excerpt );
        $keywords      = esc_html( $product_title ) . ', custom engraving Tennessee, Cerakote Nashville';
        if ( has_post_thumbnail( $post ) ) $og_image = get_the_post_thumbnail_url( $post, 'large' );
    } elseif ( is_page( 'products' ) ) {
        $title       = 'Products & Services — Custom Coatings & Engraving | Apex Coatings & Engraving';
        $description = 'Browse Apex Coatings & Engraving products including Cerakote finishing, custom engraving, laser engraving, and custom metalwork. Starting from $75.';
        $keywords    = 'Cerakote Tennessee, custom engraving products, laser engraving services Nashville';
    } elseif ( is_page( 'services' ) ) {
        $title       = 'Our Services — Cerakote, Laser Engraving & Metal Polishing | Apex Coatings & Engraving';
        $description = 'Apex Coatings & Engraving offers Cerakote application, precision laser engraving, metal polishing, graphics design, and custom gifts in Tennessee.';
        $keywords    = 'Cerakote services Nashville, laser engraving services Tennessee, metal polishing, custom gifts Tennessee';
    } elseif ( is_page( 'pmags' ) ) {
        $title       = 'Custom PMAG Engraving — Genuine Magpul AR Magazines | Apex Coatings & Engraving';
        $description = 'Custom laser engraving on genuine Magpul PMAGs. 28+ designs including military, law enforcement badges, patriotic, and custom art. $35 includes mag and engraving.';
        $keywords    = 'custom PMAG engraving, Magpul PMAG engraving, AR magazine engraving, laser engraved AR mag, custom AR magazine Tennessee';
    } elseif ( is_page( '1911-grips' ) ) {
        $title       = '1911 Grip Panels — HEX Series Custom Grips | Apex Coatings & Engraving';
        $description = 'American-made 1911 grip panels in HEX Series Black Cerakote, Aluminum Reveal, and Satin Aluminum. Made to order in-house. Starting at $90 per set.';
        $keywords    = '1911 grip panels, custom 1911 grips, HEX Series grips, aluminum 1911 grips, Cerakote 1911 grips';
    } else {
        $title       = ( $post ? get_the_title( $post ) . ' | ' : '' ) . $site_name;
        $description = 'Premium Cerakote finishing, custom engraving, and laser engraving services in Tennessee.';
        $keywords    = 'Cerakote, laser engraving, custom engraving Tennessee';
    }

    $og_url = $canonical;
    ?>
<meta name="description" content="<?php echo esc_attr( $description ); ?>">
<meta name="keywords" content="<?php echo esc_attr( $keywords ); ?>">
<meta name="author" content="Apex Coatings &amp; Engraving">
<meta name="theme-color" content="#F5831F">
<meta name="msapplication-TileColor" content="#1A1A1A">
<link rel="canonical" href="<?php echo esc_url( $canonical ); ?>">
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
<meta name="googlebot" content="index, follow, max-image-preview:large, max-snippet:-1">
<meta name="bingbot" content="index, follow">
<meta name="GPTBot" content="index, follow">
<meta name="OAI-SearchBot" content="index, follow">
<meta name="ClaudeBot" content="index, follow">
<meta name="PerplexityBot" content="index, follow">
<meta name="ChatGPT-User" content="index, follow">
<meta name="CCBot" content="index, follow">
<meta name="anthropic-ai" content="index, follow">
<meta name="Bytespider" content="index, follow">
<meta name="YouBot" content="index, follow">
<meta property="og:type" content="<?php echo is_singular('apex_product') ? 'product' : 'website'; ?>">
<meta property="og:site_name" content="<?php echo esc_attr( $site_name ); ?>">
<meta property="og:title" content="<?php echo esc_attr( $title ); ?>">
<meta property="og:description" content="<?php echo esc_attr( $description ); ?>">
<meta property="og:url" content="<?php echo esc_url( $og_url ); ?>">
<meta property="og:image" content="<?php echo esc_url( $og_image ); ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:type" content="image/webp">
<meta property="og:locale" content="en_US">
<meta property="business:contact_data:locality" content="Nashville">
<meta property="business:contact_data:region" content="Tennessee">
<meta property="business:contact_data:country_name" content="United States">
<meta property="business:contact_data:phone_number" content="+16158621660">
<meta name="geo.region" content="US-TN">
<meta name="geo.placename" content="Nashville, Tennessee">
<meta name="geo.position" content="36.1627;-86.7816">
<meta name="ICBM" content="36.1627, -86.7816">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@apexcoatingstn">
<meta name="twitter:title" content="<?php echo esc_attr( $title ); ?>">
<meta name="twitter:description" content="<?php echo esc_attr( $description ); ?>">
<meta name="twitter:image" content="<?php echo esc_url( $og_image ); ?>">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( $theme_uri . '/assets/images/favicon-32.png' ); ?>">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url( $theme_uri . '/assets/images/favicon-16.png' ); ?>">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url( $theme_uri . '/assets/images/apple-touch-icon.png' ); ?>">
<?php
    $schema = [
        '@context'           => 'https://schema.org',
        '@type'              => ['LocalBusiness', 'ProfessionalService'],
        '@id'                => 'https://apexcoatingstn.com/#organization',
        'name'               => 'Apex Coatings & Engraving',
        'alternateName'      => 'Apex Coatings and Engraving',
        'description'        => 'Premium Cerakote finishing, custom engraving, and laser engraving services in Nashville, Tennessee.',
        'telephone'          => '+16158621660',
        'email'              => 'orders@apexcoatingstn.com',
        'url'                => $site_url,
        'logo'               => $theme_uri . '/assets/images/apex-logo-hero.webp',
        'image'              => $theme_uri . '/assets/images/apex-logo-hero.webp',
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
            'postalCode'      => '37201',
            'addressCountry'  => 'US',
        ],
        'sameAs' => [
            'https://www.tiktok.com/@apexcoatingsandengraving',
            'https://www.facebook.com/apexcoatingsandengraving',
        ],
    ];
    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";

    if ( is_front_page() ) {
        $website_schema = [
            '@context'        => 'https://schema.org',
            '@type'           => 'WebSite',
            'name'            => 'Apex Coatings & Engraving',
            'url'             => $site_url,
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => [ '@type' => 'EntryPoint', 'urlTemplate' => $site_url . '/?s={search_term_string}' ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
        echo '<script type="application/ld+json">' . wp_json_encode( $website_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
    }

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
        if ( has_post_thumbnail( $post ) ) $product_schema['image'] = get_the_post_thumbnail_url( $post, 'large' );
        if ( $price ) {
            $product_schema['offers'] = [
                '@type'         => 'Offer',
                'price'         => floatval( $price ),
                'priceCurrency' => 'USD',
                'availability'  => ( $in_stock === '1' ) ? 'https://schema.org/InStock' : 'https://schema.org/PreOrder',
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
   CUSTOM POST TYPE — PRODUCTS
   ============================================================ */
function apex_register_products_cpt() {
    register_post_type( 'apex_product', [
        'labels'        => [
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
        ],
        'public'        => true,
        'has_archive'   => true,
        'rewrite'       => [ 'slug' => 'products' ],
        'supports'      => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
        'menu_icon'     => 'dashicons-tag',
        'show_in_rest'  => true,
    ] );
}
add_action( 'init', 'apex_register_products_cpt' );

/* ============================================================
   FIX: Force page-products template for /products/ URL
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
            'name'          => 'Product Categories',
            'singular_name' => 'Product Category',
            'menu_name'     => 'Categories',
        ],
        'hierarchical' => true,
        'show_ui'      => true,
        'rewrite'      => [ 'slug' => 'product-category' ],
        'show_in_rest' => true,
    ] );
}
add_action( 'init', 'apex_register_product_taxonomy' );

/* ============================================================
   PRODUCT META BOXES
   ============================================================ */
function apex_add_product_meta_boxes() {
    add_meta_box( 'apex_product_details', 'Product Details', 'apex_product_details_callback', 'apex_product', 'side', 'high' );
}
add_action( 'add_meta_boxes', 'apex_add_product_meta_boxes' );

function apex_product_details_callback( $post ) {
    wp_nonce_field( 'apex_product_details_nonce', 'apex_product_details_nonce' );
    $price    = get_post_meta( $post->ID, '_apex_price', true );
    $sku      = get_post_meta( $post->ID, '_apex_sku', true );
    $in_stock = get_post_meta( $post->ID, '_apex_in_stock', true );
    $material = get_post_meta( $post->ID, '_apex_material', true );
    $link_url = get_post_meta( $post->ID, '_apex_link_url', true );
    ?>
    <p>
        <label for="apex_price"><strong>Starting Price ($)</strong></label><br>
        <input type="number" id="apex_price" name="apex_price" value="<?php echo esc_attr($price); ?>" step="0.01" min="0" style="width:100%;margin-top:4px;">
    </p>
    <p>
        <label for="apex_sku"><strong>SKU / Item Code</strong></label><br>
        <input type="text" id="apex_sku" name="apex_sku" value="<?php echo esc_attr($sku); ?>" style="width:100%;margin-top:4px;">
    </p>
    <p>
        <label for="apex_material"><strong>Material</strong></label><br>
        <input type="text" id="apex_material" name="apex_material" value="<?php echo esc_attr($material); ?>" placeholder="e.g. Steel, Aluminum" style="width:100%;margin-top:4px;">
    </p>
    <p>
        <label for="apex_link_url"><strong>Custom Link URL</strong></label><br>
        <input type="text" id="apex_link_url" name="apex_link_url" value="<?php echo esc_attr($link_url); ?>" placeholder="e.g. /pmags" style="width:100%;margin-top:4px;">
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

    if ( isset($_POST['apex_price']) )    update_post_meta( $post_id, '_apex_price',    sanitize_text_field( wp_unslash( $_POST['apex_price'] ) ) );
    if ( isset($_POST['apex_sku']) )      update_post_meta( $post_id, '_apex_sku',      sanitize_text_field( wp_unslash( $_POST['apex_sku'] ) ) );
    if ( isset($_POST['apex_material']) ) update_post_meta( $post_id, '_apex_material', sanitize_text_field( wp_unslash( $_POST['apex_material'] ) ) );
    if ( isset($_POST['apex_link_url']) ) update_post_meta( $post_id, '_apex_link_url', sanitize_text_field( wp_unslash( $_POST['apex_link_url'] ) ) );
    update_post_meta( $post_id, '_apex_in_stock', isset($_POST['apex_in_stock']) ? '1' : '0' );
}
add_action( 'save_post_apex_product', 'apex_save_product_meta' );

/* ============================================================
   REGISTER ORDER CPT
   ============================================================ */
function apex_register_order_cpt() {
    register_post_type( 'apex_order', [
        'labels'          => [ 'name' => 'Orders', 'singular_name' => 'Order', 'menu_name' => 'Orders' ],
        'public'          => false,
        'show_ui'         => true,
        'supports'        => [ 'title', 'custom-fields' ],
        'menu_icon'       => 'dashicons-clipboard',
        'capability_type' => 'post',
    ] );
}
add_action( 'init', 'apex_register_order_cpt' );

/* ============================================================
   AJAX — SUBMIT ORDER
   ============================================================ */
function apex_submit_order() {
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ?? '' ) ), 'apex_order_nonce' ) ) {
        wp_send_json_error( [ 'message' => 'Security check failed.' ] );
    }
    $required = [ 'first_name', 'last_name', 'email', 'phone', 'address', 'city', 'state', 'zip' ];
    foreach ( $required as $field ) {
        if ( empty($_POST[$field]) ) wp_send_json_error( [ 'message' => 'Please fill in all required fields.' ] );
    }
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
    $cart_json  = wp_unslash( $_POST['cart_items'] ?? '[]' );
    $cart_items = json_decode( $cart_json, true );
    if ( ! is_array($cart_items) ) $cart_items = [];
    $cart_items = array_map( function( $item ) {
        return [ 'name' => sanitize_text_field( $item['name'] ?? '' ), 'price' => floatval( $item['price'] ?? 0 ), 'quantity' => intval( $item['quantity'] ?? 1 ) ];
    }, $cart_items );

    $order_ref  = 'APEX-' . strtoupper( substr(md5(uniqid()), 0, 8) );
    $order_date = current_time( 'F j, Y g:i A' );
    $total = 0;
    foreach ( $cart_items as $item ) $total += floatval($item['price']) * intval($item['quantity']);

    $site_name   = get_bloginfo('name') ?: 'Apex Coatings & Engraving';
    $admin_email = 'orders@apexcoatingstn.com';

    ob_start();
    ?><!DOCTYPE html><html><head><meta charset="UTF-8"><style>body{font-family:Arial,sans-serif;color:#333;background:#f5f5f5;margin:0;padding:20px}.wrap{max-width:640px;margin:0 auto;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.12)}.header{background:#1A1A1A;color:#fff;padding:30px 36px}.header h1{margin:0;font-size:22px;letter-spacing:2px;text-transform:uppercase}.header .ref{color:#F5831F;font-size:14px;margin-top:6px}.body{padding:32px 36px}h2{font-size:14px;text-transform:uppercase;letter-spacing:1px;color:#1A1A1A;border-bottom:2px solid #F5831F;padding-bottom:8px;margin:28px 0 16px}h2:first-child{margin-top:0}.info-table{width:100%;border-collapse:collapse;margin-bottom:8px}.info-table td{padding:6px 10px 6px 0;font-size:14px;vertical-align:top}.info-table td.lbl{font-size:11px;text-transform:uppercase;letter-spacing:1px;color:#999;white-space:nowrap;padding-right:14px;width:1%}table{width:100%;border-collapse:collapse}th{background:#1A1A1A;color:#fff;padding:10px 14px;font-size:11px;text-transform:uppercase;letter-spacing:1px;text-align:left}td{padding:12px 14px;border-bottom:1px solid #eee;font-size:14px}tr:last-child td{border-bottom:none}.total-row td{font-weight:800;font-size:16px;color:#1A1A1A;border-top:2px solid #1A1A1A}.total-row td.amount{color:#F5831F}.notes-box{background:#f9f9f9;border-left:3px solid #F5831F;padding:14px 18px;font-size:14px;color:#555}.footer{background:#f0f0f0;padding:20px 36px;font-size:12px;color:#999;text-align:center}</style></head><body>
<div class="wrap"><div class="header"><h1>&#9679; New Order Received</h1><div class="ref">Order Reference: <?php echo esc_html($order_ref); ?> | <?php echo esc_html($order_date); ?></div></div>
<div class="body"><h2>Customer Information</h2><table class="info-table"><tr><td class="lbl">Name</td><td><strong><?php echo esc_html("$first_name $last_name"); ?></strong></td></tr><tr><td class="lbl">Email</td><td><strong><?php echo esc_html($email); ?></strong></td></tr><tr><td class="lbl">Phone</td><td><strong><?php echo esc_html($phone); ?></strong></td></tr><?php if($company):?><tr><td class="lbl">Company</td><td><strong><?php echo esc_html($company);?></strong></td></tr><?php endif;?></table>
<h2>Shipping Address</h2><table class="info-table"><tr><td class="lbl">Street</td><td><strong><?php echo esc_html($address);?></strong></td></tr><tr><td class="lbl">City</td><td><strong><?php echo esc_html($city);?></strong></td></tr><tr><td class="lbl">State</td><td><strong><?php echo esc_html($state);?></strong></td></tr><tr><td class="lbl">ZIP</td><td><strong><?php echo esc_html($zip);?></strong></td></tr><tr><td class="lbl">Country</td><td><strong><?php echo esc_html($country);?></strong></td></tr></table>
<h2>Order Items</h2><table><thead><tr><th>Item</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr></thead><tbody><?php foreach($cart_items as $item):?><tr><td><?php echo esc_html($item['name']);?></td><td><?php echo intval($item['quantity']);?></td><td>$<?php echo number_format(floatval($item['price']),2);?></td><td>$<?php echo number_format(floatval($item['price'])*intval($item['quantity']),2);?></td></tr><?php endforeach;?><tr class="total-row"><td colspan="3"><strong>ESTIMATED TOTAL</strong></td><td class="amount"><strong>$<?php echo number_format($total,2);?></strong></td></tr></tbody></table>
<p style="font-size:12px;color:#999;margin-top:8px;">* Final pricing confirmed before work begins.</p>
<?php if($notes):?><h2>Notes</h2><div class="notes-box"><?php echo nl2br(esc_html($notes));?></div><?php endif;?></div>
<div class="footer">This order was submitted via the <?php echo esc_html($site_name);?> website.<br>Reply to contact customer at <?php echo esc_html($email);?></div></div></body></html>
    <?php
    $email_body = ob_get_clean();
    $headers = [ 'Content-Type: text/html; charset=UTF-8', "Reply-To: {$first_name} {$last_name} <{$email}>" ];
    $subject = "[{$site_name}] New Order #{$order_ref} — {$first_name} {$last_name}";
    $sent = wp_mail( $admin_email, $subject, $email_body, $headers );

    $customer_body = "<p>Hi {$first_name},</p><p>Thank you for your order request! We've received your submission (Reference: <strong>{$order_ref}</strong>) and will review it shortly.</p><p>We'll be in touch within <strong>1-2 business days</strong> to confirm your order details and pricing before any work begins.</p><p>— The {$site_name} Team</p>";
    wp_mail( $email, "Order Request Received — {$order_ref}", $customer_body, [ 'Content-Type: text/html; charset=UTF-8' ] );

    if ( $sent ) {
        wp_insert_post([ 'post_type' => 'apex_order', 'post_title' => $order_ref . ' — ' . $first_name . ' ' . $last_name, 'post_status' => 'private', 'meta_input' => [ '_order_ref' => $order_ref, '_customer_email' => $email, '_order_data' => $cart_json, '_order_total' => $total, '_order_notes' => $notes ] ]);
        wp_send_json_success([ 'message' => 'Order submitted successfully!', 'order_ref' => $order_ref ]);
    } else {
        wp_send_json_error([ 'message' => 'There was an issue sending your order. Please call us directly.' ]);
    }
}
add_action( 'wp_ajax_apex_submit_order',        'apex_submit_order' );
add_action( 'wp_ajax_nopriv_apex_submit_order', 'apex_submit_order' );

/* ============================================================
   AJAX — GET PRODUCTS (transient-cached)
   ============================================================ */
function apex_get_products() {
    $cache_key = 'apex_products_json';
    $cached    = get_transient( $cache_key );
    if ( false !== $cached ) { wp_send_json_success( $cached ); return; }

    $products = get_posts([ 'post_type' => 'apex_product', 'posts_per_page' => -1, 'post_status' => 'publish', 'no_found_rows' => true, 'update_post_term_cache' => false ]);
    $data = [];
    foreach ( $products as $p ) {
        $data[] = [ 'id' => $p->ID, 'name' => $p->post_title, 'desc' => get_the_excerpt($p), 'price' => get_post_meta($p->ID, '_apex_price', true), 'sku' => get_post_meta($p->ID, '_apex_sku', true), 'image' => get_the_post_thumbnail_url($p->ID, 'apex-card') ?: get_the_post_thumbnail_url($p->ID, 'medium'), 'in_stock' => get_post_meta($p->ID, '_apex_in_stock', true) ];
    }
    set_transient( $cache_key, $data, 12 * HOUR_IN_SECONDS );
    wp_send_json_success( $data );
}
add_action( 'wp_ajax_apex_get_products',        'apex_get_products' );
add_action( 'wp_ajax_nopriv_apex_get_products', 'apex_get_products' );

add_action( 'save_post_apex_product', function() { delete_transient( 'apex_products_json' ); } );
add_action( 'before_delete_post', function( $id ) {
    if ( get_post_type( $id ) === 'apex_product' ) delete_transient( 'apex_products_json' );
} );

/* ============================================================
   CONTACT FORM AJAX
   ============================================================ */
function apex_submit_contact() {
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ?? '' ) ), 'apex_order_nonce' ) ) {
        wp_send_json_error( [ 'message' => 'Security check failed.' ] );
    }
    $ip       = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0' ) );
    $rate_key = 'apex_contact_rate_' . md5( $ip );
    $count    = (int) get_transient( $rate_key );
    if ( $count >= 5 ) wp_send_json_error( [ 'message' => 'Too many submissions. Please try again later or call us directly.' ] );
    set_transient( $rate_key, $count + 1, HOUR_IN_SECONDS );

    $name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
    $email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
    $phone   = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
    $subject = sanitize_text_field( wp_unslash( $_POST['subject'] ?? 'General Inquiry' ) );
    $message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );

    if ( empty($name) || empty($email) || empty($message) ) wp_send_json_error([ 'message' => 'Please fill in all required fields.' ]);

    $admin_email = 'orders@apexcoatingstn.com';
    $site_name   = get_bloginfo('name') ?: 'Apex Coatings & Engraving';
    $body = "<h2>New Contact Message</h2><p><strong>From:</strong> {$name} ({$email})</p><p><strong>Phone:</strong> {$phone}</p><p><strong>Subject:</strong> {$subject}</p><hr><p>" . nl2br(esc_html($message)) . "</p>";
    $headers = [ 'Content-Type: text/html; charset=UTF-8', "Reply-To: {$name} <{$email}>" ];
    $sent = wp_mail( $admin_email, "[{$site_name}] {$subject}", $body, $headers );

    if ( $sent ) {
        wp_send_json_success([ 'message' => "Message sent! We'll get back to you within 1-2 business days." ]);
    } else {
        wp_send_json_error([ 'message' => 'Could not send message. Please call us directly.' ]);
    }
}
add_action( 'wp_ajax_apex_submit_contact',        'apex_submit_contact' );
add_action( 'wp_ajax_nopriv_apex_submit_contact', 'apex_submit_contact' );

/* ============================================================
   HELPERS
   ============================================================ */
function apex_get_products_query( $limit = -1, $cat = '' ) {
    $args = [ 'post_type' => 'apex_product', 'posts_per_page' => $limit, 'post_status' => 'publish', 'orderby' => 'menu_order', 'order' => 'ASC', 'no_found_rows' => true, 'update_post_term_cache' => false, 'update_post_meta_cache' => true ];
    if ( $cat ) {
        $args['no_found_rows']          = false;
        $args['update_post_term_cache'] = true;
        $args['tax_query'] = [[ 'taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => $cat ]];
    }
    return new WP_Query( $args );
}

function apex_price( $price ) {
    return '$' . number_format( floatval($price), 0, '.', ',' );
}

function apex_opt( $key, $default = '' ) {
    $val = get_option( 'apex_' . $key, null );
    return ( $val !== null && $val !== '' ) ? $val : $default;
}

add_filter( 'excerpt_length', fn() => 20 );
add_filter( 'excerpt_more',   fn() => '...' );

/* ============================================================
   XML SITEMAP PING
   ============================================================ */
function apex_ping_sitemap( $post_id ) {
    if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) return;
    if ( get_post_status( $post_id ) !== 'publish' ) return;
    wp_remote_get( 'https://www.bing.com/ping?sitemap=' . rawurlencode( home_url( '/sitemap_index.xml' ) ), [ 'blocking' => false, 'timeout' => 3 ] );
}
add_action( 'save_post', 'apex_ping_sitemap' );

/* ============================================================
   FLUSH REWRITE RULES ON ACTIVATION
   ============================================================ */
function apex_flush_rewrite() {
    apex_register_products_cpt();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'apex_flush_rewrite' );

/* ============================================================
   AUTO-CREATE PAGES (heroes, products, cart, checkout, etc.)
   ============================================================ */
function apex_create_default_pages() {
    if ( get_option( 'apex_pages_created_v2' ) ) return;
    $pages = [
        [ 'title' => 'Heroes Discount', 'slug' => 'heroes',   'template' => 'page-heroes.php' ],
        [ 'title' => 'Custom PMAGs',    'slug' => 'pmags',    'template' => 'page-pmags.php' ],
        [ 'title' => '1911 Grips',      'slug' => '1911-grips','template' => 'page-1911-grips.php' ],
        [ 'title' => 'Cart',            'slug' => 'cart',     'template' => 'page-cart.php' ],
        [ 'title' => 'Checkout',        'slug' => 'checkout', 'template' => 'page-checkout.php' ],
        [ 'title' => 'Contact',         'slug' => 'contact',  'template' => 'page-contact.php' ],
        [ 'title' => 'Gallery',         'slug' => 'gallery',  'template' => 'page-gallery.php' ],
        [ 'title' => 'Services',        'slug' => 'services', 'template' => 'page-services.php' ],
    ];
    foreach ( $pages as $p ) {
        if ( get_page_by_path( $p['slug'] ) ) continue;
        $page_id = wp_insert_post([ 'post_title' => $p['title'], 'post_name' => $p['slug'], 'post_content' => '', 'post_status' => 'publish', 'post_type' => 'page', 'comment_status' => 'closed' ]);
        if ( $page_id && ! is_wp_error( $page_id ) ) {
            update_post_meta( $page_id, '_wp_page_template', $p['template'] );
        }
    }
    update_option( 'apex_pages_created_v2', 1 );
}
add_action( 'after_switch_theme', 'apex_create_default_pages' );
add_action( 'init', function() {
    if ( ! get_option( 'apex_pages_created_v2' ) ) apex_create_default_pages();
} );
