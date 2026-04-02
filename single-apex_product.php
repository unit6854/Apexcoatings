<?php
/**
 * Single Product Page
 */
get_header();
the_post();

$price    = get_post_meta(get_the_ID(), '_apex_price', true);
$sku      = get_post_meta(get_the_ID(), '_apex_sku', true);
$material = get_post_meta(get_the_ID(), '_apex_material', true);
$in_stock = get_post_meta(get_the_ID(), '_apex_in_stock', true);
$has_thumb = has_post_thumbnail();

// Get gallery images from post content / ACF if available
$gallery_ids = get_post_meta(get_the_ID(), '_apex_gallery', true);
?>

<div style="background:var(--apex-black);padding:120px 0 48px;">
    <div class="container">
        <a href="<?php echo get_post_type_archive_link('apex_product'); ?>" class="page-back-link">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:16px;height:16px;"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Back to Products
        </a>
    </div>
</div>

<section style="background:var(--apex-off-white);padding:0 0 80px;">
    <div class="container" style="padding-top:48px;">

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:start;">

            <!-- Left: Images -->
            <div>
                <?php if ($has_thumb): ?>
                <div style="border-radius:8px;overflow:hidden;background:var(--apex-black);aspect-ratio:4/3;margin-bottom:16px;box-shadow:var(--shadow-heavy);">
                    <?php the_post_thumbnail('full', [
                        'style' => 'width:100%;height:100%;object-fit:cover;',
                        'alt'   => get_the_title(),
                    ]); ?>
                </div>
                <?php else: ?>
                <div style="border-radius:8px;background:var(--apex-black);aspect-ratio:4/3;margin-bottom:16px;box-shadow:var(--shadow-heavy);display:flex;align-items:center;justify-content:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 200 240" fill="none" aria-hidden="true">
                        <polygon points="100,12 188,200 12,200" fill="#1A1A1A"/>
                        <polygon points="100,50 165,185 35,185" fill="rgba(255,255,255,0.05)"/>
                        <defs><linearGradient id="sFlame" x1="100" y1="60" x2="100" y2="175" gradientUnits="userSpaceOnUse"><stop offset="0%" stop-color="#FFAD00"/><stop offset="100%" stop-color="#F5831F"/></linearGradient></defs>
                        <path d="M100,68 Q118,95 112,160 Q108,178 100,165 Q92,178 88,160 Q82,95 100,68Z" fill="url(#sFlame)" opacity="0.7"/>
                    </svg>
                </div>
                <?php endif; ?>
            </div>

            <!-- Right: Details -->
            <div>
                <!-- Breadcrumb category -->
                <?php
                $terms = get_the_terms(get_the_ID(), 'product_cat');
                if ($terms && !is_wp_error($terms)): ?>
                <div style="margin-bottom:12px;">
                    <?php foreach ($terms as $term): ?>
                    <a href="<?php echo get_term_link($term); ?>" style="font-size:11px;font-weight:700;letter-spacing:0.2em;text-transform:uppercase;color:var(--apex-orange);"><?php echo esc_html($term->name); ?></a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <h1 style="font-family:'Barlow Condensed',sans-serif;font-size:clamp(2rem,4vw,3rem);font-weight:900;text-transform:uppercase;color:var(--apex-black);line-height:1.1;margin-bottom:16px;"><?php the_title(); ?></h1>

                <!-- Stock + SKU -->
                <div style="display:flex;align-items:center;gap:16px;margin-bottom:24px;flex-wrap:wrap;">
                    <?php if ($in_stock === '1'): ?>
                    <span style="font-size:12px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:#38a169;background:rgba(56,161,105,0.1);border:1px solid rgba(56,161,105,0.3);padding:5px 14px;border-radius:50px;">Available</span>
                    <?php else: ?>
                    <span style="font-size:12px;font-weight:700;letter-spacing:0.12em;text-transform:uppercase;color:var(--apex-orange);background:rgba(245,131,31,0.1);border:1px solid rgba(245,131,31,0.3);padding:5px 14px;border-radius:50px;">Quote Required</span>
                    <?php endif; ?>
                    <?php if ($sku): ?><span style="font-size:12px;color:var(--apex-gray-mid);">SKU: <?php echo esc_html($sku); ?></span><?php endif; ?>
                    <?php if ($material): ?><span style="font-size:12px;color:var(--apex-gray-mid);"><?php echo esc_html($material); ?></span><?php endif; ?>
                </div>

                <!-- Price -->
                <?php if ($price): ?>
                <div style="margin-bottom:24px;">
                    <span style="font-family:'Barlow Condensed',sans-serif;font-size:3rem;font-weight:900;color:var(--apex-orange);line-height:1;"><?php echo apex_price($price); ?></span>
                    <span style="font-size:0.9rem;color:var(--apex-gray-mid);margin-left:8px;">starting at</span>
                </div>
                <?php endif; ?>

                <!-- Description -->
                <div style="color:var(--apex-gray-dark);line-height:1.75;margin-bottom:32px;font-size:1rem;">
                    <?php the_content(); ?>
                </div>

                <!-- Add to Cart -->
                <div style="display:flex;gap:14px;flex-wrap:wrap;">
                    <button
                        class="add-to-cart-btn"
                        data-id="<?php the_ID(); ?>"
                        data-name="<?php echo esc_attr(get_the_title()); ?>"
                        data-price="<?php echo esc_attr($price ?: '0'); ?>"
                        data-desc="<?php echo esc_attr(wp_strip_all_tags(get_the_excerpt())); ?>"
                        data-image="<?php echo esc_attr(get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') ?: ''); ?>"
                        style="flex:1;min-width:180px;justify-content:center;"
                        aria-label="Add <?php the_title(); ?> to cart"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                        Add to Cart
                    </button>
                    <a href="<?php echo home_url('/contact'); ?>" class="btn btn-outline-dark" style="justify-content:center;">
                        Get a Custom Quote
                    </a>
                </div>

                <!-- Trust badges -->
                <div style="margin-top:28px;padding-top:24px;border-top:1px solid var(--apex-gray-light);display:flex;flex-direction:column;gap:10px;">
                    <div class="trust-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:16px;height:16px;color:var(--apex-orange);flex-shrink:0;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span style="font-size:0.82rem;color:var(--apex-gray-dark);">No payment collected online — pricing confirmed before work begins</span>
                    </div>
                    <div class="trust-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width:16px;height:16px;color:var(--apex-orange);flex-shrink:0;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span style="font-size:0.82rem;color:var(--apex-gray-dark);">Standard turnaround: 3-5 business days</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Related Products -->
<?php
$terms = get_the_terms(get_the_ID(), 'product_cat');
if ($terms && !is_wp_error($terms)) {
    $related = get_posts([
        'post_type'      => 'apex_product',
        'posts_per_page' => 3,
        'post__not_in'   => [get_the_ID()],
        'tax_query'      => [[
            'taxonomy' => 'product_cat',
            'field'    => 'term_id',
            'terms'    => wp_list_pluck($terms, 'term_id'),
        ]],
    ]);

    if ($related):
    ?>
<section style="background:var(--apex-off-white);padding:60px 0 80px;border-top:1px solid var(--apex-gray-light);">
    <div class="container">
        <h2 class="section-title" style="margin-bottom:40px;">Related Products</h2>
        <div class="services-grid">
            <?php foreach ($related as $rp):
                $r_price = get_post_meta($rp->ID, '_apex_price', true);
            ?>
            <div class="service-card">
                <div class="card-top-bar"></div>
                <?php if (has_post_thumbnail($rp->ID)): ?>
                <a href="<?php echo get_permalink($rp); ?>" style="display:block;aspect-ratio:16/10;overflow:hidden;background:var(--apex-black);">
                    <?php echo get_the_post_thumbnail($rp->ID, 'medium', ['style'=>'width:100%;height:100%;object-fit:cover;']); ?>
                </a>
                <?php endif; ?>
                <div class="card-body">
                    <h3 class="card-name"><a href="<?php echo get_permalink($rp); ?>" style="color:inherit;text-decoration:none;"><?php echo esc_html($rp->post_title); ?></a></h3>
                    <?php if ($r_price): ?>
                    <div class="card-price"><span class="price-amount"><?php echo apex_price($r_price); ?></span><span class="price-label">starting at</span></div>
                    <?php endif; ?>
                    <button
                        class="add-to-cart-btn"
                        data-id="<?php echo $rp->ID; ?>"
                        data-name="<?php echo esc_attr($rp->post_title); ?>"
                        data-price="<?php echo esc_attr($r_price ?: '0'); ?>"
                        data-desc=""
                        data-image="<?php echo esc_attr(get_the_post_thumbnail_url($rp->ID,'thumbnail') ?: ''); ?>"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                        Add to Cart
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
    <?php endif;
} ?>

<?php get_footer(); ?>
