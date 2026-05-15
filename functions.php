<?php
/**
 * Reviews Service — functions.php
 * Master loader only. Zero logic lives here.
 * All features are in /inc/ files.
 *
 * @package reviewsservice
 */

defined( 'ABSPATH' ) || exit;

// ─── Theme Constants ──────────────────────────────────────────────────────────
define( 'RS_VERSION',  '1.0.0' );
define( 'RS_DIR',      get_template_directory() );
define( 'RS_URI',      get_template_directory_uri() );
define( 'RS_DOMAIN',   'reviewsservice' );

// ─── Load order is important — do not rearrange ───────────────────────────────
$_rs_files = [
    '/inc/helpers.php',            // Loaded first — all other files use it
    '/inc/security.php',
    '/inc/theme-setup.php',
    '/inc/enqueue.php',            // ALL CSS/JS loading is here — nowhere else
    '/inc/custom-post-types.php',
    '/inc/seo-schema.php',
    '/inc/customizer.php',
    '/inc/woocommerce-hooks.php',
    '/inc/dashboard-functions.php',
];

foreach ( $_rs_files as $_file ) {
    $_path = RS_DIR . $_file;
    if ( file_exists( $_path ) ) {
        require_once $_path;
    }
}

unset( $_rs_files, $_file, $_path );