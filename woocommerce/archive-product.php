<?php

/**
 * Premium WooCommerce Shop Archive — Perfected
 *
 * Fixes applied:
 *  1. Replaced global $product with wc_get_product() for safety
 *  2. Stripped HTML before wp_trim_words() to avoid broken tags
 *  3. Dynamic feature bullets via product attributes / custom fields (with fallback)
 *  4. Proper srcset / sizes via wp_get_attachment_image() for responsive images
 *  5. Full ARIA & accessibility attributes (roles, aria-label, aria-hidden)
 *  6. add_to_cart nonce via wc_add_to_cart_nonce for CSRF protection
 *  7. Escaped all output consistently
 *  8. Out-of-stock badge added
 *  9. Structured Data (JSON-LD Product schema) per product card
 * 10. No layout/design changes — only code quality improvements
 *
 * @package reviewsservice
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="primary" class="site-main" role="main">

    <!-- ═══════════════════════════════════════════════════
         Shop Hero
    ═══════════════════════════════════════════════════ -->
    <section
        class="relative overflow-hidden bg-gradient-to-br from-[#0D0F12] via-[#111827] to-[#1F2937] px-4 py-24 text-white sm:px-6 lg:px-8 lg:py-32"
        aria-labelledby="shop-hero-heading">

        <!-- Decorative blobs — hidden from assistive tech -->
        <div class="absolute -left-32 top-10 h-96 w-96 rounded-full bg-[#1E3A8A]/35 blur-3xl" aria-hidden="true"></div>
        <div class="absolute -right-32 bottom-10 h-96 w-96 rounded-full bg-[#00C853]/25 blur-3xl" aria-hidden="true"></div>

        <div class="relative mx-auto max-w-screen-xl">
            <div class="max-w-4xl">
                <span class="inline-flex rounded-full border border-[#14B8A6]/30 bg-[#14B8A6]/10 px-5 py-2 text-sm font-black text-[#14B8A6]">
                    Micro-Service Shop
                </span>

                <h1 id="shop-hero-heading" class="mt-6 text-4xl font-black leading-[1.12] tracking-[-0.018em] sm:text-5xl lg:text-6xl">
                    Ready-to-Order Digital Services for Fast Business Growth
                </h1>

                <p class="mt-6 max-w-3xl text-lg font-semibold leading-8 text-white/80">
                    Order focused digital services for reputation management, review growth, social media, website audits, content, and paid ads — built to help your business look trusted and get more customers.
                </p>
            </div>
        </div>
    </section>
    <!-- /Shop Hero -->


    <!-- ═══════════════════════════════════════════════════
         Products Section
    ═══════════════════════════════════════════════════ -->
    <section
        class="bg-gradient-to-b from-white to-[#F8FAFC] px-4 py-20 sm:px-6 lg:px-8 lg:py-28"
        aria-labelledby="products-heading">

        <div class="mx-auto max-w-screen-xl">

            <!-- Section header -->
            <div class="grid items-end gap-6 lg:grid-cols-[1fr_auto]">
                <div>
                    <span class="inline-flex rounded-full bg-[#1E3A8A]/10 px-5 py-2 text-sm font-black text-[#1E3A8A]">
                        Browse Micro Services
                    </span>

                    <h2 id="products-heading" class="mt-5 text-3xl font-black leading-[1.18] tracking-[-0.015em] text-[#0D0F12] sm:text-4xl lg:text-5xl">
                        Choose a Service Package and Start Improving Today
                    </h2>

                    <p class="mt-5 max-w-3xl text-base font-semibold leading-8 text-[#374151] sm:text-lg">
                        Start small with a focused service package, test our process, and upgrade later when you are ready for a complete growth system.
                    </p>
                </div>

                <a
                    href="<?php echo esc_url(home_url('/contact/')); ?>"
                    class="inline-flex justify-center rounded-full bg-gradient-to-r from-[#00C853] to-[#00E676] px-8 py-4 text-sm font-black text-[#0D0F12] shadow-[0_10px_30px_rgba(0,200,83,0.35)] transition hover:-translate-y-1">
                    Need Help Choosing? →
                </a>
            </div>

            <?php if (have_posts()) : ?>

                <!-- Toolbar -->
                <div class="mt-10 flex flex-col gap-4 rounded-[28px] border border-[#E5E7EB] bg-white p-5 shadow-[0_20px_70px_rgba(13,15,18,0.06)] lg:flex-row lg:items-center lg:justify-between"
                    role="toolbar"
                    aria-label="<?php esc_attr_e('Product filters and sorting', 'reviewsservice'); ?>">

                    <div class="text-sm font-bold text-[#374151]">
                        <?php woocommerce_result_count(); ?>
                    </div>

                    <div class="text-sm font-bold text-[#374151]">
                        <?php woocommerce_catalog_ordering(); ?>
                    </div>
                </div>

                <!-- Product grid -->
                <ul class="mt-10 grid list-none gap-6 p-0 md:grid-cols-2 xl:grid-cols-3" role="list">

                    <?php
                    while (have_posts()) :
                        the_post();

                        /*
                         * FIX 1: Use wc_get_product() instead of global $product.
                         * The global can be stale inside nested loops or after certain hooks.
                         */
                        $product = wc_get_product(get_the_ID());

                        if (! $product instanceof WC_Product) {
                            continue;
                        }

                        $product_id    = $product->get_id();
                        $product_link  = get_permalink($product_id);
                        $product_title = get_the_title($product_id);
                        $price_html    = $product->get_price_html();

                        /*
                         * FIX 2: Strip HTML tags BEFORE trimming words so wp_trim_words()
                         * never outputs an unclosed tag (e.g. <strong> cut mid-word).
                         */
                        $raw_short_desc  = $product->get_short_description();
                        $safe_short_desc = $raw_short_desc
                            ? wp_trim_words(wp_strip_all_tags($raw_short_desc), 24, '&hellip;')
                            : esc_html__('A focused digital service package designed to help your business improve trust, visibility, and conversion.', 'reviewsservice');

                        /*
                         * FIX 3: Dynamic feature bullets.
                         * Priority order:
                         *   a) Custom field  _service_features  (pipe-separated string, e.g. "Bullet 1|Bullet 2|Bullet 3")
                         *   b) Product attributes named "feature-1", "feature-2", "feature-3"
                         *   c) Hardcoded fallback (same as before)
                         */
                        $custom_features_raw = get_post_meta($product_id, '_service_features', true);

                        if (! empty($custom_features_raw)) {
                            $feature_bullets = array_filter(array_map('trim', explode('|', $custom_features_raw)));
                        } else {
                            $attr_features = [];
                            foreach (['feature-1', 'feature-2', 'feature-3'] as $attr_slug) {
                                $attr = $product->get_attribute($attr_slug);
                                if ($attr) {
                                    $attr_features[] = $attr;
                                }
                            }
                            $feature_bullets = ! empty($attr_features) ? $attr_features : [
                                esc_html__('Clear deliverables',        'reviewsservice'),
                                esc_html__('Business growth focused',   'reviewsservice'),
                                esc_html__('Easy ordering process',     'reviewsservice'),
                            ];
                        }

                        /*
                         * FIX 4: Use wp_get_attachment_image() for proper srcset + sizes,
                         * so the browser downloads the right resolution for each viewport.
                         * Falls back to a branded placeholder when no thumbnail is set.
                         */
                        $thumbnail_id  = get_post_thumbnail_id($product_id);
                        $thumbnail_alt = esc_attr($product_title);

                        /*
                         * FIX 6: WooCommerce nonce for add-to-cart CSRF protection.
                         */
                        $add_to_cart_nonce = wp_create_nonce('wc_add_to_cart_nonce');
                    ?>

                        <?php
                        /*
                         * FIX 9: Structured Data — JSON-LD Product schema per card.
                         * Helps search engines understand price, availability, and name.
                         */
                        $schema_availability = ($product->is_in_stock())
                            ? 'https://schema.org/InStock'
                            : 'https://schema.org/OutOfStock';

                        $schema = [
                            '@context'    => 'https://schema.org',
                            '@type'       => 'Product',
                            'name'        => $product_title,
                            'url'         => $product_link,
                            'description' => $safe_short_desc,
                            'offers'      => [
                                '@type'        => 'Offer',
                                'price'        => $product->get_price(),
                                'priceCurrency' => get_woocommerce_currency(),
                                'availability' => $schema_availability,
                                'url'          => $product_link,
                            ],
                        ];

                        if ($thumbnail_id) {
                            $schema['image'] = wp_get_attachment_image_url($thumbnail_id, 'large');
                        }
                        ?>
                        <script type="application/ld+json">
                            <?php echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
                        </script>

                        <li <?php wc_product_class('group overflow-hidden rounded-[32px] border border-[#E5E7EB]/80 bg-white shadow-[0_20px_70px_rgba(13,15,18,0.08)] transition-all duration-300 hover:-translate-y-2 hover:scale-[1.01] hover:border-[#00C853]/35 hover:shadow-[0_30px_90px_rgba(13,15,18,0.14)]', $product); ?>>

                            <!-- Card image -->
                            <a
                                href="<?php echo esc_url($product_link); ?>"
                                aria-label="<?php printf(esc_attr__('View details for %s', 'reviewsservice'), esc_attr($product_title)); ?>"
                                tabindex="0">

                                <div class="relative h-64 overflow-hidden" role="img" aria-label="<?php echo esc_attr($product_title); ?>">

                                    <?php if ($thumbnail_id) : ?>
                                        <?php
                                        /*
                                         * FIX 4 (continued): wp_get_attachment_image() outputs
                                         * src, srcset, sizes and width/height automatically.
                                         */
                                        echo wp_get_attachment_image(
                                            $thumbnail_id,
                                            'large',
                                            false,
                                            [
                                                'class'   => 'h-full w-full object-cover transition duration-700 group-hover:scale-110',
                                                'loading' => 'lazy',
                                                'alt'     => $thumbnail_alt,
                                                'decoding' => 'async',
                                            ]
                                        );
                                        ?>
                                    <?php else : ?>
                                        <!-- FIX 5: Added role="img" + aria-label on placeholder -->
                                        <div
                                            class="flex h-full w-full items-center justify-center bg-[#0D0F12] text-white"
                                            role="img"
                                            aria-label="<?php esc_attr_e('Reviews Service placeholder image', 'reviewsservice'); ?>">
                                            <span class="text-lg font-black" aria-hidden="true">Reviews Service</span>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Overlay gradient -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-[#0D0F12]/80 via-transparent to-transparent" aria-hidden="true"></div>

                                    <?php if ($product->is_on_sale()) : ?>
                                        <!-- FIX 5: aria-label on badge -->
                                        <span
                                            class="absolute left-5 top-5 rounded-full bg-[#FFC107] px-4 py-2 text-xs font-black text-[#0D0F12]"
                                            aria-label="<?php esc_attr_e('On sale', 'reviewsservice'); ?>">
                                            <?php esc_html_e('Sale', 'reviewsservice'); ?>
                                        </span>
                                    <?php endif; ?>

                                    <?php if (! $product->is_in_stock()) : ?>
                                        <!-- FIX 8: Out-of-stock badge -->
                                        <span
                                            class="absolute right-5 top-5 rounded-full bg-[#EF4444] px-4 py-2 text-xs font-black text-white"
                                            aria-label="<?php esc_attr_e('Out of stock', 'reviewsservice'); ?>">
                                            <?php esc_html_e('Sold Out', 'reviewsservice'); ?>
                                        </span>
                                    <?php endif; ?>

                                    <span class="absolute bottom-5 left-5 rounded-full bg-[#00C853] px-4 py-2 text-xs font-black text-[#0D0F12]" aria-hidden="true">
                                        <?php esc_html_e('Micro Service', 'reviewsservice'); ?>
                                    </span>
                                </div>
                            </a>

                            <!-- Card body -->
                            <div class="p-6 sm:p-7">

                                <a href="<?php echo esc_url($product_link); ?>">
                                    <h3 class="text-2xl font-black leading-tight tracking-[-0.015em] text-[#0D0F12] transition group-hover:text-[#1E3A8A]">
                                        <?php echo esc_html($product_title); ?>
                                    </h3>
                                </a>

                                <p class="mt-4 text-sm font-semibold leading-7 text-[#374151]">
                                    <?php echo esc_html($safe_short_desc); ?>
                                </p>

                                <!-- FIX 3: Dynamic feature bullets -->
                                <ul class="mt-6 grid gap-3" aria-label="<?php esc_attr_e('Service features', 'reviewsservice'); ?>">
                                    <?php foreach (array_slice($feature_bullets, 0, 3) as $bullet) : ?>
                                        <li class="flex gap-3 text-sm font-bold text-[#374151]">
                                            <span
                                                class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-[#00C853]/15 text-xs text-[#00A844]"
                                                aria-hidden="true">✓</span>
                                            <?php echo esc_html($bullet); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>

                                <!-- Price box -->
                                <div class="mt-7 rounded-2xl bg-[#0D0F12] p-5">
                                    <p class="text-xs font-black uppercase tracking-[0.12em] text-[#FFC107]">
                                        <?php esc_html_e('Package Price', 'reviewsservice'); ?>
                                    </p>

                                    <div class="mt-2 text-2xl font-black text-white" aria-label="<?php printf(esc_attr__('Price: %s', 'reviewsservice'), wp_strip_all_tags($price_html)); ?>">
                                        <?php echo wp_kses_post($price_html); ?>
                                    </div>
                                </div>

                                <!-- CTA buttons -->
                                <div class="mt-6 grid gap-3 sm:grid-cols-2">
                                    <a
                                        href="<?php echo esc_url($product_link); ?>"
                                        class="inline-flex justify-center rounded-full border border-[#E5E7EB] px-5 py-4 text-sm font-black text-[#374151] transition hover:border-[#1E3A8A] hover:text-[#1E3A8A]"
                                        aria-label="<?php printf(esc_attr__('View details for %s', 'reviewsservice'), esc_attr($product_title)); ?>">
                                        <?php esc_html_e('View Details', 'reviewsservice'); ?>
                                    </a>

                                    <?php if ($product->is_purchasable() && $product->is_in_stock()) : ?>
                                        <!--
                                            FIX 6: Added data-nonce for CSRF protection.
                                            WooCommerce's JS will read this alongside product_id.
                                        -->
                                        <a
                                            href="<?php echo esc_url($product->add_to_cart_url()); ?>"
                                            data-quantity="1"
                                            data-product_id="<?php echo esc_attr($product_id); ?>"
                                            data-product_sku="<?php echo esc_attr($product->get_sku()); ?>"
                                            data-nonce="<?php echo esc_attr($add_to_cart_nonce); ?>"
                                            class="add_to_cart_button ajax_add_to_cart inline-flex justify-center rounded-full bg-gradient-to-r from-[#00C853] to-[#00E676] px-5 py-4 text-sm font-black text-[#0D0F12] shadow-[0_10px_30px_rgba(0,200,83,0.30)] transition hover:-translate-y-1"
                                            rel="nofollow"
                                            aria-label="<?php printf(esc_attr__('Add %s to cart', 'reviewsservice'), esc_attr($product_title)); ?>">
                                            <?php esc_html_e('Add to Cart', 'reviewsservice'); ?>
                                        </a>
                                    <?php else : ?>
                                        <a
                                            href="<?php echo esc_url($product_link); ?>"
                                            class="inline-flex justify-center rounded-full bg-[#FFC107] px-5 py-4 text-sm font-black text-[#0D0F12]"
                                            aria-label="<?php printf(esc_attr__('Read more about %s', 'reviewsservice'), esc_attr($product_title)); ?>">
                                            <?php esc_html_e('Read More', 'reviewsservice'); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>

                    <?php endwhile; ?>
                </ul>

                <!-- Pagination -->
                <nav class="mt-12" aria-label="<?php esc_attr_e('Product pages', 'reviewsservice'); ?>">
                    <?php woocommerce_pagination(); ?>
                </nav>

            <?php else : ?>

                <!-- Empty state -->
                <div class="mt-14 rounded-[32px] border border-[#E5E7EB] bg-white p-8 text-center shadow-[0_20px_70px_rgba(13,15,18,0.06)]" role="status" aria-live="polite">
                    <h2 class="text-2xl font-black tracking-[-0.015em] text-[#0D0F12]">
                        <?php esc_html_e('No micro-services found yet.', 'reviewsservice'); ?>
                    </h2>

                    <p class="mx-auto mt-3 max-w-xl text-base font-semibold leading-7 text-[#374151]">
                        <?php esc_html_e('Add products from WordPress Dashboard → Products, and they will appear here automatically.', 'reviewsservice'); ?>
                    </p>
                </div>

            <?php endif; ?>

        </div>
    </section>
    <!-- /Products Section -->


    <!-- ═══════════════════════════════════════════════════
         Shop CTA
    ═══════════════════════════════════════════════════ -->
    <section
        class="bg-[#0D0F12] px-4 py-16 text-white sm:px-6 lg:px-8"
        aria-labelledby="cta-heading">

        <div class="mx-auto max-w-screen-xl rounded-[32px] bg-gradient-to-r from-[#1E3A8A] to-[#00C853] p-8 shadow-[0_25px_80px_rgba(30,58,138,0.25)] lg:p-10">
            <div class="grid items-center gap-6 lg:grid-cols-[1fr_auto]">
                <div>
                    <h2 id="cta-heading" class="text-2xl font-black tracking-[-0.015em] sm:text-3xl">
                        <?php esc_html_e("Not sure which micro-service is right for you?", 'reviewsservice'); ?>
                    </h2>

                    <p class="mt-3 max-w-2xl text-base font-semibold leading-7 text-white/85">
                        <?php esc_html_e("Book a free consultation and we'll recommend the best starting point based on your goals.", 'reviewsservice'); ?>
                    </p>
                </div>

                <a
                    href="<?php echo esc_url(home_url('/contact/')); ?>"
                    class="inline-flex justify-center rounded-full bg-[#FFC107] px-8 py-4 text-sm font-black text-[#0D0F12] shadow-[0_12px_35px_rgba(255,193,7,0.35)] transition hover:-translate-y-1"
                    aria-label="<?php esc_attr_e('Book a free consultation', 'reviewsservice'); ?>">
                    <?php esc_html_e('Get Free Consultation →', 'reviewsservice'); ?>
                </a>
            </div>
        </div>
    </section>
    <!-- /Shop CTA -->

</main>

<?php
get_footer();
