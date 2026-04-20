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
}

/* ── Enqueue wp.media on the page editor ── */
add_action( 'admin_enqueue_scripts', 'apex_enqueue_media_for_meta_boxes' );
function apex_enqueue_media_for_meta_boxes( $hook ) {
    if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ] ) ) return;
    if ( get_post_type() !== 'page' ) return;
    wp_enqueue_media();
}
