<?php
/**
 * Title: CTA Section
 * Slug: apex/cta
 * Categories: apex
 * Description: Full-width call-to-action section with two buttons on a dark background.
 */

$title = function_exists('apex_opt') ? apex_opt('cta_title', 'Ready to Start Your Project?') : 'Ready to Start Your Project?';
$body  = function_exists('apex_opt') ? apex_opt('cta_body',  "Add items to your cart and submit your order. We'll review it and be in touch within 1-2 business days — no payment required at this time.") : "Add items to your cart and submit your order. We'll review it and be in touch within 1-2 business days — no payment required at this time.";
?>
<!-- wp:html -->
<section class="cta-section section-pad">
    <div class="container cta-inner reveal">
        <h2><?php echo esc_html($title); ?></h2>
        <p><?php echo esc_html($body); ?></p>
        <div class="cta-actions">
            <a href="<?php echo esc_url(home_url('/products')); ?>" class="btn btn-primary">Shop Now</a>
            <a href="<?php echo esc_url(home_url('/contact')); ?>"  class="btn btn-outline">Request A Quote</a>
        </div>
    </div>
</section>
<!-- /wp:html -->
