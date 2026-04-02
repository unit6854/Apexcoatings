<!-- Footer -->
<footer id="apex-footer" role="contentinfo">
    <div class="container">
        <div class="footer-grid">

            <!-- Brand -->
            <div class="footer-brand">
                <a href="<?php echo home_url('/'); ?>" class="footer-brand-img-link">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/apex-logo-footer.webp'); ?>"
                         alt="Apex Coatings &amp; Engraving"
                         class="footer-brand-logo"
                         loading="lazy"
                         decoding="async"
                         width="220"
                         height="147">
                </a>
                <p>Precision Cerakote finishing, laser engraving, and custom metal work. Craftsmanship you can trust, results that last.</p>

                <!-- Social Links -->
                <?php
                $social = apex_get_social_links();
                ?>
                <div class="footer-social" aria-label="Social Media">
                    <!-- TikTok -->
                    <a href="<?php echo esc_url( $social['tiktok'] ); ?>"
                       class="footer-social-link"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="Follow Apex Coatings &amp; Engraving on TikTok">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.77a4.85 4.85 0 0 1-1.01-.08z"/>
                        </svg>
                    </a>

                    <!-- Facebook -->
                    <a href="<?php echo esc_url( $social['facebook'] ); ?>"
                       class="footer-social-link"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="Follow Apex Coatings &amp; Engraving on Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M24 12.073C24 5.404 18.627 0 12 0S0 5.404 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.41c0-3.025 1.792-4.697 4.533-4.697 1.312 0 2.686.235 2.686.235v2.97h-1.513c-1.491 0-1.956.93-1.956 1.874v2.25h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Services -->
            <div class="footer-col">
                <h4>Services</h4>
                <ul>
                    <li><a href="<?php echo home_url('/services'); ?>">Custom Engraving</a></li>
                    <li><a href="<?php echo home_url('/services'); ?>">Cerakote Finishing</a></li>
                    <li><a href="<?php echo home_url('/services'); ?>">Laser Engraving</a></li>
                    <li><a href="<?php echo home_url('/services'); ?>">Custom Finishes</a></li>
                </ul>
            </div>

            <!-- Company -->
            <div class="footer-col">
                <h4>Company</h4>
                <ul>
                    <li><a href="<?php echo home_url('/'); ?>">Home</a></li>
                    <li><a href="<?php echo home_url('/products'); ?>">Products</a></li>
                    <li><a href="<?php echo home_url('/gallery'); ?>">Gallery</a></li>
                    <li><a href="<?php echo home_url('/heroes'); ?>" style="color:var(--apex-gold);">&#127894; Heroes Discount</a></li>
                    <li><a href="<?php echo home_url('/contact'); ?>">Contact</a></li>
                    <li><a href="<?php echo home_url('/cart'); ?>">Cart</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="footer-col">
                <h4>Contact</h4>
                <ul>
                    <li style="color:rgba(255,255,255,0.5);font-size:0.9rem;padding:4px 0;">
                        <?php echo antispambot( 'ApexTN@outlook.com' ); ?>
                    </li>
                    <li style="color:rgba(255,255,255,0.5);font-size:0.9rem;padding:4px 0;">
                        <a href="tel:6158621660" style="color:rgba(255,255,255,0.5);">(615) 862-1660</a>
                    </li>
                    <li style="margin-top:16px;">
                        <a href="<?php echo home_url('/contact'); ?>" style="display:inline-block;background:linear-gradient(135deg,#FFAD00,#F5831F);color:#fff;padding:10px 20px;border-radius:4px;font-family:'Barlow Condensed',sans-serif;font-size:0.85rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;text-decoration:none;">Request A Quote</a>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <div class="footer-bottom">
        <div class="container" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
            <span>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</span>
            <span>Precision &bull; Craftsmanship &bull; Quality</span>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
