<?php
/**
 * Apex Site Content — Custom admin panel
 * Lets the site owner edit all text and images without touching code.
 */
if ( ! defined( 'ABSPATH' ) ) return;

/* ============================================================
   HELPER FUNCTIONS
   ============================================================ */

/** Get an apex option with an optional default value */
if ( ! function_exists( 'apex_opt' ) ) {
    function apex_opt( $key, $default = '' ) {
        return get_option( 'apex_' . $key, $default );
    }
}

/** Render a labelled text input */
function apex_field( $label, $key, $default = '', $hint = '' ) {
    $val = esc_attr( apex_opt( $key, $default ) );
    echo '<div class="apx-field">';
    echo '<label>' . esc_html( $label ) . ( $hint ? ' <span class="apx-hint">' . esc_html( $hint ) . '</span>' : '' ) . '</label>';
    echo '<input type="text" name="apex_' . esc_attr( $key ) . '" value="' . $val . '">';
    echo '</div>';
}

/** Render a labelled textarea */
function apex_textarea( $label, $key, $default = '', $hint = '' ) {
    echo '<div class="apx-field">';
    echo '<label>' . esc_html( $label ) . ( $hint ? ' <span class="apx-hint">' . esc_html( $hint ) . '</span>' : '' ) . '</label>';
    echo '<textarea name="apex_' . esc_attr( $key ) . '" rows="3">' . esc_textarea( apex_opt( $key, $default ) ) . '</textarea>';
    echo '</div>';
}

/* ============================================================
   ADMIN MENU
   ============================================================ */
add_action( 'admin_menu', function () {
    add_menu_page(
        'Site Content',
        'Site Content',
        'manage_options',
        'apex-content',
        'apex_render_settings_page',
        'dashicons-edit-page',
        20
    );
} );

/* ============================================================
   LOCK DOWN STYLE / CODE EDITING
   Removes the Theme File Editor, Plugin File Editor, and
   Customizer so nobody can accidentally break the site's design.
   ============================================================ */
add_action( 'admin_menu', function () {
    remove_submenu_page( 'themes.php',  'theme-editor.php'  ); // Theme File Editor
    remove_submenu_page( 'plugins.php', 'plugin-editor.php' ); // Plugin File Editor
    remove_submenu_page( 'themes.php',  'customize.php'     ); // Customizer (not used)
}, 999 );

// Block direct URL access even if someone types the URL manually
add_action( 'current_screen', function ( $screen ) {
    if ( in_array( $screen->id, [ 'theme-editor', 'plugin-editor' ], true ) ) {
        wp_die(
            '<h1 style="font-family:sans-serif;color:#1d1d1d;">Style editing is disabled</h1>' .
            '<p style="font-family:sans-serif;color:#444;">The site\'s design is protected. To update text and images, use ' .
            '<a href="' . esc_url( admin_url( 'admin.php?page=apex-content' ) ) . '">Site Content</a>.</p>',
            'Editing Disabled',
            [ 'back_link' => true ]
        );
    }
} );

/* ============================================================
   ENQUEUE ASSETS (media uploader + admin JS)
   ============================================================ */
add_action( 'admin_enqueue_scripts', function ( $hook ) {
    if ( $hook !== 'toplevel_page_apex-content' ) return;
    wp_enqueue_media();
    wp_enqueue_script( 'apex-admin-settings', get_template_directory_uri() . '/assets/js/admin-settings.js', [ 'jquery' ], null, true );
} );

/* ============================================================
   SAVE HANDLER
   ============================================================ */
add_action( 'admin_post_apex_save_content', function () {
    if ( ! current_user_can( 'manage_options' ) ) wp_die( 'Unauthorized' );
    check_admin_referer( 'apex_content_save' );

    $text_fields = [
        // Homepage
        'hero_title', 'hero_tagline',
        'products_eyebrow', 'products_heading', 'products_subtitle',
        'process_eyebrow', 'process_heading',
        'gallery_eyebrow', 'gallery_heading', 'gallery_subtitle',
        'why_eyebrow', 'why_heading',
        'reviews_eyebrow', 'reviews_heading',
        'stat_1_num', 'stat_1_label',
        'stat_2_num', 'stat_2_label',
        'stat_3_num', 'stat_3_label',
        'pmag_title', 'pmag_body_1', 'pmag_body_2',
        'pmag_bullet_1', 'pmag_bullet_2', 'pmag_bullet_3', 'pmag_bullet_4',
        'step_1_title', 'step_1_desc',
        'step_2_title', 'step_2_desc',
        'step_3_title', 'step_3_desc',
        'step_4_title', 'step_4_desc',
        'cta_title', 'cta_body',
        // Why section
        'why_1_title', 'why_1_desc',
        'why_2_title', 'why_2_desc',
        'why_3_title', 'why_3_desc',
        'why_4_title', 'why_4_desc',
        // Reviews
        'review_1_text', 'review_1_name', 'review_1_role',
        'review_2_text', 'review_2_name', 'review_2_role',
        'review_3_text', 'review_3_name', 'review_3_role',
        // Services page — cards
        'svc_1_title', 'svc_1_desc', 'svc_1_bullets',
        'svc_2_title', 'svc_2_desc', 'svc_2_bullets',
        'svc_3_title', 'svc_3_desc', 'svc_3_bullets',
        'svc_4_title', 'svc_4_desc', 'svc_4_bullets',
        'svc_5_title', 'svc_5_desc', 'svc_5_bullets',
        'svc_6_title', 'svc_6_desc', 'svc_6_bullets',
        // Services page — hero & process
        'svc_page_eyebrow', 'svc_page_h1', 'svc_page_desc',
        'svc_process_eyebrow', 'svc_process_heading', 'svc_process_desc',
        'svc_step_1_title', 'svc_step_1_desc',
        'svc_step_2_title', 'svc_step_2_desc',
        'svc_step_3_title', 'svc_step_3_desc',
        'svc_step_4_title', 'svc_step_4_desc',
        // Footer & Contact
        'footer_tagline', 'footer_services',
        'contact_email', 'contact_phone',
        // PMAGs page
        'pmags_page_eyebrow', 'pmags_page_h1', 'pmags_page_desc',
        'pmags_page_badge_1', 'pmags_page_badge_2', 'pmags_page_badge_3', 'pmags_page_badge_4',
        'pmags_info_1_title', 'pmags_info_1_desc',
        'pmags_info_2_title', 'pmags_info_2_desc',
        'pmags_info_3_title', 'pmags_info_3_desc',
        'pmags_info_4_title', 'pmags_info_4_desc',
        'pmags_grid_heading', 'pmags_grid_subtext',
        'pmags_card_price', 'pmags_card_subdesc',
        'pmags_cta_heading', 'pmags_cta_desc',
        // 1911 Grips page
        'grips_page_eyebrow', 'grips_page_h1', 'grips_page_desc',
        'grips_page_badge_1', 'grips_page_badge_2', 'grips_page_badge_3', 'grips_page_badge_4',
        'grips_info_1_title', 'grips_info_1_desc',
        'grips_info_2_title', 'grips_info_2_desc',
        'grips_info_3_title', 'grips_info_3_desc',
        'grips_info_4_title', 'grips_info_4_desc',
        'grips_grid_heading', 'grips_grid_subtext',
        'grips_1_label', 'grips_1_desc',
        'grips_2_label', 'grips_2_desc',
        'grips_3_label', 'grips_3_desc',
        'grips_4_label', 'grips_4_desc',
        // Heroes page
        'heroes_eyebrow', 'heroes_desc', 'heroes_discount',
        'heroes_qualify_eyebrow', 'heroes_qualify_h2', 'heroes_qualify_subtitle',
        'heroes_group_1_title', 'heroes_group_1_desc',
        'heroes_group_2_title', 'heroes_group_2_desc',
        'heroes_group_3_title', 'heroes_group_3_desc',
        'heroes_group_4_title', 'heroes_group_4_desc',
        'heroes_group_5_title', 'heroes_group_5_desc',
        'heroes_group_6_title', 'heroes_group_6_desc',
        'heroes_howto_eyebrow', 'heroes_howto_h2',
        'heroes_step_1_title', 'heroes_step_1_desc',
        'heroes_step_2_title', 'heroes_step_2_desc',
        'heroes_step_3_title', 'heroes_step_3_desc',
        'heroes_step_4_title', 'heroes_step_4_desc',
        'heroes_gallery_eyebrow', 'heroes_gallery_h2', 'heroes_gallery_subtitle',
        // Contact page
        'contact_page_eyebrow', 'contact_page_h1', 'contact_page_desc',
        'contact_left_h2', 'contact_left_desc', 'contact_hours', 'contact_location',
        'contact_cta_h2', 'contact_cta_desc',
        // Images
        'nav_logo_id', 'hero_logo_id', 'footer_logo_id', 'slideshow_ids',
    ];

    foreach ( $text_fields as $key ) {
        if ( isset( $_POST[ 'apex_' . $key ] ) ) {
            update_option( 'apex_' . $key, sanitize_textarea_field( wp_unslash( $_POST[ 'apex_' . $key ] ) ) );
        }
    }

    $tab = sanitize_key( $_POST['_tab'] ?? 'homepage' );
    wp_redirect( admin_url( 'admin.php?page=apex-content&tab=' . $tab . '&saved=1' ) );
    exit;
} );

/* ============================================================
   SETTINGS PAGE RENDER
   ============================================================ */
function apex_render_settings_page() {
    $tab   = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'homepage';
    $saved = isset( $_GET['saved'] );
    $url   = admin_url( 'admin.php?page=apex-content' );
    ?>
    <style>
    .apx-wrap { max-width: 900px; }
    .apx-tabs { display:flex; gap:0; flex-wrap:wrap; margin:16px 0 24px; border-bottom:2px solid #ddd; }
    .apx-tabs a { padding:10px 18px; text-decoration:none; font-weight:600; font-size:13px; color:#555; border-bottom:3px solid transparent; margin-bottom:-2px; transition:color .2s; }
    .apx-tabs a:hover { color:#F5831F; }
    .apx-tabs a.active { color:#F5831F; border-bottom-color:#F5831F; }
    .apx-box { background:#fff; border:1px solid #e0e0e0; border-radius:6px; padding:24px 28px; margin-bottom:20px; }
    .apx-box h3 { margin:0 0 18px; font-size:13px; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:#444; border-bottom:1px solid #f0f0f0; padding-bottom:10px; }
    .apx-field { margin-bottom:14px; }
    .apx-field label { display:block; font-weight:600; font-size:13px; color:#333; margin-bottom:5px; }
    .apx-hint { font-weight:400; color:#888; font-size:12px; }
    .apx-field input[type=text], .apx-field textarea { width:100%; max-width:680px; padding:8px 12px; border:1px solid #ccc; border-radius:4px; font-size:14px; box-sizing:border-box; }
    .apx-field textarea { min-height:72px; resize:vertical; }
    .apx-2col { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
    .apx-3col { display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px; }
    .apx-save { background:#F5831F !important; border-color:#d96b0a !important; color:#fff !important; font-size:15px !important; padding:9px 32px !important; height:auto !important; margin-top:8px; }
    /* Image fields */
    .apx-img-row { display:flex; align-items:center; gap:14px; margin-top:8px; flex-wrap:wrap; }
    .apx-img-preview { width:100px; height:68px; object-fit:cover; border-radius:4px; border:2px solid #ddd; display:block; }
    .apx-img-placeholder { width:100px; height:68px; background:#f5f5f5; border:2px dashed #ccc; border-radius:4px; display:flex; align-items:center; justify-content:center; color:#aaa; font-size:11px; }
    .apx-btn-pick { padding:7px 16px; background:#2271b1; color:#fff; border:none; border-radius:4px; cursor:pointer; font-size:13px; font-weight:600; }
    .apx-btn-clear { padding:7px 14px; background:#d63638; color:#fff; border:none; border-radius:4px; cursor:pointer; font-size:13px; }
    .apx-gallery { display:flex; flex-wrap:wrap; gap:10px; margin-top:10px; }
    .apx-gallery-thumb { position:relative; width:100px; height:70px; border-radius:4px; overflow:hidden; border:2px solid #ddd; cursor:grab; }
    .apx-gallery-thumb img { width:100%; height:100%; object-fit:cover; display:block; }
    .apx-gallery-thumb .apx-remove { position:absolute; top:3px; right:3px; background:rgba(180,0,0,.85); color:#fff; border:none; border-radius:50%; width:20px; height:20px; font-size:13px; line-height:20px; text-align:center; padding:0; cursor:pointer; }
    .apx-btn-add { padding:8px 18px; background:#1d8348; color:#fff; border:none; border-radius:4px; cursor:pointer; font-size:13px; font-weight:600; margin-top:8px; }
    </style>

    <div class="wrap apx-wrap">
        <h1 style="display:flex;align-items:center;gap:10px;margin-bottom:4px;">
            <span style="font-size:26px;background:linear-gradient(135deg,#FFAD00,#F5831F);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">&#11042;</span>
            Site Content Editor
        </h1>
        <p style="color:#666;margin-top:0;">Edit your website's text and images. Changes go live immediately after saving.</p>

        <?php if ( $saved ): ?>
        <div class="notice notice-success is-dismissible" style="margin:0 0 16px;"><p>✅ <strong>Saved!</strong> Your changes are live on the site.</p></div>
        <?php endif; ?>

        <nav class="apx-tabs">
            <a href="<?php echo $url; ?>&tab=homepage" class="<?php echo $tab === 'homepage' ? 'active' : ''; ?>">🏠 Homepage</a>
            <a href="<?php echo $url; ?>&tab=services" class="<?php echo $tab === 'services' ? 'active' : ''; ?>">📋 Services</a>
            <a href="<?php echo $url; ?>&tab=pmags"    class="<?php echo $tab === 'pmags'    ? 'active' : ''; ?>">🔫 PMAGs</a>
            <a href="<?php echo $url; ?>&tab=grips"    class="<?php echo $tab === 'grips'    ? 'active' : ''; ?>">🔧 1911 Grips</a>
            <a href="<?php echo $url; ?>&tab=heroes"   class="<?php echo $tab === 'heroes'   ? 'active' : ''; ?>">🎖️ Heroes</a>
            <a href="<?php echo $url; ?>&tab=contact"  class="<?php echo $tab === 'contact'  ? 'active' : ''; ?>">✉️ Contact</a>
            <a href="<?php echo $url; ?>&tab=reviews"  class="<?php echo $tab === 'reviews'  ? 'active' : ''; ?>">⭐ Reviews</a>
            <a href="<?php echo $url; ?>&tab=footer"   class="<?php echo $tab === 'footer'   ? 'active' : ''; ?>">🌍 Footer</a>
            <a href="<?php echo $url; ?>&tab=images"   class="<?php echo $tab === 'images'   ? 'active' : ''; ?>">🖼 Images</a>
        </nav>

        <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
            <?php wp_nonce_field( 'apex_content_save' ); ?>
            <input type="hidden" name="action" value="apex_save_content">
            <input type="hidden" name="_tab"   value="<?php echo esc_attr( $tab ); ?>">

            <?php if ( $tab === 'homepage' ): ?>

            <div class="apx-box">
                <h3>Hero Section</h3>
                <?php apex_field(   'Main Heading',           'hero_title',   'Custom Coatings & Engraving', '(large title on homepage)' ); ?>
                <?php apex_textarea( 'Tagline / Description', 'hero_tagline',
                    'Precision, laser engraving, Cerakote application, and guaranteed quality. Craftsmanship you can trust & products that last.' ); ?>
            </div>

            <div class="apx-box">
                <h3>Stats Bar</h3>
                <div class="apx-3col">
                    <div>
                        <?php apex_field( 'Stat 1 Number', 'stat_1_num', '15+', '(e.g. 15+)' ); ?>
                        <?php apex_field( 'Stat 1 Label',  'stat_1_label', 'Years Experience' ); ?>
                    </div>
                    <div>
                        <?php apex_field( 'Stat 2 Number', 'stat_2_num', '2K+' ); ?>
                        <?php apex_field( 'Stat 2 Label',  'stat_2_label', 'Jobs Completed' ); ?>
                    </div>
                    <div>
                        <?php apex_field( 'Stat 3 Number', 'stat_3_num', '100%' ); ?>
                        <?php apex_field( 'Stat 3 Label',  'stat_3_label', 'Satisfaction' ); ?>
                    </div>
                </div>
            </div>

            <div class="apx-box">
                <h3>Section Headings</h3>
                <p style="font-size:12px;color:#888;margin:0 0 14px;">These are the titles of each major section on the homepage.</p>
                <div class="apx-2col">
                    <?php apex_field( 'Products Section Eyebrow', 'products_eyebrow', 'What We Offer' ); ?>
                    <?php apex_field( 'Products Section Heading',  'products_heading', 'Products & Services' ); ?>
                </div>
                <?php apex_field( 'Products Subtitle', 'products_subtitle', 'Durable Cerakote finishes and precision laser engraving for gear that works as hard as you do.' ); ?>
                <div class="apx-2col" style="margin-top:10px;">
                    <?php apex_field( 'Process Eyebrow', 'process_eyebrow', 'How It Works' ); ?>
                    <?php apex_field( 'Process Heading',  'process_heading', 'Simple Process' ); ?>
                    <?php apex_field( 'Gallery Eyebrow',  'gallery_eyebrow', 'Our Work' ); ?>
                    <?php apex_field( 'Gallery Heading',  'gallery_heading', 'Recent Projects' ); ?>
                    <?php apex_field( 'Why Eyebrow',      'why_eyebrow',     'Why Apex' ); ?>
                    <?php apex_field( 'Why Heading',      'why_heading',     'Built Different' ); ?>
                    <?php apex_field( 'Reviews Eyebrow',  'reviews_eyebrow', 'What Our Clients Say' ); ?>
                    <?php apex_field( 'Reviews Heading',  'reviews_heading', 'Customer Reviews' ); ?>
                </div>
                <?php apex_field( 'Gallery Subtitle', 'gallery_subtitle', 'A sample of the craftsmanship we bring to every job.' ); ?>
            </div>

            <div class="apx-box">
                <h3>PMAG Section (Homepage)</h3>
                <?php apex_field(   'Section Title',   'pmag_title',    'Your mags. Your design. Built to last.' ); ?>
                <?php apex_textarea( 'Body Paragraph 1', 'pmag_body_1', 'We laser engrave directly into genuine Magpul PMAGs — no stickers, no paint, no vinyl, and nothing that will peel or fade. Every magazine is individually engraved with your choice of artwork, whether you pick from our designs or bring your own idea.' ); ?>
                <?php apex_textarea( 'Body Paragraph 2', 'pmag_body_2', 'Eagles, skulls, patriotic themes, unit logos, or fully custom artwork — all at a flat, upfront price.' ); ?>
                <p style="font-size:12px;color:#888;margin:8px 0 12px;">Bullet points (edit each line):</p>
                <div class="apx-2col">
                    <?php apex_field( 'Bullet 1', 'pmag_bullet_1', 'Genuine Magpul PMAGs only — no off-brand substitutes' ); ?>
                    <?php apex_field( 'Bullet 2', 'pmag_bullet_2', "Laser engraved — permanent, won't wear or fade" ); ?>
                    <?php apex_field( 'Bullet 3', 'pmag_bullet_3', '20+ designs to choose from or bring your own' ); ?>
                    <?php apex_field( 'Bullet 4', 'pmag_bullet_4', '$35 per mag — flat rate, no hidden fees' ); ?>
                </div>
            </div>

            <div class="apx-box">
                <h3>How It Works — Process Steps (Homepage)</h3>
                <div class="apx-2col">
                    <?php
                    $step_defaults = [
                        1 => [ 'Choose Your Service',   'Browse our products and add what you need to your cart. Not sure? Contact us for a custom quote.' ],
                        2 => [ 'Submit Your Order',      'Fill out the order form with your details and any special requirements or custom artwork.' ],
                        3 => [ 'We Review & Confirm',    "We'll review your order, confirm pricing, and reach out within 1-2 business days." ],
                        4 => [ 'Your Project Complete',  "Your order is finished to spec by our skilled craftsmen. We notify you when it's shipped or ready for pickup." ],
                    ];
                    for ( $i = 1; $i <= 4; $i++ ): ?>
                    <div>
                        <p style="font-weight:700;color:#F5831F;margin:0 0 6px;font-size:13px;">Step <?php echo $i; ?></p>
                        <?php apex_field(   'Title',       "step_{$i}_title", $step_defaults[$i][0] ); ?>
                        <?php apex_textarea( 'Description', "step_{$i}_desc",  $step_defaults[$i][1] ); ?>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="apx-box">
                <h3>Call to Action (bottom of homepage)</h3>
                <?php apex_field(   'CTA Headline', 'cta_title', 'Ready to Start Your Project?' ); ?>
                <?php apex_textarea( 'CTA Body',    'cta_body',  "Add items to your cart and submit your order. We'll review it and be in touch within 1-2 business days — no payment required at this time." ); ?>
            </div>

            <?php elseif ( $tab === 'services' ): ?>

            <div class="apx-box">
                <h3>Page Hero</h3>
                <div class="apx-2col">
                    <?php apex_field( 'Eyebrow',  'svc_page_eyebrow', 'What We Do' ); ?>
                    <?php apex_field( 'Heading',  'svc_page_h1',      'Our Services' ); ?>
                </div>
                <?php apex_textarea( 'Description', 'svc_page_desc', 'Where craftsmanship, precision engraving, and Cerakote all come together.' ); ?>
            </div>

            <?php
            $svc_defaults = [
                1 => [ 'Engraving',              'Custom laser engraving on pistol slides, AR mags, and more. Skulls, florals, patriotic themes, or anything you can dream up.',                              "Pistol slides\nAR-15 / AR-10 mags\nRevolvers, shotguns & rifles\nCustom artwork & logos" ],
                2 => [ 'Cerakoting',             'Precision Cerakote application on pistol slides. Single-color finishes start at $100. Multi-color patterns and custom designs are quoted per project.',      "Single color: Starting at \$100\nMulti-color & themes: quoted per job\nC-series, H-series, & Elite series available\nCorrosion & UV resistant" ],
                3 => [ 'Graphics Design',        'Custom artwork designed in-house for your project. We work with your ideas or create original designs from scratch. Design fees start at $75.',               "Original design work\nLogo vectorization\nCustom artwork & logos\nFree with any bulk order" ],
                4 => [ 'Cerakote Application',   'Industry-leading ceramic polymer coating applied to firearms, blades, tactical gear, car parts; just about anything you can think of. Corrosion-resistant, abrasion-resistant, and available in dozens of colors.', "Pistols, rifles & handguns\nKnives & blades\nMulti-color & camo patterns\nCorrosion & UV resistant" ],
                5 => [ 'Metal Polishing',        'Professional metal polishing for firearm components, knives, and precision parts. We remove scratches, oxidation, machining marks, and surface defects to restore clarity, shine, and uniformity.',                   "Ideal for slides, frames, controls, and small components\nSurface correction & refinement\nScratch, oxidation & blemish removal\nMirror, satin, or brushed finish options" ],
                6 => [ 'Custom Gifts & Memorials','Personalized gifts for any occasion — pet memorials, retirement plaques, wedding gifts, and military tributes. Unique, custom pieces that last forever.',   "Pet memorial stones\nRetirement & wedding plaques\nMilitary & first responder tributes\nBulk orders welcome" ],
            ];
            for ( $i = 1; $i <= 6; $i++ ): ?>
            <div class="apx-box">
                <h3>Service Card <?php echo $i; ?></h3>
                <?php apex_field(   'Title',                             "svc_{$i}_title",   $svc_defaults[$i][0] ); ?>
                <?php apex_textarea( 'Description',                      "svc_{$i}_desc",    $svc_defaults[$i][1] ); ?>
                <?php apex_textarea( 'Bullet Points (one per line, max 4)', "svc_{$i}_bullets", $svc_defaults[$i][2],
                    'Each line becomes a checkmark bullet on the card' ); ?>
            </div>
            <?php endfor; ?>

            <div class="apx-box">
                <h3>How It Works — Process Section</h3>
                <div class="apx-2col">
                    <?php apex_field( 'Eyebrow', 'svc_process_eyebrow', 'Simple Process' ); ?>
                    <?php apex_field( 'Heading', 'svc_process_heading', 'How It Works' ); ?>
                </div>
                <?php apex_textarea( 'Description', 'svc_process_desc', "Drop us a line, send in your item, and we'll handle the rest." ); ?>
                <div class="apx-2col" style="margin-top:16px;">
                    <?php
                    $svc_step_defaults = [
                        1 => [ 'Contact Us',       'Reach out via our contact form or phone with your project details.' ],
                        2 => [ 'Ship or Drop Off', 'Send us your item or drop it off locally.' ],
                        3 => [ 'We Get to Work',   'Our team processes your order with precision and care.' ],
                        4 => [ 'Delivered',         'We ship it back or you pick it up — ready to show off.' ],
                    ];
                    for ( $i = 1; $i <= 4; $i++ ): ?>
                    <div>
                        <p style="font-weight:700;color:#F5831F;margin:0 0 6px;font-size:13px;">Step <?php echo $i; ?></p>
                        <?php apex_field(   'Title',       "svc_step_{$i}_title", $svc_step_defaults[$i][0] ); ?>
                        <?php apex_textarea( 'Description', "svc_step_{$i}_desc",  $svc_step_defaults[$i][1] ); ?>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>

            <?php elseif ( $tab === 'pmags' ): ?>

            <div class="apx-box">
                <h3>Page Hero</h3>
                <div class="apx-2col">
                    <?php apex_field( 'Eyebrow', 'pmags_page_eyebrow', 'Genuine Magpul PMAGs' ); ?>
                    <?php apex_field( 'Heading', 'pmags_page_h1',      'Custom PMAG Engraving' ); ?>
                </div>
                <?php apex_textarea( 'Description', 'pmags_page_desc', 'We only use genuine Magpul PMAGs and laser engrave custom designs and art directly into them. Each magazine is individually engraved — no stickers, no paint, no fading. Built to last as long as the mag itself.' ); ?>
                <p style="font-size:12px;color:#888;margin:8px 0 10px;">Trust badges (shown below hero description):</p>
                <div class="apx-2col">
                    <?php apex_field( 'Badge 1', 'pmags_page_badge_1', '✓ Genuine Magpul PMAGs Only' ); ?>
                    <?php apex_field( 'Badge 2', 'pmags_page_badge_2', '✓ Laser Engraved' ); ?>
                    <?php apex_field( 'Badge 3', 'pmags_page_badge_3', '✓ Custom Artwork Available' ); ?>
                    <?php apex_field( 'Badge 4', 'pmags_page_badge_4', '✓ Bulk discounts available' ); ?>
                </div>
            </div>

            <div class="apx-box">
                <h3>Info Bar (4 items)</h3>
                <div class="apx-2col">
                    <?php
                    $pmags_info_defaults = [
                        1 => [ 'Genuine Magpul Only',   'No off-brand substitutes' ],
                        2 => [ 'Turnaround',             '1–3 business days' ],
                        3 => [ 'Pricing',                '$35 includes magazine and engraving on both sides' ],
                        4 => [ 'Custom Designs',         "Don't see what you want? Ask us" ],
                    ];
                    for ( $i = 1; $i <= 4; $i++ ): ?>
                    <div>
                        <p style="font-weight:700;color:#F5831F;margin:0 0 6px;font-size:13px;">Item <?php echo $i; ?></p>
                        <?php apex_field( 'Title', "pmags_info_{$i}_title", $pmags_info_defaults[$i][0] ); ?>
                        <?php apex_field( 'Description', "pmags_info_{$i}_desc", $pmags_info_defaults[$i][1] ); ?>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="apx-box">
                <h3>Design Grid</h3>
                <?php apex_field(   'Section Heading', 'pmags_grid_heading', 'Choose Your Design' ); ?>
                <?php apex_textarea( 'Subtext',        'pmags_grid_subtext', 'Select a design below and add it to your cart. After we clear your shipping address, you will receive an invoice by email to complete your purchase.' ); ?>
            </div>

            <div class="apx-box">
                <h3>Product Card (shown on every design card)</h3>
                <?php apex_field( 'Price Tag',    'pmags_card_price',   'Only $35' ); ?>
                <?php apex_field( 'Sub-description', 'pmags_card_subdesc', 'Genuine Magpul PMAG · Laser Engraved · Custom artwork' ); ?>
            </div>

            <div class="apx-box">
                <h3>Custom Design CTA (bottom of grid)</h3>
                <?php apex_field(   'Heading',     'pmags_cta_heading', "Don't See What You Want?" ); ?>
                <?php apex_textarea( 'Description', 'pmags_cta_desc',   "Have something specific in mind? We engrave custom artwork, logos, text, and anything else you can dream up. Bring your idea — we'll bring it to life on your mag." ); ?>
            </div>

            <?php elseif ( $tab === 'grips' ): ?>

            <div class="apx-box">
                <h3>Page Hero</h3>
                <div class="apx-2col">
                    <?php apex_field( 'Eyebrow', 'grips_page_eyebrow', 'Custom 1911 Accessories' ); ?>
                    <?php apex_field( 'Heading', 'grips_page_h1',      '1911 Grip Panels' ); ?>
                </div>
                <?php apex_textarea( 'Description', 'grips_page_desc', "Each grip panel is individually crafted and finished in-house. Whether you're after the durability of Cerakote, the classic look of natural aluminum, or the warmth of real wood — we have a grip to match your 1911 and your style. This product line is expanding regularly." ); ?>
                <p style="font-size:12px;color:#888;margin:8px 0 10px;">Trust badges:</p>
                <div class="apx-2col">
                    <?php apex_field( 'Badge 1', 'grips_page_badge_1', '✓ Made In-House' ); ?>
                    <?php apex_field( 'Badge 2', 'grips_page_badge_2', '✓ Multiple Finishes Available' ); ?>
                    <?php apex_field( 'Badge 3', 'grips_page_badge_3', '✓ Custom Options — Quote Based' ); ?>
                    <?php apex_field( 'Badge 4', 'grips_page_badge_4', '✓ Prices start at $90' ); ?>
                </div>
            </div>

            <div class="apx-box">
                <h3>Info Bar (4 items)</h3>
                <div class="apx-2col">
                    <?php
                    $grips_info_defaults = [
                        1 => [ 'Built to Last',         'Quality materials, quality finishes' ],
                        2 => [ 'Turnaround',             '5–10 business days' ],
                        3 => [ 'Pricing',                'Apex exclusives $90' ],
                        4 => [ 'Custom designed grips',  'Quote-based · Contact us' ],
                    ];
                    for ( $i = 1; $i <= 4; $i++ ): ?>
                    <div>
                        <p style="font-weight:700;color:#F5831F;margin:0 0 6px;font-size:13px;">Item <?php echo $i; ?></p>
                        <?php apex_field( 'Title',       "grips_info_{$i}_title", $grips_info_defaults[$i][0] ); ?>
                        <?php apex_field( 'Description', "grips_info_{$i}_desc",  $grips_info_defaults[$i][1] ); ?>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="apx-box">
                <h3>Grid Heading</h3>
                <?php apex_field(   'Section Heading', 'grips_grid_heading', 'Apex Exclusive Designs' ); ?>
                <?php apex_textarea( 'Subtext',        'grips_grid_subtext', 'Each set is made to order right here in America — never mass-produced overseas. Time, skill, and craftsmanship go into every piece we make.' ); ?>
            </div>

            <div class="apx-box">
                <h3>Grip Product Cards (4 designs)</h3>
                <?php
                $grips_card_defaults = [
                    1 => [ 'HEX Series Black Cerakote Aluminum Reveal', 'Aluminum grip panels machined with a deep HEX pattern and finished in matte Black Cerakote — aggressive texture, zero compromise.' ],
                    2 => [ 'HEX Series Black Cerakote',                 'HEX-machined aluminum panels coated in clean, matte Black Cerakote. Hard-wearing, purpose-built, and made right here in-house.' ],
                    3 => [ 'HEX Series Satin Aluminum',                 'HEX-machined aluminum panels with a natural satin finish — understated, classic, and built to last a lifetime.' ],
                    4 => [ 'Traditional Redwood',                       'Warm, classic redwood grip panels hand-fitted for a timeless look and feel.' ],
                ];
                for ( $i = 1; $i <= 4; $i++ ): ?>
                <div style="padding-bottom:16px;border-bottom:1px solid #f5f5f5;margin-bottom:16px;">
                    <p style="font-weight:700;color:#F5831F;margin:0 0 8px;font-size:13px;">Grip <?php echo $i; ?></p>
                    <?php apex_field(   'Label / Name', "grips_{$i}_label", $grips_card_defaults[$i][0] ); ?>
                    <?php apex_textarea( 'Description',  "grips_{$i}_desc",  $grips_card_defaults[$i][1] ); ?>
                </div>
                <?php endfor; ?>
            </div>

            <?php elseif ( $tab === 'heroes' ): ?>

            <div class="apx-box">
                <h3>Page Hero</h3>
                <?php apex_field(   'Eyebrow',     'heroes_eyebrow',  'Honoring Those Who Serve' ); ?>
                <?php apex_textarea( 'Description', 'heroes_desc',     'At Apex Coatings & Engraving, we proudly honor the sacrifice and service of those who protect and serve our communities. That dedication deserves recognition.' ); ?>
                <?php apex_field(   'Discount %',  'heroes_discount', '10%', '(the large number shown on the hero — e.g. 10%)' ); ?>
            </div>

            <div class="apx-box">
                <h3>Who Qualifies Section</h3>
                <div class="apx-2col">
                    <?php apex_field( 'Eyebrow',  'heroes_qualify_eyebrow',  'Who Qualifies' ); ?>
                    <?php apex_field( 'Heading',  'heroes_qualify_h2',       'Service Members & First Responders' ); ?>
                </div>
                <?php apex_field( 'Subtitle', 'heroes_qualify_subtitle', 'If you serve your community — this discount is for you.' ); ?>
                <div class="apx-2col" style="margin-top:16px;">
                    <?php
                    $group_defaults = [
                        1 => [ 'Active Military',     'All active duty branches — Army, Navy, Air Force, Marines, Coast Guard, Space Force. Currently serving our country.' ],
                        2 => [ 'Veterans',            'Honorably discharged veterans of any branch of the United States Armed Forces. Your service is never forgotten.' ],
                        3 => [ 'Firefighters',        'Career and volunteer firefighters. The men and women who run toward danger so others can run away.' ],
                        4 => [ 'Law Enforcement',     'Police officers, sheriffs, deputies, state troopers, and federal law enforcement keeping our communities safe.' ],
                        5 => [ 'Emergency Medical',   'EMTs, paramedics, and emergency medical responders. First on scene when every second counts.' ],
                        6 => [ 'Healthcare Heroes',   'Nurses, doctors, and frontline healthcare workers. Dedicated to healing and saving lives every single day.' ],
                    ];
                    for ( $i = 1; $i <= 6; $i++ ): ?>
                    <div>
                        <p style="font-weight:700;color:#F5831F;margin:0 0 6px;font-size:13px;">Group <?php echo $i; ?></p>
                        <?php apex_field(   'Title',       "heroes_group_{$i}_title", $group_defaults[$i][0] ); ?>
                        <?php apex_textarea( 'Description', "heroes_group_{$i}_desc",  $group_defaults[$i][1] ); ?>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="apx-box">
                <h3>How To Claim Section</h3>
                <div class="apx-2col">
                    <?php apex_field( 'Eyebrow', 'heroes_howto_eyebrow', 'How To Claim' ); ?>
                    <?php apex_field( 'Heading', 'heroes_howto_h2',      'Simple Process' ); ?>
                </div>
                <div class="apx-2col" style="margin-top:16px;">
                    <?php
                    $heroes_step_defaults = [
                        1 => [ 'Browse & Order',         'Choose your products or services and submit your order form on our website.' ],
                        2 => [ 'Mention Your Service',   'In the order notes or contact form, let us know your branch, department, or role.' ],
                        3 => [ 'Provide Verification',   'Bring or email a valid ID, badge, or military/VA card at pickup or with your request.' ],
                        4 => [ '10% Discount Applied',   'We apply your 10% heroes discount to the final invoice. No codes needed.' ],
                    ];
                    for ( $i = 1; $i <= 4; $i++ ): ?>
                    <div>
                        <p style="font-weight:700;color:#F5831F;margin:0 0 6px;font-size:13px;">Step <?php echo $i; ?></p>
                        <?php apex_field(   'Title',       "heroes_step_{$i}_title", $heroes_step_defaults[$i][0] ); ?>
                        <?php apex_textarea( 'Description', "heroes_step_{$i}_desc",  $heroes_step_defaults[$i][1] ); ?>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="apx-box">
                <h3>Work Samples Gallery</h3>
                <div class="apx-2col">
                    <?php apex_field( 'Eyebrow', 'heroes_gallery_eyebrow', 'Our Work' ); ?>
                    <?php apex_field( 'Heading', 'heroes_gallery_h2',      'Crafted for Those Who Carry' ); ?>
                </div>
                <?php apex_field( 'Subtitle', 'heroes_gallery_subtitle', 'Custom engraving and Cerakote finishing — including military and first responder themed designs.' ); ?>
            </div>

            <?php elseif ( $tab === 'contact' ): ?>

            <div class="apx-box">
                <h3>Page Hero</h3>
                <div class="apx-2col">
                    <?php apex_field( 'Eyebrow', 'contact_page_eyebrow', 'Get in Touch' ); ?>
                    <?php apex_field( 'Heading', 'contact_page_h1',      'Contact Us' ); ?>
                </div>
                <?php apex_textarea( 'Description', 'contact_page_desc', "Have a custom project in mind? Need a quote? We'll get back to you within 1-2 business days." ); ?>
            </div>

            <div class="apx-box">
                <h3>Left Panel</h3>
                <?php apex_field(   'Heading',     'contact_left_h2',   "Let's Build Something Exceptional" ); ?>
                <?php apex_textarea( 'Description', 'contact_left_desc', "Whether it's a single custom piece or a large production run, we handle every job with the same level of precision and care. Tell us what you need — we'll make it happen." ); ?>
            </div>

            <div class="apx-box">
                <h3>Contact Details</h3>
                <?php apex_field( 'Email Address', 'contact_email', 'orders@apexcoatingstn.com' ); ?>
                <?php apex_field( 'Phone Number',  'contact_phone', '(615) 862-1660' ); ?>
                <?php apex_textarea( 'Business Hours', 'contact_hours',    "Mon–Fri: 8AM – 5PM\nSat: 9AM – 2PM", 'Use a new line to separate days' ); ?>
                <?php apex_textarea( 'Location Text',  'contact_location', "Available for drop-off & pickup\nContact us for address", 'Use a new line for the second line' ); ?>
            </div>

            <div class="apx-box">
                <h3>Bottom CTA</h3>
                <?php apex_field(   'Heading',     'contact_cta_h2',   'Ready to Order?' ); ?>
                <?php apex_textarea( 'Description', 'contact_cta_desc', 'Browse our product catalog, add items to your cart, and submit your order request. Fast and easy.' ); ?>
            </div>

            <?php elseif ( $tab === 'reviews' ): ?>

            <div class="apx-box">
                <h3>Why Choose Apex — 4 Cards</h3>
                <div class="apx-2col">
                    <?php
                    $why_defaults = [
                        1 => [ 'Coatings Specialists',  'We understand the unique requirements for specialty coatings. We offer Cerakote, powder coat, and polished finishes.' ],
                        2 => [ 'Precision Every Time',  "State-of-the-art laser and engraving equipment. Sub-millimeter accuracy on every job, whether it's one piece or a hundred." ],
                        3 => [ 'Fast Turnaround',       "We respect your time. Rush options available. Most standard jobs turned around in 3-5 business days without compromising quality." ],
                        4 => [ 'Quality Guaranteed',    "At APEX, we set the standard for others to follow. Sub-par work will never leave our shop. If your project isn't right, you don't pay." ],
                    ];
                    for ( $i = 1; $i <= 4; $i++ ): ?>
                    <div>
                        <p style="font-weight:700;color:#F5831F;margin:0 0 6px;font-size:13px;">Card <?php echo $i; ?></p>
                        <?php apex_field(   'Title',       "why_{$i}_title", $why_defaults[$i][0] ); ?>
                        <?php apex_textarea( 'Description', "why_{$i}_desc",  $why_defaults[$i][1] ); ?>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>

            <?php
            $review_defaults = [
                1 => [ 'Apex engraved my custom 1911 and the work was flawless. Sharp, clean, exactly what I described. Will absolutely use them again.', 'Mike T.', 'Firearms Collector' ],
                2 => [ 'Got my AR lower Cerakoted in OD green. Perfect coverage, no runs, matched the color swatch exactly. Solid work at a fair price.',   'Jake R.', 'Competition Shooter' ],
                3 => [ 'Custom engraving on my hunting rifle stock — turned out better than I imagined. These guys take their craft seriously.',             'Derek S.', 'Avid Hunter' ],
            ];
            for ( $i = 1; $i <= 3; $i++ ): ?>
            <div class="apx-box">
                <h3>Customer Review <?php echo $i; ?></h3>
                <?php apex_textarea( 'Review Text',    "review_{$i}_text", $review_defaults[$i][0] ); ?>
                <div class="apx-2col">
                    <?php apex_field( 'Name',  "review_{$i}_name", $review_defaults[$i][1] ); ?>
                    <?php apex_field( 'Title / Role', "review_{$i}_role", $review_defaults[$i][2] ); ?>
                </div>
            </div>
            <?php endfor;

            elseif ( $tab === 'footer' ): ?>

            <div class="apx-box">
                <h3>Footer</h3>
                <?php apex_textarea( 'Tagline (under logo in footer)', 'footer_tagline',
                    'Precision, laser engraving, Cerakote application, and guaranteed quality. Craftsmanship you can trust & products that last.' ); ?>
                <?php apex_textarea( 'Services List (one per line)', 'footer_services',
                    "Laser Engraving\nCerakote Application\nGraphics Design\nCustom Finishes",
                    'Each line becomes a link in the footer Services column' ); ?>
            </div>

            <div class="apx-box">
                <h3>Contact Information</h3>
                <?php apex_field( 'Email Address', 'contact_email', 'ApexTN@outlook.com' ); ?>
                <?php apex_field( 'Phone Number',  'contact_phone', '(615) 862-1660' ); ?>
            </div>

            <?php elseif ( $tab === 'images' ): ?>

            <div class="apx-box">
                <h3>Logo Images</h3>
                <p style="color:#666;font-size:13px;margin:0 0 16px;">Upload images via the WordPress Media Library. Use WebP or PNG with transparent background for best results.</p>
                <?php
                $logos = [
                    [ 'Nav Logo (top bar — shown on dark background)', 'nav_logo_id',    get_template_directory_uri() . '/assets/images/apex-logo-nav.webp' ],
                    [ 'Hero Logo (large logo on homepage)',             'hero_logo_id',   get_template_directory_uri() . '/assets/images/apex-logo-hero.webp' ],
                    [ 'Footer Logo (white version for dark footer)',    'footer_logo_id', get_template_directory_uri() . '/assets/images/apex-logo-footer.webp' ],
                ];
                foreach ( $logos as [ $label, $key, $fallback ] ):
                    $stored_id  = apex_opt( $key );
                    $preview_url = $stored_id ? wp_get_attachment_url( $stored_id ) : $fallback;
                ?>
                <div class="apx-field" style="padding-bottom:16px;border-bottom:1px solid #f0f0f0;margin-bottom:16px;">
                    <label><?php echo esc_html( $label ); ?></label>
                    <div class="apx-img-row" id="apx-row-<?php echo esc_attr( $key ); ?>">
                        <?php if ( $preview_url ): ?>
                        <img src="<?php echo esc_url( $preview_url ); ?>" class="apx-img-preview" id="apx-preview-<?php echo esc_attr( $key ); ?>" alt="">
                        <?php else: ?>
                        <div class="apx-img-placeholder" id="apx-preview-<?php echo esc_attr( $key ); ?>">No image</div>
                        <?php endif; ?>
                        <input type="hidden" name="apex_<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $stored_id ); ?>" id="apx-input-<?php echo esc_attr( $key ); ?>">
                        <button type="button" class="apx-btn-pick apx-pick-logo" data-key="<?php echo esc_attr( $key ); ?>">Choose Image</button>
                        <?php if ( $stored_id ): ?>
                        <button type="button" class="apx-btn-clear apx-clear-logo" data-key="<?php echo esc_attr( $key ); ?>">Remove</button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="apx-box">
                <h3>Hero Slideshow Images</h3>
                <p style="color:#666;font-size:13px;margin:0 0 12px;">These photos scroll automatically across the homepage. Add or remove images here. If empty, the site uses its default built-in slideshow.</p>
                <?php $ss_ids = array_filter( explode( ',', apex_opt( 'slideshow_ids', '' ) ) ); ?>
                <div class="apx-gallery" id="apx-slideshow-gallery">
                    <?php foreach ( $ss_ids as $id ):
                        $thumb = wp_get_attachment_image_url( $id, 'thumbnail' );
                        if ( ! $thumb ) continue; ?>
                    <div class="apx-gallery-thumb" data-id="<?php echo esc_attr( $id ); ?>">
                        <img src="<?php echo esc_url( $thumb ); ?>" alt="">
                        <button type="button" class="apx-remove" title="Remove">×</button>
                    </div>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="apex_slideshow_ids" id="apx-slideshow-ids" value="<?php echo esc_attr( apex_opt( 'slideshow_ids', '' ) ); ?>">
                <br>
                <button type="button" class="apx-btn-add" id="apx-add-slides">+ Add Photos</button>
            </div>

            <?php endif; ?>

            <p>
                <input type="submit" class="button button-primary apx-save" value="💾  Save Changes">
            </p>
        </form>
    </div>
    <?php
}
