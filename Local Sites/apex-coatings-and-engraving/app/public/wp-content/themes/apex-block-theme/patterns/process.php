<?php
/**
 * Title: Process Section
 * Slug: apex/process
 * Categories: apex
 * Description: Four-step how-it-works section on dark background.
 */

$eyebrow = function_exists('apex_opt') ? apex_opt('process_eyebrow', 'How It Works')   : 'How It Works';
$heading = function_exists('apex_opt') ? apex_opt('process_heading', 'Simple Process') : 'Simple Process';
$steps   = [
    ['num'=>'01','title'=>function_exists('apex_opt')?apex_opt('step_1_title','Choose Your Service'):'Choose Your Service',  'desc'=>function_exists('apex_opt')?apex_opt('step_1_desc','Browse our products and add what you need to your cart. Not sure? Contact us for a custom quote.'):'Browse our products and add what you need to your cart. Not sure? Contact us for a custom quote.'],
    ['num'=>'02','title'=>function_exists('apex_opt')?apex_opt('step_2_title','Submit Your Order'):'Submit Your Order',        'desc'=>function_exists('apex_opt')?apex_opt('step_2_desc','Fill out the order form with your details and any special requirements or custom artwork.'):'Fill out the order form with your details and any special requirements or custom artwork.'],
    ['num'=>'03','title'=>function_exists('apex_opt')?apex_opt('step_3_title','We Review & Confirm'):'We Review & Confirm',    'desc'=>function_exists('apex_opt')?apex_opt('step_3_desc',"We'll review your order, confirm pricing, and reach out within 1-2 business days."):"We'll review your order, confirm pricing, and reach out within 1-2 business days."],
    ['num'=>'04','title'=>function_exists('apex_opt')?apex_opt('step_4_title','Your Project Complete'):'Your Project Complete', 'desc'=>function_exists('apex_opt')?apex_opt('step_4_desc',"Your order is finished to spec by our skilled craftsmen. We notify you when it's shipped or ready for pickup."):"Your order is finished to spec by our skilled craftsmen."],
];
?>
<!-- wp:html -->
<section style="background:var(--apex-black);padding:80px 0;">
    <div class="container">
        <div class="text-center reveal" style="margin-bottom:56px;">
            <span class="eyebrow"><?php echo esc_html($eyebrow); ?></span>
            <h2 class="section-title" style="color:var(--apex-white);"><?php echo esc_html($heading); ?></h2>
        </div>
        <div class="process-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:32px;">
            <?php foreach ($steps as $step): ?>
            <div class="process-step reveal" style="text-align:center;position:relative;">
                <div style="width:64px;height:64px;border:2px solid rgba(245,131,31,0.4);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;position:relative;">
                    <span style="font-family:'Barlow Condensed',sans-serif;font-size:1.2rem;font-weight:900;background:linear-gradient(135deg,#FFAD00,#F5831F);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;"><?php echo esc_html($step['num']); ?></span>
                </div>
                <h3 style="font-family:'Barlow Condensed',sans-serif;font-size:1.1rem;font-weight:800;text-transform:uppercase;letter-spacing:0.08em;color:var(--apex-white);margin-bottom:12px;"><?php echo esc_html($step['title']); ?></h3>
                <p style="font-size:0.9rem;color:rgba(255,255,255,0.5);line-height:1.7;"><?php echo esc_html($step['desc']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<!-- /wp:html -->
