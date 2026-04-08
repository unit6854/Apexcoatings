<?php
/**
 * Template Name: Gallery Page
 * Auto-used for page with slug: gallery
 */
get_header();

// Build image + video list from uploads/apex-gallery/
$gallery_dir  = WP_CONTENT_DIR . '/uploads/apex-gallery/';
$gallery_url  = content_url('/uploads/apex-gallery/');

$all_webp  = glob($gallery_dir . '*.webp') ?: [];
$all_mp4   = glob($gallery_dir . '*.mp4')  ?: [];

// Exclude WP-generated thumbnail variants and manually excluded duplicates, then sort newest file first
$excluded = ['apex-ar-rifle-skull-spider-closeup-01.webp'];
$image_files = array_values(array_filter($all_webp, function($f) use ($excluded) {
    $base = basename($f);
    return !preg_match('/-\d+x\d+\.webp$/', $base)
        && !preg_match('/-scaled\.webp$/', $base)
        && !in_array($base, $excluded);
}));
usort($image_files, function($a, $b) {
    return filemtime($b) - filemtime($a); // newest first
});

// Merge: images first, then videos
$all_media = array_merge(
    array_map(fn($f) => ['file' => $f, 'type' => 'image'], $image_files),
    array_map(fn($f) => ['file' => $f, 'type' => 'video'], $all_mp4)
);

// Map slug-style filenames to human-readable labels
function apex_gallery_label($filename) {
    $name = pathinfo($filename, PATHINFO_FILENAME);
    // Remove leading "apex-" prefix
    $name = preg_replace('/^apex-/', '', $name);
    // Remove trailing numbers like -01, -02
    $name = preg_replace('/-\d+(-\w+)?$/', '', $name);
    // Replace dashes with spaces
    $name = str_replace('-', ' ', $name);
    return ucwords($name);
}

// Categorise images and videos
function apex_gallery_category($filename, $type = 'image') {
    if ($type === 'video') return 'Videos';
    $f = strtolower($filename);
    if (strpos($f, 'ar-mag') !== false || strpos($f, 'ar-rifle') !== false) return 'AR Engraving';
    if (strpos($f, '1911') !== false)               return 'Pistol Engraving';
    if (strpos($f, 'knife') !== false)              return 'Knife Engraving';
    if (strpos($f, 'wood-sign') !== false)          return 'Laser Signs';
    if (strpos($f, 'slate-coaster') !== false)      return 'Laser Coasters';
    if (strpos($f, 'pet-memorial') !== false)       return 'Memorial Stones';
    return 'Custom Work';
}

// Group by category — videos always last
$by_cat = [];
foreach ($all_media as $item) {
    $cat = apex_gallery_category(basename($item['file']), $item['type']);
    $by_cat[$cat][] = $item;
}
// Sort with Videos last
uksort($by_cat, function($a, $b) {
    if ($a === 'Videos') return 1;
    if ($b === 'Videos') return -1;
    return strcmp($a, $b);
});
?>

<!-- Page Hero -->
<div style="background:var(--apex-black);padding:140px 0 60px;">
    <div class="container">
        <span class="eyebrow" style="display:block;margin-bottom:12px;">Our Work</span>
        <h1 class="section-title" style="color:var(--apex-white);">Gallery</h1>
        <p style="color:rgba(255,255,255,0.55);max-width:560px;line-height:1.7;margin-top:16px;">Real jobs. Real craftsmanship. Every project shown was American made right here in our shop.</p>
    </div>
</div>

<!-- Filter Tabs -->
<div style="background:var(--apex-black);border-top:1px solid rgba(255,255,255,0.07);padding:0 0 32px;">
    <div class="container">
        <div style="display:flex;flex-wrap:wrap;gap:10px;" id="gallery-filters" role="group" aria-label="Filter gallery">
            <button class="btn btn-sm btn-primary gallery-filter-btn active" data-filter="all">All Work</button>
            <?php foreach (array_keys($by_cat) as $cat): ?>
            <button class="btn btn-sm btn-outline-dark gallery-filter-btn" style="border-color:rgba(255,255,255,0.25);color:rgba(255,255,255,0.7);" data-filter="<?php echo esc_attr(sanitize_title($cat)); ?>">
                <?php echo esc_html($cat); ?>
            </button>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Gallery Grid -->
<section style="background:#111;padding:48px 0 80px;">
    <div class="container">
        <div id="gallery-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px;">
            <?php foreach ($by_cat as $cat => $items): ?>
                <?php foreach ($items as $item): ?>
                <?php
                    $filepath = $item['file'];
                    $type     = $item['type'];
                    $filename = basename($filepath);
                    $url      = $gallery_url . $filename;
                    $label    = apex_gallery_label($filename);
                    $cat_slug = sanitize_title($cat);
                ?>
                <div class="gallery-item reveal" data-cat="<?php echo esc_attr($cat_slug); ?>" data-type="<?php echo $type; ?>" data-url="<?php echo esc_url($url); ?>"
                     style="position:relative;overflow:hidden;border-radius:6px;aspect-ratio:4/3;cursor:<?php echo $type === 'video' ? 'pointer' : 'zoom-in'; ?>;background:#1a1a1a;"
                     onclick="apexLightbox(this)">

                    <?php if ($type === 'video'): ?>
                    <video data-src="<?php echo esc_url($url); ?>"
                           muted loop playsinline preload="none"
                           style="width:100%;height:100%;object-fit:cover;display:block;transition:transform 0.4s;">
                    </video>
                    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:48px;height:48px;background:rgba(245,131,31,0.9);border-radius:50%;display:flex;align-items:center;justify-content:center;pointer-events:none;box-shadow:0 2px 16px rgba(0,0,0,0.5);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#fff"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                    </div>
                    <?php else: ?>
                    <img src="<?php echo esc_url($url); ?>"
                         alt="<?php echo esc_attr($label . ' — Apex Coatings &amp; Engraving'); ?>"
                         loading="lazy" decoding="async"
                         width="800" height="600"
                         style="width:100%;height:100%;object-fit:cover;transition:transform 0.4s;display:block;">
                    <?php endif; ?>

                    <div class="gallery-overlay" style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.75) 0%,transparent 55%);opacity:0;transition:opacity 0.3s;display:flex;align-items:flex-end;padding:16px;">
                        <div>
                            <span style="font-size:10px;letter-spacing:0.12em;text-transform:uppercase;color:var(--apex-orange);font-weight:700;"><?php echo esc_html($cat); ?></span>
                            <p style="margin:4px 0 0;font-family:'Barlow Condensed',sans-serif;font-size:1rem;font-weight:700;color:#fff;text-transform:uppercase;letter-spacing:0.05em;"><?php echo $type === 'video' ? 'Laser Engraving Demo' : esc_html($label); ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>

        <?php if (empty($all_media)): ?>
        <div style="text-align:center;padding:80px 40px;color:rgba(255,255,255,0.4);">
            <p style="font-size:1.2rem;">Gallery coming soon — check back after we upload our work photos.</p>
            <a href="<?php echo esc_url( home_url('/contact') ); ?>" class="btn btn-primary" style="margin-top:24px;">Contact Us</a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA -->
<section style="background:var(--apex-black);padding:64px 0;text-align:center;">
    <div class="container">
        <h2 class="section-title" style="color:var(--apex-white);margin-bottom:16px;">Want Something Like This?</h2>
        <p style="color:rgba(255,255,255,0.55);max-width:480px;margin:0 auto 32px;line-height:1.7;">Every piece is custom. Send us your idea and we'll make it happen.</p>
        <a href="<?php echo esc_url( home_url('/contact') ); ?>" class="btn btn-primary">Request A Quote</a>
    </div>
</section>

<!-- Lightbox -->
<div id="apex-lightbox" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.95);z-index:9999;align-items:center;justify-content:center;" onclick="closeLightbox()">
    <button onclick="closeLightbox()" style="position:absolute;top:20px;right:28px;background:none;border:none;color:#fff;font-size:2.5rem;cursor:pointer;line-height:1;z-index:10000;">&times;</button>
    <img id="apex-lightbox-img" src="" alt="" data-no-optimize="1" style="max-width:92vw;max-height:88vh;object-fit:contain;border-radius:4px;box-shadow:0 8px 48px rgba(0,0,0,0.6);display:none;">
    <video id="apex-lightbox-video" src="" controls autoplay style="max-width:92vw;max-height:88vh;border-radius:4px;box-shadow:0 8px 48px rgba(0,0,0,0.6);display:none;" onclick="event.stopPropagation()"></video>
</div>

<script>
(function(){
    // Fix: body has a CSS transform which breaks position:fixed — move lightbox to <html>
    var _lb = document.getElementById('apex-lightbox');
    if (_lb) document.documentElement.appendChild(_lb);

    // Lazy-load + autoplay videos only when visible (saves memory on mobile)
    if ('IntersectionObserver' in window) {
        var videoObs = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                var vid = entry.target;
                if (entry.isIntersecting) {
                    // Load src if not yet loaded
                    if (!vid.src && vid.dataset.src) {
                        vid.src = vid.dataset.src;
                    }
                    vid.play().catch(function(){});
                } else {
                    vid.pause();
                }
            });
        }, { threshold: 0.25 });

        document.querySelectorAll('.gallery-item video').forEach(function(vid) {
            videoObs.observe(vid);
        });
        // Prevent memory leak — disconnect when page unloads
        window.addEventListener('beforeunload', function () { videoObs.disconnect(); }, { once: true });
    } else {
        // Fallback: load and play all (old browsers)
        document.querySelectorAll('.gallery-item video').forEach(function(vid) {
            if (vid.dataset.src) vid.src = vid.dataset.src;
            vid.play().catch(function(){});
        });
    }

    // Hover effect on gallery items
    document.querySelectorAll('.gallery-item').forEach(function(el){
        var media = el.querySelector('img') || el.querySelector('video');
        var ov    = el.querySelector('.gallery-overlay');
        if (!media) return;
        el.addEventListener('mouseenter', function(){ media.style.transform='scale(1.06)'; ov.style.opacity='1'; });
        el.addEventListener('mouseleave', function(){ media.style.transform=''; ov.style.opacity='0'; });
    });

    // Filter buttons
    document.querySelectorAll('.gallery-filter-btn').forEach(function(btn){
        btn.addEventListener('click', function(){
            var filter = this.dataset.filter;
            document.querySelectorAll('.gallery-filter-btn').forEach(function(b){
                b.classList.remove('active','btn-primary');
                b.classList.add('btn-outline-dark');
                b.style.borderColor = 'rgba(255,255,255,0.25)';
                b.style.color = 'rgba(255,255,255,0.7)';
            });
            this.classList.add('active','btn-primary');
            this.classList.remove('btn-outline-dark');
            this.style.borderColor = '';
            this.style.color = '';
            document.querySelectorAll('.gallery-item').forEach(function(item){
                if (filter === 'all' || item.dataset.cat === filter) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
})();

function apexLightbox(el) {
    var type   = el.dataset.type;
    var url    = el.dataset.url;
    var lb     = document.getElementById('apex-lightbox');
    var lbImg  = document.getElementById('apex-lightbox-img');
    var lbVid  = document.getElementById('apex-lightbox-video');

    if (type === 'video') {
        lbImg.style.display = 'none';
        lbImg.src = '';
        lbVid.src = url;
        lbVid.style.display = 'block';
        lbVid.play();
    } else {
        lbVid.style.display = 'none';
        lbVid.pause();
        lbVid.src = '';
        lbImg.removeAttribute('width');
        lbImg.removeAttribute('height');
        lbImg.src = url;
        lbImg.alt = el.querySelector('img') ? el.querySelector('img').alt : '';
        lbImg.style.display = 'block';
    }
    lb.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    var lb  = document.getElementById('apex-lightbox');
    var vid = document.getElementById('apex-lightbox-video');
    lb.style.display = 'none';
    vid.pause();
    vid.src = '';
    document.getElementById('apex-lightbox-img').src = '';
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e){ if(e.key==='Escape') closeLightbox(); });
</script>

<?php get_footer(); ?>
