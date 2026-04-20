<?php get_header(); ?>

<!-- ============================================================
     HERO SECTION
     ============================================================ -->
<section class="hero" id="home">
    <div class="hero-bg">
        <div class="hero-grid"></div>
    </div>

    <!-- Hero Text — centered above the slideshow -->
    <div class="container hero-content">
        <div class="hero-text reveal">

            <!-- Glowing APEX logo above title -->
            <div class="hero-logo-wrap">
                <div class="hero-logo-glow"></div>
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/apex-logo-hero.webp'); ?>"
                     alt="Apex Coatings &amp; Engraving"
                     class="hero-logo-img"
                     loading="eager"
                     fetchpriority="high"
                     decoding="sync">
            </div>

            <div class="hero-title-wrap">
                <h1 class="hero-title">
                    <?php
                    $hero_title = apex_opt( 'hero_title', 'Custom Coatings & Engraving' );
                    $parts = explode( '&', $hero_title, 2 );
                    if ( count($parts) === 2 ) {
                        echo esc_html( trim($parts[0]) ) . ' <span class="accent">&amp;</span><br>' . esc_html( trim($parts[1]) );
                    } else {
                        echo esc_html( $hero_title );
                    }
                    ?>
                </h1>
            </div>

            <p class="hero-desc">
                <?php echo esc_html( apex_opt( 'hero_tagline', 'Precision, laser engraving, Cerakote application, and guaranteed quality. Craftsmanship you can trust & products that last.' ) ); ?>
            </p>

            <div class="hero-actions">
                <a href="<?php echo esc_url( home_url('/products') ); ?>" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 12V22H4V12"/><path d="M22 7H2v5h20V7z"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                    Browse Products
                </a>
                <a href="<?php echo esc_url( home_url('/contact') ); ?>" class="btn btn-outline">
                    Request A Quote
                </a>
            </div>

            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-num"><?php echo esc_html( apex_opt( 'stat_1_num', '15+' ) ); ?></div>
                    <div class="stat-label"><?php echo esc_html( apex_opt( 'stat_1_label', 'Years Experience' ) ); ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-num"><?php echo esc_html( apex_opt( 'stat_2_num', '2K+' ) ); ?></div>
                    <div class="stat-label"><?php echo esc_html( apex_opt( 'stat_2_label', 'Jobs Completed' ) ); ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-num"><?php echo esc_html( apex_opt( 'stat_3_num', '100%' ) ); ?></div>
                    <div class="stat-label"><?php echo esc_html( apex_opt( 'stat_3_label', 'Satisfaction' ) ); ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="scroll-indicator" id="scroll-indicator" aria-hidden="true">
        <span>Scroll</span>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
    </div>

    <!-- Full-Width Slideshow Strip — below the text -->
    <div class="hero-visual">
        <div class="hero-slideshow-wrap" aria-label="Gallery of engraving work">
            <div class="hero-slideshow-track" id="hero-slideshow-track">
                <?php
                // ── Native WP post meta: home_slider_ids ─────────────────────
                // Populated via "Homepage Slider Images" meta box on this page.
                // Stored as comma-separated attachment IDs in post meta.
                $slider_ids = array_filter( explode( ',', (string) get_post_meta( get_the_ID(), 'home_slider_ids', true ) ) );

                $acf_slides = [];
                foreach ( $slider_ids as $att_id ) {
                    $att_id  = (int) $att_id;
                    $src     = wp_get_attachment_image_src( $att_id, 'full' );
                    if ( ! $src ) continue;
                    $acf_slides[] = [
                        'url'    => $src[0],
                        'width'  => $src[1],
                        'height' => $src[2],
                        'alt'    => get_post_meta( $att_id, '_wp_attachment_image_alt', true ),
                    ];
                }

                // Fallback: auto-discover from theme folder if meta is empty
                if ( empty( $acf_slides ) ) {
                    $slide_dir = get_template_directory() . '/assets/images/slideshow/';
                    $slide_uri = get_template_directory_uri() . '/assets/images/slideshow/';
                    $raw_files = glob( $slide_dir . '*.webp' ) ?: [];
                    $acf_slides = array_map( function( $path ) use ( $slide_uri ) {
                        $file = basename( $path );
                        $name = pathinfo( $file, PATHINFO_FILENAME );
                        $alt  = ucwords( str_replace( ['-','_'], ' ', preg_replace( '/^apex-|-\d+$/', '', $name ) ) );
                        return [ 'url' => $slide_uri . $file, 'alt' => trim( $alt ), 'width' => 1400, 'height' => 800 ];
                    }, $raw_files );
                }

                if ( ! empty( $acf_slides ) ):
                    // Duplicate the set for seamless infinite CSS scroll
                    foreach ( [ $acf_slides, $acf_slides ] as $set ):
                        foreach ( $set as $slide ):
                            $img_url = esc_url( $slide['url'] );
                            $img_alt = esc_attr( ( ! empty( $slide['alt'] ) ? $slide['alt'] : '' ) . ' — Apex Engraving' );
                            $img_w   = ! empty( $slide['width']  ) ? (int) $slide['width']  : 1400;
                            $img_h   = ! empty( $slide['height'] ) ? (int) $slide['height'] : 800;
                ?>
                <img src="<?php echo $img_url; ?>"
                     alt="<?php echo $img_alt; ?>"
                     loading="lazy"
                     decoding="async"
                     width="<?php echo $img_w; ?>"
                     height="<?php echo $img_h; ?>">
                <?php endforeach; endforeach; endif; ?>
            </div>
        </div>
    </div>

    <!-- Scroll cue -->
    <div class="scroll-cue" aria-hidden="true">
        <div class="scroll-cue-arrow">
            <div class="scroll-cue-dot"></div>
        </div>
        <span>Scroll</span>
    </div>
</section>

<!-- ============================================================
     SERVICES STRIP
     ============================================================ -->
<div style="background:var(--apex-black);border-top:1px solid rgba(245,131,31,0.15);border-bottom:1px solid rgba(245,131,31,0.15);">
    <div class="container">
        <div style="display:flex;flex-wrap:wrap;gap:0;justify-content:space-around;padding:20px 0;">
            <?php
            $strips = [
                ['icon'=>'⚡','label'=>'Laser Engraving'],
                ['icon'=>'🔥','label'=>'Cerakote Application'],
                ['icon'=>'🎨','label'=>'Graphics Design'],
                ['icon'=>'✦','label'=>'Custom Finishes'],
            ];
            foreach ($strips as $s): ?>
            <div style="display:flex;align-items:center;gap:10px;padding:8px 20px;color:rgba(255,255,255,0.65);font-family:'Barlow Condensed',sans-serif;font-size:0.9rem;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;">
                <span aria-hidden="true"><?php echo $s['icon']; ?></span>
                <span><?php echo $s['label']; ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- ============================================================
     FEATURED PRODUCTS
     ============================================================ -->
<section class="services-section section-pad" id="products">
    <div class="container">
        <div class="text-center reveal">
            <span class="eyebrow"><?php echo esc_html( apex_opt( 'products_eyebrow', 'What We Offer' ) ); ?></span>
            <h2 class="section-title"><?php echo esc_html( apex_opt( 'products_heading', 'Products & Services' ) ); ?></h2>
            <p class="section-subtitle"><?php echo esc_html( apex_opt( 'products_subtitle', 'Durable Cerakote finishes and precision laser engraving for gear that works as hard as you do.' ) ); ?></p>
        </div>

        <?php
        $products_query = apex_get_products_query(6);

        if ( $products_query->have_posts() ):
        ?>
        <div class="services-grid">
            <?php while ( $products_query->have_posts() ) : $products_query->the_post();
                $pid       = get_the_ID();
                $price     = get_post_meta($pid, '_apex_price', true);
                $material  = get_post_meta($pid, '_apex_material', true);
                $in_stock  = get_post_meta($pid, '_apex_in_stock', true);
                $sku       = get_post_meta($pid, '_apex_sku', true);
                $has_thumb = has_post_thumbnail();
                $link_url  = get_post_meta($pid, '_apex_link_url', true);
                $card_url  = $link_url ? home_url($link_url) : get_permalink($pid);
                $is_linkout = !empty($link_url);
            ?>
            <div class="service-card reveal" data-product-id="<?php the_ID(); ?>">
                <div class="card-top-bar"></div>

                <?php if ($has_thumb): ?>
                <div style="aspect-ratio:16/10;overflow:hidden;background:var(--apex-black);">
                    <?php the_post_thumbnail('full', [
                        'style' => 'width:100%;height:100%;object-fit:cover;opacity:0.92;transition:opacity 0.3s,transform 0.4s;',
                        'class' => 'product-thumb-img',
                    ]); ?>
                </div>
                <?php endif; ?>

                <div class="card-body">
                    <?php if (!$has_thumb): ?>
                    <div class="card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                    </div>
                    <?php endif; ?>

                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:8px;">
                        <h3 class="card-name"><?php the_title(); ?></h3>
                        <?php if ($in_stock === '1'): ?>
                        <span style="flex-shrink:0;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#38a169;background:rgba(56,161,105,0.1);border:1px solid rgba(56,161,105,0.3);padding:3px 10px;border-radius:50px;">In Stock</span>
                        <?php else: ?>
                        <span style="flex-shrink:0;font-size:11px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:#e53e3e;background:rgba(229,62,62,0.08);border:1px solid rgba(229,62,62,0.2);padding:3px 10px;border-radius:50px;">Quote Only</span>
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

                    <?php if ($is_linkout): ?>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:auto;">
                        <a href="<?php echo esc_url($card_url); ?>" class="btn btn-outline-dark btn-sm" style="justify-content:center;">
                            View Designs
                        </a>
                        <a href="<?php echo esc_url($card_url); ?>" class="btn btn-primary btn-sm" style="justify-content:center;">
                            Order Now
                        </a>
                    </div>
                    <?php else: ?>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:auto;">
                        <a href="<?php echo esc_url( get_permalink($pid) ); ?>" class="btn btn-outline-dark btn-sm" style="justify-content:center;">View Details</a>
                        <a href="<?php echo esc_url( home_url('/contact') ); ?>" class="btn btn-primary btn-sm" style="justify-content:center;">Request Quote</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>

        <?php else: // No products yet — show default service cards ?>
        <div class="services-grid">
            <?php
            $defaults = [
                [
                    'name'     => 'Custom Pistol Slide Engraving',
                    'desc'     => 'Precision engraving on pistol slides. Every design is unique — quoted per project. Starting at $75.',
                    'price'    => '75',
                    'features' => ['Custom artwork & logos', 'Deep-cut laser precision', 'Quoted per project'],
                    'img'      => 'apex-1911-floral-scroll-engraving-03-full.webp',
                ],
                [
                    'name'     => 'Cerakote Finishing',
                    'desc'     => 'Industry-leading ceramic polymer coating for firearms and blades. Corrosion-resistant, abrasion-resistant, and built to outlast anything else on the market.',
                    'price'    => '75',
                    'features' => ['Single-color slides: $100–$200', 'Multi-color & themes priced by project', 'Corrosion & UV resistant'],
                    'img'      => 'apex-ar-mag-eagle-american-flag-01-set.webp',
                ],
                [
                    'name'     => 'Laser Engraving',
                    'desc'     => 'High-precision laser etching for custom branding, serial numbers, decorative patterns, and personalized gifts on any metal surface.',
                    'price'    => '149',
                    'features' => ['Sub-millimeter precision', 'Photos & vector artwork', 'Knives, plaques, metals'],
                    'img'      => 'apex-ar-mag-grim-reaper-skeleton-01.webp',
                ],
                [
                    'name'     => 'Cerakote — Slide Finishing',
                    'desc'     => 'Single-color Cerakote on pistol slides starting at $100. Multiple colors, patterns, and custom themes are quoted per project.',
                    'price'    => '100',
                    'features' => ['Single color: $100–$200', 'Multi-color & themes: quoted per job', 'H-series & Elite series available'],
                    'img'      => 'apex-ar-mag-punisher-thin-blue-line-01.webp',
                ],
                [
                    'name'     => 'Custom Knife Engraving',
                    'desc'     => 'Custom laser engraving on knives and blades. Personalized gifts, tactical markings, or full custom artwork — quoted per project.',
                    'price'    => '30',
                    'features' => ['Custom artwork & text', 'Any blade type', 'Quoted per project'],
                    'img'      => 'apex-knife-floral-scroll-engraving-01.webp',
                ],
            ];
            $prod_img_uri = get_template_directory_uri() . '/assets/images/products/';
            $prod_img_dir = get_template_directory() . '/assets/images/products/';
            foreach ($defaults as $svc):
                $img_file = $prod_img_dir . $svc['img'];
                $has_img  = file_exists($img_file);
            ?>
            <div class="service-card reveal">
                <div class="card-top-bar"></div>
                <?php if ($has_img): ?>
                <div style="aspect-ratio:16/10;overflow:hidden;background:var(--apex-black);">
                    <img src="<?php echo esc_url($prod_img_uri . $svc['img']); ?>"
                         alt="<?php echo esc_attr($svc['name']); ?>"
                         loading="lazy"
                         class="product-thumb-img"
                         style="width:100%;height:100%;object-fit:cover;opacity:0.9;transition:opacity 0.3s,transform 0.4s;">
                </div>
                <?php endif; ?>
                <div class="card-body">
                    <h3 class="card-name"><?php echo esc_html($svc['name']); ?></h3>
                    <p class="card-desc"><?php echo esc_html($svc['desc']); ?></p>
                    <div class="card-price">
                        <span class="price-amount">$<?php echo $svc['price']; ?></span>
                        <span class="price-label">starting at</span>
                    </div>
                    <ul class="card-features">
                        <?php foreach ($svc['features'] as $f): ?>
                        <li><?php echo esc_html($f); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:auto;">
                        <a href="<?php echo esc_url( home_url('/products') ); ?>" class="btn btn-outline-dark btn-sm" style="justify-content:center;">View Details</a>
                        <a href="<?php echo esc_url( home_url('/contact') ); ?>" class="btn btn-primary btn-sm" style="justify-content:center;">Request Quote</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="text-center" style="margin-top:48px;">
            <a href="<?php echo esc_url( home_url('/products') ); ?>" class="btn btn-outline-dark">
                View All Products
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>
    </div>
</section>

<!-- ============================================================
     CUSTOM PMAG ENGRAVING FEATURE SECTION
     ============================================================ -->
<section style="background:#111;padding:80px 0;overflow:hidden;">
    <div class="container">
        <div id="pmag-feature-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:center;">

            <!-- Image -->
            <div class="reveal" style="position:relative;border-radius:10px;overflow:hidden;box-shadow:0 24px 64px rgba(0,0,0,0.6);">
                <img src="<?php echo esc_url(content_url('/uploads/2026/03/apex-ar-mag-eagle-american-flag-01-set.webp')); ?>"
                     alt="Three custom laser-engraved AR magazines with American eagle design — Apex Coatings &amp; Engraving"
                     loading="lazy" decoding="async"
                     style="width:100%;height:100%;object-fit:cover;display:block;border-radius:10px;">
                <div style="position:absolute;top:16px;left:16px;background:var(--apex-gradient);color:#fff;font-family:'Barlow Condensed',sans-serif;font-size:0.75rem;font-weight:800;letter-spacing:0.12em;text-transform:uppercase;padding:5px 14px;border-radius:4px;">
                    Genuine Magpul PMAGs
                </div>
            </div>

            <!-- Text -->
            <div class="reveal">
                <span class="eyebrow" style="display:block;margin-bottom:14px;">Custom PMAG Engraving</span>
                <h2 class="section-title" style="color:var(--apex-white);margin-bottom:20px;"><?php echo esc_html( apex_opt( 'pmag_title', 'Your mags. Your design. Built to last.' ) ); ?></h2>
                <p style="color:rgba(255,255,255,0.6);line-height:1.8;font-size:1.05rem;margin-bottom:16px;">
                    <?php echo esc_html( apex_opt( 'pmag_body_1', 'We laser engrave directly into genuine Magpul PMAGs — no stickers, no paint, no vinyl, and nothing that will peel or fade. Every magazine is individually engraved with your choice of artwork, whether you pick from our designs or bring your own idea.' ) ); ?>
                </p>
                <p style="color:rgba(255,255,255,0.6);line-height:1.8;font-size:1.05rem;margin-bottom:32px;">
                    <?php echo esc_html( apex_opt( 'pmag_body_2', 'Eagles, skulls, patriotic themes, unit logos, or fully custom artwork — all at a flat, upfront price.' ) ); ?>
                </p>
                <ul style="list-style:none;padding:0;margin:0 0 36px;display:flex;flex-direction:column;gap:10px;">
                    <?php foreach ([
                        apex_opt( 'pmag_bullet_1', 'Genuine Magpul PMAGs only — no off-brand substitutes' ),
                        apex_opt( 'pmag_bullet_2', "Laser engraved — permanent, won't wear or fade" ),
                        apex_opt( 'pmag_bullet_3', '20+ designs to choose from or bring your own' ),
                        apex_opt( 'pmag_bullet_4', '$35 per mag — flat rate, no hidden fees' ),
                    ] as $point): ?>
                    <li style="display:flex;align-items:center;gap:10px;color:rgba(255,255,255,0.8);font-size:0.95rem;">
                        <span style="flex-shrink:0;width:22px;height:22px;background:rgba(245,131,31,0.15);border:1px solid rgba(245,131,31,0.4);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--apex-orange);font-size:13px;font-weight:700;">✓</span>
                        <?php echo esc_html($point); ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <a href="<?php echo esc_url( home_url('/pmags') ); ?>" class="btn btn-outline-dark btn-sm" style="justify-content:center;">
                        View Designs
                    </a>
                    <a href="<?php echo esc_url( home_url('/pmags') ); ?>" class="btn btn-primary btn-sm" style="justify-content:center;">
                        Order Now
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- Section Divider -->
<div class="apex-section-divider" aria-hidden="true">
    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
        <polygon points="16,2 30,16 16,30 2,16" fill="none" stroke="url(#divGrad)" stroke-width="2"/>
        <polygon points="16,8 24,16 16,24 8,16" fill="url(#divGrad2)" opacity="0.6"/>
        <defs>
            <linearGradient id="divGrad" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0%" stop-color="#FFAD00"/>
                <stop offset="100%" stop-color="#F5831F"/>
            </linearGradient>
            <linearGradient id="divGrad2" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0%" stop-color="#FFAD00"/>
                <stop offset="100%" stop-color="#F5831F"/>
            </linearGradient>
        </defs>
    </svg>
</div>

<!-- ============================================================
     PROCESS SECTION
     ============================================================ -->
<section style="background:var(--apex-black);padding:80px 0;">
    <div class="container">
        <div class="text-center reveal" style="margin-bottom:56px;">
            <span class="eyebrow"><?php echo esc_html( apex_opt( 'process_eyebrow', 'How It Works' ) ); ?></span>
            <h2 class="section-title" style="color:var(--apex-white);"><?php echo esc_html( apex_opt( 'process_heading', 'Simple Process' ) ); ?></h2>
        </div>
        <div class="process-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:32px;">
            <?php
            $steps = [
                ['num'=>'01','title'=> apex_opt('step_1_title','Choose Your Service'),   'desc'=> apex_opt('step_1_desc','Browse our products and add what you need to your cart. Not sure? Contact us for a custom quote.')],
                ['num'=>'02','title'=> apex_opt('step_2_title','Submit Your Order'),      'desc'=> apex_opt('step_2_desc','Fill out the order form with your details and any special requirements or custom artwork.')],
                ['num'=>'03','title'=> apex_opt('step_3_title','We Review & Confirm'),    'desc'=> apex_opt('step_3_desc',"We'll review your order, confirm pricing, and reach out within 1-2 business days.")],
                ['num'=>'04','title'=> apex_opt('step_4_title','Your Project Complete'),  'desc'=> apex_opt('step_4_desc',"Your order is finished to spec by our skilled craftsmen. We notify you when it's shipped or ready for pickup.")],
            ];
            foreach ($steps as $i => $step): ?>
            <div class="process-step reveal" style="text-align:center;position:relative;">
                <?php if ($i < count($steps)-1): ?>
                <div aria-hidden="true" style="display:none;"></div>
                <?php endif; ?>
                <div style="width:64px;height:64px;border:2px solid rgba(245,131,31,0.4);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;position:relative;">
                    <span style="font-family:'Barlow Condensed',sans-serif;font-size:1.2rem;font-weight:900;background:linear-gradient(135deg,#FFAD00,#F5831F);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;"><?php echo $step['num']; ?></span>
                </div>
                <h3 style="font-family:'Barlow Condensed',sans-serif;font-size:1.1rem;font-weight:800;text-transform:uppercase;letter-spacing:0.08em;color:var(--apex-white);margin-bottom:12px;"><?php echo esc_html($step['title']); ?></h3>
                <p style="font-size:0.9rem;color:rgba(255,255,255,0.5);line-height:1.7;"><?php echo esc_html($step['desc']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     PORTFOLIO / GALLERY SECTION
     ============================================================ -->
<section class="portfolio-section section-pad" id="gallery">
    <div class="container">
        <div class="text-center reveal" style="margin-bottom:48px;">
            <span class="eyebrow"><?php echo esc_html( apex_opt( 'gallery_eyebrow', 'Our Work' ) ); ?></span>
            <h2 class="section-title"><?php echo esc_html( apex_opt( 'gallery_heading', 'Recent Projects' ) ); ?></h2>
            <p class="section-subtitle"><?php echo esc_html( apex_opt( 'gallery_subtitle', 'A sample of the craftsmanship we bring to every job.' ) ); ?></p>
        </div>

        <div class="portfolio-grid reveal">
            <?php
            $prod_dir = get_template_directory() . '/assets/images/products/';
            $prod_uri = get_template_directory_uri() . '/assets/images/products/';
            $all_imgs = glob($prod_dir . '*.webp');
            // Pick 6 representative images
            $picks = [
                'apex-1911-floral-scroll-engraving-03-full.webp'      => ['Pistol Engraving',    '1911 Floral Scroll'],
                'apex-ar-rifle-dont-tread-skull-mag-01.webp'           => ['Custom Engraving',    'AR-15 Don\'t Tread On Me'],
                'apex-ar-mag-eagle-american-flag-01-set.webp'          => ['AR Magazine Art',     'Eagle & American Flag'],
                'apex-ar-mag-punisher-thin-blue-line-01.webp'          => ['Laser Engraving',     'Thin Blue Line Punisher'],
                'apex-ar-mag-skull-spider-01.webp'                     => ['Custom Artwork',      'Skull & Spider Design'],
                'apex-1911-custom-wood-grips-01-full.webp'             => ['Custom 1911 Grips',   'Engraved Wood Grips'],
            ];
            foreach ($picks as $file => $meta):
                $img_path = $prod_dir . $file;
                if (!file_exists($img_path)) continue;
            ?>
            <div class="portfolio-item">
                <img src="<?php echo esc_url($prod_uri . $file); ?>"
                     alt="<?php echo esc_attr($meta[1]); ?> — Apex Coatings &amp; Engraving"
                     loading="lazy"
                     style="width:100%;height:100%;object-fit:cover;display:block;transition:transform 0.5s ease;">
                <div class="portfolio-overlay">
                    <span class="portfolio-cat"><?php echo esc_html($meta[0]); ?></span>
                    <span class="portfolio-name"><?php echo esc_html($meta[1]); ?></span>
                </div>
            </div>
            <?php endforeach;
            // If any picks are missing, fill with remaining images
            $shown = count($picks);
            if ($shown < 6 && $all_imgs):
                foreach ($all_imgs as $img):
                    if ($shown >= 6) break;
                    $fn = basename($img);
                    if (isset($picks[$fn])) continue;
                    $label = ucwords(str_replace(['-','_'],' ', preg_replace('/^apex-|-\d+[a-z-]*$/', '', pathinfo($fn, PATHINFO_FILENAME))));
            ?>
            <div class="portfolio-item">
                <img src="<?php echo esc_url($prod_uri . $fn); ?>"
                     alt="<?php echo esc_attr(trim($label)); ?> — Apex Engraving"
                     loading="lazy"
                     style="width:100%;height:100%;object-fit:cover;display:block;transition:transform 0.5s ease;">
                <div class="portfolio-overlay">
                    <span class="portfolio-cat">Custom Work</span>
                    <span class="portfolio-name"><?php echo esc_html(trim($label)); ?></span>
                </div>
            </div>
            <?php $shown++; endforeach; endif; ?>
        </div>

        <div class="text-center" style="margin-top:40px;">
            <a href="<?php echo esc_url( home_url('/gallery') ); ?>" class="btn btn-outline-dark">
                Full Gallery
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>
    </div>
</section>

<!-- ============================================================
     WHY CHOOSE APEX
     ============================================================ -->
<section class="why-section section-pad" id="why">
    <div class="container">
        <div class="text-center reveal" style="margin-bottom:56px;">
            <span class="eyebrow"><?php echo esc_html( apex_opt( 'why_eyebrow', 'Why Apex' ) ); ?></span>
            <h2 class="section-title" style="color:var(--apex-white);"><?php echo esc_html( apex_opt( 'why_heading', 'Built Different' ) ); ?></h2>
        </div>

        <div class="why-grid">
            <?php
            $whys = [
                [
                    'title' => apex_opt( 'why_1_title', 'Coatings Specialists' ),
                    'desc'  => apex_opt( 'why_1_desc',  'We understand the unique requirements for specialty coatings. We offer Cerakote, powder coat, and polished finishes.' ),
                    'icon'  => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>',
                ],
                [
                    'title' => apex_opt( 'why_2_title', 'Precision Every Time' ),
                    'desc'  => apex_opt( 'why_2_desc',  "State-of-the-art laser and engraving equipment. Sub-millimeter accuracy on every job, whether it's one piece or a hundred." ),
                    'icon'  => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
                ],
                [
                    'title' => apex_opt( 'why_3_title', 'Fast Turnaround' ),
                    'desc'  => apex_opt( 'why_3_desc',  'We respect your time. Rush options available. Most standard jobs turned around in 3-5 business days without compromising quality.' ),
                    'icon'  => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
                ],
                [
                    'title' => apex_opt( 'why_4_title', 'Quality Guaranteed' ),
                    'desc'  => apex_opt( 'why_4_desc',  "At APEX, we set the standard for others to follow. Sub-par work will never leave our shop. If your project isn't right, you don't pay. If it can't be fixed, we replace it. No exceptions, no excuses — just accountability and craftsmanship." ),
                    'icon'  => '<polyline points="20 6 9 17 4 12"/>',
                ],
            ];
            foreach ($whys as $w): ?>
            <div class="why-card reveal">
                <div class="why-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><?php echo $w['icon']; ?></svg>
                </div>
                <h3><?php echo esc_html($w['title']); ?></h3>
                <p><?php echo esc_html($w['desc']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     TESTIMONIALS
     ============================================================ -->
<section style="background:var(--apex-off-white);padding:80px 0;">
    <div class="container">
        <div class="text-center reveal" style="margin-bottom:48px;">
            <span class="eyebrow"><?php echo esc_html( apex_opt( 'reviews_eyebrow', 'What Our Clients Say' ) ); ?></span>
            <h2 class="section-title"><?php echo esc_html( apex_opt( 'reviews_heading', "What They're Saying" ) ); ?></h2>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px;">
            <?php
            $reviews = [
                ['stars'=>5,'text'=> apex_opt('review_1_text','Apex engraved my custom 1911 and the work was flawless. Sharp, clean, exactly what I described. Will absolutely use them again.'),'name'=> apex_opt('review_1_name','Mike T.'),'role'=> apex_opt('review_1_role','Firearms Collector')],
                ['stars'=>5,'text'=> apex_opt('review_2_text','Got my AR lower Cerakoted in OD green. Perfect coverage, no runs, matched the color swatch exactly. Solid work at a fair price.'),'name'=> apex_opt('review_2_name','Jake R.'),'role'=> apex_opt('review_2_role','Competition Shooter')],
                ['stars'=>5,'text'=> apex_opt('review_3_text','Custom engraving on my hunting rifle stock — turned out better than I imagined. These guys take their craft seriously.'),'name'=> apex_opt('review_3_name','Derek S.'),'role'=> apex_opt('review_3_role','Avid Hunter')],
            ];
            foreach ($reviews as $r): ?>
            <div class="reveal" style="background:var(--apex-white);border-radius:8px;padding:32px;box-shadow:var(--shadow-card);border-top:3px solid var(--apex-orange);">
                <div style="display:flex;gap:4px;margin-bottom:16px;" aria-label="<?php echo $r['stars']; ?> out of 5 stars">
                    <?php for($s=0;$s<5;$s++): ?>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="<?php echo $s<$r['stars']?'#FFAD00':'#E0E0E0'; ?>" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <?php endfor; ?>
                </div>
                <p style="font-size:0.95rem;color:var(--apex-gray-dark);line-height:1.75;margin-bottom:20px;font-style:italic;">"<?php echo esc_html($r['text']); ?>"</p>
                <div>
                    <strong style="font-family:'Barlow Condensed',sans-serif;font-weight:800;text-transform:uppercase;letter-spacing:0.08em;"><?php echo esc_html($r['name']); ?></strong>
                    <span style="display:block;font-size:0.8rem;color:var(--apex-gray-mid);margin-top:2px;"><?php echo esc_html($r['role']); ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     CTA SECTION
     ============================================================ -->
<section class="cta-section section-pad">
    <div class="container cta-inner reveal">
        <h2><?php echo esc_html( apex_opt( 'cta_title', 'Ready to Start Your Project?' ) ); ?></h2>
        <p><?php echo esc_html( apex_opt( 'cta_body', "Add items to your cart and submit your order. We'll review it and be in touch within 1-2 business days — no payment required at this time." ) ); ?></p>
        <div class="cta-actions">
            <a href="<?php echo esc_url( home_url('/products') ); ?>" class="btn btn-primary">
                Shop Now
            </a>
            <a href="<?php echo esc_url( home_url('/contact') ); ?>" class="btn btn-outline">
                Request A Quote
            </a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
