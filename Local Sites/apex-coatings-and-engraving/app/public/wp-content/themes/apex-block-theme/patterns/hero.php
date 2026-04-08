<?php
/**
 * Title: Hero Section
 * Slug: apex/hero
 * Categories: apex
 * Description: Full-viewport hero with logo, title, CTA buttons, stats, and animated slideshow.
 */

$hero_title   = function_exists('apex_opt') ? apex_opt('hero_title',   'Custom Coatings & Engraving') : 'Custom Coatings & Engraving';
$hero_tagline = function_exists('apex_opt') ? apex_opt('hero_tagline', 'Precision, laser engraving, Cerakote application, and guaranteed quality. Craftsmanship you can trust & products that last.') : 'Precision, laser engraving, Cerakote application, and guaranteed quality.';
$stat_1_num   = function_exists('apex_opt') ? apex_opt('stat_1_num',   '15+') : '15+';
$stat_1_label = function_exists('apex_opt') ? apex_opt('stat_1_label', 'Years Experience') : 'Years Experience';
$stat_2_num   = function_exists('apex_opt') ? apex_opt('stat_2_num',   '2K+') : '2K+';
$stat_2_label = function_exists('apex_opt') ? apex_opt('stat_2_label', 'Jobs Completed') : 'Jobs Completed';
$stat_3_num   = function_exists('apex_opt') ? apex_opt('stat_3_num',   '100%') : '100%';
$stat_3_label = function_exists('apex_opt') ? apex_opt('stat_3_label', 'Satisfaction') : 'Satisfaction';

$parts  = explode( '&', $hero_title, 2 );
$title_html = count($parts) === 2
    ? esc_html(trim($parts[0])) . ' <span class="accent">&amp;</span><br>' . esc_html(trim($parts[1]))
    : esc_html($hero_title);

$slide_dir = get_template_directory() . '/assets/images/slideshow/';
$slide_uri = get_template_directory_uri() . '/assets/images/slideshow/';
$slides    = file_exists($slide_dir) ? (glob($slide_dir . '*.webp') ?: []) : [];
?>
<!-- wp:html -->
<section class="hero" id="home">
    <div class="hero-bg"><div class="hero-grid"></div></div>

    <div class="container hero-content">
        <div class="hero-text reveal">
            <div class="hero-logo-wrap">
                <div class="hero-logo-glow"></div>
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/apex-logo-hero.webp'); ?>"
                     alt="Apex Coatings &amp; Engraving" class="hero-logo-img"
                     loading="eager" fetchpriority="high" decoding="sync">
            </div>
            <div class="hero-title-wrap">
                <h1 class="hero-title"><?php echo $title_html; ?></h1>
            </div>
            <p class="hero-desc"><?php echo esc_html($hero_tagline); ?></p>
            <div class="hero-actions">
                <a href="<?php echo esc_url(home_url('/products')); ?>" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 12V22H4V12"/><path d="M22 7H2v5h20V7z"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
                    Browse Products
                </a>
                <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-outline">Request A Quote</a>
            </div>
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-num"><?php echo esc_html($stat_1_num); ?></div>
                    <div class="stat-label"><?php echo esc_html($stat_1_label); ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-num"><?php echo esc_html($stat_2_num); ?></div>
                    <div class="stat-label"><?php echo esc_html($stat_2_label); ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-num"><?php echo esc_html($stat_3_num); ?></div>
                    <div class="stat-label"><?php echo esc_html($stat_3_label); ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="scroll-indicator" id="scroll-indicator" aria-hidden="true">
        <span>Scroll</span>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
    </div>

    <div class="hero-visual">
        <div class="hero-slideshow-wrap" aria-label="Gallery of engraving work">
            <div class="hero-slideshow-track" id="hero-slideshow-track">
                <?php if ($slides):
                    foreach ([$slides, $slides] as $set):
                        foreach ($set as $slide):
                            $name  = basename($slide, '.webp');
                            $label = ucwords(str_replace(['-','_'], ' ', preg_replace('/^apex-|-\d+$/', '', $name)));
                ?>
                <img src="<?php echo esc_url($slide_uri . basename($slide)); ?>"
                     alt="<?php echo esc_attr(trim($label)); ?> — Apex Engraving"
                     loading="lazy" decoding="async" width="1400" height="800">
                <?php endforeach; endforeach; endif; ?>
            </div>
        </div>
    </div>

    <div class="scroll-cue" aria-hidden="true">
        <div class="scroll-cue-arrow"><div class="scroll-cue-dot"></div></div>
        <span>Scroll</span>
    </div>
</section>
<!-- /wp:html -->
