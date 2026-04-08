<?php
/**
 * Title: Portfolio / Gallery Section
 * Slug: apex/portfolio
 * Categories: apex
 * Description: 3-column portfolio grid showcasing recent work with overlay labels.
 */

$eyebrow  = function_exists('apex_opt') ? apex_opt('gallery_eyebrow',  'Our Work')                                              : 'Our Work';
$heading  = function_exists('apex_opt') ? apex_opt('gallery_heading',  'Recent Projects')                                       : 'Recent Projects';
$subtitle = function_exists('apex_opt') ? apex_opt('gallery_subtitle', 'A sample of the craftsmanship we bring to every job.')  : 'A sample of the craftsmanship we bring to every job.';

$prod_dir = get_template_directory() . '/assets/images/products/';
$prod_uri = get_template_directory_uri() . '/assets/images/products/';
$all_imgs = file_exists($prod_dir) ? (glob($prod_dir . '*.webp') ?: []) : [];

$picks = [
    'apex-1911-floral-scroll-engraving-03-full.webp'     => ['Pistol Engraving',   '1911 Floral Scroll'],
    'apex-ar-rifle-dont-tread-skull-mag-01.webp'          => ['Custom Engraving',   "AR-15 Don't Tread On Me"],
    'apex-ar-mag-eagle-american-flag-01-set.webp'         => ['AR Magazine Art',    'Eagle & American Flag'],
    'apex-ar-mag-punisher-thin-blue-line-01.webp'         => ['Laser Engraving',    'Thin Blue Line Punisher'],
    'apex-ar-mag-skull-spider-01.webp'                    => ['Custom Artwork',     'Skull & Spider Design'],
    'apex-1911-custom-wood-grips-01-full.webp'            => ['Custom 1911 Grips',  'Engraved Wood Grips'],
];
?>
<!-- wp:html -->
<section class="portfolio-section section-pad" id="gallery">
    <div class="container">
        <div class="text-center reveal" style="margin-bottom:48px;">
            <span class="eyebrow"><?php echo esc_html($eyebrow); ?></span>
            <h2 class="section-title"><?php echo esc_html($heading); ?></h2>
            <p class="section-subtitle"><?php echo esc_html($subtitle); ?></p>
        </div>
        <div class="portfolio-grid reveal">
            <?php
            $shown = 0;
            foreach ($picks as $file => $meta):
                if (!file_exists($prod_dir . $file)) continue;
            ?>
            <div class="portfolio-item">
                <img src="<?php echo esc_url($prod_uri . $file); ?>"
                     alt="<?php echo esc_attr($meta[1]); ?> — Apex Coatings &amp; Engraving"
                     loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block;transition:transform 0.5s ease;">
                <div class="portfolio-overlay">
                    <span class="portfolio-cat"><?php echo esc_html($meta[0]); ?></span>
                    <span class="portfolio-name"><?php echo esc_html($meta[1]); ?></span>
                </div>
            </div>
            <?php $shown++; endforeach;
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
                     loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block;transition:transform 0.5s ease;">
                <div class="portfolio-overlay">
                    <span class="portfolio-cat">Custom Work</span>
                    <span class="portfolio-name"><?php echo esc_html(trim($label)); ?></span>
                </div>
            </div>
            <?php $shown++; endforeach; endif; ?>
        </div>
        <div class="text-center" style="margin-top:40px;">
            <a href="<?php echo esc_url(home_url('/gallery')); ?>" class="btn btn-outline-dark">
                Full Gallery
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </a>
        </div>
    </div>
</section>
<!-- /wp:html -->
