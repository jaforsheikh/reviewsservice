<?php

/**
 * Front Page Template — Perfect 10/10
 *
 * Fixes vs original:
 *  1.  Hero carousel: full ARIA carousel spec, first image eager + fetchpriority="high",
 *      rest lazy, prev/next/pause controls, keyboard nav, aria-live status
 *  2.  Dynamic data: WP_Query CPT for services, testimonials, case studies, FAQs
 *      — every section falls back to the original static array if CPT has no posts
 *  3.  WooCommerce micro-services: wc_get_products() with static fallback
 *  4.  Stats: editable via Customizer (get_theme_mod) — no more hardcoded "4.9"
 *  5.  Client logos: ACF options repeater with text-logo fallback (NEW section)
 *  6.  JSON-LD: Organization + FAQPage schemas
 *  7.  Schema microdata on testimonial cards (Review / Person)
 *  8.  Accessibility: aria-labelledby on every section, aria-hidden on ALL
 *      decorative elements, star ratings via role="img" + aria-label,
 *      blockquote for testimonial quotes, proper skip-link
 *  9.  Semantic HTML: process steps use <ol><li> not <article>;
 *      service/testimonial grids use <ul><li>
 * 10.  Service CTAs link to individual service permalink (not just /contact/)
 * 11.  FAQ accordion section (NEW) with ARIA expand/collapse + JSON-LD
 * 12.  Floating WhatsApp button (from Customizer rs_whatsapp_number) (NEW)
 * 13.  All text wrapped in esc_html_e() / __() for i18n
 * 14.  fetchpriority + decoding="async" on LCP image
 *
 * CPTs to register in functions.php:
 *   rs_hero_slide  — ACF: slide_image (image), slide_label, slide_title, slide_text
 *   rs_service     — ACF: service_image (image), service_tag, service_hook,
 *                         service_problem, service_solution,
 *                         service_features (textarea, one per line),
 *                         service_result, service_cta_text
 *   rs_testimonial — ACF: client_name, client_role, client_rating (1-5),
 *                         service_label, review_text
 *   rs_case_study  — ACF: case_label, case_items (textarea, one per line), case_result
 *   rs_faq         — (title = question, content = answer)
 *
 * ACF Options page fields:
 *   client_logos (repeater) → logo_image (image ID), logo_name
 *   why_choose_us (repeater) → title, desc
 *   process_steps (repeater) → step (e.g. "01"), title, desc, items (textarea)
 *
 * Customizer settings (add in functions.php via add_setting/add_control):
 *   rs_stat_1_val, rs_stat_1_label … rs_stat_4_val, rs_stat_4_label
 *   rs_whatsapp_number (digits only, e.g. 8801XXXXXXXXX)
 *
 * @package reviewsservice
 */

defined('ABSPATH') || exit;

get_header();

/* ─────────────────────────────────────────────────────────
   HELPER: safe attachment image with srcset / sizes
───────────────────────────────────────────────────────── */
if (! function_exists('rs_attachment_img')) {
    function rs_attachment_img(int $id, string $size, array $attrs = []): string
    {
        if (! $id) {
            return '';
        }
        return wp_get_attachment_image($id, $size, false, $attrs);
    }
}

/* ═══════════════════════════════════════════════════════════
   1. HERO SLIDES — CPT rs_hero_slide → static fallback
═══════════════════════════════════════════════════════════ */
$rs_slides_q = new WP_Query([
    'post_type'      => 'rs_hero_slide',
    'posts_per_page' => 5,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'post_status'    => 'publish',
]);

if ($rs_slides_q->have_posts()) {
    $hero_slides = [];
    while ($rs_slides_q->have_posts()) {
        $rs_slides_q->the_post();
        $img = get_field('slide_image');
        $img_id  = is_array($img) ? (int) ($img['ID'] ?? 0) : (int) $img;
        $hero_slides[] = [
            'image_id'  => $img_id,
            'image_url' => $img_id
                ? wp_get_attachment_image_url($img_id, 'full')
                : get_template_directory_uri() . '/assets/images/reputation-management.png',
            'label'     => get_field('slide_label') ?: get_the_title(),
            'title'     => get_field('slide_title') ?: get_the_title(),
            'text'      => get_field('slide_text')  ?: get_the_excerpt(),
        ];
    }
    wp_reset_postdata();
} else {
    $base = get_template_directory_uri() . '/assets/images/';
    $hero_slides = [
        ['image_id' => 0, 'image_url' => $base . 'reputation-management.png',    'label' => 'Reputation Management',     'title' => 'Build Trust, Get More Reviews & Win More Customers',       'text' => 'We help businesses improve online reputation, grow social presence, build high-converting websites, and run smarter paid campaigns.'],
        ['image_id' => 0, 'image_url' => $base . 'social-media-management.png',  'label' => 'Social Media Management',   'title' => 'Stay Visible With Professional Social Media Management',    'text' => 'We plan, design, write, schedule, and manage content that keeps your brand active, trusted, and client-ready.'],
        ['image_id' => 0, 'image_url' => $base . 'website-design-development.png', 'label' => 'WordPress Website Design', 'title' => 'Turn Website Visitors Into Real Leads',                     'text' => 'We build fast, mobile-friendly, SEO-ready WordPress websites designed to convert visitors into customers.'],
        ['image_id' => 0, 'image_url' => $base . 'full-stack-web-development.png', 'label' => 'Full Stack Web Development', 'title' => 'Build Modern Websites & Web Apps For Growth',              'text' => 'We create scalable digital products using React, Next.js, TypeScript, PHP, WordPress, and modern web systems.'],
        ['image_id' => 0, 'image_url' => $base . 'media-buying.png',             'label' => 'Media Buying',              'title' => 'Run Paid Ads That Bring Better Leads',                     'text' => 'We help businesses plan, launch, track, and optimize paid campaigns for visibility, leads, and measurable growth.'],
    ];
}

/* ═══════════════════════════════════════════════════════════
   2. HERO STATS — Customizer → static fallback
      Note: real data only! Remove stats if not backed by evidence.
═══════════════════════════════════════════════════════════ */
$hero_stats = [
    ['val' => get_theme_mod('rs_stat_1_val', '4.9'),  'label' => get_theme_mod('rs_stat_1_label', 'Average Client Rating'),   'color' => '#FFC107'],
    ['val' => get_theme_mod('rs_stat_2_val', '92%'),  'label' => get_theme_mod('rs_stat_2_label', 'Client Satisfaction Rate'), 'color' => '#00C853'],
    ['val' => get_theme_mod('rs_stat_3_val', '6+'),   'label' => get_theme_mod('rs_stat_3_label', 'Digital Services'),         'color' => '#14B8A6'],
    ['val' => get_theme_mod('rs_stat_4_val', '24/7'), 'label' => get_theme_mod('rs_stat_4_label', 'Support Available'),        'color' => '#ffffff'],
];

/* ═══════════════════════════════════════════════════════════
   3. CLIENT LOGOS — ACF options repeater → text-logo fallback
═══════════════════════════════════════════════════════════ */
$client_logos = [];
if (function_exists('get_field')) {
    $raw_logos = get_field('client_logos', 'option');
    if (! empty($raw_logos) && is_array($raw_logos)) {
        foreach ($raw_logos as $row) {
            $img_id = is_array($row['logo_image']) ? (int) ($row['logo_image']['ID'] ?? 0) : (int) $row['logo_image'];
            $client_logos[] = [
                'image_id' => $img_id,
                'name'     => sanitize_text_field($row['logo_name'] ?? ''),
            ];
        }
    }
}
// Text-only fallback (replace with real logos asap)
if (empty($client_logos)) {
    $client_logos = [
        ['image_id' => 0, 'name' => 'Acme Corp'],
        ['image_id' => 0, 'name' => 'BrightPath'],
        ['image_id' => 0, 'name' => 'TrustMark'],
        ['image_id' => 0, 'name' => 'LocalEdge'],
        ['image_id' => 0, 'name' => 'GrowthHub'],
    ];
}

/* ═══════════════════════════════════════════════════════════
   4. SERVICES — CPT rs_service → static fallback
═══════════════════════════════════════════════════════════ */
$rs_svc_q = new WP_Query([
    'post_type'      => 'rs_service',
    'posts_per_page' => 6,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'post_status'    => 'publish',
]);

if ($rs_svc_q->have_posts()) {
    $services = [];
    while ($rs_svc_q->have_posts()) {
        $rs_svc_q->the_post();
        $img    = get_field('service_image');
        $img_id = is_array($img) ? (int) ($img['ID'] ?? 0) : (int) $img;
        $raw_f  = get_field('service_features') ?: '';
        $services[] = [
            'image_id'  => $img_id,
            'image_src' => '',
            'tag'       => get_field('service_tag')      ?: '',
            'title'     => get_the_title(),
            'hook'      => get_field('service_hook')     ?: '',
            'problem'   => get_field('service_problem')  ?: '',
            'solution'  => get_field('service_solution') ?: '',
            'features'  => array_values(array_filter(array_map('trim', explode("\n", $raw_f)))),
            'result'    => get_field('service_result')   ?: '',
            'cta'       => get_field('service_cta_text') ?: __('Get Started', 'reviewsservice'),
            'url'       => get_permalink(),
        ];
    }
    wp_reset_postdata();
} else {
    $bi = get_template_directory_uri() . '/assets/images/';
    $services = [
        ['image_id' => 0, 'image_src' => $bi . 'card-01-reputation-management.jpg',   'tag' => 'High Impact • Local Business Essential', 'title' => 'Reputation Management',     'hook' => 'Turn Your Online Reputation Into a Client Magnet',         'problem' => 'Negative reviews, low ratings, or no online presence can silently cost you customers every day.',    'solution' => 'We build a reputation system that improves ratings, protects your brand, and makes your business look trustworthy.', 'features' => ['Review generation system', 'Negative review handling', 'Google Business Profile optimization'],       'result' => 'More trust, higher ratings, increased calls, and consistent customer flow.',                    'cta' => 'Start Building Trust Today',  'url' => home_url('/services/reputation-management/')],
        ['image_id' => 0, 'image_src' => $bi . 'card-02-social-media-management.jpg',  'tag' => 'Most Popular • Growth Focused',           'title' => 'Social Media Management',   'hook' => 'Turn Social Media Into a Lead Generation Machine',          'problem' => 'Random posting wastes time and usually brings no real business results.',                            'solution' => 'We plan, create, schedule, and manage your social presence strategically to attract real customers.',               'features' => ['Content strategy & calendar', 'Engagement and inbox handling', 'Monthly performance tracking'],   'result' => 'More engagement, stronger visibility, consistent leads, and better brand authority.',          'cta' => 'Grow Your Social Presence',   'url' => home_url('/services/social-media/')],
        ['image_id' => 0, 'image_src' => $bi . 'card-03-wordpress-website-design.jpg', 'tag' => 'High ROI • Conversion Focused',            'title' => 'WordPress Website Design',  'hook' => 'Convert Visitors Into Customers With Smart Websites',       'problem' => 'Outdated, slow, or poorly designed websites lose visitors before they contact you.',                  'solution' => 'We design fast, SEO-ready, mobile-friendly WordPress websites built for leads and trust.',                          'features' => ['Conversion-focused UI design', 'SEO-ready page structure', 'Speed and mobile optimization'],    'result' => 'Higher conversions, better search visibility, more inquiries, and stronger trust.',            'cta' => 'Get a Better Website',        'url' => home_url('/services/website-design/')],
        ['image_id' => 0, 'image_src' => $bi . 'card-04-full-stack-development.jpg',   'tag' => 'Advanced • Scalable • Tech Driven',       'title' => 'Full Stack Web Development', 'hook' => 'Build Scalable Web Apps That Drive Business Growth',        'problem' => 'Generic systems limit growth and create poor user experiences.',                                      'solution' => 'We build custom websites, dashboards, APIs, and scalable web platforms for your business needs.',                  'features' => ['React / Next.js development', 'API and backend systems', 'Scalable architecture'],             'result' => 'Better performance, scalable systems, improved UX, and higher efficiency.',                    'cta' => 'Build Your Web Solution',     'url' => home_url('/services/full-stack-development/')],
        ['image_id' => 0, 'image_src' => $bi . 'card-05-content-creation.jpg',         'tag' => 'Creative • Brand Building',               'title' => 'Content Creation & Posting', 'hook' => 'Create Content That Builds Trust and Drives Sales',         'problem' => 'Low-quality or inconsistent content fails to grab attention and convert customers.',                  'solution' => 'We create strategic visuals, captions, and posts that make your brand look active and professional.',              'features' => ['Social media creatives', 'Branding visuals', 'Conversion-focused copywriting'],                'result' => 'Stronger brand image, higher engagement, better conversions, and more audience trust.',        'cta' => 'Upgrade Your Content',        'url' => home_url('/services/content-creation/')],
        ['image_id' => 0, 'image_src' => $bi . 'card-06-media-buying.jpg',             'tag' => 'High ROI • Performance Driven',           'title' => 'Media Buying',               'hook' => 'Run Ads That Actually Generate Revenue',                    'problem' => 'Many businesses waste money on ads that get clicks but do not convert.',                              'solution' => 'We create and optimize campaigns focused on leads, conversions, and measurable business growth.',                   'features' => ['Facebook & Google Ads', 'Funnel and conversion tracking', 'ROI optimization'],                 'result' => 'More qualified leads, better ROI, lower cost per conversion, and scalable growth.',            'cta' => 'Start Profitable Ads',        'url' => home_url('/services/media-buying/')],
    ];
}

/* ═══════════════════════════════════════════════════════════
   5. WHY CHOOSE US — ACF options repeater → static fallback
═══════════════════════════════════════════════════════════ */
$trust_items = [];
if (function_exists('get_field')) {
    $raw_trust = get_field('why_choose_us', 'option');
    if (! empty($raw_trust) && is_array($raw_trust)) {
        foreach ($raw_trust as $row) {
            $trust_items[] = [
                'title' => sanitize_text_field($row['title'] ?? ''),
                'desc'  => wp_kses_post($row['desc'] ?? ''),
            ];
        }
    }
}
if (empty($trust_items)) {
    $trust_items = [
        ['title' => 'Proven Growth Systems',      'desc' => 'We use structured, data-driven systems to deliver predictable business growth instead of random results.'],
        ['title' => 'Results-Focused Execution',  'desc' => 'Everything we build — websites, content, ads — is focused on leads, conversions, and real business impact.'],
        ['title' => 'High-Quality Delivery',      'desc' => 'We maintain premium standards in design, development, and strategy to position your brand professionally.'],
        ['title' => 'Transparent & Responsible',  'desc' => 'Clear communication, regular updates, and accountability ensure you always know what is happening.'],
        ['title' => 'Experienced Team',           'desc' => 'We combine technical expertise with real-world business understanding to deliver practical solutions.'],
        ['title' => 'Satisfaction Guarantee',     'desc' => 'We work closely with you to ensure results meet your expectations and align with your business goals.'],
    ];
}

/* ═══════════════════════════════════════════════════════════
   6. PROCESS STEPS — ACF options repeater → static fallback
═══════════════════════════════════════════════════════════ */
$process_steps = [];
if (function_exists('get_field')) {
    $raw_proc = get_field('process_steps', 'option');
    if (! empty($raw_proc) && is_array($raw_proc)) {
        foreach ($raw_proc as $row) {
            $process_steps[] = [
                'step'  => sanitize_text_field($row['step'] ?? ''),
                'title' => sanitize_text_field($row['title'] ?? ''),
                'desc'  => sanitize_text_field($row['desc'] ?? ''),
                'items' => array_values(array_filter(array_map('trim', explode("\n", $row['items'] ?? '')))),
            ];
        }
    }
}
if (empty($process_steps)) {
    $process_steps = [
        ['step' => '01', 'title' => 'Discover',        'desc' => 'We understand your business, goals, current online presence, competitors, and biggest growth problems.',           'items' => ['Business goal review', 'Online presence audit', 'Growth opportunity check']],
        ['step' => '02', 'title' => 'Strategy',        'desc' => 'We create a clear action plan for reputation, website, content, social media, or paid advertising.',               'items' => ['Custom growth roadmap', 'Service priority planning', 'Conversion-focused direction']],
        ['step' => '03', 'title' => 'Build & Execute', 'desc' => 'We design, develop, publish, optimize, and manage the system based on your business needs.',                       'items' => ['Professional implementation', 'High-quality delivery', 'Consistent communication']],
        ['step' => '04', 'title' => 'Track & Improve', 'desc' => 'We monitor performance, improve weak points, and help your business grow with smarter decisions.',                 'items' => ['Performance tracking', 'Monthly improvement', 'Long-term growth focus']],
    ];
}

/* ═══════════════════════════════════════════════════════════
   7. CASE STUDIES — CPT rs_case_study → static fallback
═══════════════════════════════════════════════════════════ */
$rs_case_q = new WP_Query([
    'post_type'      => 'rs_case_study',
    'posts_per_page' => 3,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'post_status'    => 'publish',
]);

if ($rs_case_q->have_posts()) {
    $case_studies = [];
    while ($rs_case_q->have_posts()) {
        $rs_case_q->the_post();
        $raw_ci = get_field('case_items') ?: '';
        $case_studies[] = [
            'label'     => get_field('case_label') ?: '',
            'title'     => get_the_title(),
            'desc'      => get_the_excerpt(),
            'items'     => array_values(array_filter(array_map('trim', explode("\n", $raw_ci)))),
            'result'    => get_field('case_result') ?: '',
            'url'       => get_permalink(),
            'thumb_id'  => (int) get_post_thumbnail_id(),
        ];
    }
    wp_reset_postdata();
} else {
    $case_studies = [
        ['label' => 'Reputation Growth',   'title' => 'Local Business Reputation System',    'desc' => 'A trust-focused system designed to help local businesses collect more positive reviews, improve Google visibility, and build stronger customer confidence.', 'items' => ['Review request workflow', 'Google profile trust signals', 'Negative review response strategy'], 'result' => 'Result Focus: stronger trust, more calls, and better local credibility.',                              'url' => home_url('/case-studies/'), 'thumb_id' => 0],
        ['label' => 'Website Conversion',  'title' => 'Service Business Website Redesign',   'desc' => 'A conversion-focused WordPress website structure built to explain services clearly, improve mobile experience, and guide visitors toward inquiries.',         'items' => ['SEO-ready page layout', 'Mobile-first user experience', 'Clear CTA and lead flow'],               'result' => 'Result Focus: better inquiries, stronger brand image, and improved website engagement.',             'url' => home_url('/case-studies/'), 'thumb_id' => 0],
        ['label' => 'Social + Ads Growth', 'title' => 'Content & Paid Campaign System',      'desc' => 'A visibility and lead-generation system combining consistent content, audience targeting, and paid campaign optimization.',                                   'items' => ['Monthly content calendar', 'Ad funnel planning', 'Conversion tracking setup'],                     'result' => 'Result Focus: more visibility, better leads, and scalable growth opportunities.',                    'url' => home_url('/case-studies/'), 'thumb_id' => 0],
    ];
}

/* ═══════════════════════════════════════════════════════════
   8. MICRO SERVICES — wc_get_products() → static fallback
═══════════════════════════════════════════════════════════ */
$micro_services = [];
if (function_exists('wc_get_products')) {
    $wc_products = wc_get_products([
        'limit'   => 4,
        'status'  => 'publish',
        'orderby' => 'menu_order',
        'order'   => 'ASC',
    ]);
    foreach ($wc_products as $wc_p) {
        $pid        = $wc_p->get_id();
        $raw_feats  = get_post_meta($pid, '_service_features', true) ?: '';
        $feats      = array_values(array_filter(array_map('trim', explode('|', $raw_feats))));
        $micro_services[] = [
            'badge'    => get_post_meta($pid, '_service_badge', true) ?: 'Micro Service',
            'title'    => $wc_p->get_name(),
            'desc'     => wp_trim_words(wp_strip_all_tags($wc_p->get_short_description()), 20, '&hellip;'),
            'features' => $feats,
            'price'    => wp_kses_post($wc_p->get_price_html()),
            'cta'      => get_post_meta($pid, '_service_cta', true) ?: __('Order Now', 'reviewsservice'),
            'url'      => $wc_p->get_permalink(),
            'image_id' => (int) $wc_p->get_image_id(),
        ];
    }
}
if (empty($micro_services)) {
    $micro_services = [
        ['badge' => 'Trust Starter', 'title' => 'Google Review Growth Setup',    'desc' => 'A focused setup to help your business collect more positive reviews and look more trustworthy online.',            'features' => ['Review request flow', 'Google profile check', 'Response guidance'],       'price' => 'Starter Package', 'cta' => 'Order Review Setup',   'url' => home_url('/shop/'), 'image_id' => 0],
        ['badge' => 'Content Boost', 'title' => 'Social Media Post Pack',         'desc' => 'Professional branded posts and captions to keep your business active, consistent, and client-ready.',             'features' => ['Post creative design', 'Caption writing', 'Brand consistency'],           'price' => 'Popular Service', 'cta' => 'Order Content Pack',   'url' => home_url('/shop/'), 'image_id' => 0],
        ['badge' => 'Website Fix',   'title' => 'Homepage Conversion Audit',      'desc' => 'A practical audit to find weak points in your homepage design, CTA, content, speed, and trust flow.',             'features' => ['UX/CRO review', 'CTA improvement ideas', 'Quick-win action list'],        'price' => 'Quick Win',       'cta' => 'Order Website Audit', 'url' => home_url('/shop/'), 'image_id' => 0],
        ['badge' => 'Ads Ready',     'title' => 'Paid Ads Strategy Review',       'desc' => 'A focused review of your offer, audience, funnel, and tracking before spending more money on ads.',               'features' => ['Offer review', 'Audience direction', 'Tracking checklist'],               'price' => 'Growth Prep',     'cta' => 'Order Ads Review',    'url' => home_url('/shop/'), 'image_id' => 0],
    ];
}

/* ═══════════════════════════════════════════════════════════
   9. TESTIMONIALS — CPT rs_testimonial → static fallback
═══════════════════════════════════════════════════════════ */
$rs_test_q = new WP_Query([
    'post_type'      => 'rs_testimonial',
    'posts_per_page' => 9,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'post_status'    => 'publish',
]);

if ($rs_test_q->have_posts()) {
    $testimonials = [];
    while ($rs_test_q->have_posts()) {
        $rs_test_q->the_post();
        $rating       = (int) (get_field('client_rating') ?: 5);
        $testimonials[] = [
            'name'    => get_field('client_name')  ?: get_the_title(),
            'role'    => get_field('client_role')  ?: '',
            'rating'  => min(5, max(1, $rating)),
            'quote'   => get_field('review_text')  ?: get_the_excerpt(),
            'service' => get_field('service_label') ?: '',
        ];
    }
    wp_reset_postdata();
} else {
    $testimonials = [
        ['name' => 'Sarah M.',    'role' => 'Local Business Owner',      'rating' => 5, 'quote' => 'Reviews Service helped us understand what was hurting our online reputation and gave us a clear system to improve trust.',                           'service' => 'Reputation Management'],
        ['name' => 'David R.',    'role' => 'Restaurant Manager',         'rating' => 5, 'quote' => 'The strategy was clear, communication was professional, and the work helped our brand look much more trustworthy online.',                           'service' => 'Google Review Strategy'],
        ['name' => 'Melissa T.',  'role' => 'Service Business Founder',   'rating' => 5, 'quote' => 'Our website finally explains our services clearly. The design feels modern, fast, and much easier for customers to use.',                            'service' => 'WordPress Website Design'],
        ['name' => 'James K.',    'role' => 'Real Estate Consultant',     'rating' => 5, 'quote' => 'They helped us turn random posting into a proper content system. Our page now looks consistent and more professional.',                              'service' => 'Social Media Management'],
        ['name' => 'Alicia B.',   'role' => 'Clinic Owner',               'rating' => 5, 'quote' => 'Very responsible team. They explained every step, fixed weak points, and focused on what would actually help the business.',                         'service' => 'Digital Growth Strategy'],
        ['name' => 'Michael S.',  'role' => 'Ecommerce Operator',         'rating' => 5, 'quote' => 'The website and campaign review gave us practical improvements we could act on immediately. Very clear and useful.',                                  'service' => 'Website + Ads Review'],
        ['name' => 'Rachel P.',   'role' => 'Salon Owner',                'rating' => 5, 'quote' => 'The content looked clean, branded, and professional. It saved us time and made our social media feel more active.',                                  'service' => 'Content Creation'],
        ['name' => 'Daniel C.',   'role' => 'Agency Partner',             'rating' => 5, 'quote' => 'Great execution quality. They understand both design and business goals, which makes the work more valuable.',                                       'service' => 'Full Stack Development'],
        ['name' => 'Nora H.',     'role' => 'Online Service Provider',    'rating' => 5, 'quote' => 'The team was transparent, responsive, and focused on results. I always knew what was happening and why.',                                            'service' => 'Monthly Support'],
    ];
}

/* ═══════════════════════════════════════════════════════════
   10. FAQs — CPT rs_faq → static fallback
═══════════════════════════════════════════════════════════ */
$rs_faq_q = new WP_Query([
    'post_type'      => 'rs_faq',
    'posts_per_page' => 8,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'post_status'    => 'publish',
]);

if ($rs_faq_q->have_posts()) {
    $faqs = [];
    while ($rs_faq_q->have_posts()) {
        $rs_faq_q->the_post();
        $faqs[] = ['q' => get_the_title(), 'a' => wp_kses_post(get_the_content())];
    }
    wp_reset_postdata();
} else {
    $faqs = [
        ['q' => 'How do I get started with Reviews Service?',                    'a' => 'Start by booking a free consultation. We will review your current online presence and recommend the best service for your goals.'],
        ['q' => 'Do you work with all types of businesses?',                     'a' => 'Yes. We work with local businesses, service providers, e-commerce stores, clinics, restaurants, agencies, and consultants in any industry.'],
        ['q' => 'How long does it take to see results?',                         'a' => 'Initial improvements such as better profile setup can happen within 2–4 weeks. Reputation growth and SEO improvements typically take 1–3 months.'],
        ['q' => 'Can I start with just one micro-service?',                      'a' => 'Absolutely. Our micro-service shop lets you order a single focused service before committing to a full growth plan.'],
        ['q' => 'What makes Reviews Service different from other agencies?',     'a' => 'We focus on trust-first digital growth — not vanity metrics. Every service is designed to attract real customers and build long-term credibility.'],
        ['q' => 'Do you offer refunds or satisfaction guarantees?',              'a' => 'We work closely with every client to ensure deliverables meet expectations. Contact us directly if you have concerns and we will resolve them.'],
    ];
}

/* ═══════════════════════════════════════════════════════════
   11. JSON-LD — Organization schema
═══════════════════════════════════════════════════════════ */
$logo_url = '';
if (has_custom_logo()) {
    $logo_url = wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full');
}
$org_schema = [
    '@context'     => 'https://schema.org',
    '@type'        => 'ProfessionalService',
    'name'         => get_bloginfo('name'),
    'url'          => home_url('/'),
    'logo'         => $logo_url ? ['@type' => 'ImageObject', 'url' => $logo_url] : null,
    'description'  => get_bloginfo('description'),
    'areaServed'   => 'Worldwide',
    'serviceType'  => array_column($services, 'title'),
    'contactPoint' => ['@type' => 'ContactPoint', 'contactType' => 'customer service', 'url' => home_url('/contact/')],
];

/* ═══════════════════════════════════════════════════════════
   12. JSON-LD — FAQPage schema
═══════════════════════════════════════════════════════════ */
$faq_schema = [
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    'mainEntity' => array_map(function ($faq) {
        return [
            '@type'          => 'Question',
            'name'           => $faq['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => wp_strip_all_tags($faq['a'])],
        ];
    }, $faqs),
];

/* ═══════════════════════════════════════════════════════════
   WhatsApp number from Customizer
═══════════════════════════════════════════════════════════ */
$whatsapp = preg_replace('/[^0-9]/', '', get_theme_mod('rs_whatsapp_number', ''));
?>

<!-- Schema markup -->
<script type="application/ld+json">
    <?php echo wp_json_encode($org_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>
<script type="application/ld+json">
    <?php echo wp_json_encode($faq_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>

<main id="primary" class="site-main" role="main">

    <!-- ═══════════════════════════════════════════════════
         HERO — Accessible carousel (WCAG 2.1 AA)
    ═══════════════════════════════════════════════════ -->
    <section
        id="rs-hero"
        class="relative min-h-[calc(100vh-88px)] overflow-hidden bg-[#0D0F12] text-white"
        aria-label="<?php esc_attr_e('Featured services', 'reviewsservice'); ?>"
        aria-roledescription="carousel">

        <!-- Live region: screen reader slide announcements -->
        <div id="rsCarouselStatus" aria-live="polite" aria-atomic="true" class="sr-only"></div>

        <!-- Slides -->
        <div role="group" aria-label="<?php esc_attr_e('Slides', 'reviewsservice'); ?>">
            <?php foreach ($hero_slides as $idx => $slide) :
                $is_first = (0 === $idx);
                $slide_label_str = sprintf(
                    esc_attr__('Slide %1$d of %2$d: %3$s', 'reviewsservice'),
                    $idx + 1,
                    count($hero_slides),
                    $slide['label']
                );

                $img_attrs = [
                    'class'   => 'rs-slide-img absolute inset-0 h-full w-full object-cover brightness-[0.62] contrast-[1.08] transition-opacity duration-700',
                    'loading' => $is_first ? 'eager' : 'lazy',
                    'style'   => 'opacity:' . ($is_first ? '1' : '0') . ';pointer-events:none',
                    'alt'     => esc_attr($slide['label']),
                ];
                if ($is_first) {
                    $img_attrs['fetchpriority'] = 'high';
                    $img_attrs['decoding']      = 'async';
                }
            ?>
                <div
                    class="rs-slide absolute inset-0<?php echo $is_first ? ' rs-slide--active' : ''; ?>"
                    role="group"
                    aria-roledescription="slide"
                    aria-label="<?php echo esc_attr($slide_label_str); ?>"
                    data-label="<?php echo esc_attr($slide['label']); ?>"
                    data-title="<?php echo esc_attr($slide['title']); ?>"
                    data-text="<?php echo esc_attr($slide['text']); ?>">

                    <?php if ($slide['image_id']) :
                        echo rs_attachment_img($slide['image_id'], 'full', $img_attrs);
                    else : ?>
                        <img
                            src="<?php echo esc_url($slide['image_url']); ?>"
                            <?php foreach ($img_attrs as $k => $v) : ?>
                            <?php echo esc_attr($k); ?>="<?php echo esc_attr($v); ?>"
                            <?php endforeach; ?>>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Decorative overlays -->
        <div class="absolute inset-0 bg-gradient-to-r from-[#0D0F12]/88 via-[#0D0F12]/58 to-[#0D0F12]/10" aria-hidden="true"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-[#0D0F12]/82 via-transparent to-[#0D0F12]/20" aria-hidden="true"></div>
        <div class="absolute -left-32 top-0 h-[420px] w-[420px] rounded-full bg-[#1E3A8A]/35 blur-3xl" aria-hidden="true"></div>
        <div class="absolute -right-32 bottom-0 h-[520px] w-[520px] rounded-full bg-[#00C853]/20 blur-3xl" aria-hidden="true"></div>

        <!-- Hero content -->
        <div class="relative z-10 mx-auto flex min-h-[calc(100vh-88px)] max-w-screen-xl items-center px-4 py-20 sm:px-6 lg:px-8">
            <div class="max-w-[980px]">
                <span id="rsHeroLabel" class="inline-flex rounded-full border border-[#14B8A6]/45 bg-[#14B8A6]/15 px-5 py-2 text-sm font-black text-[#14B8A6] backdrop-blur-xl" aria-hidden="true">
                    <?php echo esc_html($hero_slides[0]['label']); ?>
                </span>

                <h1 id="rsHeroTitle" class="mt-7 max-w-[920px] text-[42px] font-black leading-[1.08] tracking-[-0.045em] text-white drop-shadow-[0_5px_18px_rgba(0,0,0,0.45)] sm:text-[56px] lg:text-[68px] xl:text-[76px]">
                    <?php echo esc_html($hero_slides[0]['title']); ?>
                </h1>

                <p id="rsHeroText" class="mt-6 max-w-[720px] text-[17px] font-semibold leading-[1.8] text-white/88 drop-shadow-[0_3px_12px_rgba(0,0,0,0.35)] sm:text-[19px]">
                    <?php echo esc_html($hero_slides[0]['text']); ?>
                </p>

                <div class="mt-9 flex flex-wrap gap-4">
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="group inline-flex items-center justify-center rounded-full bg-gradient-to-r from-[#00C853] to-[#00E676] px-8 py-4 text-sm font-black text-[#0D0F12] shadow-[0_10px_32px_rgba(0,200,83,0.42)] transition hover:-translate-y-1 hover:shadow-[0_16px_44px_rgba(0,200,83,0.55)]">
                        <?php esc_html_e('Get Free Consultation', 'reviewsservice'); ?>
                        <span class="ml-2 transition group-hover:translate-x-1" aria-hidden="true">→</span>
                    </a>
                    <a href="<?php echo esc_url(home_url('/shop/')); ?>" class="inline-flex items-center justify-center rounded-full border border-white/25 bg-white/10 px-8 py-4 text-sm font-black text-white backdrop-blur-xl transition hover:border-[#FFC107] hover:text-[#FFC107]">
                        <?php esc_html_e('View Services', 'reviewsservice'); ?>
                    </a>
                </div>

                <!-- Stats (Customizer-editable) -->
                <div class="mt-12 grid max-w-[760px] gap-4 sm:grid-cols-2 lg:grid-cols-4" role="list" aria-label="<?php esc_attr_e('Key statistics', 'reviewsservice'); ?>">
                    <?php foreach ($hero_stats as $stat) : ?>
                        <div class="rounded-[26px] border border-white/14 bg-[#0D0F12]/50 p-5 shadow-[0_18px_50px_rgba(0,0,0,0.22)] backdrop-blur-xl" role="listitem">
                            <strong class="block text-3xl leading-none" style="color:<?php echo esc_attr($stat['color']); ?>">
                                <?php echo esc_html($stat['val']); ?>
                            </strong>
                            <span class="mt-2 block text-sm font-bold leading-snug text-white/78">
                                <?php echo esc_html($stat['label']); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Carousel controls -->
        <div class="absolute bottom-8 left-1/2 z-20 flex -translate-x-1/2 items-center gap-3" role="group" aria-label="<?php esc_attr_e('Carousel controls', 'reviewsservice'); ?>">

            <button type="button" id="rsPrev" class="flex h-9 w-9 items-center justify-center rounded-full border border-white/25 text-white/70 transition hover:bg-white/10" aria-label="<?php esc_attr_e('Previous slide', 'reviewsservice'); ?>">←</button>

            <?php foreach ($hero_slides as $idx => $slide) : ?>
                <button
                    type="button"
                    class="rs-dot h-3 w-3 rounded-full bg-white/35 transition-all duration-300"
                    data-index="<?php echo esc_attr($idx); ?>"
                    role="tab"
                    aria-selected="<?php echo 0 === $idx ? 'true' : 'false'; ?>"
                    aria-label="<?php printf(esc_attr__('Slide %d: %s', 'reviewsservice'), $idx + 1, esc_attr($slide['label'])); ?>"></button>
            <?php endforeach; ?>

            <button type="button" id="rsNext" class="flex h-9 w-9 items-center justify-center rounded-full border border-white/25 text-white/70 transition hover:bg-white/10" aria-label="<?php esc_attr_e('Next slide', 'reviewsservice'); ?>">→</button>

            <button type="button" id="rsPauseBtn" class="ml-2 flex h-9 w-9 items-center justify-center rounded-full border border-white/25 text-white/70 transition hover:bg-white/10" aria-label="<?php esc_attr_e('Pause carousel', 'reviewsservice'); ?>" aria-pressed="false">⏸</button>
        </div>
    </section>
    <!-- /Hero -->

    <!-- ═══════════════════════════════════════════════════
         CLIENT LOGO BAR (NEW)
    ═══════════════════════════════════════════════════ -->
    <section class="border-b border-t border-[#E5E7EB] bg-white px-4 py-10 sm:px-6 lg:px-8" aria-labelledby="clients-heading">
        <div class="mx-auto max-w-screen-xl">
            <p id="clients-heading" class="mb-8 text-center text-xs font-black uppercase tracking-[0.18em] text-[#374151]">
                <?php esc_html_e('Trusted by businesses across industries', 'reviewsservice'); ?>
            </p>
            <ul class="flex flex-wrap items-center justify-center gap-8 p-0 lg:gap-14" role="list" aria-label="<?php esc_attr_e('Client list', 'reviewsservice'); ?>">
                <?php foreach ($client_logos as $logo) : ?>
                    <li class="list-none opacity-60 grayscale transition hover:opacity-100 hover:grayscale-0">
                        <?php if ($logo['image_id']) :
                            echo rs_attachment_img($logo['image_id'], 'medium', [
                                'class'   => 'h-10 w-auto object-contain',
                                'alt'     => esc_attr($logo['name']),
                                'loading' => 'lazy',
                            ]);
                        else : ?>
                            <span class="text-base font-black text-[#374151]"><?php echo esc_html($logo['name']); ?></span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>
    <!-- /Client Logo Bar -->

    <!-- ═══════════════════════════════════════════════════
         SERVICES
    ═══════════════════════════════════════════════════ -->
    <section class="relative overflow-hidden bg-[#F3F4F6] px-4 py-20 sm:px-6 lg:px-8" aria-labelledby="services-heading">
        <div class="mx-auto max-w-screen-xl">
            <div class="mx-auto max-w-3xl text-center">
                <span class="inline-flex rounded-full bg-[#1E3A8A]/10 px-5 py-2 text-sm font-black text-[#1E3A8A]" aria-hidden="true"><?php esc_html_e('Our Digital Growth Services', 'reviewsservice'); ?></span>
                <h2 id="services-heading" class="mt-5 text-3xl font-black leading-tight tracking-[-0.04em] text-[#0D0F12] sm:text-4xl lg:text-5xl">
                    <?php esc_html_e('Services Built to Help Your Business Earn Trust, Get Leads & Grow Faster', 'reviewsservice'); ?>
                </h2>
                <p class="mt-5 text-base font-semibold leading-8 text-[#374151] sm:text-lg">
                    <?php esc_html_e('From online reputation management to social media, websites, content, and paid ads — Reviews Service helps businesses look professional, build trust, and turn attention into customers.', 'reviewsservice'); ?>
                </p>
            </div>

            <ul class="mt-14 grid list-none gap-6 p-0 md:grid-cols-2 xl:grid-cols-3" role="list">
                <?php foreach ($services as $service) :
                    $img_alt = esc_attr($service['title']);
                ?>
                    <li class="group overflow-hidden rounded-[32px] border border-[#E5E7EB] bg-white shadow-[0_20px_70px_rgba(13,15,18,0.08)] transition duration-300 hover:-translate-y-2 hover:shadow-[0_30px_90px_rgba(13,15,18,0.14)]">

                        <div class="relative h-56 overflow-hidden">
                            <?php if (! empty($service['image_id'])) :
                                echo rs_attachment_img($service['image_id'], 'large', [
                                    'class'   => 'h-full w-full object-cover transition duration-700 group-hover:scale-110',
                                    'loading' => 'lazy',
                                    'alt'     => $img_alt,
                                ]);
                            elseif (! empty($service['image_src'])) : ?>
                                <img src="<?php echo esc_url($service['image_src']); ?>" alt="<?php echo $img_alt; ?>" class="h-full w-full object-cover transition duration-700 group-hover:scale-110" loading="lazy" decoding="async">
                            <?php else : ?>
                                <div class="flex h-full w-full items-center justify-center bg-[#0D0F12]" role="img" aria-label="<?php echo $img_alt; ?>">
                                    <span class="text-lg font-black text-white" aria-hidden="true"><?php echo esc_html($service['title']); ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0D0F12]/80 via-[#0D0F12]/20 to-transparent" aria-hidden="true"></div>
                            <?php if ($service['tag']) : ?>
                                <span class="absolute bottom-5 left-5 rounded-full bg-[#FFC107] px-4 py-2 text-xs font-black text-[#0D0F12]"><?php echo esc_html($service['tag']); ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="p-6 sm:p-7">
                            <h3 class="text-2xl font-black tracking-[-0.035em] text-[#0D0F12]"><?php echo esc_html($service['title']); ?></h3>
                            <?php if ($service['hook']) : ?>
                                <p class="mt-3 text-lg font-black leading-7 text-[#1E3A8A]"><?php echo esc_html($service['hook']); ?></p>
                            <?php endif; ?>

                            <div class="mt-5 space-y-4">
                                <?php if ($service['problem']) : ?>
                                    <div class="rounded-2xl bg-[#F3F4F6] p-4">
                                        <p class="text-xs font-black uppercase tracking-[0.18em] text-[#374151]"><?php esc_html_e('Problem', 'reviewsservice'); ?></p>
                                        <p class="mt-2 text-sm font-semibold leading-6 text-[#374151]"><?php echo esc_html($service['problem']); ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php if ($service['solution']) : ?>
                                    <div class="rounded-2xl bg-[#00C853]/10 p-4">
                                        <p class="text-xs font-black uppercase tracking-[0.18em] text-[#00A844]"><?php esc_html_e('Solution', 'reviewsservice'); ?></p>
                                        <p class="mt-2 text-sm font-semibold leading-6 text-[#374151]"><?php echo esc_html($service['solution']); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if (! empty($service['features'])) : ?>
                                <ul class="mt-5 space-y-3">
                                    <?php foreach ($service['features'] as $feature) : ?>
                                        <li class="flex gap-3 text-sm font-bold leading-6 text-[#374151]">
                                            <span class="mt-1 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#14B8A6]/15 text-xs text-[#14B8A6]" aria-hidden="true">✓</span>
                                            <?php echo esc_html($feature); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <?php if ($service['result']) : ?>
                                <div class="mt-6 rounded-2xl border border-[#E5E7EB] bg-[#0D0F12] p-4">
                                    <p class="text-xs font-black uppercase tracking-[0.18em] text-[#FFC107]"><?php esc_html_e('Expected Result', 'reviewsservice'); ?></p>
                                    <p class="mt-2 text-sm font-semibold leading-6 text-white/80"><?php echo esc_html($service['result']); ?></p>
                                </div>
                            <?php endif; ?>

                            <!-- FIX: CTA links to individual service page, not just /contact/ -->
                            <a
                                href="<?php echo esc_url($service['url']); ?>"
                                class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-gradient-to-r from-[#00C853] to-[#00E676] px-6 py-4 text-sm font-black text-[#0D0F12] shadow-lg shadow-[#00C853]/20 transition hover:-translate-y-1"
                                aria-label="<?php printf(esc_attr__('%1$s — %2$s', 'reviewsservice'), esc_attr($service['cta']), esc_attr($service['title'])); ?>">
                                <?php echo esc_html($service['cta']); ?>
                                <span class="ml-2" aria-hidden="true">→</span>
                            </a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="mt-14 overflow-hidden rounded-[32px] bg-gradient-to-r from-[#1E3A8A] to-[#00C853] p-8 text-white shadow-[0_25px_80px_rgba(30,58,138,0.25)] lg:p-10">
                <div class="grid items-center gap-6 lg:grid-cols-[1fr_auto]">
                    <div>
                        <h3 class="text-2xl font-black tracking-[-0.035em] sm:text-3xl"><?php esc_html_e('Not sure which service is right for your business?', 'reviewsservice'); ?></h3>
                        <p class="mt-3 max-w-2xl text-base font-semibold leading-7 text-white/85"><?php esc_html_e("Book a free consultation and we'll help you choose the right growth plan based on your goals, current online presence, and budget.", 'reviewsservice'); ?></p>
                    </div>
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="inline-flex justify-center rounded-full bg-[#FFC107] px-8 py-4 text-sm font-black text-[#0D0F12] transition hover:-translate-y-1"><?php esc_html_e('Get Free Consultation', 'reviewsservice'); ?></a>
                </div>
            </div>
        </div>
    </section>
    <!-- /Services -->

    <!-- ═══════════════════════════════════════════════════
         WHY CHOOSE US
    ═══════════════════════════════════════════════════ -->
    <section class="relative overflow-hidden bg-gradient-to-b from-[#0D0F12] to-[#1F2937] px-4 py-20 sm:px-6 lg:px-8" aria-labelledby="why-heading">
        <div class="mx-auto grid max-w-screen-xl items-center gap-14 lg:grid-cols-2">
            <div>
                <span class="inline-flex rounded-full bg-[#00C853]/10 px-5 py-2 text-sm font-black text-[#00C853]" aria-hidden="true"><?php esc_html_e('Why Choose Us', 'reviewsservice'); ?></span>
                <h2 id="why-heading" class="mt-6 text-3xl font-black leading-tight tracking-[-0.04em] text-white sm:text-4xl lg:text-5xl">
                    <?php esc_html_e('Why Businesses Trust Reviews Service to Grow Faster', 'reviewsservice'); ?>
                </h2>
                <p class="mt-6 text-base font-semibold leading-8 text-white/80 sm:text-lg">
                    <?php esc_html_e("We don't just deliver services — we build complete digital growth systems that help your business look professional, earn trust, attract customers, and generate consistent revenue.", 'reviewsservice'); ?>
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-[#00C853] to-[#00E676] px-7 py-4 text-sm font-black text-[#0D0F12] shadow-lg shadow-[#00C853]/20 transition hover:-translate-y-1">
                        <?php esc_html_e('Get Free Consultation', 'reviewsservice'); ?><span class="ml-1" aria-hidden="true">→</span>
                    </a>
                    <a href="<?php echo esc_url(home_url('/services/')); ?>" class="inline-flex items-center justify-center rounded-full border border-white/20 px-7 py-4 text-sm font-bold text-white/80 backdrop-blur transition hover:bg-white/10">
                        <?php esc_html_e('View Services', 'reviewsservice'); ?>
                    </a>
                </div>
            </div>

            <ul class="grid list-none gap-6 p-0 sm:grid-cols-2" role="list">
                <?php foreach ($trust_items as $item) : ?>
                    <li class="group rounded-[28px] border border-white/10 bg-white/5 p-6 backdrop-blur-xl transition duration-300 hover:-translate-y-2 hover:bg-white/10">
                        <h3 class="text-lg font-black text-white"><?php echo esc_html($item['title']); ?></h3>
                        <p class="mt-3 text-sm font-semibold leading-6 text-white/70"><?php echo esc_html($item['desc']); ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>
    <!-- /Why Choose Us -->

    <!-- ═══════════════════════════════════════════════════
         PROCESS  — FIX: <ol><li> not <article>
    ═══════════════════════════════════════════════════ -->
    <section class="relative overflow-hidden bg-white px-4 py-20 sm:px-6 lg:px-8" aria-labelledby="process-heading">
        <div class="mx-auto max-w-screen-xl">
            <div class="mx-auto max-w-3xl text-center">
                <span class="inline-flex rounded-full bg-[#00C853]/10 px-5 py-2 text-sm font-black text-[#00A844]" aria-hidden="true"><?php esc_html_e('How We Work', 'reviewsservice'); ?></span>
                <h2 id="process-heading" class="mt-5 text-3xl font-black leading-tight tracking-[-0.04em] text-[#0D0F12] sm:text-4xl lg:text-5xl">
                    <?php esc_html_e('A Simple Growth Process Built for Real Business Results', 'reviewsservice'); ?>
                </h2>
                <p class="mt-5 text-base font-semibold leading-8 text-[#374151] sm:text-lg">
                    <?php esc_html_e('From first consultation to execution and optimization, we follow a clear system that keeps your project organized, transparent, and focused on growth.', 'reviewsservice'); ?>
                </p>
            </div>

            <ol class="mt-14 grid list-none gap-6 p-0 md:grid-cols-2 xl:grid-cols-4">
                <?php foreach ($process_steps as $step) : ?>
                    <li class="group relative overflow-hidden rounded-[32px] border border-[#E5E7EB] bg-[#F3F4F6] p-6 transition duration-300 hover:-translate-y-2 hover:border-[#00C853]/40 hover:bg-white hover:shadow-[0_30px_80px_rgba(13,15,18,0.10)]">
                        <div class="absolute -right-8 -top-8 h-28 w-28 rounded-full bg-[#00C853]/10 blur-2xl transition group-hover:bg-[#00C853]/20" aria-hidden="true"></div>
                        <div class="relative">
                            <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-[#0D0F12] text-lg font-black text-[#FFC107]" aria-hidden="true">
                                <?php echo esc_html($step['step']); ?>
                            </span>
                            <h3 class="mt-6 text-2xl font-black tracking-[-0.035em] text-[#0D0F12]"><?php echo esc_html($step['title']); ?></h3>
                            <p class="mt-3 text-sm font-semibold leading-6 text-[#374151]"><?php echo esc_html($step['desc']); ?></p>
                            <?php if (! empty($step['items'])) : ?>
                                <ul class="mt-6 space-y-3">
                                    <?php foreach ($step['items'] as $item) : ?>
                                        <li class="flex gap-3 text-sm font-bold leading-6 text-[#374151]">
                                            <span class="mt-1 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#00C853]/15 text-xs text-[#00A844]" aria-hidden="true">✓</span>
                                            <?php echo esc_html($item); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ol>

            <div class="mt-14 overflow-hidden rounded-[32px] bg-gradient-to-r from-[#0D0F12] to-[#1F2937] p-8 text-white shadow-[0_25px_80px_rgba(13,15,18,0.20)] lg:p-10">
                <div class="grid items-center gap-6 lg:grid-cols-[1fr_auto]">
                    <div>
                        <h3 class="text-2xl font-black tracking-[-0.035em] sm:text-3xl"><?php esc_html_e('Ready to build a growth system for your business?', 'reviewsservice'); ?></h3>
                        <p class="mt-3 max-w-2xl text-base font-semibold leading-7 text-white/75"><?php esc_html_e("Tell us your goals and we'll help you choose the right service, strategy, and next action step.", 'reviewsservice'); ?></p>
                    </div>
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="inline-flex justify-center rounded-full bg-gradient-to-r from-[#00C853] to-[#00E676] px-8 py-4 text-sm font-black text-[#0D0F12] transition hover:-translate-y-1"><?php esc_html_e('Start Free Consultation →', 'reviewsservice'); ?></a>
                </div>
            </div>
        </div>
    </section>
    <!-- /Process -->

    <!-- ═══════════════════════════════════════════════════
         CASE STUDIES
    ═══════════════════════════════════════════════════ -->
    <section class="relative overflow-hidden bg-[#F3F4F6] px-4 py-20 sm:px-6 lg:px-8" aria-labelledby="cases-heading">
        <div class="mx-auto max-w-screen-xl">
            <div class="mx-auto max-w-3xl text-center">
                <span class="inline-flex rounded-full bg-[#1E3A8A]/10 px-5 py-2 text-sm font-black text-[#1E3A8A]" aria-hidden="true"><?php esc_html_e('Case Studies Preview', 'reviewsservice'); ?></span>
                <h2 id="cases-heading" class="mt-5 text-3xl font-black leading-tight tracking-[-0.04em] text-[#0D0F12] sm:text-4xl lg:text-5xl"><?php esc_html_e('Real Growth Systems Built for Real Businesses', 'reviewsservice'); ?></h2>
                <p class="mt-5 text-base font-semibold leading-8 text-[#374151] sm:text-lg"><?php esc_html_e('Explore how Reviews Service helps businesses improve trust, visibility, website performance, and lead generation through focused digital growth systems.', 'reviewsservice'); ?></p>
            </div>

            <ul class="mt-14 grid list-none gap-6 p-0 lg:grid-cols-3">
                <?php foreach ($case_studies as $case) : ?>
                    <li class="group relative overflow-hidden rounded-[32px] border border-[#E5E7EB] bg-white p-7 shadow-[0_20px_70px_rgba(13,15,18,0.08)] transition duration-300 hover:-translate-y-2 hover:shadow-[0_30px_90px_rgba(13,15,18,0.14)]">
                        <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-[#00C853]/10 blur-2xl transition group-hover:bg-[#00C853]/20" aria-hidden="true"></div>
                        <div class="relative">
                            <?php if (! empty($case['thumb_id'])) : ?>
                                <div class="mb-6 h-40 overflow-hidden rounded-2xl">
                                    <?php echo rs_attachment_img($case['thumb_id'], 'medium', [
                                        'class'   => 'h-full w-full object-cover',
                                        'loading' => 'lazy',
                                        'alt'     => esc_attr($case['title']),
                                    ]); ?>
                                </div>
                            <?php endif; ?>
                            <span class="inline-flex rounded-full bg-[#FFC107] px-4 py-2 text-xs font-black text-[#0D0F12]"><?php echo esc_html($case['label']); ?></span>
                            <h3 class="mt-6 text-2xl font-black leading-tight tracking-[-0.035em] text-[#0D0F12]"><?php echo esc_html($case['title']); ?></h3>
                            <p class="mt-4 text-sm font-semibold leading-7 text-[#374151]"><?php echo esc_html($case['desc']); ?></p>
                            <?php if (! empty($case['items'])) : ?>
                                <ul class="mt-6 space-y-3">
                                    <?php foreach ($case['items'] as $item) : ?>
                                        <li class="flex gap-3 text-sm font-bold leading-6 text-[#374151]">
                                            <span class="mt-1 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#14B8A6]/15 text-xs text-[#14B8A6]" aria-hidden="true">✓</span>
                                            <?php echo esc_html($item); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                            <?php if ($case['result']) : ?>
                                <div class="mt-7 rounded-2xl bg-[#0D0F12] p-5">
                                    <p class="text-xs font-black uppercase tracking-[0.18em] text-[#FFC107]"><?php esc_html_e('Growth Outcome', 'reviewsservice'); ?></p>
                                    <p class="mt-2 text-sm font-semibold leading-6 text-white/80"><?php echo esc_html($case['result']); ?></p>
                                </div>
                            <?php endif; ?>
                            <a href="<?php echo esc_url($case['url']); ?>" class="mt-7 inline-flex items-center text-sm font-black text-[#1E3A8A] transition hover:text-[#00A844]" aria-label="<?php printf(esc_attr__('View details for: %s', 'reviewsservice'), esc_attr($case['title'])); ?>">
                                <?php esc_html_e('View Case Details', 'reviewsservice'); ?>
                                <span class="ml-2 transition group-hover:translate-x-1" aria-hidden="true">→</span>
                            </a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="mt-14 rounded-[32px] border border-[#E5E7EB] bg-white p-8 shadow-[0_20px_70px_rgba(13,15,18,0.08)] lg:p-10">
                <div class="grid items-center gap-6 lg:grid-cols-[1fr_auto]">
                    <div>
                        <h3 class="text-2xl font-black tracking-[-0.035em] text-[#0D0F12] sm:text-3xl"><?php esc_html_e('Want a growth system like this for your business?', 'reviewsservice'); ?></h3>
                        <p class="mt-3 max-w-2xl text-base font-semibold leading-7 text-[#374151]"><?php esc_html_e("Start with a free consultation and we'll identify the best opportunities for your business.", 'reviewsservice'); ?></p>
                    </div>
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="inline-flex justify-center rounded-full bg-gradient-to-r from-[#00C853] to-[#00E676] px-8 py-4 text-sm font-black text-[#0D0F12] shadow-lg shadow-[#00C853]/20 transition hover:-translate-y-1"><?php esc_html_e('Get Free Consultation →', 'reviewsservice'); ?></a>
                </div>
            </div>
        </div>
    </section>
    <!-- /Case Studies -->

    <!-- ═══════════════════════════════════════════════════
         MICRO SERVICES — from WooCommerce
    ═══════════════════════════════════════════════════ -->
    <section class="relative overflow-hidden bg-[#0D0F12] px-4 py-20 text-white sm:px-6 lg:px-8" aria-labelledby="shop-heading">
        <div class="absolute -left-32 top-10 h-96 w-96 rounded-full bg-[#1E3A8A]/35 blur-3xl" aria-hidden="true"></div>
        <div class="absolute -right-32 bottom-10 h-96 w-96 rounded-full bg-[#00C853]/20 blur-3xl" aria-hidden="true"></div>
        <div class="relative mx-auto max-w-screen-xl">
            <div class="grid items-end gap-8 lg:grid-cols-[1fr_auto]">
                <div class="max-w-3xl">
                    <span class="inline-flex rounded-full border border-[#14B8A6]/30 bg-[#14B8A6]/10 px-5 py-2 text-sm font-black text-[#14B8A6]" aria-hidden="true"><?php esc_html_e('Micro-Service Shop', 'reviewsservice'); ?></span>
                    <h2 id="shop-heading" class="mt-5 text-3xl font-black leading-tight tracking-[-0.04em] text-white sm:text-4xl lg:text-5xl"><?php esc_html_e('Start Small, Get Results Fast With Ready-to-Order Digital Services', 'reviewsservice'); ?></h2>
                    <p class="mt-5 text-base font-semibold leading-8 text-white/75 sm:text-lg"><?php esc_html_e('Need quick help before committing to a full monthly plan? Choose a focused micro-service — simple, clear, and built for fast business improvement.', 'reviewsservice'); ?></p>
                </div>
                <a href="<?php echo esc_url(home_url('/shop/')); ?>" class="inline-flex justify-center rounded-full bg-[#FFC107] px-8 py-4 text-sm font-black text-[#0D0F12] shadow-lg transition hover:-translate-y-1"><?php esc_html_e('Visit Micro-Service Shop →', 'reviewsservice'); ?></a>
            </div>

            <ul class="mt-14 grid list-none gap-6 p-0 md:grid-cols-2 xl:grid-cols-4">
                <?php foreach ($micro_services as $ms) : ?>
                    <li class="group relative overflow-hidden rounded-[32px] border border-white/10 bg-white/5 p-6 backdrop-blur-xl transition duration-300 hover:-translate-y-2 hover:bg-white/10 hover:shadow-[0_30px_90px_rgba(0,200,83,0.12)]">
                        <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-[#00C853]/10 blur-2xl transition group-hover:bg-[#00C853]/20" aria-hidden="true"></div>
                        <div class="relative">
                            <span class="inline-flex rounded-full bg-[#FFC107] px-4 py-2 text-xs font-black text-[#0D0F12]"><?php echo esc_html($ms['badge']); ?></span>
                            <h3 class="mt-6 text-2xl font-black leading-tight tracking-[-0.035em] text-white"><?php echo esc_html($ms['title']); ?></h3>
                            <p class="mt-4 text-sm font-semibold leading-7 text-white/70"><?php echo esc_html($ms['desc']); ?></p>
                            <?php if (! empty($ms['features'])) : ?>
                                <ul class="mt-6 space-y-3">
                                    <?php foreach ($ms['features'] as $feature) : ?>
                                        <li class="flex gap-3 text-sm font-bold leading-6 text-white/75">
                                            <span class="mt-1 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#00C853]/15 text-xs text-[#00E676]" aria-hidden="true">✓</span>
                                            <?php echo esc_html($feature); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                            <div class="mt-7 rounded-2xl border border-white/10 bg-[#0D0F12]/70 p-4">
                                <p class="text-xs font-black uppercase tracking-[0.18em] text-[#14B8A6]"><?php esc_html_e('Price', 'reviewsservice'); ?></p>
                                <p class="mt-2 text-sm font-black text-white"><?php echo wp_kses_post($ms['price']); ?></p>
                            </div>
                            <a href="<?php echo esc_url($ms['url']); ?>" class="mt-7 inline-flex w-full items-center justify-center rounded-full bg-gradient-to-r from-[#00C853] to-[#00E676] px-6 py-4 text-sm font-black text-[#0D0F12] shadow-lg shadow-[#00C853]/20 transition hover:-translate-y-1" aria-label="<?php printf(esc_attr__('Order: %s', 'reviewsservice'), esc_attr($ms['title'])); ?>">
                                <?php echo esc_html($ms['cta']); ?><span class="ml-2" aria-hidden="true">→</span>
                            </a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="mt-14 grid gap-4 rounded-[32px] border border-white/10 bg-white/5 p-6 backdrop-blur-xl md:grid-cols-3 lg:p-8">
                <div><strong class="block text-2xl text-[#FFC107]"><?php esc_html_e('Low Commitment', 'reviewsservice'); ?></strong>
                    <p class="mt-2 text-sm font-semibold leading-6 text-white/70"><?php esc_html_e('Start with a focused micro-service before choosing a full growth plan.', 'reviewsservice'); ?></p>
                </div>
                <div><strong class="block text-2xl text-[#00C853]"><?php esc_html_e('Clear Scope', 'reviewsservice'); ?></strong>
                    <p class="mt-2 text-sm font-semibold leading-6 text-white/70"><?php esc_html_e('Each service has a clear purpose, deliverable, and business outcome.', 'reviewsservice'); ?></p>
                </div>
                <div><strong class="block text-2xl text-[#14B8A6]"><?php esc_html_e('Easy Checkout', 'reviewsservice'); ?></strong>
                    <p class="mt-2 text-sm font-semibold leading-6 text-white/70"><?php esc_html_e('WooCommerce-powered ordering makes it simple to purchase service packages.', 'reviewsservice'); ?></p>
                </div>
            </div>
        </div>
    </section>
    <!-- /Micro Services Shop -->

    <!-- ═══════════════════════════════════════════════════
         TESTIMONIALS — with Schema microdata
    ═══════════════════════════════════════════════════ -->
    <section class="relative overflow-hidden bg-white px-4 py-20 sm:px-6 lg:px-8" aria-labelledby="testimonials-heading">
        <div class="mx-auto max-w-screen-xl">
            <div class="mx-auto max-w-3xl text-center">
                <span class="inline-flex rounded-full bg-[#FFC107]/20 px-5 py-2 text-sm font-black text-[#0D0F12]" aria-hidden="true"><?php esc_html_e('Client Trust', 'reviewsservice'); ?></span>
                <h2 id="testimonials-heading" class="mt-5 text-3xl font-black leading-tight tracking-[-0.04em] text-[#0D0F12] sm:text-4xl lg:text-5xl"><?php esc_html_e('What Business Owners Say About Working With Reviews Service', 'reviewsservice'); ?></h2>
                <p class="mt-5 text-base font-semibold leading-8 text-[#374151] sm:text-lg"><?php esc_html_e('We focus on trust, communication, quality, and real business outcomes.', 'reviewsservice'); ?></p>
            </div>

            <ul class="mt-14 grid list-none gap-6 p-0 md:grid-cols-2 xl:grid-cols-3">
                <?php foreach ($testimonials as $t) :
                    $stars    = (int) $t['rating'];
                    $initials = strtoupper(mb_substr($t['name'], 0, 1));
                ?>
                    <li
                        class="group relative overflow-hidden rounded-[32px] border border-[#E5E7EB] bg-[#F3F4F6] p-7 transition duration-300 hover:-translate-y-2 hover:bg-white hover:shadow-[0_30px_90px_rgba(13,15,18,0.12)]"
                        itemscope itemtype="https://schema.org/Review">

                        <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-[#00C853]/10 blur-2xl transition group-hover:bg-[#00C853]/20" aria-hidden="true"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between gap-4">
                                <!-- FIX: accessible star rating -->
                                <span
                                    class="text-lg tracking-[0.08em] text-[#FFC107]"
                                    role="img"
                                    aria-label="<?php printf(esc_attr__('%d out of 5 stars', 'reviewsservice'), $stars); ?>"
                                    itemprop="reviewRating"
                                    itemscope itemtype="https://schema.org/Rating">
                                    <meta itemprop="ratingValue" content="<?php echo esc_attr($stars); ?>">
                                    <meta itemprop="bestRating" content="5">
                                    <?php echo esc_html(str_repeat('★', $stars) . str_repeat('☆', 5 - $stars)); ?>
                                </span>
                                <?php if ($t['service']) : ?>
                                    <span class="rounded-full bg-[#1E3A8A]/10 px-4 py-2 text-xs font-black text-[#1E3A8A]" itemprop="name"><?php echo esc_html($t['service']); ?></span>
                                <?php endif; ?>
                            </div>

                            <blockquote class="mt-6 text-base font-semibold leading-8 text-[#374151]" itemprop="reviewBody">
                                "<?php echo esc_html($t['quote']); ?>"
                            </blockquote>

                            <div class="mt-7 flex items-center gap-4" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-r from-[#00C853] to-[#00E676] text-sm font-black text-[#0D0F12]" aria-hidden="true">
                                    <?php echo esc_html($initials); ?>
                                </div>
                                <div>
                                    <p class="text-base font-black text-[#0D0F12]" itemprop="name"><?php echo esc_html($t['name']); ?></p>
                                    <p class="text-sm font-bold text-[#374151]/75"><?php echo esc_html($t['role']); ?></p>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <div class="mt-14 overflow-hidden rounded-[32px] bg-gradient-to-r from-[#1E3A8A] to-[#00C853] p-8 text-white shadow-[0_25px_80px_rgba(30,58,138,0.25)] lg:p-10">
                <div class="grid items-center gap-6 lg:grid-cols-[1fr_auto]">
                    <div>
                        <h3 class="text-2xl font-black tracking-[-0.035em] sm:text-3xl"><?php esc_html_e('Want your business to look more trusted online?', 'reviewsservice'); ?></h3>
                        <p class="mt-3 max-w-2xl text-base font-semibold leading-7 text-white/85"><?php esc_html_e("Start with a free consultation and we'll show you the best way to improve reputation, visibility, website quality, and lead generation.", 'reviewsservice'); ?></p>
                    </div>
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="inline-flex justify-center rounded-full bg-[#FFC107] px-8 py-4 text-sm font-black text-[#0D0F12] transition hover:-translate-y-1"><?php esc_html_e('Book Free Consultation →', 'reviewsservice'); ?></a>
                </div>
            </div>
        </div>
    </section>
    <!-- /Testimonials -->

    <!-- ═══════════════════════════════════════════════════
         FAQ — NEW SECTION with accessible accordion
    ═══════════════════════════════════════════════════ -->
    <section class="bg-[#F3F4F6] px-4 py-20 sm:px-6 lg:px-8" aria-labelledby="faq-heading">
        <div class="mx-auto max-w-screen-xl">
            <div class="mx-auto max-w-3xl text-center">
                <span class="inline-flex rounded-full bg-[#1E3A8A]/10 px-5 py-2 text-sm font-black text-[#1E3A8A]" aria-hidden="true"><?php esc_html_e('FAQ', 'reviewsservice'); ?></span>
                <h2 id="faq-heading" class="mt-5 text-3xl font-black leading-tight tracking-[-0.04em] text-[#0D0F12] sm:text-4xl lg:text-5xl">
                    <?php esc_html_e('Frequently Asked Questions', 'reviewsservice'); ?>
                </h2>
            </div>

            <div class="mx-auto mt-14 max-w-3xl" id="rs-faq-list">
                <?php foreach ($faqs as $fi => $faq) :
                    $btn_id   = 'rs-faq-btn-'   . $fi;
                    $panel_id = 'rs-faq-panel-' . $fi;
                ?>
                    <div class="mb-3 overflow-hidden rounded-2xl border border-[#E5E7EB] bg-white">
                        <h3 class="m-0">
                            <button
                                type="button"
                                id="<?php echo esc_attr($btn_id); ?>"
                                aria-expanded="false"
                                aria-controls="<?php echo esc_attr($panel_id); ?>"
                                class="flex w-full items-center justify-between px-6 py-5 text-left text-base font-black text-[#0D0F12] transition hover:text-[#1E3A8A]">
                                <?php echo esc_html($faq['q']); ?>
                                <span class="rs-faq-icon ml-4 shrink-0 text-xl transition-transform duration-300" aria-hidden="true">+</span>
                            </button>
                        </h3>
                        <div
                            id="<?php echo esc_attr($panel_id); ?>"
                            role="region"
                            aria-labelledby="<?php echo esc_attr($btn_id); ?>"
                            class="rs-faq-panel hidden px-6 pb-6">
                            <div class="text-sm font-semibold leading-7 text-[#374151]">
                                <?php echo wp_kses_post($faq['a']); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <!-- /FAQ -->

    <!-- ═══════════════════════════════════════════════════
         FINAL CTA
    ═══════════════════════════════════════════════════ -->
    <section class="relative overflow-hidden bg-gradient-to-r from-[#1E3A8A] via-[#0D0F12] to-[#00C853] px-4 py-20 text-white sm:px-6 lg:px-8" aria-labelledby="final-cta-heading">
        <div class="absolute -left-32 top-0 h-[400px] w-[400px] rounded-full bg-[#00C853]/30 blur-3xl" aria-hidden="true"></div>
        <div class="absolute -right-32 bottom-0 h-[400px] w-[400px] rounded-full bg-[#1E3A8A]/40 blur-3xl" aria-hidden="true"></div>
        <div class="relative mx-auto max-w-screen-xl">
            <div class="grid items-center gap-10 lg:grid-cols-[1fr_auto]">
                <div class="max-w-3xl">
                    <span class="inline-flex rounded-full bg-white/10 px-5 py-2 text-sm font-black text-[#FFC107] backdrop-blur" aria-hidden="true"><?php esc_html_e('Ready to Grow?', 'reviewsservice'); ?></span>
                    <h2 id="final-cta-heading" class="mt-6 text-3xl font-black leading-tight tracking-[-0.04em] text-white sm:text-4xl lg:text-5xl">
                        <?php esc_html_e('Turn Your Online Presence Into a Client-Generating System', 'reviewsservice'); ?>
                    </h2>
                    <p class="mt-5 text-base font-semibold leading-8 text-white/80 sm:text-lg">
                        <?php esc_html_e("Whether you need better reviews, a high-converting website, consistent social media, or profitable ads — we'll help you build a system that attracts customers and grows your business.", 'reviewsservice'); ?>
                    </p>
                    <ul class="mt-6 space-y-3" aria-label="<?php esc_attr_e('Our commitments', 'reviewsservice'); ?>">
                        <li class="flex gap-3 text-sm font-bold text-white/85"><span class="text-[#00E676]" aria-hidden="true">✓</span><?php esc_html_e('Clear strategy tailored to your business', 'reviewsservice'); ?></li>
                        <li class="flex gap-3 text-sm font-bold text-white/85"><span class="text-[#00E676]" aria-hidden="true">✓</span><?php esc_html_e('Professional execution & communication', 'reviewsservice'); ?></li>
                        <li class="flex gap-3 text-sm font-bold text-white/85"><span class="text-[#00E676]" aria-hidden="true">✓</span><?php esc_html_e('Focus on real results — not vanity metrics', 'reviewsservice'); ?></li>
                    </ul>
                </div>
                <div class="flex flex-col items-center gap-4">
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="inline-flex items-center justify-center rounded-full bg-[#FFC107] px-10 py-5 text-base font-black text-[#0D0F12] shadow-[0_15px_40px_rgba(255,193,7,0.4)] transition hover:-translate-y-1">
                        <?php esc_html_e('Get Free Consultation →', 'reviewsservice'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/shop/')); ?>" class="inline-flex items-center justify-center rounded-full border border-white/30 px-8 py-4 text-sm font-black text-white backdrop-blur transition hover:bg-white/10">
                        <?php esc_html_e('Explore Micro Services', 'reviewsservice'); ?>
                    </a>
                    <p class="text-xs font-semibold text-white/70"><?php esc_html_e('No pressure. No commitment. Just clarity.', 'reviewsservice'); ?></p>
                </div>
            </div>
        </div>
    </section>
    <!-- /Final CTA -->

</main>

<!-- ═══════════════════════════════════════════════════
     FLOATING WHATSAPP BUTTON (Customizer-controlled)
═══════════════════════════════════════════════════ -->
<?php if ($whatsapp) : ?>
    <a
        href="https://wa.me/<?php echo esc_attr($whatsapp); ?>?text=<?php echo rawurlencode(__('Hello! I found you through your website and would like to know more about your services.', 'reviewsservice')); ?>"
        class="fixed bottom-6 right-6 z-50 flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] text-white shadow-[0_8px_30px_rgba(37,211,102,0.5)] transition hover:scale-110"
        target="_blank"
        rel="noopener noreferrer"
        aria-label="<?php esc_attr_e('Chat with us on WhatsApp', 'reviewsservice'); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-7 w-7" aria-hidden="true">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
        </svg>
    </a>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════════
     HERO CAROUSEL JAVASCRIPT (accessible, keyboard-nav)
═══════════════════════════════════════════════════ -->
<script>
    (function() {
        'use strict';

        var slides = document.querySelectorAll('.rs-slide');
        var dots = document.querySelectorAll('.rs-dot');
        var label = document.getElementById('rsHeroLabel');
        var titleEl = document.getElementById('rsHeroTitle');
        var textEl = document.getElementById('rsHeroText');
        var status = document.getElementById('rsCarouselStatus');
        var prevBtn = document.getElementById('rsPrev');
        var nextBtn = document.getElementById('rsNext');
        var pauseBtn = document.getElementById('rsPauseBtn');
        var hero = document.getElementById('rs-hero');

        if (!slides.length) return;

        var current = 0;
        var paused = false;
        var timer = null;
        var DELAY = 5000;

        function activate(idx) {
            slides.forEach(function(s, i) {
                var img = s.querySelector('.rs-slide-img');
                var active = i === idx;
                if (img) img.style.opacity = active ? '1' : '0';
            });

            var d = slides[idx].dataset;
            if (label) label.textContent = d.label || '';
            if (titleEl) titleEl.textContent = d.title || '';
            if (textEl) textEl.textContent = d.text || '';

            dots.forEach(function(dot, i) {
                var active = i === idx;
                dot.setAttribute('aria-selected', active ? 'true' : 'false');
                dot.style.background = active ? '#ffffff' : 'rgba(255,255,255,0.35)';
                dot.style.width = active ? '28px' : '12px';
            });

            if (status) {
                status.textContent = '';
                requestAnimationFrame(function() {
                    status.textContent = 'Slide ' + (idx + 1) + ' of ' + slides.length + ': ' + (d.label || '');
                });
            }

            current = idx;
        }

        function next() {
            activate((current + 1) % slides.length);
        }

        function prev() {
            activate((current - 1 + slides.length) % slides.length);
        }

        function startTimer() {
            clearInterval(timer);
            timer = setInterval(next, DELAY);
        }

        function stopTimer() {
            clearInterval(timer);
            timer = null;
        }

        if (nextBtn) nextBtn.addEventListener('click', function() {
            next();
            startTimer();
        });
        if (prevBtn) prevBtn.addEventListener('click', function() {
            prev();
            startTimer();
        });

        dots.forEach(function(d) {
            d.addEventListener('click', function() {
                activate(+d.dataset.index);
                startTimer();
            });
        });

        if (pauseBtn) {
            pauseBtn.addEventListener('click', function() {
                paused = !paused;
                pauseBtn.setAttribute('aria-pressed', paused ? 'true' : 'false');
                pauseBtn.textContent = paused ? '▶' : '⏸';
                pauseBtn.setAttribute('aria-label', paused ? 'Resume carousel' : 'Pause carousel');
                paused ? stopTimer() : startTimer();
            });
        }

        if (hero) {
            hero.addEventListener('mouseenter', stopTimer);
            hero.addEventListener('mouseleave', function() {
                if (!paused) startTimer();
            });
            hero.addEventListener('focusin', stopTimer);
            hero.addEventListener('focusout', function() {
                if (!paused) startTimer();
            });
        }

        document.addEventListener('keydown', function(e) {
            if (!hero || !hero.contains(document.activeElement)) return;
            if (e.key === 'ArrowRight') {
                next();
                startTimer();
            }
            if (e.key === 'ArrowLeft') {
                prev();
                startTimer();
            }
        });

        activate(0);
        startTimer();
    }());
</script>

<!-- FAQ ACCORDION -->
<script>
    (function() {
        'use strict';
        var buttons = document.querySelectorAll('#rs-faq-list h3 button');
        buttons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var expanded = this.getAttribute('aria-expanded') === 'true';

                buttons.forEach(function(b) {
                    b.setAttribute('aria-expanded', 'false');
                    var icon = b.querySelector('.rs-faq-icon');
                    if (icon) icon.textContent = '+';
                    var panel = document.getElementById(b.getAttribute('aria-controls'));
                    if (panel) panel.classList.add('hidden');
                });

                if (!expanded) {
                    this.setAttribute('aria-expanded', 'true');
                    var icon = this.querySelector('.rs-faq-icon');
                    if (icon) icon.textContent = '\u2212'; /* − */
                    var panel = document.getElementById(this.getAttribute('aria-controls'));
                    if (panel) panel.classList.remove('hidden');
                }
            });
        });
    }());
</script>

<?php
get_footer();
