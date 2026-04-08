<?php
/**
 * Template Name: Products Page
 * Auto-used for page with slug: products
 * Renders apex_product CPT directly — bypasses WooCommerce query conflicts.
 */
get_header();

// Get active category filter from URL
$active_cat = isset($_GET['cat']) ? sanitize_title($_GET['cat']) : '';

// Use direct DB query to bypass all WooCommerce/plugin query filters
global $wpdb;

if ( $active_cat ) {
    // Filter by category
    $term = get_term_by( 'slug', $active_cat, 'product_cat' );
    $term_id = $term ? (int) $term->term_id : 0;
    $product_ids = $term_id ? $wpdb->get_col( $wpdb->prepare(
        "SELECT p.ID FROM {$wpdb->posts} p
         INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
         INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
         WHERE p.post_type = 'apex_product'
           AND p.post_status = 'publish'
           AND tt.taxonomy = 'product_cat'
           AND tt.term_id = %d
         ORDER BY p.post_date ASC LIMIT 24",
        $term_id
    ) ) : [];
} else {
    $product_ids = $wpdb->get_col(
        "SELECT ID FROM {$wpdb->posts}
         WHERE post_type = 'apex_product' AND post_status = 'publish'
         ORDER BY post_date ASC LIMIT 24"
    );
}

// Convert IDs to post objects
$products_list = array_map( 'get_post', $product_ids );
$categories = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false]);
?>

<div style="background:var(--apex-black);padding:140px 0 60px;">
    <div class="container">
        <span class="eyebrow" style="display:block;margin-bottom:12px;">What We Offer</span>
        <h1 class="section-title" style="color:var(--apex-white);">Products &amp; Services</h1>
    </div>
</div>

<section style="background:var(--apex-off-white);padding:48px 0 80px;">
    <div class="container">

        <!-- Quick Links -->
        <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:40px;">
            <a href="<?php echo esc_url( home_url('/pmags') ); ?>"
               class="btn btn-sm btn-outline-dark"
               style="font-size:0.8rem;">🎯 Custom PMAGs</a>
            <a href="<?php echo esc_url( home_url('/1911-grips') ); ?>"
               class="btn btn-sm btn-outline-dark"
               style="font-size:0.8rem;">🔫 1911 Grips</a>
        </div>

        <?php if (!empty($products_list)): ?>
        <div class="services-grid">
            <?php foreach ($products_list as $post): setup_postdata($post);
                $pid      = $post->ID;
                $price    = get_post_meta($pid, '_apex_price', true);
                $material = get_post_meta($pid, '_apex_material', true);
                $in_stock = get_post_meta($pid, '_apex_in_stock', true);
                $thumb_id = get_post_thumbnail_id($pid);
                // Link-out override (e.g. PMAG → /pmags, 1911 Grips → /1911-grips)
                $link_url = get_post_meta($pid, '_apex_link_url', true);
                $card_url = $link_url ? home_url($link_url) : get_permalink($pid);
                $is_linkout = !empty($link_url);
            ?>
            <div class="service-card reveal">
                <div class="card-top-bar"></div>
                <?php if ($thumb_id): ?>
                <a href="<?php echo esc_url($card_url); ?>" style="display:block;aspect-ratio:16/10;overflow:hidden;background:var(--apex-black);">
                    <?php echo wp_get_attachment_image($thumb_id, 'full', false, ['style'=>'width:100%;height:100%;object-fit:cover;transition:transform 0.4s;','class'=>'product-thumb-img']); ?>
                </a>
                <?php else: ?>
                <div style="aspect-ratio:16/10;background:linear-gradient(135deg,#1a1a1a,#2a2a2a);display:flex;align-items:center;justify-content:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="rgba(245,131,31,0.4)" stroke-width="1.5" style="width:48px;height:48px;"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                </div>
                <?php endif; ?>
                <div class="card-body">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:10px;margin-bottom:8px;">
                        <h2 class="card-name" style="font-size:1.25rem;">
                            <a href="<?php echo esc_url($card_url); ?>" style="color:inherit;text-decoration:none;"><?php echo get_the_title($pid); ?></a>
                        </h2>
                        <?php if ($in_stock === '1'): ?>
                        <span style="flex-shrink:0;font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#38a169;background:rgba(56,161,105,0.1);border:1px solid rgba(56,161,105,0.3);padding:3px 8px;border-radius:50px;">In Stock</span>
                        <?php endif; ?>
                    </div>
                    <?php if ($material): ?><p style="font-size:11px;letter-spacing:0.12em;text-transform:uppercase;color:var(--apex-gray-mid);margin-bottom:10px;"><?php echo esc_html($material); ?></p><?php endif; ?>
                    <p class="card-desc"><?php echo wp_trim_words(get_the_excerpt() ?: get_the_content(null, false, $pid), 18); ?></p>
                    <?php if ($price): ?>
                    <div class="card-price"><span class="price-amount"><?php echo apex_price($price); ?></span><span class="price-label">starting at</span></div>
                    <?php endif; ?>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:auto;">
                        <a href="<?php echo esc_url($card_url); ?>" class="btn btn-outline-dark btn-sm" style="justify-content:center;">
                            <?php echo $is_linkout ? 'View Designs' : 'Details'; ?>
                        </a>
                        <?php if (!$is_linkout): ?>
                        <button class="add-to-cart-btn btn-sm"
                            data-id="<?php echo $pid; ?>"
                            data-name="<?php echo esc_attr(get_the_title($pid)); ?>"
                            data-price="<?php echo esc_attr($price ?: '0'); ?>"
                            data-desc="<?php echo esc_attr(wp_trim_words(get_the_excerpt() ?: '', 12)); ?>"
                            data-image="<?php echo esc_attr($thumb_id ? wp_get_attachment_image_url($thumb_id, 'full') : ''); ?>"
                            aria-label="Add <?php echo esc_attr(get_the_title($pid)); ?> to cart">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:15px;height:15px;" aria-hidden="true"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                            Add to Cart
                        </button>
                        <?php else: ?>
                        <a href="<?php echo esc_url($card_url); ?>" class="btn btn-primary btn-sm" style="justify-content:center;">
                            Order Now
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; wp_reset_postdata(); ?>
        </div>
        <?php else: ?>
        <div style="text-align:center;padding:80px;">
            <p>No products listed yet. <a href="<?php echo esc_url( home_url('/contact') ); ?>">Contact us</a> for a custom quote.</p>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
