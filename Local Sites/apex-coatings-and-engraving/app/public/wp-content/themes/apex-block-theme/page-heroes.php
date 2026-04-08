<?php
/**
 * Template Name: Heroes Discount Page
 * Heroes — Veterans, First Responders & Emergency Services discount page
 */
get_header();
?>

<!-- ============================================================
     HEROES PAGE HERO
     ============================================================ -->
<section style="position:relative;background:var(--apex-dark);padding:160px 0 80px;overflow:hidden;">
    <div style="position:absolute;inset:0;background:
        radial-gradient(ellipse 70% 80% at 50% 50%, rgba(245,131,31,0.07) 0%, transparent 70%);
        pointer-events:none;"></div>

    <div class="container" style="position:relative;z-index:2;text-align:center;">
        <div style="font-size:5rem;margin-bottom:24px;animation:logoPulseGlow 4s ease-in-out infinite;" aria-hidden="true">🎖️</div>

        <span class="eyebrow" style="font-size:13px;letter-spacing:0.25em;"><?php echo esc_html( apex_opt( 'heroes_eyebrow', 'Honoring Those Who Serve' ) ); ?></span>

        <h1 style="font-family:var(--font-heading);font-size:clamp(3rem,7vw,5.5rem);font-weight:900;text-transform:uppercase;color:var(--apex-white);line-height:1.0;margin:16px 0 24px;">
            Heroes<br>
            <span style="background:var(--apex-gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Discount</span><br>
            Program
        </h1>

        <p style="font-size:1.2rem;color:rgba(255,255,255,0.7);max-width:600px;margin:0 auto 40px;line-height:1.75;">
            <?php echo esc_html( apex_opt( 'heroes_desc', 'At Apex Coatings & Engraving, we proudly honor the sacrifice and service of those who protect and serve our communities. That dedication deserves recognition.' ) ); ?>
        </p>

        <div style="display:inline-flex;align-items:center;gap:20px;background:rgba(245,131,31,0.12);border:2px solid rgba(245,131,31,0.5);border-radius:12px;padding:24px 48px;">
            <span style="font-family:var(--font-heading);font-size:clamp(3rem,6vw,4.5rem);font-weight:900;background:var(--apex-gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;line-height:1;"><?php echo esc_html( apex_opt( 'heroes_discount', '10%' ) ); ?></span>
            <div style="text-align:left;">
                <div style="font-family:var(--font-heading);font-size:1.4rem;font-weight:900;text-transform:uppercase;color:var(--apex-white);letter-spacing:0.06em;">OFF</div>
                <div style="font-size:0.8rem;letter-spacing:0.15em;text-transform:uppercase;color:rgba(255,255,255,0.55);">Your Entire Order</div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     WHO QUALIFIES
     ============================================================ -->
<section style="background:var(--apex-off-white);padding:80px 0;">
    <div class="container">
        <div class="text-center reveal" style="margin-bottom:56px;">
            <span class="eyebrow"><?php echo esc_html( apex_opt( 'heroes_qualify_eyebrow', 'Who Qualifies' ) ); ?></span>
            <h2 class="section-title"><?php echo esc_html( apex_opt( 'heroes_qualify_h2', 'Service Members & First Responders' ) ); ?></h2>
            <p class="section-subtitle"><?php echo esc_html( apex_opt( 'heroes_qualify_subtitle', 'If you serve your community — this discount is for you.' ) ); ?></p>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:28px;">
            <?php
            $groups = [
                ['icon'=>'🇺🇸','title'=> apex_opt('heroes_group_1_title','Active Military'),     'desc'=> apex_opt('heroes_group_1_desc','All active duty branches — Army, Navy, Air Force, Marines, Coast Guard, Space Force. Currently serving our country.')],
                ['icon'=>'🎖️','title'=> apex_opt('heroes_group_2_title','Veterans'),              'desc'=> apex_opt('heroes_group_2_desc','Honorably discharged veterans of any branch of the United States Armed Forces. Your service is never forgotten.')],
                ['icon'=>'🚒','title'=> apex_opt('heroes_group_3_title','Firefighters'),           'desc'=> apex_opt('heroes_group_3_desc','Career and volunteer firefighters. The men and women who run toward danger so others can run away.')],
                ['icon'=>'🚔','title'=> apex_opt('heroes_group_4_title','Law Enforcement'),        'desc'=> apex_opt('heroes_group_4_desc','Police officers, sheriffs, deputies, state troopers, and federal law enforcement keeping our communities safe.')],
                ['icon'=>'🚑','title'=> apex_opt('heroes_group_5_title','Emergency Medical'),      'desc'=> apex_opt('heroes_group_5_desc','EMTs, paramedics, and emergency medical responders. First on scene when every second counts.')],
                ['icon'=>'🏥','title'=> apex_opt('heroes_group_6_title','Healthcare Heroes'),      'desc'=> apex_opt('heroes_group_6_desc','Nurses, doctors, and frontline healthcare workers. Dedicated to healing and saving lives every single day.')],
            ];
            foreach ($groups as $g): ?>
            <div class="reveal" style="background:var(--apex-white);border-radius:10px;padding:32px;box-shadow:var(--shadow-card);border-top:3px solid var(--apex-orange);text-align:center;">
                <div style="font-size:3rem;margin-bottom:16px;" aria-hidden="true"><?php echo $g['icon']; ?></div>
                <h3 style="font-family:var(--font-heading);font-size:1.25rem;font-weight:800;text-transform:uppercase;letter-spacing:0.06em;color:var(--apex-black);margin-bottom:12px;"><?php echo esc_html($g['title']); ?></h3>
                <p style="font-size:0.9rem;color:var(--apex-gray-dark);line-height:1.7;"><?php echo esc_html($g['desc']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     HOW TO CLAIM
     ============================================================ -->
<section style="background:var(--apex-black);padding:80px 0;">
    <div class="container">
        <div class="text-center reveal" style="margin-bottom:56px;">
            <span class="eyebrow"><?php echo esc_html( apex_opt( 'heroes_howto_eyebrow', 'How To Claim' ) ); ?></span>
            <h2 class="section-title" style="color:var(--apex-white);"><?php echo esc_html( apex_opt( 'heroes_howto_h2', 'Simple Process' ) ); ?></h2>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:32px;max-width:900px;margin:0 auto;">
            <?php
            $steps = [
                ['num'=>'01','title'=> apex_opt('heroes_step_1_title','Browse & Order'),          'desc'=> apex_opt('heroes_step_1_desc','Choose your products or services and submit your order form on our website.')],
                ['num'=>'02','title'=> apex_opt('heroes_step_2_title','Mention Your Service'),    'desc'=> apex_opt('heroes_step_2_desc','In the order notes or contact form, let us know your branch, department, or role.')],
                ['num'=>'03','title'=> apex_opt('heroes_step_3_title','Provide Verification'),    'desc'=> apex_opt('heroes_step_3_desc','Bring or email a valid ID, badge, or military/VA card at pickup or with your request.')],
                ['num'=>'04','title'=> apex_opt('heroes_step_4_title','10% Discount Applied'),    'desc'=> apex_opt('heroes_step_4_desc','We apply your 10% heroes discount to the final invoice. No codes needed.')],
            ];
            foreach ($steps as $step): ?>
            <div class="reveal" style="text-align:center;">
                <div style="width:64px;height:64px;border:2px solid rgba(245,131,31,0.4);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                    <span style="font-family:var(--font-heading);font-size:1.2rem;font-weight:900;background:var(--apex-gradient);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;"><?php echo $step['num']; ?></span>
                </div>
                <h3 style="font-family:var(--font-heading);font-size:1.05rem;font-weight:800;text-transform:uppercase;letter-spacing:0.08em;color:var(--apex-white);margin-bottom:10px;"><?php echo esc_html($step['title']); ?></h3>
                <p style="font-size:0.88rem;color:rgba(255,255,255,0.5);line-height:1.75;"><?php echo esc_html($step['desc']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================
     WORK SAMPLES GALLERY
     ============================================================ -->
<section style="background:var(--apex-off-white);padding:80px 0;">
    <div class="container">
        <div class="text-center reveal" style="margin-bottom:40px;">
            <span class="eyebrow"><?php echo esc_html( apex_opt( 'heroes_gallery_eyebrow', 'Our Work' ) ); ?></span>
            <h2 class="section-title"><?php echo esc_html( apex_opt( 'heroes_gallery_h2', 'Crafted for Those Who Carry' ) ); ?></h2>
            <p class="section-subtitle"><?php echo esc_html( apex_opt( 'heroes_gallery_subtitle', 'Custom engraving and Cerakote finishing — including military and first responder themed designs.' ) ); ?></p>
        </div>

        <?php
        $prod_uri = get_template_directory_uri() . '/assets/images/products/';
        $prod_dir = get_template_directory() . '/assets/images/products/';
        $all_imgs = glob($prod_dir . '*.webp');
        if ($all_imgs):
            $priority = [
                'apex-ar-mag-officer-badge-bethesda-01.webp',
                'apex-ar-mag-patrolman-michigan-city-badge-01.webp',
                'apex-ar-mag-patrolman-michigan-city-badge-02.webp',
                'apex-ar-mag-sheriff-montgomery-county-badge-01.webp',
                'apex-ar-mag-fire-department-badge-01.webp',
                'apex-ar-mag-dont-tread-on-me-snake-01.webp',
                'apex-ar-mag-fafo-eagle-pair-01.webp',
            ];
            $show = [];
            foreach ($priority as $p) {
                if (file_exists($prod_dir . $p)) $show[] = $p;
            }
            foreach ($all_imgs as $img) {
                if (count($show) >= 8) break;
                $fn = basename($img);
                if (!in_array($fn, $show)) $show[] = $fn;
            }
        ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;">
            <?php foreach (array_slice($show, 0, 8) as $fn):
                $label = ucwords(str_replace(['-','_'],' ', preg_replace('/^apex-|-\d+[a-z-]*$/','',$fn)));
            ?>
            <div class="reveal" style="border-radius:8px;overflow:hidden;aspect-ratio:1;box-shadow:var(--shadow-card);">
                <img src="<?php echo esc_url($prod_uri . $fn); ?>"
                     alt="<?php echo esc_attr(trim($label)); ?>"
                     class="heroes-gallery-img"
                     loading="lazy"
                     decoding="async">
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- ============================================================
     CTA
     ============================================================ -->
<section class="cta-section section-pad">
    <div class="container cta-inner reveal">
        <h2>Ready to Place Your Order?</h2>
        <p>Mention your service in the order notes and we'll apply your 10% heroes discount automatically. Thank you for everything you do.</p>
        <p style="font-size:0.95rem;opacity:0.75;margin-top:-8px;">Browse our <a href="<?php echo esc_url( home_url('/pmags') ); ?>" style="color:var(--apex-orange);">custom PMAG designs</a> and <a href="<?php echo esc_url( home_url('/1911-grips') ); ?>" style="color:var(--apex-orange);">1911 grip panels</a> — both eligible for your heroes discount.</p>
        <div class="cta-actions">
            <a href="<?php echo esc_url( home_url('/products') ); ?>" class="btn btn-primary">Shop Now</a>
            <a href="<?php echo esc_url( home_url('/contact') ); ?>" class="btn btn-outline">Contact Us</a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
