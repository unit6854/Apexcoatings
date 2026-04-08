<?php
/**
 * Title: Why Choose Apex
 * Slug: apex/why-apex
 * Categories: apex
 * Description: Four feature cards on dark background explaining Apex's differentiators.
 */

$eyebrow = function_exists('apex_opt') ? apex_opt('why_eyebrow', 'Why Apex')        : 'Why Apex';
$heading = function_exists('apex_opt') ? apex_opt('why_heading', 'Built Different') : 'Built Different';
$whys = [
    ['title'=>function_exists('apex_opt')?apex_opt('why_1_title','Coatings Specialists'):'Coatings Specialists',   'desc'=>function_exists('apex_opt')?apex_opt('why_1_desc','We understand the unique requirements for specialty coatings. We offer Cerakote, powder coat, and polished finishes.'):'We understand the unique requirements for specialty coatings.','icon'=>'<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>'],
    ['title'=>function_exists('apex_opt')?apex_opt('why_2_title','Precision Every Time'):'Precision Every Time',   'desc'=>function_exists('apex_opt')?apex_opt('why_2_desc',"State-of-the-art laser and engraving equipment. Sub-millimeter accuracy on every job, whether it's one piece or a hundred."):"State-of-the-art laser and engraving equipment. Sub-millimeter accuracy on every job.",'icon'=>'<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>'],
    ['title'=>function_exists('apex_opt')?apex_opt('why_3_title','Fast Turnaround'):'Fast Turnaround',             'desc'=>function_exists('apex_opt')?apex_opt('why_3_desc','We respect your time. Rush options available. Most standard jobs turned around in 3-5 business days without compromising quality.'):'We respect your time. Most standard jobs turned around in 3-5 business days.','icon'=>'<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
    ['title'=>function_exists('apex_opt')?apex_opt('why_4_title','Quality Guaranteed'):'Quality Guaranteed',       'desc'=>function_exists('apex_opt')?apex_opt('why_4_desc',"At APEX, we set the standard for others to follow. Sub-par work will never leave our shop. If your project isn't right, you don't pay. No exceptions, no excuses — just accountability and craftsmanship."):"At APEX, we set the standard for others to follow. If it isn't right, you don't pay.",'icon'=>'<polyline points="20 6 9 17 4 12"/>'],
];
?>
<!-- wp:html -->
<section class="why-section section-pad" id="why">
    <div class="container">
        <div class="text-center reveal" style="margin-bottom:56px;">
            <span class="eyebrow"><?php echo esc_html($eyebrow); ?></span>
            <h2 class="section-title" style="color:var(--apex-white);"><?php echo esc_html($heading); ?></h2>
        </div>
        <div class="why-grid">
            <?php foreach ($whys as $w): ?>
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
<!-- /wp:html -->
