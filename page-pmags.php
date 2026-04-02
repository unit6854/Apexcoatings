<?php
/**
 * Template Name: PMAG Designs Page
 * Slug: pmags
 */
get_header();

$pmag_uri = get_template_directory_uri() . '/assets/images/pmags/';
$pmag_dir = get_template_directory() . '/assets/images/pmags/';

// Designs ordered newest-first (28 → 1), renumbered sequentially for display
$designs = [
    ['num' => 1,  'file' => 'pmag-design-28.webp',        'label' => 'Design #1'],
    ['num' => 2,  'file' => 'pmag-design-23.webp',        'label' => 'Design #2'],
    ['num' => 3,  'file' => 'pmag-design-22.webp',        'label' => 'Design #3'],
    ['num' => 4,  'file' => 'pmag-design-20-and-21.webp', 'label' => 'Design #4'],
    ['num' => 5,  'file' => 'pmag-design-19.webp',        'label' => 'Design #5'],
    ['num' => 6,  'file' => 'pmag-design-16.webp',        'label' => 'Design #6'],
    ['num' => 7,  'file' => 'pmag-design-15.webp',        'label' => 'Design #7'],
    ['num' => 8,  'file' => 'pmag-design-14.webp',        'label' => 'Design #8'],
    ['num' => 9,  'file' => 'pmag-design-13.webp',        'label' => 'Design #9'],
    ['num' => 10, 'file' => 'pmag-design-12.webp',        'label' => 'Design #10'],
    ['num' => 11, 'file' => 'pmag-design-11.webp',        'label' => 'Design #11'],
    ['num' => 12, 'file' => 'pmag-design-10.webp',        'label' => 'Design #12'],
    ['num' => 13, 'file' => 'pmag-design-9.webp',         'label' => 'Design #13'],
    ['num' => 14, 'file' => 'pmag-design-8.webp',         'label' => 'Design #14'],
    ['num' => 15, 'file' => 'pmag-design-7.webp',         'label' => 'Design #15'],
    ['num' => 16, 'file' => 'pmag-design-6.webp',         'label' => 'Design #16'],
    ['num' => 17, 'file' => 'pmag-design-5.webp',         'label' => 'Design #17'],
    ['num' => 18, 'file' => 'pmag-design-4.webp',         'label' => 'Design #18'],
    ['num' => 19, 'file' => 'pmag-design-3.webp',         'label' => 'Design #19'],
    ['num' => 20, 'file' => 'pmag-design-2.webp',         'label' => 'Design #20'],
    ['num' => 21, 'file' => 'pmag-design-1.webp',         'label' => 'Design #21'],
];

// Filter to only designs that actually have files
$designs = array_values(array_filter($designs, function($d) use ($pmag_dir) {
    return file_exists($pmag_dir . $d['file']);
}));
?>

<!-- Page Hero -->
<div class="pmag-hero">
    <div class="container">
        <span class="eyebrow" style="display:block;margin-bottom:10px;">Genuine Magpul PMAGs</span>
        <h1 class="section-title" style="color:var(--apex-white);margin-bottom:16px;">Custom PMAG Engraving</h1>
        <p class="pmag-hero-desc">We only use genuine Magpul PMAGs and laser engrave custom designs and art directly into them. Each magazine is individually engraved — no stickers, no paint, no fading. Built to last as long as the mag itself.</p>
        <div class="pmag-badges">
            <span class="pmag-badge">✓ Genuine Magpul PMAGs Only</span>
            <span class="pmag-badge">✓ Laser Engraved</span>
            <span class="pmag-badge">✓ Custom Artwork Available</span>
            <span class="pmag-badge">✓ Quote-Based Pricing</span>
        </div>
    </div>
</div>

<!-- Info Bar -->
<div class="pmag-infobar">
    <div class="container">
        <div class="pmag-info-grid">
            <div class="pmag-info-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--apex-orange)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <div>
                    <strong>Genuine Magpul Only</strong>
                    <span>No off-brand substitutes</span>
                </div>
            </div>
            <div class="pmag-info-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--apex-orange)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <div>
                    <strong>Turnaround</strong>
                    <span>5–10 business days</span>
                </div>
            </div>
            <div class="pmag-info-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--apex-orange)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                <div>
                    <strong>Pricing</strong>
                    <span>Quote-based · Starting at $30</span>
                </div>
            </div>
            <div class="pmag-info-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--apex-orange)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                <div>
                    <strong>Custom Designs</strong>
                    <span>Don't see what you want? Ask us</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Design Grid -->
<section class="pmag-section">
    <div class="container">
        <div class="pmag-section-header">
            <h2 class="section-title" style="color:var(--apex-white);">Choose Your Design</h2>
            <p style="color:rgba(255,255,255,0.55);margin-top:8px;">Select a design below and add it to your request. We'll quote the final price based on your order.</p>
        </div>

        <div class="pmag-grid" id="pmag-grid">
            <?php foreach ($designs as $design):
                $img_url  = $pmag_uri . $design['file'];
                $label    = $design['label'];
                $num      = $design['num'];
                $cart_id  = 'pmag-design-' . (is_string($num) ? strtolower(str_replace(' & ', '-and-', $num)) : $num);
                $cart_name = 'Custom PMAG Engraving — ' . $label;
            ?>
            <div class="pmag-card reveal" data-design="<?php echo esc_attr($cart_id); ?>">

                <!-- Image -->
                <div class="pmag-card-img-wrap" onclick="openPmagLightbox('<?php echo esc_js($img_url); ?>', '<?php echo esc_js($label); ?>')">
                    <img src="<?php echo esc_url($img_url); ?>"
                         alt="<?php echo esc_attr($cart_name . ' — Apex Coatings &amp; Engraving'); ?>"
                         loading="lazy" decoding="async"
                         width="600" height="600"
                         class="pmag-card-img">
                    <div class="pmag-zoom-hint">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="pmag-card-body">
                    <div class="pmag-card-top">
                        <span class="pmag-design-num"><?php echo esc_html($label); ?></span>
                        <span class="pmag-price-tag">Quote Only</span>
                    </div>
                    <p class="pmag-card-desc">Genuine Magpul PMAG · Laser Engraved · Custom artwork</p>

                    <button class="btn btn-primary pmag-add-btn"
                            onclick="apexPmagAddToCart('<?php echo esc_js($cart_id); ?>', '<?php echo esc_js($cart_name); ?>')"
                            data-id="<?php echo esc_attr($cart_id); ?>"
                            data-name="<?php echo esc_attr($cart_name); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                        Request This Design
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Custom Design CTA -->
        <div class="pmag-custom-cta">
            <div class="pmag-custom-inner">
                <div>
                    <h3 style="color:var(--apex-white);font-family:'Barlow Condensed',sans-serif;font-size:1.6rem;font-weight:800;text-transform:uppercase;letter-spacing:0.04em;margin:0 0 8px;">Don't See What You Want?</h3>
                    <p style="color:rgba(255,255,255,0.6);margin:0;">We engrave custom artwork, logos, text, and anything else you can imagine. Bring your idea and we'll make it happen.</p>
                </div>
                <a href="<?php echo home_url('/contact'); ?>" class="btn btn-outline" style="white-space:nowrap;flex-shrink:0;">Request Custom Design</a>
            </div>
        </div>
    </div>
</section>

<!-- Lightbox -->
<div id="pmag-lightbox" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.95);z-index:9999;align-items:center;justify-content:center;flex-direction:column;" onclick="closePmagLightbox()">
    <button onclick="closePmagLightbox()" style="position:absolute;top:20px;right:28px;background:none;border:none;color:#fff;font-size:2.5rem;cursor:pointer;line-height:1;z-index:10000;">&times;</button>
    <img id="pmag-lightbox-img" src="" alt="" width="900" height="900" style="max-width:92vw;max-height:85vh;object-fit:contain;border-radius:6px;box-shadow:0 8px 48px rgba(0,0,0,0.6);" onclick="event.stopPropagation()">
    <p id="pmag-lightbox-label" style="color:rgba(255,255,255,0.6);margin-top:14px;font-family:'Barlow Condensed',sans-serif;font-size:1rem;text-transform:uppercase;letter-spacing:0.1em;"></p>
</div>

<style>
/* ── PMAG Page Styles ──────────────────────────────── */
.pmag-hero {
    background: var(--apex-black);
    padding: 140px 0 56px;
    border-bottom: 1px solid rgba(255,255,255,0.07);
}
.pmag-hero-desc {
    color: rgba(255,255,255,0.6);
    max-width: 640px;
    line-height: 1.75;
    margin: 0 0 24px;
    font-size: 1.05rem;
}
.pmag-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 4px;
}
.pmag-badge {
    background: rgba(245,131,31,0.12);
    border: 1px solid rgba(245,131,31,0.3);
    color: var(--apex-orange);
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    padding: 6px 14px;
    border-radius: 4px;
}
.pmag-infobar {
    background: #161616;
    border-bottom: 1px solid rgba(255,255,255,0.07);
    padding: 28px 0;
}
.pmag-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 24px;
}
.pmag-info-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}
.pmag-info-item svg { flex-shrink: 0; margin-top: 2px; }
.pmag-info-item strong {
    display: block;
    color: var(--apex-white);
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.pmag-info-item span {
    color: rgba(255,255,255,0.5);
    font-size: 0.85rem;
}
.pmag-section {
    background: #111;
    padding: 64px 0 80px;
}
.pmag-section-header {
    text-align: center;
    margin-bottom: 48px;
}
.pmag-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 20px;
}
.pmag-card {
    background: #1a1a1a;
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 8px;
    overflow: hidden;
    transition: border-color 0.25s, transform 0.25s, box-shadow 0.25s;
}
.pmag-card:hover {
    border-color: rgba(245,131,31,0.4);
    transform: translateY(-3px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.4);
}
.pmag-card-img-wrap {
    position: relative;
    aspect-ratio: 1/1;
    overflow: hidden;
    cursor: zoom-in;
    background: #222;
}
.pmag-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.4s;
}
.pmag-card:hover .pmag-card-img { transform: scale(1.05); }
.pmag-zoom-hint {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(0,0,0,0.55);
    border-radius: 50%;
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.2s;
}
.pmag-card:hover .pmag-zoom-hint { opacity: 1; }
.pmag-card-body {
    padding: 16px;
}
.pmag-card-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 6px;
}
.pmag-design-num {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 1.15rem;
    font-weight: 800;
    color: var(--apex-white);
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.pmag-price-tag {
    background: rgba(245,131,31,0.15);
    color: var(--apex-orange);
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 3px 8px;
    border-radius: 3px;
}
.pmag-card-desc {
    color: rgba(255,255,255,0.4);
    font-size: 0.8rem;
    margin: 0 0 14px;
    line-height: 1.5;
}
.pmag-add-btn {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 0.85rem;
    padding: 10px 16px;
}
.pmag-add-btn.added {
    background: #2a7a2a !important;
    border-color: #2a7a2a !important;
}
.pmag-custom-cta {
    margin-top: 64px;
    border: 1px solid rgba(245,131,31,0.25);
    border-radius: 10px;
    background: rgba(245,131,31,0.05);
    padding: 36px 40px;
}
.pmag-custom-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 32px;
    flex-wrap: wrap;
}

/* Mobile */
@media (max-width: 640px) {
    .pmag-hero { padding: 110px 0 40px; }
    .pmag-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px; }
    .pmag-card-body { padding: 12px; }
    .pmag-design-num { font-size: 1rem; }
    .pmag-custom-cta { padding: 24px 20px; }
    .pmag-custom-inner { flex-direction: column; align-items: flex-start; }
    .pmag-info-grid { grid-template-columns: 1fr 1fr; }
}
</style>

<script>
function openPmagLightbox(url, label) {
    var lb  = document.getElementById('pmag-lightbox');
    var img = document.getElementById('pmag-lightbox-img');
    var lbl = document.getElementById('pmag-lightbox-label');
    img.src  = url;
    img.alt  = label;
    lbl.textContent = label + ' — Apex Coatings & Engraving';
    lb.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closePmagLightbox() {
    document.getElementById('pmag-lightbox').style.display = 'none';
    document.getElementById('pmag-lightbox-img').src = '';
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e){ if(e.key==='Escape') closePmagLightbox(); });

function apexPmagAddToCart(id, name) {
    // Use same cart system as rest of site
    var cart = JSON.parse(localStorage.getItem('apexCart') || '[]');
    var existing = cart.find(function(i){ return i.id === id; });
    if (existing) {
        existing.qty = (existing.qty || 1) + 1;
    } else {
        cart.push({ id: id, name: name, price: 0, qty: 1, quote: true });
    }
    localStorage.setItem('apexCart', JSON.stringify(cart));

    // Update cart badge
    var total = cart.reduce(function(s, i){ return s + (i.qty || 1); }, 0);
    var badge = document.getElementById('nav-cart-count');
    if (badge) {
        badge.textContent = total;
        badge.style.display = total > 0 ? 'flex' : 'none';
    }

    // Visual feedback on button
    var btn = document.querySelector('[data-id="' + id + '"]');
    if (btn) {
        var orig = btn.innerHTML;
        btn.classList.add('added');
        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Added to Cart';
        setTimeout(function(){
            btn.classList.remove('added');
            btn.innerHTML = orig;
        }, 2000);
    }

    // Show toast if available
    if (typeof apexToast === 'function') {
        apexToast(name + ' added to cart!', 'success');
    }
}
</script>

<?php get_footer(); ?>
