<?php
/**
 * Title: Testimonials
 * Slug: apex/testimonials
 * Categories: apex
 * Description: Three customer review cards with star ratings on off-white background.
 */

$eyebrow = function_exists('apex_opt') ? apex_opt('reviews_eyebrow', 'What Our Clients Say') : 'What Our Clients Say';
$heading = function_exists('apex_opt') ? apex_opt('reviews_heading', "What They're Saying")  : "What They're Saying";
$reviews = [
    ['stars'=>5,'text'=>function_exists('apex_opt')?apex_opt('review_1_text','Apex engraved my custom 1911 and the work was flawless. Sharp, clean, exactly what I described. Will absolutely use them again.'):'Apex engraved my custom 1911 and the work was flawless. Sharp, clean, exactly what I described. Will absolutely use them again.','name'=>function_exists('apex_opt')?apex_opt('review_1_name','Mike T.'):'Mike T.','role'=>function_exists('apex_opt')?apex_opt('review_1_role','Firearms Collector'):'Firearms Collector'],
    ['stars'=>5,'text'=>function_exists('apex_opt')?apex_opt('review_2_text','Got my AR lower Cerakoted in OD green. Perfect coverage, no runs, matched the color swatch exactly. Solid work at a fair price.'):'Got my AR lower Cerakoted in OD green. Perfect coverage, no runs, matched the color swatch exactly. Solid work at a fair price.','name'=>function_exists('apex_opt')?apex_opt('review_2_name','Jake R.'):'Jake R.','role'=>function_exists('apex_opt')?apex_opt('review_2_role','Competition Shooter'):'Competition Shooter'],
    ['stars'=>5,'text'=>function_exists('apex_opt')?apex_opt('review_3_text','Custom engraving on my hunting rifle stock — turned out better than I imagined. These guys take their craft seriously.'):'Custom engraving on my hunting rifle stock — turned out better than I imagined. These guys take their craft seriously.','name'=>function_exists('apex_opt')?apex_opt('review_3_name','Derek S.'):'Derek S.','role'=>function_exists('apex_opt')?apex_opt('review_3_role','Avid Hunter'):'Avid Hunter'],
];
?>
<!-- wp:html -->
<section style="background:var(--apex-off-white);padding:80px 0;">
    <div class="container">
        <div class="text-center reveal" style="margin-bottom:48px;">
            <span class="eyebrow"><?php echo esc_html($eyebrow); ?></span>
            <h2 class="section-title"><?php echo esc_html($heading); ?></h2>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px;">
            <?php foreach ($reviews as $r): ?>
            <div class="reveal" style="background:var(--apex-white);border-radius:8px;padding:32px;box-shadow:var(--shadow-card);border-top:3px solid var(--apex-orange);">
                <div style="display:flex;gap:4px;margin-bottom:16px;" aria-label="<?php echo $r['stars']; ?> out of 5 stars">
                    <?php for ($s=0;$s<5;$s++): ?>
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
<!-- /wp:html -->
