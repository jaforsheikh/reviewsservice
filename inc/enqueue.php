<?php
/**
 * Reviews Service — Asset Enqueue
 *
 * This is the ONLY place where CSS and JS are loaded.
 * functions.php must NOT have any wp_enqueue_scripts hooks.
 *
 * CSS entry:   src/input.css → compiled to assets/css/app.css
 * JS loading:  assets/js/global.js (sitewide)
 *              assets/js/home.js   (homepage only)
 *              assets/js/dashboard.js (dashboard only)
 *
 * @package reviewsservice
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', 'rs_enqueue_assets' );

function rs_enqueue_assets(): void {

    // ── 1. Google Fonts ───────────────────────────────────────────────────────
    wp_enqueue_style(
        'rs-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Poppins:wght@500;600;700;800;900&display=swap',
        [],
        null
    );

    // ── 2. Compiled Tailwind CSS ──────────────────────────────────────────────
    $css_path = RS_DIR . '/assets/css/app.css';

    if ( file_exists( $css_path ) ) {
        wp_enqueue_style(
            'rs-app',
            RS_URI . '/assets/css/app.css',
            [ 'rs-fonts' ],
            filemtime( $css_path )
        );
    }

    // ── 3. Global JS (every page) ─────────────────────────────────────────────
    $global_js = RS_DIR . '/assets/js/global.js';

    if ( file_exists( $global_js ) ) {
        wp_enqueue_script(
            'rs-global',
            RS_URI . '/assets/js/global.js',
            [],
            filemtime( $global_js ),
            [ 'strategy' => 'defer', 'in_footer' => true ]
        );
    }

    // ── 4. Homepage JS (front page only) ─────────────────────────────────────
    if ( is_front_page() ) {
        $home_js = RS_DIR . '/assets/js/home.js';

        if ( file_exists( $home_js ) ) {
            wp_enqueue_script(
                'rs-home',
                RS_URI . '/assets/js/home.js',
                [ 'rs-global' ],
                filemtime( $home_js ),
                [ 'strategy' => 'defer', 'in_footer' => true ]
            );
        }
    }

    // ── 5. Dashboard JS (dashboard page only) ────────────────────────────────
    if ( is_page_template( 'page-templates/page-dashboard.php' ) ) {
        $dash_js = RS_DIR . '/assets/js/dashboard.js';

        if ( file_exists( $dash_js ) ) {
            wp_enqueue_script(
                'rs-dashboard',
                RS_URI . '/assets/js/dashboard.js',
                [ 'rs-global' ],
                filemtime( $dash_js ),
                [ 'strategy' => 'defer', 'in_footer' => true ]
            );
        }
    }

    // ── 6. Pass PHP data to JS ────────────────────────────────────────────────
    wp_localize_script( 'rs-global', 'RS', [
        'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
        'nonce'      => wp_create_nonce( 'rs_nonce' ),
        'homeUrl'    => esc_url( home_url( '/' ) ),
        'themeUrl'   => esc_url( RS_URI ),
        'isLoggedIn' => is_user_logged_in() ? '1' : '0',
        'userId'     => (string) get_current_user_id(),
        'locale'     => get_locale(),
    ] );
}

// ── Remove WooCommerce default styles ─────────────────────────────────────────
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

// ── Remove unused WordPress default styles ────────────────────────────────────
add_action( 'wp_enqueue_scripts', 'rs_remove_default_styles', 100 );

function rs_remove_default_styles(): void {
    if ( ! is_singular() || ! has_blocks() ) {
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wp-block-library-theme' );
        wp_dequeue_style( 'global-styles' );
    }
}

// ── Preconnect hints ──────────────────────────────────────────────────────────
add_action( 'wp_head', 'rs_preconnect', 1 );

function rs_preconnect(): void {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}