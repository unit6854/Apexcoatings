<?php
/**
 * Template Name: 1911 Grips Page
 * Slug: 1911-grips
 */
get_header();

$grips_uri = get_template_directory_uri() . '/assets/images/1911-grips/';
$grips_dir = get_template_directory() . '/assets/images/1911-grips/';

$grips = [
    [
        'file'  => 'black-cerakote-aluminum-reveal.webp',
        'label' => 'Black Cerakote Aluminum Reveal',
        'desc'  => 'Aluminum grip panels with a precision Black Cerakote finish and machined reveal cutouts.',
        'id'    => '1911-grips-black-cerakote-aluminum-reveal',
    ],
    [
        'file'  => 'black-cerakote.webp',
        'label' => 'Black Cerakote',
        'desc'  => 'Clean, matte Black Cerakote finish over aluminum — durable and purpose-built.',
        'id'    => '1911-grips-black-cerakote',
    ],
    [
        'file'  => 'satin-aluminum.webp',
        'label' => 'Satin Aluminum',
        'desc'  => 'Natural satin-finished aluminum panels — understated, classic, and built to last.',
        'id'    => '1911-grips-satin-aluminum',
    ],
    [
        'file'  => 'traditional-redwood.webp',
        'label' => 'Traditional Redwood',
        'desc'  => 'Warm, classic redwood grip panels hand-fitted for a timeless look and feel.',
        'id'    => '1911-grips-traditional-redwood',
    ],
];

// Filter to only grips that actually have files
$grips = array_values(array_filter($grips, function($g) use ($grips_dir) {
    return file_exists($grips_dir . $g['file']);
}));
?>

<!-- Page Hero -->
<div class="grips-hero">
    <div class="container">
        <span class="eyebrow" style="display:block;margin-bottom:10px;">Custom 1911 Accessories</span>
        <h1 class="section-title" style="color:var(--apex-white);margin-bottom:16px;">1911 Grip Panels</h1>
        <p class="grips-hero-desc">Each grip panel is individually crafted and finished in-house. Whether you're after the durability of Cerakote, the classic look of natural aluminum, or the warmth of real wood — we have a grip to match your 1911 and your style. This product line is expanding regularly.</p>
        <div class="grips-badges">
            <span class="grips-badge">✓ Made In-House</span>
            <span class="grips-badge">✓ Multiple Finishes Available</span>
            <span class="grips-badge">✓ Custom Options — Quote Based</span>
            <span class="grips-badge">✓ $90 Per Set</span>
        </div>
    </div>
</div>

<!-- Info Bar -->
<div class="grips-infobar">
    <div class="container">
        <div class="grips-info-grid">
            <div class="grips-info-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--apex-orange)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <div>
                    <strong>Built to Last</strong>
                    <span>Quality materials, quality finish</span>
                </div>
            </div>
            <div class="grips-info-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--apex-orange)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <div>
                    <strong>Turnaround</strong>
                    <span>5–10 business days</span>
                </div>
            </div>
            <div class="grips-info-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--apex-orange)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                <div>
                    <strong>Pricing</strong>
                    <span>$90 per set</span>
                </div>
            </div>
            <div class="grips-info-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--apex-orange)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                <div>
                    <strong>Custom Grips</strong>
                    <span>Quote-based · Contact us</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grip Cards -->
<section class="grips-section">
    <div class="container">
        <div class="grips-section-header">
            <h2 class="section-title" style="color:var(--apex-white);">Available Finishes</h2>
            <p style="color:rgba(255,255,255,0.55);margin-top:8px;">Each panel is a separate product. Select the finish you want and request a quote — we'll get back to you with pricing and availability.</p>
        </div>

        <div class="grips-grid" id="grips-grid">
            <?php foreach ($grips as $grip):
                $img_url  = $grips_uri . $grip['file'];
                $label    = $grip['label'];
                $cart_id  = $grip['id'];
                $cart_name = '1911 Grip Panels — ' . $label;
            ?>
            <div class="grips-card reveal" data-grip="<?php echo esc_attr($cart_id); ?>">

                <!-- Image -->
                <div class="grips-card-img-wrap" onclick="openGripsLightbox('<?php echo esc_js($img_url); ?>', '<?php echo esc_js($label); ?>')">
                    <img src="<?php echo esc_url($img_url); ?>"
                         alt="<?php echo esc_attr($cart_name . ' — Apex Coatings &amp; Engraving'); ?>"
                         loading="lazy" decoding="async"
                         width="800" height="800"
                         class="grips-card-img">
                    <div class="grips-zoom-hint">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="grips-card-body">
                    <div class="grips-card-top">
                        <span class="grips-name"><?php echo esc_html($label); ?></span>
                        <span class="grips-price-tag">$90</span>
                    </div>
                    <p class="grips-card-desc"><?php echo esc_html($grip['desc']); ?></p>

                    <button class="btn btn-primary grips-add-btn"
                            onclick="apexGripsAddToCart('<?php echo esc_js($cart_id); ?>', '<?php echo esc_js($cart_name); ?>')"
                            data-id="<?php echo esc_attr($cart_id); ?>"
                            data-name="<?php echo esc_attr($cart_name); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                        Request This Finish
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Custom / Expanding CTA -->
        <div class="grips-custom-cta">
            <div class="grips-custom-inner">
                <div>
                    <h3 style="color:var(--apex-white);font-family:'Barlow Condensed',sans-serif;font-size:1.6rem;font-weight:800;text-transform:uppercase;letter-spacing:0.04em;margin:0 0 8px;">Don't See What You're Looking For?</h3>
                    <p style="color:rgba(255,255,255,0.6);margin:0;">Our 1911 grip lineup is growing. If you have a specific finish, material, or engraving in mind — reach out and we'll work with you to make it happen.</p>
                </div>
                <a href="<?php echo home_url('/contact'); ?>" class="btn btn-outline" style="white-space:nowrap;flex-shrink:0;">Request Custom Grip</a>
            </div>
        </div>
    </div>
</section>

<!-- Lightbox -->
<div id="grips-lightbox" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.95);z-index:9999;align-items:center;justify-content:center;flex-direction:column;" onclick="closeGripsLightbox()">
    <button onclick="closeGripsLightbox()" style="position:absolute;top:20px;right:28px;background:none;border:none;color:#fff;font-size:2.5rem;cursor:pointer;line-height:1;z-index:10000;">&times;</button>
    <img id="grips-lightbox-img" src="" alt="" width="900" height="900" style="max-width:92vw;max-height:85vh;object-fit:contain;border-radius:6px;box-shadow:0 8px 48px rgba(0,0,0,0.6);" onclick="event.stopPropagation()">
    <p id="grips-lightbox-label" style="color:rgba(255,255,255,0.6);margin-top:14px;font-family:'Barlow Condensed',sans-serif;font-size:1rem;text-transform:uppercase;letter-spacing:0.1em;"></p>
</div>

<style>
/* ── 1911 Grips Page Styles ──────────────────────────────── */
.grips-hero {
    background: var(--apex-black);
    padding: 140px 0 56px;
    border-bottom: 1px solid rgba(255,255,255,0.07);
}
.grips-hero-desc {
    color: rgba(255,255,255,0.6);
    max-width: 640px;
    line-height: 1.75;
    margin: 0 0 24px;
    font-size: 1.05rem;
}
.grips-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 4px;
}
.grips-badge {
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
.grips-infobar {
    background: #161616;
    border-bottom: 1px solid rgba(255,255,255,0.07);
    padding: 28px 0;
}
.grips-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 24px;
}
.grips-info-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}
.grips-info-item svg { flex-shrink: 0; margin-top: 2px; }
.grips-info-item strong {
    display: block;
    color: var(--apex-white);
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.grips-info-item span {
    color: rgba(255,255,255,0.5);
    font-size: 0.85rem;
}
.grips-section {
    background: #111;
    padding: 64px 0 80px;
}
.grips-section-header {
    text-align: center;
    margin-bottom: 48px;
}
.grips-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
}
.grips-card {
    background: #1a1a1a;
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 8px;
    overflow: hidden;
    transition: border-color 0.25s, transform 0.25s, box-shadow 0.25s;
}
.grips-card:hover {
    border-color: rgba(245,131,31,0.4);
    transform: translateY(-3px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.4);
}
.grips-card-img-wrap {
    position: relative;
    aspect-ratio: 1/1;
    overflow: hidden;
    cursor: zoom-in;
    background: #222;
}
.grips-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.4s;
}
.grips-card:hover .grips-card-img { transform: scale(1.05); }
.grips-zoom-hint {
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
.grips-card:hover .grips-zoom-hint { opacity: 1; }
.grips-card-body {
    padding: 18px;
}
.grips-card-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}
.grips-name {
    font-family: 'Barlow Condensed', sans-serif;
    font-size: 1.2rem;
    font-weight: 800;
    color: var(--apex-white);
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.grips-price-tag {
    background: rgba(245,131,31,0.15);
    color: var(--apex-orange);
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 3px 8px;
    border-radius: 3px;
    white-space: nowrap;
}
.grips-card-desc {
    color: rgba(255,255,255,0.45);
    font-size: 0.85rem;
    margin: 0 0 16px;
    line-height: 1.6;
}
.grips-add-btn {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 0.85rem;
    padding: 10px 16px;
}
.grips-add-btn.added {
    background: #2a7a2a !important;
    border-color: #2a7a2a !important;
}
.grips-custom-cta {
    margin-top: 64px;
    border: 1px solid rgba(245,131,31,0.25);
    border-radius: 10px;
    background: rgba(245,131,31,0.05);
    padding: 36px 40px;
}
.grips-custom-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 32px;
    flex-wrap: wrap;
}

@media (max-width: 640px) {
    .grips-hero { padding: 110px 0 40px; }
    .grips-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
    .grips-card-body { padding: 12px; }
    .grips-name { font-size: 1rem; }
    .grips-custom-cta { padding: 24px 20px; }
    .grips-custom-inner { flex-direction: column; align-items: flex-start; }
    .grips-info-grid { grid-template-columns: 1fr 1fr; }
}
</style>

<script>
function openGripsLightbox(url, label) {
    var lb  = document.getElementById('grips-lightbox');
    var img = document.getElementById('grips-lightbox-img');
    var lbl = document.getElementById('grips-lightbox-label');
    img.src  = url;
    img.alt  = label;
    lbl.textContent = label + ' — Apex Coatings & Engraving';
    lb.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeGripsLightbox() {
    document.getElementById('grips-lightbox').style.display = 'none';
    document.getElementById('grips-lightbox-img').src = '';
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e){ if(e.key==='Escape') closeGripsLightbox(); });

function apexGripsAddToCart(id, name) {
    var cart = JSON.parse(localStorage.getItem('apexCart') || '[]');
    var existing = cart.find(function(i){ return i.id === id; });
    if (existing) {
        existing.qty = (existing.qty || 1) + 1;
    } else {
        cart.push({ id: id, name: name, price: 0, qty: 1, quote: true });
    }
    localStorage.setItem('apexCart', JSON.stringify(cart));

    var total = cart.reduce(function(s, i){ return s + (i.qty || 1); }, 0);
    var badge = document.getElementById('nav-cart-count');
    if (badge) {
        badge.textContent = total;
        badge.style.display = total > 0 ? 'flex' : 'none';
    }

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

    if (typeof apexToast === 'function') {
        apexToast(name + ' added to cart!', 'success');
    }
}
</script>

<?php get_footer(); ?>
