<?php
/**
 * Title: Featured Products
 * Slug: apex/featured-products
 * Categories: apex
 * Description: Products grid pulling from the apex_product CPT, with fallback static cards.
 */

$eyebrow  = function_exists('apex_opt') ? apex_opt('products_eyebrow', 'What We Offer')         : 'What We Offer';
$heading  = function_exists('apex_opt') ? apex_opt('products_heading', 'Products & Services')    : 'Products & Services';
$subtitle = function_exists('apex_opt') ? apex_opt('products_subtitle', 'Durable Cerakote finishes and precision laser engraving for gear that works as hard as you do.') : 'Durable Cerakote finishes and precision laser engraving for gear that works as hard as you do.';

$products_query = function_exists('apex_get_products_query') ? apex_get_products_query(6) : null;
$has_products   = $products_query && $products_query->have_posts();

$defaults = [
    ['name'=>'Custom Pistol Slide Engraving','desc'=>'Precision engraving on pistol slides. Every design is unique — quoted per project.','price'=>'75','features'=>['Custom artwork & logos','Deep-cut laser precision','Quoted per project'],'img'=>'apex-1911-floral-scroll-engraving-03-full.webp'],
    ['name'=>'Cerakote Finishing','desc'=>'Industry-leading ceramic polymer coating for firearms and blades. Corrosion-resistant and built to outlast anything else on the market.','price'=>'75','features'=>['Single-color slides: $100–$200','Multi-color & themes priced by project','Corrosion & UV resistant'],'img'=>'apex-ar-mag-eagle-american-flag-01-set.webp'],
    ['name'=>'Laser Engraving','desc'=>'High-precision laser etching for custom branding, serial numbers, decorative patterns, and personalized gifts on any metal surface.','price'=>'149','features'=>['Sub-millimeter precision','Photos & vector artwork','Knives, plaques, metals'],'img'=>'apex-ar-mag-grim-reaper-skeleton-01.webp'],
    ['name'=>'Cerakote — Slide Finishing','desc'=>'Single-color Cerakote on pistol slides starting at $100. Multiple colors, patterns, and custom themes are quoted per project.','price'=>'100','features'=>['Single color: $100–$200','Multi-color & themes: quoted per job','H-series & Elite series available'],'img'=>'apex-ar-mag-punisher-thin-blue-line-01.webp'],
    ['name'=>'Custom Knife Engraving','desc'=>'Custom laser engraving on knives and blades. Personalized gifts, tactical markings, or full custom artwork — quoted per project.','price'=>'30','features'=>['Custom artwork & text','Any blade type','Quoted per project'],'img'=>'apex-knife-floral-scroll-engraving-01.webp'],
];
$prod_img_uri = get_template_directory_uri() . '/assets/images/products/';
$prod_img_dir = get_template_directory() . '/assets/images/products/';
?>
<!-- wp:html -->
<section class="services-section section-pad" id="products">
    <div class="container">
        <div class="text-center reveal">
            <span class="eyebrow"><?php echo esc_html($eyebrow); ?></span>
            <h2 class="section-title"><?php echo esc_html($heading); ?></h2>
            <p class="section-subtitle"><?php echo esc_html($subtitle); ?></p>
        </div>

        <div class="services-grid">
        <?php if ($has_products):
            while ($products_query->have_posts()): $products_query->the_post();
                $pid      = get_the_ID();
                $price    = get_post_meta($pid, '_apex_price', true);
                $material = get_post_meta($pid, '_apex_material', true);
                $in_stock = get_post_meta($pid, '_apex_in_stock', true);
                $sku      = get_post_meta($pid, '_apex_sku', true);
                $link_url = get_post_meta($pid, '_apex_link_url', true);
                $card_url = $link_url ? home_url($link_url) : get_permalink($pid);
                $is_linkout = !empty($link_url);
                $has_thumb  = has_post_thumbnail();
        ?>
            <div class="service-card reveal" data-product-id="<?php the_ID(); ?>">
                <div class="card-top-bar"></div>
                <?php if ($has_thumb): ?>
                <div style="aspect-ratio:16/10;overflow:hidden;background:var(--apex-black);">
                    <?php the_post_thumbnail('full', ['style'=>'width:100%;height:100%;object-fit:cover;opacity:0.92;transition:opacity 0.3s,transform 0.4s;','class'=>'product-thumb-img']); ?>
                </div>
                <?php endif; ?>
                <div class="card-body">
                    <?php if (!$has_thumb): ?>
                    <div class="card-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg></div>
                    <?php endif; ?>
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:8px;">
                        <h3 class="card-name"><?php the_title(); ?></h3>
                        <?php if ($in_stock === '1'): ?>
                        <span style="flex-shrink:0;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#38a169;background:rgba(56,161,105,0.1);border:1px solid rgba(56,161,105,0.3);padding:3px 10px;border-radius:50px;">In Stock</span>
                        <?php else: ?>
                        <span style="flex-shrink:0;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#e53e3e;background:rgba(229,62,62,0.08);border:1px solid rgba(229,62,62,0.2);padding:3px 10px;border-radius:50px;">Quote Only</span>
                        <?php endif; ?>
                    </div>
                    <?php if ($material): ?><p style="font-size:11px;letter-spacing:0.12em;text-transform:uppercase;color:var(--apex-gray-mid);margin-bottom:10px;"><?php echo esc_html($material); ?></p><?php endif; ?>
                    <p class="card-desc"><?php the_excerpt(); ?></p>
                    <?php if ($price): ?>
                    <div class="card-price">
                        <span class="price-amount"><?php echo function_exists('apex_price') ? apex_price($price) : '$'.esc_html($price); ?></span>
                        <span class="price-label">starting at</span>
                    </div>
                    <?php endif; ?>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:auto;">
                        <?php if ($is_linkout): ?>
                        <a href="<?php echo esc_url($card_url); ?>" class="btn btn-outline-dark btn-sm" style="justify-content:center;">View Designs</a>
                        <a href="<?php echo esc_url($card_url); ?>" class="btn btn-primary btn-sm" style="justify-content:center;">Order Now</a>
                        <?php else: ?>
                        <a href="<?php echo esc_url(get_permalink($pid)); ?>" class="btn btn-outline-dark btn-sm" style="justify-content:center;">View Details</a>
                        <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-primary btn-sm" style="justify-content:center;">Request Quote</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; wp_reset_postdata();
        else:
            foreach ($defaults as $svc):
                $img_exists = file_exists($prod_img_dir . $svc['img']);
        ?>
            <div class="service-card reveal">
                <div class="card-top-bar"></div>
                <?php if ($img_exists): ?>
                <div style="aspect-ratio:16/10;overflow:hidden;background:var(--apex-black);">
                    <img src="<?php echo esc_url($prod_img_uri . $svc['img']); ?>" alt="<?php echo esc_attr($svc['name']); ?>" loading="lazy" class="product-thumb-img" style="width:100%;height:100%;object-fit:cover;opacity:0.9;transition:opacity 0.3s,transform 0.4s;">
                </div>
                <?php endif; ?>
                <div class="card-body">
                    <h3 class="card-name"><?php echo esc_html($svc['name']); ?></h3>
                    <p class="card-desc"><?php echo esc_html($svc['desc']); ?></p>
                    <div class="card-price">
                        <span class="price-amount">$<?php echo esc_html($svc['price']); ?></span>
                        <span class="price-label">starting at</span>
                    </div>
                    <ul class="card-features">
                        <?php foreach ($svc['features'] as $f): ?><li><?php echo esc_html($f); ?></li><?php endforeach; ?>
                    </ul>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:auto;">
                        <a href="<?php echo esc_url(home_url('/products')); ?>" class="btn btn-outline-dark btn-sm" style="justify-content:center;">View Details</a>
                        <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-primary btn-sm" style="justify-content:center;">Request Quote</a>
                    </div>
                </div>
            </div>
        <?php endforeach; endif; ?>
        </div>

        <div class="text-center" style="margin-top:48px;">
            <a href="<?php echo esc_url(home_url('/products')); ?>" class="btn btn-outline-dark">
                View All Products
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>
    </div>
</section>
<!-- /wp:html -->
