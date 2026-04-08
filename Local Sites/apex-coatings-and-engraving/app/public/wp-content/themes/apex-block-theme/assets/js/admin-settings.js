/**
 * Apex Admin Settings — media picker for logos and slideshow
 */
(function ($) {
    'use strict';

    /* ── Single logo image picker ──────────────────────────── */
    $(document).on('click', '.apx-pick-logo', function () {
        var key   = $(this).data('key');
        var frame = wp.media({ title: 'Choose Image', multiple: false, library: { type: 'image' } });

        frame.on('select', function () {
            var a = frame.state().get('selection').first().toJSON();
            $('#apx-input-' + key).val(a.id);

            var $preview = $('#apx-preview-' + key);
            if ($preview.is('img')) {
                $preview.attr('src', a.url);
            } else {
                // replace placeholder div with img
                $preview.replaceWith('<img src="' + a.url + '" class="apx-img-preview" id="apx-preview-' + key + '" alt="">');
            }

            // show remove button if not already there
            var $row = $('#apx-row-' + key);
            if (!$row.find('.apx-clear-logo').length) {
                $row.append('<button type="button" class="apx-btn-clear apx-clear-logo" data-key="' + key + '">Remove</button>');
            }
        });

        frame.open();
    });

    $(document).on('click', '.apx-clear-logo', function () {
        var key = $(this).data('key');
        $('#apx-input-' + key).val('');
        var $preview = $('#apx-preview-' + key);
        $preview.replaceWith('<div class="apx-img-placeholder" id="apx-preview-' + key + '">No image</div>');
        $(this).remove();
    });

    /* ── Slideshow gallery picker ──────────────────────────── */
    var slideshowFrame;

    $('#apx-add-slides').on('click', function () {
        if (slideshowFrame) { slideshowFrame.open(); return; }

        slideshowFrame = wp.media({ title: 'Select Slideshow Photos', multiple: true, library: { type: 'image' } });

        slideshowFrame.on('select', function () {
            var current = $('#apx-slideshow-ids').val().split(',').filter(Boolean);

            slideshowFrame.state().get('selection').each(function (attachment) {
                var a     = attachment.toJSON();
                var idStr = String(a.id);
                if (current.indexOf(idStr) !== -1) return; // skip dupes

                current.push(idStr);
                var thumb = (a.sizes && a.sizes.thumbnail) ? a.sizes.thumbnail.url : a.url;
                $('#apx-slideshow-gallery').append(
                    '<div class="apx-gallery-thumb" data-id="' + idStr + '">' +
                    '<img src="' + thumb + '" alt="">' +
                    '<button type="button" class="apx-remove" title="Remove">&times;</button>' +
                    '</div>'
                );
            });

            $('#apx-slideshow-ids').val(current.join(','));
        });

        slideshowFrame.open();
    });

    $(document).on('click', '.apx-remove', function () {
        var $thumb = $(this).closest('.apx-gallery-thumb');
        var id     = String($thumb.data('id'));
        var ids    = $('#apx-slideshow-ids').val().split(',').filter(function (i) { return i !== id; });
        $('#apx-slideshow-ids').val(ids.join(','));
        $thumb.remove();
    });

}(jQuery));
