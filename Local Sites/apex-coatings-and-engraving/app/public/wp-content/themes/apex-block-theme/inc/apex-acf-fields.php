<?php
/**
 * Native WordPress Media Meta Boxes
 *
 * Replaces ACF Pro Gallery/Repeater with built-in wp.media uploader.
 * No plugin required. Data stored as post meta (comma-separated attachment IDs).
 *
 * Fields registered:
 *   home_slider_ids   — front page  → used by front-page.php
 *   pmag_gallery_ids  — pmags page  → used by page-pmags.php
 */

add_action( 'add_meta_boxes', 'apex_register_media_meta_boxes' );
function apex_register_media_meta_boxes() {
    // Homepage Slider — only on the static front page
    $front_page_id = (int) get_option( 'page_on_front' );
    if ( $front_page_id ) {
        add_meta_box(
            'apex_home_slider',
            'Homepage Slider Images',
            'apex_home_slider_meta_box',
            'page',
            'normal',
            'high',
            [ 'post_id' => $front_page_id ]
        );
    }

    // PMAG Gallery — only on the pmags page
    $pmags_page = get_page_by_path( 'pmags' );
    if ( $pmags_page ) {
        add_meta_box(
            'apex_pmag_gallery',
            'PMAG Gallery Images',
            'apex_pmag_gallery_meta_box',
            'page',
            'normal',
            'high',
            [ 'post_id' => $pmags_page->ID ]
        );
    }

    // Gallery — images + videos
    $gallery_page = get_page_by_path( 'gallery' );
    if ( $gallery_page ) {
        add_meta_box(
            'apex_gallery_media',
            'Gallery Media (Images & Videos)',
            'apex_gallery_media_meta_box',
            'page',
            'normal',
            'high',
            [ 'post_id' => $gallery_page->ID ]
        );
    }

    // Heroes — work samples images
    $heroes_page = get_page_by_path( 'heroes' );
    if ( $heroes_page ) {
        add_meta_box(
            'apex_heroes_gallery',
            'Heroes Gallery Images',
            'apex_heroes_gallery_meta_box',
            'page',
            'normal',
            'high',
            [ 'post_id' => $heroes_page->ID ]
        );
    }

    // 1911 Grips — product listings
    $grips_page = get_page_by_path( '1911-grips' );
    if ( $grips_page ) {
        add_meta_box(
            'apex_grips_items',
            '1911 Grip Listings',
            'apex_grips_items_meta_box',
            'page',
            'normal',
            'high',
            [ 'post_id' => $grips_page->ID ]
        );
    }

    // Recent Projects portfolio — only on the static front page
    if ( $front_page_id ) {
        add_meta_box(
            'apex_home_portfolio',
            'Recent Projects (Homepage)',
            'apex_home_portfolio_meta_box',
            'page',
            'normal',
            'high',
            [ 'post_id' => $front_page_id ]
        );
    }
}

/* ── Slider meta box callback ── */
function apex_home_slider_meta_box( $post, $meta_box ) {
    $target_id = $meta_box['args']['post_id'];
    if ( (int) $post->ID !== $target_id ) return; // only render on the front page

    $ids = get_post_meta( $post->ID, 'home_slider_ids', true );
    wp_nonce_field( 'apex_home_slider_save', 'apex_home_slider_nonce' );
    apex_render_gallery_meta_box_ui( 'home_slider_ids', $ids, 'Drag to reorder. Recommended size: 1400 × 800 px or wider.' );
}

/* ── PMAG Gallery meta box callback ── */
function apex_pmag_gallery_meta_box( $post, $meta_box ) {
    $target_id = $meta_box['args']['post_id'];
    if ( (int) $post->ID !== $target_id ) return;

    $ids = get_post_meta( $post->ID, 'pmag_gallery_ids', true );
    wp_nonce_field( 'apex_pmag_gallery_save', 'apex_pmag_gallery_nonce' );
    apex_render_gallery_meta_box_ui( 'pmag_gallery_ids', $ids, 'First image = Design #1. Drag to reorder.' );
}

/* ── Gallery (images + videos) meta box callback ── */
function apex_gallery_media_meta_box( $post, $meta_box ) {
    $target_id = $meta_box['args']['post_id'];
    if ( (int) $post->ID !== $target_id ) return;

    $image_ids = get_post_meta( $post->ID, 'gallery_image_ids', true );
    $video_ids = get_post_meta( $post->ID, 'gallery_video_ids', true );
    wp_nonce_field( 'apex_gallery_media_save', 'apex_gallery_media_nonce' );

    echo '<h4 style="margin:0 0 4px;">Images</h4>';
    apex_render_gallery_meta_box_ui( 'gallery_image_ids', $image_ids, 'Drag to reorder. Shown in the gallery grid.' );

    echo '<h4 style="margin:16px 0 4px;">Videos</h4>';
    apex_render_video_meta_box_ui( 'gallery_video_ids', $video_ids, 'MP4 videos shown at the end of the gallery.' );
}

/* ── Video UI renderer ── */
function apex_render_video_meta_box_ui( $field_name, $ids_csv, $instructions ) {
    $ids  = array_filter( explode( ',', (string) $ids_csv ) );
    $uniq = esc_js( $field_name );
    ?>
    <p style="color:#666;margin-bottom:12px;"><?php echo esc_html( $instructions ); ?></p>
    <div id="<?php echo $uniq; ?>_preview" style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px;min-height:60px;padding:8px;background:#f9f9f9;border:1px dashed #ccc;border-radius:4px;">
        <?php foreach ( $ids as $id ) :
            $att_id = (int) $id;
            $url    = wp_get_attachment_url( $att_id );
            if ( ! $url ) continue;
            $thumb = wp_get_attachment_image_url( $att_id, 'thumbnail' );
            $fname = basename( $url );
        ?>
        <div class="apex-gallery-thumb" data-id="<?php echo $att_id; ?>" style="position:relative;cursor:grab;width:80px;height:80px;border-radius:3px;overflow:hidden;border:2px solid #ddd;background:#1a1a1a;">
            <?php if ( $thumb ) : ?>
                <img src="<?php echo esc_url( $thumb ); ?>" style="width:100%;height:100%;object-fit:cover;">
            <?php else : ?>
                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;padding:4px;">
                    <span style="color:#fff;font-size:9px;text-align:center;word-break:break-all;line-height:1.2;"><?php echo esc_html( $fname ); ?></span>
                </div>
            <?php endif; ?>
            <button type="button" onclick="apexRemoveThumb(this,'<?php echo $uniq; ?>')" style="position:absolute;top:2px;right:2px;background:rgba(0,0,0,0.65);color:#fff;border:none;border-radius:50%;width:18px;height:18px;font-size:12px;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;">×</button>
        </div>
        <?php endforeach; ?>
    </div>
    <input type="hidden" id="<?php echo $uniq; ?>_ids" name="<?php echo esc_attr( $field_name ); ?>" value="<?php echo esc_attr( $ids_csv ); ?>">
    <button type="button" class="button" onclick="apexOpenVideoModal('<?php echo $uniq; ?>')">+ Add / Select Videos</button>
    <script>
    (function(){
        var field = '<?php echo $uniq; ?>';

        window.apexOpenVideoModal = window.apexOpenVideoModal || function(f) {
            var frame = wp.media({
                title: 'Select Videos',
                button: { text: 'Add to Gallery' },
                multiple: true,
                library: { type: 'video' }
            });
            frame.on('select', function() {
                var sel = frame.state().get('selection');
                sel.each(function(att) {
                    var data    = att.toJSON();
                    var preview = document.getElementById(f + '_preview');
                    if (preview.querySelector('[data-id="' + data.id + '"]')) return;
                    var div = document.createElement('div');
                    div.className    = 'apex-gallery-thumb';
                    div.dataset.id   = data.id;
                    div.style.cssText = 'position:relative;cursor:grab;width:80px;height:80px;border-radius:3px;overflow:hidden;border:2px solid #ddd;background:#1a1a1a;';
                    var thumb = (data.image && data.image.src) ? data.image.src : '';
                    if (thumb) {
                        div.innerHTML = '<img src="' + thumb + '" style="width:100%;height:100%;object-fit:cover;">';
                    } else {
                        var fname = data.filename || (data.url ? data.url.split('/').pop() : 'video');
                        div.innerHTML = '<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;padding:4px;"><span style="color:#fff;font-size:9px;text-align:center;word-break:break-all;line-height:1.2;">' + fname + '</span></div>';
                    }
                    div.innerHTML += '<button type="button" onclick="apexRemoveThumb(this,\'' + f + '\')" style="position:absolute;top:2px;right:2px;background:rgba(0,0,0,0.65);color:#fff;border:none;border-radius:50%;width:18px;height:18px;font-size:12px;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;">×</button>';
                    preview.appendChild(div);
                    apexSyncIds(f);
                });
            });
            frame.open();
        };

        (function initDrag(f) {
            var preview = document.getElementById(f + '_preview');
            if (!preview) return;
            var dragged = null;
            preview.addEventListener('dragstart', function(e) { dragged = e.target.closest('.apex-gallery-thumb'); if (dragged) dragged.style.opacity = '0.4'; });
            preview.addEventListener('dragend',   function()  { if (dragged) { dragged.style.opacity = '1'; dragged = null; apexSyncIds(f); } });
            preview.addEventListener('dragover',  function(e) { e.preventDefault(); });
            preview.addEventListener('drop',      function(e) { e.preventDefault(); var t = e.target.closest('.apex-gallery-thumb'); if (dragged && t && dragged !== t) preview.insertBefore(dragged, t.nextSibling); });
            document.querySelectorAll('#' + f + '_preview .apex-gallery-thumb').forEach(function(el) { el.setAttribute('draggable', 'true'); });
            new MutationObserver(function() {
                document.querySelectorAll('#' + f + '_preview .apex-gallery-thumb:not([draggable])').forEach(function(el) { el.setAttribute('draggable', 'true'); });
            }).observe(preview, { childList: true });
        })(field);
    })();
    </script>
    <?php
}

/* ── Heroes gallery meta box callback ── */
function apex_heroes_gallery_meta_box( $post, $meta_box ) {
    $target_id = $meta_box['args']['post_id'];
    if ( (int) $post->ID !== $target_id ) return;

    $ids = get_post_meta( $post->ID, 'heroes_gallery_ids', true );
    wp_nonce_field( 'apex_heroes_gallery_save', 'apex_heroes_gallery_nonce' );
    apex_render_gallery_meta_box_ui( 'heroes_gallery_ids', $ids, 'Images shown in the work samples section. Drag to reorder.' );
}

/* ── 1911 Grip Listings meta box callback ── */
function apex_grips_items_meta_box( $post, $meta_box ) {
    $target_id = $meta_box['args']['post_id'];
    if ( (int) $post->ID !== $target_id ) return;

    $items_json = get_post_meta( $post->ID, 'grips_items_json', true );
    $items      = $items_json ? json_decode( $items_json, true ) : [];
    if ( ! is_array( $items ) ) $items = [];
    wp_nonce_field( 'apex_grips_items_save', 'apex_grips_items_nonce' );
    ?>
    <p style="color:#666;margin-bottom:12px;">Add grip listings. Each item needs an image, name, description, and price. Drag rows to reorder.</p>

    <div id="grips_items_list" style="margin-bottom:12px;">
        <?php foreach ( $items as $item ) :
            $att_id = (int) ( $item['id'] ?? 0 );
            $src    = $att_id ? wp_get_attachment_image_url( $att_id, 'thumbnail' ) : '';
        ?>
        <div class="grips-item-row" data-id="<?php echo $att_id; ?>" draggable="true" style="display:flex;align-items:flex-start;gap:10px;padding:10px;margin-bottom:8px;background:#f9f9f9;border:1px solid #ddd;border-radius:4px;cursor:grab;">
            <div style="flex-shrink:0;width:60px;height:60px;border-radius:3px;overflow:hidden;border:1px solid #ccc;">
                <?php if ( $src ) : ?>
                <img src="<?php echo esc_url( $src ); ?>" style="width:100%;height:100%;object-fit:cover;">
                <?php else : ?>
                <div style="background:#ddd;width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:10px;color:#999;">No img</div>
                <?php endif; ?>
            </div>
            <div style="flex:1;display:grid;grid-template-columns:1fr 1fr;gap:6px;">
                <div>
                    <label style="font-size:11px;font-weight:600;display:block;margin-bottom:2px;">Name / Label</label>
                    <input type="text" class="grips-item-label" value="<?php echo esc_attr( $item['label'] ?? '' ); ?>" style="width:100%;font-size:12px;">
                </div>
                <div>
                    <label style="font-size:11px;font-weight:600;display:block;margin-bottom:2px;">Price ($)</label>
                    <input type="text" class="grips-item-price" value="<?php echo esc_attr( $item['price'] ?? '90' ); ?>" style="width:100%;font-size:12px;">
                </div>
                <div style="grid-column:1/-1;">
                    <label style="font-size:11px;font-weight:600;display:block;margin-bottom:2px;">Description</label>
                    <textarea class="grips-item-desc" style="width:100%;font-size:12px;height:50px;resize:vertical;"><?php echo esc_textarea( $item['desc'] ?? '' ); ?></textarea>
                </div>
            </div>
            <button type="button" onclick="this.closest('.grips-item-row').remove();apexSyncGrips();" style="background:none;border:none;cursor:pointer;font-size:18px;color:#a00;padding:0;line-height:1;flex-shrink:0;">×</button>
        </div>
        <?php endforeach; ?>
    </div>

    <input type="hidden" id="grips_items_json" name="grips_items_json" value="<?php echo esc_attr( $items_json ?: '[]' ); ?>">
    <button type="button" class="button" onclick="apexOpenGripsMedia()">+ Add Grip Images</button>

    <script>
    function apexSyncGrips() {
        var rows  = document.querySelectorAll('#grips_items_list .grips-item-row');
        var items = Array.from(rows).map(function(row) {
            return {
                id:    row.dataset.id,
                label: row.querySelector('.grips-item-label').value,
                desc:  row.querySelector('.grips-item-desc').value,
                price: row.querySelector('.grips-item-price').value
            };
        });
        document.getElementById('grips_items_json').value = JSON.stringify(items);
    }
    document.getElementById('grips_items_list').addEventListener('input', apexSyncGrips);

    function apexOpenGripsMedia() {
        var frame = wp.media({
            title:    'Select Grip Images',
            button:   { text: 'Add to Listings' },
            multiple: true,
            library:  { type: 'image' }
        });
        frame.on('select', function() {
            var sel = frame.state().get('selection');
            sel.each(function(att) {
                var data  = att.toJSON();
                var list  = document.getElementById('grips_items_list');
                var src   = (data.sizes && data.sizes.thumbnail) ? data.sizes.thumbnail.url : data.url;
                var label = (data.caption || data.title || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
                var row   = document.createElement('div');
                row.className     = 'grips-item-row';
                row.dataset.id    = data.id;
                row.draggable     = true;
                row.style.cssText = 'display:flex;align-items:flex-start;gap:10px;padding:10px;margin-bottom:8px;background:#f9f9f9;border:1px solid #ddd;border-radius:4px;cursor:grab;';
                row.innerHTML =
                    '<div style="flex-shrink:0;width:60px;height:60px;border-radius:3px;overflow:hidden;border:1px solid #ccc;">'
                    + '<img src="' + src + '" style="width:100%;height:100%;object-fit:cover;"></div>'
                    + '<div style="flex:1;display:grid;grid-template-columns:1fr 1fr;gap:6px;">'
                    + '<div><label style="font-size:11px;font-weight:600;display:block;margin-bottom:2px;">Name / Label</label>'
                    + '<input type="text" class="grips-item-label" value="' + label + '" style="width:100%;font-size:12px;"></div>'
                    + '<div><label style="font-size:11px;font-weight:600;display:block;margin-bottom:2px;">Price ($)</label>'
                    + '<input type="text" class="grips-item-price" value="90" style="width:100%;font-size:12px;"></div>'
                    + '<div style="grid-column:1/-1;"><label style="font-size:11px;font-weight:600;display:block;margin-bottom:2px;">Description</label>'
                    + '<textarea class="grips-item-desc" style="width:100%;font-size:12px;height:50px;resize:vertical;"></textarea></div></div>'
                    + '<button type="button" onclick="this.closest(\'.grips-item-row\').remove();apexSyncGrips();" style="background:none;border:none;cursor:pointer;font-size:18px;color:#a00;padding:0;line-height:1;flex-shrink:0;">×</button>';
                list.appendChild(row);
                apexSyncGrips();
            });
        });
        frame.open();
    }

    (function() {
        var list    = document.getElementById('grips_items_list');
        var dragged = null;
        list.addEventListener('dragstart', function(e) { dragged = e.target.closest('.grips-item-row'); if (dragged) dragged.style.opacity = '0.4'; });
        list.addEventListener('dragend',   function()  { if (dragged) { dragged.style.opacity = '1'; dragged = null; apexSyncGrips(); } });
        list.addEventListener('dragover',  function(e) { e.preventDefault(); });
        list.addEventListener('drop',      function(e) { e.preventDefault(); var t = e.target.closest('.grips-item-row'); if (dragged && t && dragged !== t) { list.insertBefore(dragged, t.nextSibling); apexSyncGrips(); } });
        new MutationObserver(function() {
            list.querySelectorAll('.grips-item-row:not([draggable])').forEach(function(el) { el.setAttribute('draggable', 'true'); });
        }).observe(list, { childList: true });
    })();

    apexSyncGrips();
    </script>
    <?php
}

/* ── Recent Projects portfolio meta box callback ── */
function apex_home_portfolio_meta_box( $post, $meta_box ) {
    $target_id = $meta_box['args']['post_id'];
    if ( (int) $post->ID !== $target_id ) return;

    $items_json = get_post_meta( $post->ID, 'home_portfolio_json', true );
    $items      = $items_json ? json_decode( $items_json, true ) : [];
    if ( ! is_array( $items ) ) $items = [];
    wp_nonce_field( 'apex_home_portfolio_save', 'apex_home_portfolio_nonce' );
    ?>
    <p style="color:#666;margin-bottom:12px;">Images shown in the "Recent Projects" section on the homepage. Each item has a small category label and a larger title. Drag rows to reorder. No limit on count.</p>

    <div id="portfolio_items_list" style="margin-bottom:12px;">
        <?php foreach ( $items as $item ) :
            $att_id = (int) ( $item['id'] ?? 0 );
            $src    = $att_id ? wp_get_attachment_image_url( $att_id, 'thumbnail' ) : '';
        ?>
        <div class="portfolio-item-row" data-id="<?php echo $att_id; ?>" draggable="true" style="display:flex;align-items:flex-start;gap:10px;padding:10px;margin-bottom:8px;background:#f9f9f9;border:1px solid #ddd;border-radius:4px;cursor:grab;">
            <div style="flex-shrink:0;width:70px;height:70px;border-radius:3px;overflow:hidden;border:1px solid #ccc;">
                <?php if ( $src ) : ?>
                <img src="<?php echo esc_url( $src ); ?>" style="width:100%;height:100%;object-fit:cover;">
                <?php else : ?>
                <div style="background:#ddd;width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:10px;color:#999;">No img</div>
                <?php endif; ?>
            </div>
            <div style="flex:1;display:grid;grid-template-columns:1fr 1fr;gap:6px;">
                <div>
                    <label style="font-size:11px;font-weight:600;display:block;margin-bottom:2px;">Category (small label)</label>
                    <input type="text" class="portfolio-item-cat" value="<?php echo esc_attr( $item['cat'] ?? '' ); ?>" placeholder="e.g. Pistol Engraving" style="width:100%;font-size:12px;">
                </div>
                <div>
                    <label style="font-size:11px;font-weight:600;display:block;margin-bottom:2px;">Title (large text)</label>
                    <input type="text" class="portfolio-item-name" value="<?php echo esc_attr( $item['name'] ?? '' ); ?>" placeholder="e.g. 1911 Floral Scroll" style="width:100%;font-size:12px;">
                </div>
            </div>
            <button type="button" onclick="this.closest('.portfolio-item-row').remove();apexSyncPortfolio();" style="background:none;border:none;cursor:pointer;font-size:18px;color:#a00;padding:0;line-height:1;flex-shrink:0;">×</button>
        </div>
        <?php endforeach; ?>
    </div>

    <input type="hidden" id="home_portfolio_json" name="home_portfolio_json" value="<?php echo esc_attr( $items_json ?: '[]' ); ?>">
    <button type="button" class="button" onclick="apexOpenPortfolioMedia()">+ Add Portfolio Images</button>

    <script>
    function apexSyncPortfolio() {
        var rows  = document.querySelectorAll('#portfolio_items_list .portfolio-item-row');
        var items = Array.from(rows).map(function(row) {
            return {
                id:   row.dataset.id,
                cat:  row.querySelector('.portfolio-item-cat').value,
                name: row.querySelector('.portfolio-item-name').value
            };
        });
        document.getElementById('home_portfolio_json').value = JSON.stringify(items);
    }
    document.getElementById('portfolio_items_list').addEventListener('input', apexSyncPortfolio);

    function apexOpenPortfolioMedia() {
        var frame = wp.media({
            title:    'Select Portfolio Images',
            button:   { text: 'Add to Portfolio' },
            multiple: true,
            library:  { type: 'image' }
        });
        frame.on('select', function() {
            var sel = frame.state().get('selection');
            sel.each(function(att) {
                var data  = att.toJSON();
                var list  = document.getElementById('portfolio_items_list');
                var src   = (data.sizes && data.sizes.thumbnail) ? data.sizes.thumbnail.url : data.url;
                var label = (data.caption || data.title || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
                var row   = document.createElement('div');
                row.className     = 'portfolio-item-row';
                row.dataset.id    = data.id;
                row.draggable     = true;
                row.style.cssText = 'display:flex;align-items:flex-start;gap:10px;padding:10px;margin-bottom:8px;background:#f9f9f9;border:1px solid #ddd;border-radius:4px;cursor:grab;';
                row.innerHTML =
                    '<div style="flex-shrink:0;width:70px;height:70px;border-radius:3px;overflow:hidden;border:1px solid #ccc;">'
                    + '<img src="' + src + '" style="width:100%;height:100%;object-fit:cover;"></div>'
                    + '<div style="flex:1;display:grid;grid-template-columns:1fr 1fr;gap:6px;">'
                    + '<div><label style="font-size:11px;font-weight:600;display:block;margin-bottom:2px;">Category (small label)</label>'
                    + '<input type="text" class="portfolio-item-cat" value="" placeholder="e.g. Pistol Engraving" style="width:100%;font-size:12px;"></div>'
                    + '<div><label style="font-size:11px;font-weight:600;display:block;margin-bottom:2px;">Title (large text)</label>'
                    + '<input type="text" class="portfolio-item-name" value="' + label + '" placeholder="e.g. 1911 Floral Scroll" style="width:100%;font-size:12px;"></div>'
                    + '</div>'
                    + '<button type="button" onclick="this.closest(\'.portfolio-item-row\').remove();apexSyncPortfolio();" style="background:none;border:none;cursor:pointer;font-size:18px;color:#a00;padding:0;line-height:1;flex-shrink:0;">×</button>';
                list.appendChild(row);
                apexSyncPortfolio();
            });
        });
        frame.open();
    }

    (function() {
        var list    = document.getElementById('portfolio_items_list');
        var dragged = null;
        list.addEventListener('dragstart', function(e) { dragged = e.target.closest('.portfolio-item-row'); if (dragged) dragged.style.opacity = '0.4'; });
        list.addEventListener('dragend',   function()  { if (dragged) { dragged.style.opacity = '1'; dragged = null; apexSyncPortfolio(); } });
        list.addEventListener('dragover',  function(e) { e.preventDefault(); });
        list.addEventListener('drop',      function(e) { e.preventDefault(); var t = e.target.closest('.portfolio-item-row'); if (dragged && t && dragged !== t) { list.insertBefore(dragged, t.nextSibling); apexSyncPortfolio(); } });
        new MutationObserver(function() {
            list.querySelectorAll('.portfolio-item-row:not([draggable])').forEach(function(el) { el.setAttribute('draggable', 'true'); });
        }).observe(list, { childList: true });
    })();

    apexSyncPortfolio();
    </script>
    <?php
}

/* ── Shared UI renderer ── */
function apex_render_gallery_meta_box_ui( $field_name, $ids_csv, $instructions ) {
    $ids    = array_filter( explode( ',', (string) $ids_csv ) );
    $uniq   = esc_js( $field_name );
    ?>
    <p style="color:#666;margin-bottom:12px;"><?php echo esc_html( $instructions ); ?></p>

    <div id="<?php echo $uniq; ?>_preview" style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px;min-height:60px;padding:8px;background:#f9f9f9;border:1px dashed #ccc;border-radius:4px;">
        <?php foreach ( $ids as $id ) :
            $src = wp_get_attachment_image_url( (int) $id, 'thumbnail' );
            if ( ! $src ) continue;
        ?>
        <div class="apex-gallery-thumb" data-id="<?php echo (int) $id; ?>" style="position:relative;cursor:grab;width:80px;height:80px;border-radius:3px;overflow:hidden;border:2px solid #ddd;">
            <img src="<?php echo esc_url( $src ); ?>" style="width:100%;height:100%;object-fit:cover;">
            <button type="button" onclick="apexRemoveThumb(this,'<?php echo $uniq; ?>')" style="position:absolute;top:2px;right:2px;background:rgba(0,0,0,0.65);color:#fff;border:none;border-radius:50%;width:18px;height:18px;font-size:12px;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;">×</button>
        </div>
        <?php endforeach; ?>
    </div>

    <input type="hidden" id="<?php echo $uniq; ?>_ids" name="<?php echo esc_attr( $field_name ); ?>" value="<?php echo esc_attr( $ids_csv ); ?>">

    <button type="button" class="button" onclick="apexOpenMediaModal('<?php echo $uniq; ?>')">
        + Add / Select Images
    </button>

    <script>
    (function(){
        var field = '<?php echo $uniq; ?>';

        window.apexOpenMediaModal = window.apexOpenMediaModal || function(f) {
            var frame = wp.media({
                title: 'Select Images',
                button: { text: 'Add to Gallery' },
                multiple: true,
                library: { type: 'image' }
            });
            frame.on('select', function(){
                var sel = frame.state().get('selection');
                sel.each(function(att){
                    apexAddThumb(att.toJSON(), f);
                });
                apexSyncIds(f);
            });
            frame.open();
        };

        window.apexAddThumb = window.apexAddThumb || function(att, f){
            var preview = document.getElementById(f + '_preview');
            // avoid duplicates
            if (preview.querySelector('[data-id="'+att.id+'"]')) return;
            var div = document.createElement('div');
            div.className = 'apex-gallery-thumb';
            div.dataset.id = att.id;
            div.style.cssText = 'position:relative;cursor:grab;width:80px;height:80px;border-radius:3px;overflow:hidden;border:2px solid #ddd;';
            var src = (att.sizes && att.sizes.thumbnail) ? att.sizes.thumbnail.url : att.url;
            div.innerHTML = '<img src="'+src+'" style="width:100%;height:100%;object-fit:cover;">'
                          + '<button type="button" onclick="apexRemoveThumb(this,\''+f+'\')" style="position:absolute;top:2px;right:2px;background:rgba(0,0,0,0.65);color:#fff;border:none;border-radius:50%;width:18px;height:18px;font-size:12px;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;">×</button>';
            preview.appendChild(div);
        };

        window.apexRemoveThumb = window.apexRemoveThumb || function(btn, f){
            btn.closest('.apex-gallery-thumb').remove();
            apexSyncIds(f);
        };

        window.apexSyncIds = window.apexSyncIds || function(f){
            var thumbs = document.querySelectorAll('#'+f+'_preview .apex-gallery-thumb');
            var ids = Array.from(thumbs).map(function(el){ return el.dataset.id; });
            document.getElementById(f+'_ids').value = ids.join(',');
        };

        // Make thumbs sortable via native drag-and-drop
        (function initDrag(f){
            var preview = document.getElementById(f+'_preview');
            if (!preview) return;
            var dragged = null;
            preview.addEventListener('dragstart', function(e){
                dragged = e.target.closest('.apex-gallery-thumb');
                if (dragged) dragged.style.opacity = '0.4';
            });
            preview.addEventListener('dragend', function(){ if(dragged){ dragged.style.opacity='1'; dragged=null; apexSyncIds(f); } });
            preview.addEventListener('dragover', function(e){ e.preventDefault(); });
            preview.addEventListener('drop', function(e){
                e.preventDefault();
                var target = e.target.closest('.apex-gallery-thumb');
                if (dragged && target && dragged !== target) {
                    preview.insertBefore(dragged, target.nextSibling);
                }
            });
            document.querySelectorAll('#'+f+'_preview .apex-gallery-thumb').forEach(function(el){
                el.setAttribute('draggable','true');
            });
            // also make dynamically added thumbs draggable
            new MutationObserver(function(){
                document.querySelectorAll('#'+f+'_preview .apex-gallery-thumb:not([draggable])').forEach(function(el){
                    el.setAttribute('draggable','true');
                });
            }).observe(preview, {childList:true});
        })(field);
    })();
    </script>
    <?php
}

/* ── Save handlers ── */
add_action( 'save_post', 'apex_save_media_meta_boxes' );
function apex_save_media_meta_boxes( $post_id ) {
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    // Slider
    if ( isset( $_POST['apex_home_slider_nonce'] ) &&
         wp_verify_nonce( $_POST['apex_home_slider_nonce'], 'apex_home_slider_save' ) &&
         isset( $_POST['home_slider_ids'] ) ) {
        $ids = implode( ',', array_filter( array_map( 'absint', explode( ',', sanitize_text_field( $_POST['home_slider_ids'] ) ) ) ) );
        update_post_meta( $post_id, 'home_slider_ids', $ids );
    }

    // PMAG Gallery
    if ( isset( $_POST['apex_pmag_gallery_nonce'] ) &&
         wp_verify_nonce( $_POST['apex_pmag_gallery_nonce'], 'apex_pmag_gallery_save' ) &&
         isset( $_POST['pmag_gallery_ids'] ) ) {
        $ids = implode( ',', array_filter( array_map( 'absint', explode( ',', sanitize_text_field( $_POST['pmag_gallery_ids'] ) ) ) ) );
        update_post_meta( $post_id, 'pmag_gallery_ids', $ids );
    }

    // Gallery — images + videos
    if ( isset( $_POST['apex_gallery_media_nonce'] ) &&
         wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['apex_gallery_media_nonce'] ) ), 'apex_gallery_media_save' ) ) {
        if ( isset( $_POST['gallery_image_ids'] ) ) {
            $ids = implode( ',', array_filter( array_map( 'absint', explode( ',', sanitize_text_field( wp_unslash( $_POST['gallery_image_ids'] ) ) ) ) ) );
            update_post_meta( $post_id, 'gallery_image_ids', $ids );
        }
        if ( isset( $_POST['gallery_video_ids'] ) ) {
            $ids = implode( ',', array_filter( array_map( 'absint', explode( ',', sanitize_text_field( wp_unslash( $_POST['gallery_video_ids'] ) ) ) ) ) );
            update_post_meta( $post_id, 'gallery_video_ids', $ids );
        }
    }

    // Heroes gallery
    if ( isset( $_POST['apex_heroes_gallery_nonce'] ) &&
         wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['apex_heroes_gallery_nonce'] ) ), 'apex_heroes_gallery_save' ) &&
         isset( $_POST['heroes_gallery_ids'] ) ) {
        $ids = implode( ',', array_filter( array_map( 'absint', explode( ',', sanitize_text_field( wp_unslash( $_POST['heroes_gallery_ids'] ) ) ) ) ) );
        update_post_meta( $post_id, 'heroes_gallery_ids', $ids );
    }

    // Recent Projects portfolio
    if ( isset( $_POST['apex_home_portfolio_nonce'] ) &&
         wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['apex_home_portfolio_nonce'] ) ), 'apex_home_portfolio_save' ) &&
         isset( $_POST['home_portfolio_json'] ) ) {
        $raw   = wp_unslash( $_POST['home_portfolio_json'] );
        $items = json_decode( $raw, true );
        if ( is_array( $items ) ) {
            $clean = array_map( function( $item ) {
                return [
                    'id'   => absint( $item['id']   ?? 0 ),
                    'cat'  => sanitize_text_field( $item['cat']  ?? '' ),
                    'name' => sanitize_text_field( $item['name'] ?? '' ),
                ];
            }, $items );
            update_post_meta( $post_id, 'home_portfolio_json', wp_json_encode( $clean ) );
        }
    }

    // 1911 Grip listings
    if ( isset( $_POST['apex_grips_items_nonce'] ) &&
         wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['apex_grips_items_nonce'] ) ), 'apex_grips_items_save' ) &&
         isset( $_POST['grips_items_json'] ) ) {
        $raw   = wp_unslash( $_POST['grips_items_json'] );
        $items = json_decode( $raw, true );
        if ( is_array( $items ) ) {
            $clean = array_map( function( $item ) {
                return [
                    'id'    => absint( $item['id']    ?? 0 ),
                    'label' => sanitize_text_field( $item['label']    ?? '' ),
                    'desc'  => sanitize_textarea_field( $item['desc'] ?? '' ),
                    'price' => sanitize_text_field( $item['price']    ?? '90' ),
                ];
            }, $items );
            update_post_meta( $post_id, 'grips_items_json', wp_json_encode( $clean ) );
        }
    }
}

/* ── Enqueue wp.media on the page editor ── */
add_action( 'admin_enqueue_scripts', 'apex_enqueue_media_for_meta_boxes' );
function apex_enqueue_media_for_meta_boxes( $hook ) {
    if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ] ) ) return;
    if ( get_post_type() !== 'page' ) return;
    wp_enqueue_media();
}
