<?php
/**
 * Template Name: Services Page
 * Auto-used for page with slug: services
 */
get_header();
?>

<!-- Page Hero -->
<div style="background:var(--apex-black);padding:140px 0 60px;">
    <div class="container">
        <span class="eyebrow" style="display:block;margin-bottom:12px;"><?php echo esc_html( apex_opt( 'svc_page_eyebrow', 'What We Do' ) ); ?></span>
        <h1 class="section-title" style="color:var(--apex-white);"><?php echo esc_html( apex_opt( 'svc_page_h1', 'Our Services' ) ); ?></h1>
        <p style="color:rgba(255,255,255,0.55);max-width:580px;line-height:1.7;margin-top:16px;"><?php echo esc_html( apex_opt( 'svc_page_desc', 'Where craftsmanship, precision engraving, and Cerakote all come together.' ) ); ?></p>
    </div>
</div>

<!-- Services Grid -->
<section style="background:var(--apex-off-white);padding:64px 0 80px;">
    <div class="container">
        <div class="services-grid">
            <?php
            $svc_icons = [
                1 => '<path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>',
                2 => '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/><line x1="4.93" y1="4.93" x2="9.17" y2="9.17"/><line x1="14.83" y1="14.83" x2="19.07" y2="19.07"/><line x1="14.83" y1="9.17" x2="19.07" y2="4.93"/><line x1="4.93" y1="19.07" x2="9.17" y2="14.83"/>',
                3 => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
                4 => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>',
                5 => '<circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/>',
                6 => '<polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/>',
            ];
            $svc_defaults = [
                1 => ['Engraving',              'Custom laser engraving on pistol slides, AR mags, and more. Skulls, florals, patriotic themes, or anything you can dream up. Browse our <a href="' . home_url('/pmags') . '" style="color:var(--apex-orange);">custom PMAG engraving catalog</a> to see 28+ available designs.',                              "Pistol slides\nAR-15 / AR-10 mags\nRevolvers, shotguns & rifles\nCustom artwork & logos"],
                2 => ['Cerakoting',             'Precision Cerakote application on pistol slides. Single-color finishes start at $100. Multi-color patterns and custom designs are quoted per project. See our <a href="' . home_url('/1911-grips') . '" style="color:var(--apex-orange);">HEX Series 1911 Grip Panels</a> for a finished example of Cerakote on aluminum.',      "Single color: Starting at \$100\nMulti-color & themes: quoted per job\nC-series, H-series, & Elite series available\nCorrosion & UV resistant"],
                3 => ['Graphics Design',        'Custom artwork designed in-house for your project. We work with your ideas or create original designs from scratch. Design fees start at $75.',               "Original design work\nLogo vectorization\nCustom artwork & logos\nFree with any bulk order"],
                4 => ['Cerakote Application',   'Industry-leading ceramic polymer coating applied to firearms, blades, tactical gear, car parts; just about anything you can think of. Corrosion-resistant, abrasion-resistant, and available in dozens of colors.', "Pistols, rifles & handguns\nKnives & blades\nMulti-color & camo patterns\nCorrosion & UV resistant"],
                5 => ['Metal Polishing',        'Professional metal polishing for firearm components, knives, and precision parts. We remove scratches, oxidation, machining marks, and surface defects to restore clarity, shine, and uniformity. Whether you want a clean satin sheen or a true mirror finish, every piece is polished with care and attention to detail.', "Ideal for slides, frames, controls, and small components\nSurface correction & refinement\nScratch, oxidation & blemish removal\nMirror, satin, or brushed finish options"],
                6 => ['Custom Gifts & Memorials','Personalized gifts for any occasion — pet memorials, retirement plaques, wedding gifts, and military tributes. Unique, custom pieces that last forever.',  "Pet memorial stones\nRetirement & wedding plaques\nMilitary & first responder tributes\nBulk orders welcome"],
            ];
            for ( $i = 1; $i <= 6; $i++ ):
                $title   = apex_opt( "svc_{$i}_title",   $svc_defaults[$i][0] );
                $desc    = apex_opt( "svc_{$i}_desc",    $svc_defaults[$i][1] );
                $bullets = array_filter( explode( "\n", apex_opt( "svc_{$i}_bullets", $svc_defaults[$i][2] ) ) );
            ?>
            <div class="service-card reveal">
                <div class="card-top-bar"></div>
                <div class="card-body">
                    <div class="card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?php echo $svc_icons[$i]; ?></svg>
                    </div>
                    <h2 class="card-name"><?php echo esc_html( $title ); ?></h2>
                    <p class="card-desc"><?php echo wp_kses( $desc, [ 'a' => [ 'href' => [], 'style' => [] ] ] ); ?></p>
                    <ul style="list-style:none;padding:0;margin:0 0 20px;display:flex;flex-direction:column;gap:8px;">
                        <?php foreach ( array_slice( $bullets, 0, 4 ) as $bullet ): ?>
                        <li style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--apex-gray-dark);">
                            <span style="color:var(--apex-orange);font-size:16px;">✓</span>
                            <?php echo esc_html( trim( $bullet ) ); ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="<?php echo esc_url( home_url('/contact') ); ?>" class="btn btn-primary btn-sm">Request A Quote</a>
                </div>
            </div>
            <?php endfor; ?>

        </div><!-- /.services-grid -->
    </div>
</section>

<!-- Process / CTA Strip -->
<section style="background:var(--apex-black);padding:64px 0;">
    <div class="container" style="text-align:center;">
        <span class="eyebrow" style="display:block;margin-bottom:12px;"><?php echo esc_html( apex_opt( 'svc_process_eyebrow', 'Simple Process' ) ); ?></span>
        <h2 class="section-title" style="color:var(--apex-white);margin-bottom:16px;"><?php echo esc_html( apex_opt( 'svc_process_heading', 'How It Works' ) ); ?></h2>
        <p style="color:rgba(255,255,255,0.55);max-width:520px;margin:0 auto 48px;line-height:1.7;"><?php echo esc_html( apex_opt( 'svc_process_desc', "Drop us a line, send in your item, and we'll handle the rest." ) ); ?></p>
        <?php
        $svc_step_defaults = [
            1 => [ '01', 'Contact Us',       'Reach out via our contact form or phone with your project details.' ],
            2 => [ '02', 'Ship or Drop Off', 'Send us your item or drop it off locally.' ],
            3 => [ '03', 'We Get to Work',   'Our team processes your order with precision and care.' ],
            4 => [ '04', 'Delivered',         "We ship it back or you pick it up — ready to show off." ],
        ];
        ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:32px;max-width:900px;margin:0 auto 48px;">
            <?php for ( $i = 1; $i <= 4; $i++ ):
                $step_title = apex_opt( "svc_step_{$i}_title", $svc_step_defaults[$i][1] );
                $step_desc  = apex_opt( "svc_step_{$i}_desc",  $svc_step_defaults[$i][2] );
            ?>
            <div style="text-align:center;">
                <div style="width:56px;height:56px;border-radius:50%;background:var(--apex-gradient);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:1.3rem;color:#fff;"><?php echo esc_html( $svc_step_defaults[$i][0] ); ?></div>
                <h3 style="font-family:'Barlow Condensed',sans-serif;font-size:1.2rem;font-weight:700;color:var(--apex-white);margin-bottom:8px;text-transform:uppercase;"><?php echo esc_html( $step_title ); ?></h3>
                <p style="font-size:13px;color:rgba(255,255,255,0.5);line-height:1.6;"><?php echo esc_html( $step_desc ); ?></p>
            </div>
            <?php endfor; ?>
        </div>
        <a href="<?php echo esc_url( home_url('/contact') ); ?>" class="btn btn-primary">Request A Quote</a>
    </div>
</section>

<?php get_footer(); ?>
