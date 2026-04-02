<?php
/**
 * Template Name: Contact Page
 */
get_header();
?>

<div style="background:var(--apex-black);padding:140px 0 60px;">
    <div class="container">
        <span class="eyebrow" style="display:block;margin-bottom:12px;">Get in Touch</span>
        <h1 class="section-title" style="color:var(--apex-white);">Contact Us</h1>
        <p style="color:rgba(255,255,255,0.55);max-width:480px;line-height:1.7;">Have a custom project in mind? Need a quote? We'll get back to you within 1-2 business days.</p>
    </div>
</div>

<section class="contact-page-wrap" style="padding-top:64px;">
    <div class="container">
        <div class="contact-grid">

            <!-- Left: Info -->
            <div>
                <h2 style="font-family:'Barlow Condensed',sans-serif;font-size:2.2rem;font-weight:900;text-transform:uppercase;margin-bottom:16px;">Let's Build Something Exceptional</h2>
                <p style="color:var(--apex-gray-dark);line-height:1.8;margin-bottom:36px;">Whether it's a single custom piece or a large production run, we handle every job with the same level of precision and care. Tell us what you need — we'll make it happen.</p>

                <div class="contact-details">
                    <div class="contact-detail">
                        <div class="contact-detail-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </div>
                        <div class="contact-detail-text">
                            <strong>Email</strong>
                            <span><?php echo antispambot('ApexTN@outlook.com'); ?></span>
                        </div>
                    </div>

                    <div class="contact-detail">
                        <div class="contact-detail-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13.9a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 3.18l3-.01a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 10a16 16 0 0 0 6.09 6.09l.91-.91a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </div>
                        <div class="contact-detail-text">
                            <strong>Phone</strong>
                            <span><a href="tel:6158621660" style="color:var(--apex-gray-dark);">(615) 862-1660</a></span>
                        </div>
                    </div>

                    <div class="contact-detail">
                        <div class="contact-detail-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <div class="contact-detail-text">
                            <strong>Business Hours</strong>
                            <span>Mon–Fri: 8AM – 5PM<br>Sat: 9AM – 2PM</span>
                        </div>
                    </div>

                    <div class="contact-detail">
                        <div class="contact-detail-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div class="contact-detail-text">
                            <strong>Location</strong>
                            <span>Available for drop-off &amp; pickup<br>Contact us for address</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Form -->
            <div>
                <div class="contact-form-card">
                    <div id="contact-alert" class="form-alert" role="alert"></div>

                    <form id="apex-contact-form" novalidate>
                        <div class="form-group">
                            <label class="form-label" for="contact-name">Your Name <span class="required">*</span></label>
                            <input class="form-input" type="text" id="contact-name" name="name" autocomplete="name" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="contact-email">Email <span class="required">*</span></label>
                                <input class="form-input" type="email" id="contact-email" name="email" autocomplete="email" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="contact-phone">Phone</label>
                                <input class="form-input" type="tel" id="contact-phone" name="phone" autocomplete="tel">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="contact-subject">Subject</label>
                            <select class="form-select" id="contact-subject" name="subject">
                                <option value="General Inquiry">General Inquiry</option>
                                <option value="Quote Request">Quote Request</option>
                                <option value="Custom Engraving">Custom Engraving</option>
                                <option value="Cerakote Finishing">Cerakote Finishing</option>
                                <option value="Laser Engraving">Laser Engraving</option>
                                <option value="Order Status">Order Status</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="contact-message">Message <span class="required">*</span></label>
                            <textarea class="form-textarea" id="contact-message" name="message" rows="6" placeholder="Describe your project, material, finish preferences, dimensions, quantity, etc." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            Send Message
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- CTA -->
<section style="background:var(--apex-off-white);padding:60px 0;border-top:1px solid var(--apex-gray-light);margin-top:80px;">
    <div class="container text-center">
        <h2 class="section-title" style="margin-bottom:16px;">Ready to Order?</h2>
        <p style="color:var(--apex-gray-dark);margin-bottom:32px;max-width:480px;margin-left:auto;margin-right:auto;">Browse our product catalog, add items to your cart, and submit your order request. Fast and easy.</p>
        <a href="<?php echo home_url('/products'); ?>" class="btn btn-primary">Browse Products</a>
    </div>
</section>

<?php get_footer(); ?>
