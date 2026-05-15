<?php
/**
 * Reviews Service — Helper Functions
 *
 * These are utility functions used by ALL templates.
 *
 * Available functions:
 *   rs_get_part()        → include any template-parts file with variables
 *   rs_get_section()     → shorthand: loads template-parts/sections/
 *   rs_get_component()   → shorthand: loads template-parts/components/
 *   rs_get_ui()          → shorthand: loads template-parts/ui/
 *   rs_option()          → get theme customizer value with fallback
 *   rs_svg()             → output inline SVG from assets/icons/
 *   rs_get_meta()        → get post meta with fallback
 *   rs_reading_time()    → estimate blog post reading time
 *   rs_active_class()    → output CSS class if condition is true
 *   rs_kses_basic()      → allowed HTML for wp_kses()
 *   rs_get_breadcrumb()  → array of breadcrumb items for templates
 *   rs_e()               → echo esc_html() shorthand
 *   rs_url()             → echo esc_url() shorthand
 *   rs_attr()            → echo esc_attr() shorthand
 *
 * @package reviewsservice
 */

defined( 'ABSPATH' ) || exit;

// ─── 1. Template Part Loader ──────────────────────────────────────────────────
/**
 * Load a template part and pass variables to it.
 *
 * Usage:
 *   rs_get_part( 'sections/hero' );
 *   rs_get_part( 'components/service-card', [ 'title' => 'SEO', 'url' => '/services/seo' ] );
 *   rs_get_part( 'ui/button', [ 'text' => 'Get Started', 'url' => '/contact' ] );
 *
 * @param string $slug  Path relative to /template-parts/ (without .php)
 * @param array  $args  Variables available inside the template as $variable_name
 */
function rs_get_part( string $slug, array $args = [] ): void {
    $file = get_template_directory() . '/template-parts/' . $slug . '.php';

    if ( ! file_exists( $file ) ) {
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            // phpcs:ignore
            error_log( 'ReviewsService: Missing template part: ' . $file );
        }
        return;
    }

    if ( ! empty( $args ) ) {
        // phpcs:ignore WordPress.PHP.DontExtract
        extract( $args, EXTR_SKIP );
    }

    include $file;
}

/**
 * Shorthand for sections.
 * rs_get_section( 'hero' ) → template-parts/sections/hero.php
 */
function rs_get_section( string $name, array $args = [] ): void {
    rs_get_part( 'sections/' . $name, $args );
}

/**
 * Shorthand for components.
 * rs_get_component( 'service-card', [...] ) → template-parts/components/service-card.php
 */
function rs_get_component( string $name, array $args = [] ): void {
    rs_get_part( 'components/' . $name, $args );
}

/**
 * Shorthand for UI elements.
 * rs_get_ui( 'button', [...] ) → template-parts/ui/button.php
 */
function rs_get_ui( string $name, array $args = [] ): void {
    rs_get_part( 'ui/' . $name, $args );
}

// ─── 2. Customizer Value ─────────────────────────────────────────────────────
/**
 * Get a WordPress Customizer setting with a fallback.
 *
 * @param string $key      Customizer setting key
 * @param mixed  $default  Default if not set
 * @return mixed
 */
function rs_option( string $key, mixed $default = '' ): mixed {
    return get_theme_mod( $key, $default );
}

// ─── 3. Inline SVG ───────────────────────────────────────────────────────────
/**
 * Output an inline SVG from /assets/icons/
 *
 * @param string $name   Filename without .svg
 * @param string $class  CSS classes to add
 * @param string $aria   aria-label (empty = decorative)
 */
function rs_svg( string $name, string $class = '', string $aria = '' ): void {
    $path = get_template_directory() . '/assets/icons/' . sanitize_file_name( $name ) . '.svg';

    if ( ! file_exists( $path ) ) return;

    $svg = file_get_contents( $path ); // phpcs:ignore

    if ( $class ) {
        $svg = preg_replace( '/<svg/', '<svg class="' . esc_attr( $class ) . '"', $svg, 1 );
    }

    if ( $aria ) {
        $svg = preg_replace( '/<svg/', '<svg role="img" aria-label="' . esc_attr( $aria ) . '"', $svg, 1 );
    } else {
        $svg = preg_replace( '/<svg/', '<svg aria-hidden="true" focusable="false"', $svg, 1 );
    }

    echo $svg; // phpcs:ignore
}

// ─── 4. Post Meta ────────────────────────────────────────────────────────────
/**
 * Get post meta with fallback.
 *
 * @param string $key      Meta key
 * @param mixed  $default  Default if empty
 * @param int    $post_id  Post ID (0 = current post)
 */
function rs_get_meta( string $key, mixed $default = '', int $post_id = 0 ): mixed {
    $post_id = $post_id ?: get_the_ID();
    $value   = get_post_meta( $post_id, $key, true );
    return ! empty( $value ) ? $value : $default;
}

// ─── 5. Reading Time ─────────────────────────────────────────────────────────
/**
 * Estimate reading time. Returns e.g. "4 min read"
 *
 * @param int $post_id  Post ID (0 = current)
 * @return string
 */
function rs_reading_time( int $post_id = 0 ): string {
    $content  = get_post_field( 'post_content', $post_id ?: get_the_ID() );
    $count    = str_word_count( wp_strip_all_tags( $content ) );
    $minutes  = max( 1, (int) ceil( $count / 200 ) );

    /* translators: %d = number of minutes */
    return sprintf( _n( '%d min read', '%d min read', $minutes, RS_DOMAIN ), $minutes );
}

// ─── 6. Active Class ─────────────────────────────────────────────────────────
/**
 * Output a CSS class string if condition is true.
 * Useful for nav links, active tabs, selected states.
 *
 * @param bool   $condition
 * @param string $class     Class name to output (default 'active')
 * @param bool   $echo      Echo (true) or return (false)
 */
function rs_active_class( bool $condition, string $class = 'active', bool $echo = true ): string {
    $output = $condition ? $class : '';
    if ( $echo ) echo esc_attr( $output );
    return $output;
}

// ─── 7. Allowed HTML ─────────────────────────────────────────────────────────
/**
 * Returns allowed HTML tags for wp_kses() in content areas.
 * Use: echo wp_kses( $content, rs_kses_basic() );
 */
function rs_kses_basic(): array {
    return [
        'strong' => [],
        'em'     => [],
        'br'     => [],
        'span'   => [ 'class' => true, 'id' => true ],
        'a'      => [ 'href' => true, 'title' => true, 'target' => true, 'rel' => true, 'class' => true, 'aria-label' => true ],
        'p'      => [ 'class' => true ],
        'ul'     => [ 'class' => true ],
        'ol'     => [ 'class' => true ],
        'li'     => [ 'class' => true ],
        'mark'   => [ 'class' => true ],
        'code'   => [],
        'del'    => [],
        'ins'    => [],
    ];
}

// ─── 8. Breadcrumb Data ───────────────────────────────────────────────────────
/**
 * Build breadcrumb items array.
 * Used by template-parts/ui/breadcrumbs.php for display
 * and by inc/seo-schema.php for BreadcrumbList schema.
 *
 * Returns: [ ['label' => 'Home', 'url' => '/', 'current' => false], ... ]
 */
function rs_get_breadcrumb(): array {
    $crumbs = [];

    $crumbs[] = [
        'label'   => __( 'Home', RS_DOMAIN ),
        'url'     => home_url( '/' ),
        'current' => false,
    ];

    if ( is_singular() ) {
        if ( 'post' === get_post_type() ) {
            $blog_id = (int) get_option( 'page_for_posts' );
            if ( $blog_id ) {
                $crumbs[] = [
                    'label'   => get_the_title( $blog_id ),
                    'url'     => get_permalink( $blog_id ),
                    'current' => false,
                ];
            }
        }

        if ( 'rs_service' === get_post_type() ) {
            $crumbs[] = [
                'label'   => __( 'Services', RS_DOMAIN ),
                'url'     => home_url( '/services/' ),
                'current' => false,
            ];
        }

        if ( 'rs_case_study' === get_post_type() ) {
            $crumbs[] = [
                'label'   => __( 'Case Studies', RS_DOMAIN ),
                'url'     => home_url( '/case-studies/' ),
                'current' => false,
            ];
        }

        $crumbs[] = [
            'label'   => get_the_title(),
            'url'     => '',
            'current' => true,
        ];

    } elseif ( is_page() ) {
        $crumbs[] = [
            'label'   => get_the_title(),
            'url'     => '',
            'current' => true,
        ];

    } elseif ( is_archive() ) {
        $crumbs[] = [
            'label'   => get_the_archive_title(),
            'url'     => '',
            'current' => true,
        ];

    } elseif ( is_search() ) {
        $crumbs[] = [
            /* translators: %s = search term */
            'label'   => sprintf( __( 'Search: %s', RS_DOMAIN ), get_search_query() ),
            'url'     => '',
            'current' => true,
        ];

    } elseif ( is_404() ) {
        $crumbs[] = [
            'label'   => __( 'Page Not Found', RS_DOMAIN ),
            'url'     => '',
            'current' => true,
        ];
    }

    return $crumbs;
}

// ─── 9. Safe Output Shorthands ───────────────────────────────────────────────
/** Echo escaped text */
function rs_e( string $text ): void {
    echo esc_html( $text );
}

/** Echo escaped URL */
function rs_url( string $url ): void {
    echo esc_url( $url );
}

/** Echo escaped attribute */
function rs_attr( string $text ): void {
    echo esc_attr( $text );
}

// ─── 10. WhatsApp URL Builder ────────────────────────────────────────────────
/**
 * Build a wa.me link from the Customizer phone number.
 *
 * @param string $message  Pre-filled message text
 * @return string          Full WhatsApp URL or empty string if no number
 */
function rs_whatsapp_url( string $message = '' ): string {
    $number = preg_replace( '/[^0-9]/', '', rs_option( 'rs_whatsapp_number', '' ) );
    if ( ! $number ) return '';

    $msg = $message ?: __( 'Hello! I found you through your website and would like to know more about your services.', RS_DOMAIN );
    return 'https://wa.me/' . $number . '?text=' . rawurlencode( $msg );
}

// ─── 11. Image helper (used in templates) ────────────────────────────────────
/**
 * Output a WordPress attachment image with lazy loading defaults.
 *
 * @param int    $id      Attachment ID
 * @param string $size    Image size slug
 * @param array  $attrs   Additional attributes
 */
function rs_attachment_img( int $id, string $size = 'large', array $attrs = [] ): string {
    if ( ! $id ) return '';

    $defaults = [
        'loading'  => 'lazy',
        'decoding' => 'async',
    ];

    return wp_get_attachment_image( $id, $size, false, array_merge( $defaults, $attrs ) );
}

// ─── 12. Services query ──────────────────────────────────────────────────────
/**
 * Get published services as WP_Post array.
 *
 * @param int $limit  -1 = all
 */
function rs_get_services( int $limit = -1 ): array {
    return get_posts( [
        'post_type'      => 'rs_service',
        'posts_per_page' => $limit,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'no_found_rows'  => true,
    ] );
}

/**
 * Get published testimonials.
 */
function rs_get_testimonials( int $limit = 9 ): array {
    return get_posts( [
        'post_type'      => 'rs_testimonial',
        'posts_per_page' => $limit,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'no_found_rows'  => true,
    ] );
}

/**
 * Get published case studies.
 */
function rs_get_case_studies( int $limit = 3 ): array {
    return get_posts( [
        'post_type'      => 'rs_case_study',
        'posts_per_page' => $limit,
        'post_status'    => 'publish',
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'no_found_rows'  => true,
    ] );
}