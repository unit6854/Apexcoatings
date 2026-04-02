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
        <span class="eyebrow" style="display:block;margin-bottom:12px;">What We Do</span>
        <h1 class="section-title" style="color:var(--apex-white);">Our Services</h1>
        <p style="color:rgba(255,255,255,0.55);max-width:580px;line-height:1.7;margin-top:16px;">From custom engraving to Cerakote finishing — every job is built to your specs, finished to last a lifetime.</p>
    </div>
</div>

<!-- Magazine Restriction Disclaimer -->
<div style="background:#1a0a00;border-top:3px solid #F5831F;border-bottom:1px solid rgba(245,131,31,0.25);padding:24px 0;">
    <div class="container">
        <div style="display:flex;align-items:flex-start;gap:18px;">
            <div style="flex-shrink:0;width:36px;height:36px;background:var(--apex-gradient);border-radius:50%;display:flex;align-items:center;justify-content:center;margin-top:2px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <div>
                <p style="font-family:'Barlow Condensed',sans-serif;font-size:1.05rem;font-weight:700;color:#F5831F;text-transform:uppercase;letter-spacing:0.08em;margin:0 0 6px;">⚠ Shipping Restriction — Magazine Engraving Orders</p>
                <p style="color:rgba(255,255,255,0.8);font-size:0.9rem;line-height:1.7;margin:0 0 12px;">We <strong style="color:#fff;">cannot ship magazine engraving orders</strong> to the following states and territories due to high-capacity magazine import restrictions. Customers in these areas may still request other services.</p>
                <div style="display:flex;flex-wrap:wrap;gap:6px 14px;">
                    <?php
                    $restricted = ['California','Colorado','Connecticut','Delaware','District of Columbia','Hawaii','Illinois','Maryland','Massachusetts','New Jersey','New York','Oregon','Rhode Island','Vermont','Washington','Puerto Rico'];
                    foreach ($restricted as $state): ?>
                    <span style="font-size:0.82rem;color:rgba(255,255,255,0.6);display:flex;align-items:center;gap:4px;"><span style="color:#F5831F;font-size:10px;">&#9679;</span><?php echo esc_html($state); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Services Grid -->
<section style="background:var(--apex-off-white);padding:64px 0 80px;">
    <div class="container">
        <div class="services-grid">

            <!-- Engraving -->
            <div class="service-card reveal">
                <div class="card-top-bar"></div>
                <div class="card-body">
                    <div class="card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                    </div>
                    <h2 class="card-name">Engraving</h2>
                    <p class="card-desc">Custom laser engraving on pistol slides, AR mags, and more. Skulls, florals, patriotic themes, or anything you can dream up.</p>
                    <ul style="list-style:none;padding:0;margin:0 0 20px;display:flex;flex-direction:column;gap:8px;">
                        <li style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--apex-gray-dark);"><span style="color:var(--apex-orange);font-size:16px;">✓</span> Pistol slides</li>
                        <li style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--apex-gray-dark);"><span style="color:var(--apex-orange);font-size:16px;">✓</span> AR-15 / AR-10 mags</li>
                        <li style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--apex-gray-dark);"><span style="color:var(--apex-orange);font-size:16px;">✓</span> Revolvers, shotguns &amp; rifles</li>
                        <li style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--apex-gray-dark);"><span style="color:var(--apex-orange);font-size:16px;">✓</span> Custom artwork &amp; logos</li>
                    </ul>
                    <a href="<?php echo home_url('/contact'); ?>" class="btn btn-primary btn-sm">Request A Quote</a>
                </div>
            </div>

            <!-- Cerakoting -->
            <div class="service-card reveal">
                <div class="card-top-bar"></div>
                <div class="card-body">
                    <div class="card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/><line x1="4.93" y1="4.93" x2="9.17" y2="9.17"/><line x1="14.83" y1="14.83" x2="19.07" y2="19.07"/><line x1="14.83" y1="9.17" x2="19.07" y2="4.93"/><line x1="4.93" y1="19.07" x2="9.17" y2="14.83"/></svg>
                    </div>
                    <h2 class="card-name">Cerakoting</h2>
                    <p class="card-desc">Precision Cerakote application on pistol slides. Single-color finishes start at $100–$200. Multi-color patterns, themes, and custom designs are quoted per project.</p>
                    <ul style="list-style:none;padding:0;margin:0 0 20px;display:flex;flex-direction:column;gap:8px;">
                        <li style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--apex-gray-dark);"><span style="color:var(--apex-orange);font-size:16px;">✓</span> Single color: $100–$200</li>
                        <li style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--apex-gray-dark);"><span style="color:var(--apex-orange);font-size:16px;">✓</span> Multi-color &amp; themes: quoted per job</li>
                        <li style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--apex-gray-dark);"><span style="color:var(--apex-orange);font-size:16px;">✓</span> H-series &amp; Elite series available</li>
                        <li style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--apex-gray-dark);"><span style="color:var(--apex-orange);font-size:16px;">✓</span> Corrosion &amp; UV resistant</li>
                    </ul>
                    <a href="<?php echo home_url('/contact'); ?>" class="btn btn-primary btn-sm">Request A Quote</a>
                </div>
            </div>

            <!-- Laser Engraving -->
            <div class="service-card reveal">
                <div class="card-top-bar"></div>
                <div class="card-body">
                    <div class="card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                    </div>
                    <h2 class="card-name">Laser Engraving</h2>
                    <p class="card-desc">High-precision laser engraving on wood, metal, glass, slate, stone, and more. Perfect for gifts, memorials, signage, and personalized keepsakes.</p>
                    <ul style="list-style:none;padding:0;margin:0 0 20px;display:flex;flex-direction:column;gap:8px;">
                        <li style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--apex-gray-dark);"><span style="color:var(--apex-orange);font-size:16px;">✓</span> Wood signs &amp; plaques</li>
                        <li style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--apex-gray-dark);"><span style="color:var(--apex-orange);font-size:16px;">✓</span> Slate coasters &amp; tiles</li>
                        <li style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--apex-gray-dark);"><span style="color:var(--apex-orange);font-size:16px;">✓</span> Pet memorial stones</li>
                        <li style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--apex-gray-dark);"><span style="color:var(--apex-orange);font-size:16px;">✓</span> Custom gifts &amp; awards</li>
                    </ul>
                    <a href="<?php echo home_url('/contact'); ?>" class="btn btn-primary btn-sm">Request A Quote</a>
                </div>
            </div>

            <!-- Cerakote -->
            <div class="service-card reveal">
                <div class="card-top-bar"></div>
                <div class="card-body">
                    <div class="card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    <h2 class="card-name">Cerakote Finishing</h2>
                    <p class="card-desc">Industry-leading ceramic polymer coating applied to firearms, blades, and tactical gear. Corrosion-resistant, abrasion-resistant, and available in dozens of tactical colors.</p>
                    <ul style="list-style:none;padding:0;margin:0 0 20px;display:flex;flex-direction:column;gap:8px;">
                        <li style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--apex-gray-dark);"><span style="color:var(--apex-orange);font-size:16px;">✓</span> Pistols, rifles &amp; handguns</li>
                        <li style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--apex-gray-dark);"><span style="color:var(--apex-orange);font-size:16px;">✓</span> Knives &amp; blades</li>
                        <li style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--apex-gray-dark);"><span style="color:var(--apex-orange);font-size:16px;">✓</span> Multi-color &amp; camo patterns</li>
                        <li style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--apex-gray-dark);"><span style="color:var(--apex-orange);font-size:16px;">✓</span> Corrosion &amp; UV resistant</li>
                    </ul>
                    <a href="<?php echo home_url('/contact'); ?>" class="btn btn-primary btn-sm">Request A Quote</a>
                </div>
            </div>


            <!-- Cerakote Polishing -->
            <div class="service-card reveal">
                <div class="card-top-bar"></div>
                <div class="card-body">
                    <div class="card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                    </div>
                    <h2 class="card-name">Cerakote Polishing</h2>
                    <p class="card-desc">Surface prep and polishing before or after Cerakote application for a flawless, professional finish. Removes surface imperfections, scratches, and oxidation so your coating bonds stronger and looks sharper.</p>
                    <ul style="list-style:none;padding:0;margin:0 0 20px;display:flex;flex-direction:column;gap:8px;">
                        <li style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--apex-gray-dark);"><span style="color:var(--apex-orange);font-size:16px;">✓</span> Pre-coat surface prep &amp; polishing</li>
                        <li style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--apex-gray-dark);"><span style="color:var(--apex-orange);font-size:16px;">✓</span> Scratch &amp; oxidation removal</li>
                        <li style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--apex-gray-dark);"><span style="color:var(--apex-orange);font-size:16px;">✓</span> Mirror or satin finish options</li>
                        <li style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--apex-gray-dark);"><span style="color:var(--apex-orange);font-size:16px;">✓</span> Standalone polish available</li>
                    </ul>
                    <a href="<?php echo home_url('/contact'); ?>" class="btn btn-primary btn-sm">Request A Quote</a>
                </div>
            </div>

            <!-- Custom Gifts -->
            <div class="service-card reveal">
                <div class="card-top-bar"></div>
                <div class="card-body">
                    <div class="card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                    </div>
                    <h2 class="card-name">Custom Gifts &amp; Memorials</h2>
                    <p class="card-desc">Personalized gifts for any occasion — pet memorials, retirement plaques, wedding gifts, and military tributes. Unique, custom pieces that last forever.</p>
                    <ul style="list-style:none;padding:0;margin:0 0 20px;display:flex;flex-direction:column;gap:8px;">
                        <li style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--apex-gray-dark);"><span style="color:var(--apex-orange);font-size:16px;">✓</span> Pet memorial stones</li>
                        <li style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--apex-gray-dark);"><span style="color:var(--apex-orange);font-size:16px;">✓</span> Retirement &amp; wedding plaques</li>
                        <li style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--apex-gray-dark);"><span style="color:var(--apex-orange);font-size:16px;">✓</span> Military &amp; first responder tributes</li>
                        <li style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--apex-gray-dark);"><span style="color:var(--apex-orange);font-size:16px;">✓</span> Bulk orders welcome</li>
                    </ul>
                    <a href="<?php echo home_url('/contact'); ?>" class="btn btn-primary btn-sm">Request A Quote</a>
                </div>
            </div>

        </div><!-- /.services-grid -->
    </div>
</section>

<!-- Process / CTA Strip -->
<section style="background:var(--apex-black);padding:64px 0;">
    <div class="container" style="text-align:center;">
        <span class="eyebrow" style="display:block;margin-bottom:12px;">Simple Process</span>
        <h2 class="section-title" style="color:var(--apex-white);margin-bottom:16px;">How It Works</h2>
        <p style="color:rgba(255,255,255,0.55);max-width:520px;margin:0 auto 48px;line-height:1.7;">Drop us a line, send over your item, and we'll handle the rest.</p>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:32px;max-width:900px;margin:0 auto 48px;">
            <?php
            $steps = [
                ['01','Contact Us','Reach out via our contact form or phone with your project details.'],
                ['02','Ship or Drop Off','Send us your item or drop it off locally.'],
                ['03','We Get to Work','Our team processes your order with precision and care.'],
                ['04','Delivered','We ship it back or you pick it up — ready to show off.'],
            ];
            foreach ($steps as $step): ?>
            <div style="text-align:center;">
                <div style="width:56px;height:56px;border-radius:50%;background:var(--apex-gradient);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:1.3rem;color:#fff;"><?php echo $step[0]; ?></div>
                <h3 style="font-family:'Barlow Condensed',sans-serif;font-size:1.2rem;font-weight:700;color:var(--apex-white);margin-bottom:8px;text-transform:uppercase;"><?php echo $step[1]; ?></h3>
                <p style="font-size:13px;color:rgba(255,255,255,0.5);line-height:1.6;"><?php echo $step[2]; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        <a href="<?php echo home_url('/contact'); ?>" class="btn btn-primary">Request A Quote</a>
    </div>
</section>

<?php get_footer(); ?>
