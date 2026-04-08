<?php
/**
 * Auto-activates apex-block-theme once, then self-deletes.
 * Runs on every page load until activation succeeds.
 */
add_action('init', function() {
    if (get_stylesheet() === 'apex-block-theme') {
        // Already active — remove self
        @unlink(__FILE__);
        return;
    }
    if (!get_option('apex_block_theme_activation_attempted')) {
        update_option('apex_block_theme_activation_attempted', 1);
        switch_theme('apex-block-theme');
        // Flush rules after switch
        flush_rewrite_rules(false);
    }
    if (get_stylesheet() === 'apex-block-theme') {
        @unlink(__FILE__);
    }
}, 1);
