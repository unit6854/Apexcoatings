<?php
/**
 * Title: PMAG Feature Section
 * Slug: apex/pmag-feature
 * Categories: apex
 * Description: Dark two-column section featuring custom PMAG engraving with image and bullet list.
 */

$pmag_title   = function_exists('apex_opt') ? apex_opt('pmag_title',   'Your mags. Your design. Built to last.') : 'Your mags. Your design. Built to last.';
$pmag_body_1  = function_exists('apex_opt') ? apex_opt('pmag_body_1',  'We laser engrave directly into genuine Magpul PMAGs — no stickers, no paint, no vinyl, and nothing that will peel or fade. Every magazine is individually engraved with your choice of artwork, whether you pick from our designs or bring your own idea.') : 'We laser engrave directly into genuine Magpul PMAGs — no stickers, no paint, no vinyl, and nothing that will peel or fade.';
$pmag_body_2  = function_exists('apex_opt') ? apex_opt('pmag_body_2',  'Eagles, skulls, patriotic themes, unit logos, or fully custom artwork — all at a flat, upfront price.') : 'Eagles, skulls, patriotic themes, unit logos, or fully custom artwork — all at a flat, upfront price.';
$bullet_1     = function_exists('apex_opt') ? apex_opt('pmag_bullet_1','Genuine Magpul PMAGs only — no off-brand substitutes') : 'Genuine Magpul PMAGs only — no off-brand substitutes';
$bullet_2     = function_exists('apex_opt') ? apex_opt('pmag_bullet_2',"Laser engraved — permanent, won't wear or fade") : "Laser engraved — permanent, won't wear or fade";
$bullet_3     = function_exists('apex_opt') ? apex_opt('pmag_bullet_3','20+ designs to choose from or bring your own') : '20+ designs to choose from or bring your own';
$bullet_4     = function_exists('apex_opt') ? apex_opt('pmag_bullet_4','$35 per mag — flat rate, no hidden fees') : '$35 per mag — flat rate, no hidden fees';
?>
<!-- wp:html -->
<section style="background:#111;padding:80px 0;overflow:hidden;">
    <div class="container">
        <div id="pmag-feature-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:center;">
            <div class="reveal" style="position:relative;border-radius:10px;overflow:hidden;box-shadow:0 24px 64px rgba(0,0,0,0.6);">
                <img src="<?php echo esc_url(content_url('/uploads/2026/03/apex-ar-mag-eagle-american-flag-01-set.webp')); ?>"
                     alt="Three custom laser-engraved AR magazines with American eagle design — Apex Coatings &amp; Engraving"
                     loading="lazy" decoding="async"
                     style="width:100%;height:100%;object-fit:cover;display:block;border-radius:10px;">
                <div style="position:absolute;top:16px;left:16px;background:var(--apex-gradient);color:#fff;font-family:'Barlow Condensed',sans-serif;font-size:0.75rem;font-weight:800;letter-spacing:0.12em;text-transform:uppercase;padding:5px 14px;border-radius:4px;">Genuine Magpul PMAGs</div>
            </div>
            <div class="reveal">
                <span class="eyebrow" style="display:block;margin-bottom:14px;">Custom PMAG Engraving</span>
                <h2 class="section-title" style="color:var(--apex-white);margin-bottom:20px;"><?php echo esc_html($pmag_title); ?></h2>
                <p style="color:rgba(255,255,255,0.6);line-height:1.8;font-size:1.05rem;margin-bottom:16px;"><?php echo esc_html($pmag_body_1); ?></p>
                <p style="color:rgba(255,255,255,0.6);line-height:1.8;font-size:1.05rem;margin-bottom:32px;"><?php echo esc_html($pmag_body_2); ?></p>
                <ul style="list-style:none;padding:0;margin:0 0 36px;display:flex;flex-direction:column;gap:10px;">
                    <?php foreach ([$bullet_1,$bullet_2,$bullet_3,$bullet_4] as $point): ?>
                    <li style="display:flex;align-items:center;gap:10px;color:rgba(255,255,255,0.8);font-size:0.95rem;">
                        <span style="flex-shrink:0;width:22px;height:22px;background:rgba(245,131,31,0.15);border:1px solid rgba(245,131,31,0.4);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--apex-orange);font-size:13px;font-weight:700;">✓</span>
                        <?php echo esc_html($point); ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <a href="<?php echo esc_url(home_url('/pmags')); ?>" class="btn btn-outline-dark btn-sm" style="justify-content:center;">View Designs</a>
                    <a href="<?php echo esc_url(home_url('/pmags')); ?>" class="btn btn-primary btn-sm" style="justify-content:center;">Order Now</a>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="apex-section-divider" aria-hidden="true">
    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
        <polygon points="16,2 30,16 16,30 2,16" fill="none" stroke="url(#divGrad)" stroke-width="2"/>
        <polygon points="16,8 24,16 16,24 8,16" fill="url(#divGrad2)" opacity="0.6"/>
        <defs>
            <linearGradient id="divGrad" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#FFAD00"/><stop offset="100%" stop-color="#F5831F"/></linearGradient>
            <linearGradient id="divGrad2" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#FFAD00"/><stop offset="100%" stop-color="#F5831F"/></linearGradient>
        </defs>
    </svg>
</div>
<!-- /wp:html -->
