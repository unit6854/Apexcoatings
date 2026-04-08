<?php
/**
 * Product Archive / Shop Page
 */
get_header();

// Get all product categories for filter
$categories = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false]);
?>

<div style="background:var(--apex-black);padding:140px 0 60px;margin-bottom:0;">
    <div class="container">
        <span class="eyebrow" style="display:block;margin-bottom:12px;">Our Catalog</span>
        <h1 class="section-title" style="color:var(--apex-white);">Products &amp; Services</h1>
        <p style="color:rgba(255,255,255,0.55);max-width:560px;line-height:1.7;">Custom engraving, Cerakote, and metal finishing — built to order, built to last.</p>
    </div>
</div>

<section style="background:var(--apex-off-white);padding:48px 0 80px;">
    <div class="container">

        <!-- Category Filter -->
        <?php if (!is_wp_error($categories) && !empty($categories)): ?>
        <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:40px;" role="group" aria-label="Filter by category">
            <a href="<?php echo get_post_type_archive_link('apex_product'); ?>"
               class="btn btn-sm <?php echo !is_tax('product_cat') ? 'btn-primary' : 'btn-outline-dark'; ?>"
               style="font-size:0.8rem;">
               All
            </a>
            <?php foreach ($categories as $cat): ?>
            <a href="<?php echo get_term_link($cat); ?>"
               class="btn btn-sm <?php echo is_tax('product_cat', $cat->term_id) ? 'btn-primary' : 'btn-outline-dark'; ?>"
               style="font-size:0.8rem;">
               <?php echo esc_html($cat->name); ?>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Products Grid -->
        <?php if (have_posts()): ?>
        <div class="services-grid">
            <?php while (have_posts()): the_post();
                $price    = get_post_meta(get_the_ID(), '_apex_price', true);
                $material = get_post_meta(get_the_ID(), '_apex_material', true);
                $in_stock = get_post_meta(get_the_ID(), '_apex_in_stock', true);
                $has_thumb = has_post_thumbnail();
            ?>
            <div class="service-card reveal">
                <div class="card-top-bar"></div>

                <?php if ($has_thumb): ?>
                <a href="<?php the_permalink(); ?>" style="display:block;aspect-ratio:16/10;overflow:hidden;background:var(--apex-black);">
                    <?php the_post_thumbnail('full', [
                        'style' => 'width:100%;height:100%;object-fit:cover;opacity:0.92;transition:transform 0.4s,opacity 0.3s;',
                        'class' => 'product-thumb-img',
                    ]); ?>
                </a>
                <?php endif; ?>

                <div class="card-body">
                    <?php if (!$has_thumb): ?>
                    <div class="card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                    </div>
                    <?php endif; ?>

                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:10px;margin-bottom:8px;">
                        <h2 class="card-name" style="font-size:1.3rem;">
                            <a href="<?php the_permalink(); ?>" style="color:inherit;text-decoration:none;"><?php the_title(); ?></a>
                        </h2>
                        <?php if ($in_stock === '1'): ?>
                        <span style="flex-shrink:0;font-size:10px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#38a169;background:rgba(56,161,105,0.1);border:1px solid rgba(56,161,105,0.3);padding:3px 8px;border-radius:50px;">In Stock</span>
                        <?php endif; ?>
                    </div>

                    <?php if ($material): ?>
                    <p style="font-size:11px;letter-spacing:0.12em;text-transform:uppercase;color:var(--apex-gray-mid);margin-bottom:10px;"><?php echo esc_html($material); ?></p>
                    <?php endif; ?>

                    <p class="card-desc"><?php the_excerpt(); ?></p>

                    <?php if ($price): ?>
                    <div class="card-price">
                        <span class="price-amount"><?php echo apex_price($price); ?></span>
                        <span class="price-label">starting at</span>
                    </div>
                    <?php endif; ?>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:auto;">
                        <a href="<?php the_permalink(); ?>" class="btn btn-outline-dark btn-sm" style="justify-content:center;">View Details</a>
                        <a href="<?php echo esc_url( home_url('/contact') ); ?>" class="btn btn-primary btn-sm" style="justify-content:center;">Request Quote</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <div style="margin-top:48px;text-align:center;">
            <?php the_posts_pagination(['mid_size' => 2, 'prev_text' => '&larr; Prev', 'next_text' => 'Next &rarr;']); ?>
        </div>

        <?php else: ?>
        <div style="text-align:center;padding:80px 40px;background:var(--apex-white);border-radius:8px;box-shadow:var(--shadow-card);">
            <h2 style="margin-bottom:12px;">No Products Yet</h2>
            <p style="color:var(--apex-gray-dark);margin-bottom:32px;">Check back soon or <a href="<?php echo esc_url( home_url('/contact') ); ?>">contact us</a> for a custom quote.</p>
        </div>
        <?php endif; ?>

    </div>
</section>

<?php get_footer(); ?>
