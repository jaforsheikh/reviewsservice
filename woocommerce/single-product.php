<?php

/**
 * Clean WooCommerce Single Product - Tailwind Layout
 * File: woocommerce/single-product.php
 */

defined('ABSPATH') || exit;

get_header();

global $product;

if (!is_a($product, 'WC_Product')) {
  $product = wc_get_product(get_the_ID());
}

if (!$product) {
  get_footer();
  return;
}

$product_id        = $product->get_id();
$main_image_id     = $product->get_image_id();
$gallery_image_ids = $product->get_gallery_image_ids();
$related_ids       = wc_get_related_products($product_id, 4);
?>

<main class="bg-[#F3F4F6]">

  <!-- Product Hero Start -->
  <section class="bg-gradient-to-br from-[#0D0F12] via-[#111827] to-[#1E3A8A] py-14 lg:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

      <?php while (have_posts()) : the_post(); ?>

        <div class="grid items-start gap-8 lg:grid-cols-2 lg:gap-12">

          <!-- Product Gallery Start -->
          <div class="rounded-[2rem] bg-white/10 p-4 shadow-2xl backdrop-blur">
            <div class="overflow-hidden rounded-[1.5rem] bg-white">
              <?php if ($main_image_id) : ?>
                <?php echo wp_get_attachment_image($main_image_id, 'large', false, [
                  'class' => 'rs-main-product-image aspect-[4/3] w-full object-cover',
                  'alt'   => esc_attr(get_the_title()),
                ]); ?>
              <?php else : ?>
                <?php echo wc_placeholder_img('large', [
                  'class' => 'aspect-[4/3] w-full object-cover',
                ]); ?>
              <?php endif; ?>
            </div>

            <?php if (!empty($gallery_image_ids)) : ?>
              <div class="mt-4 flex gap-3 overflow-x-auto pb-2">
                <?php if ($main_image_id) : ?>
                  <button type="button"
                    class="rs-thumb-btn h-20 w-20 shrink-0 overflow-hidden rounded-2xl border-2 border-[#00C853] bg-white p-1"
                    data-full-image="<?php echo esc_url(wp_get_attachment_image_url($main_image_id, 'large')); ?>">
                    <?php echo wp_get_attachment_image($main_image_id, 'thumbnail', false, [
                      'class' => 'h-full w-full rounded-xl object-cover',
                    ]); ?>
                  </button>
                <?php endif; ?>

                <?php foreach ($gallery_image_ids as $image_id) : ?>
                  <button type="button"
                    class="rs-thumb-btn h-20 w-20 shrink-0 overflow-hidden rounded-2xl border border-white/20 bg-white p-1 transition hover:border-[#00C853]"
                    data-full-image="<?php echo esc_url(wp_get_attachment_image_url($image_id, 'large')); ?>">
                    <?php echo wp_get_attachment_image($image_id, 'thumbnail', false, [
                      'class' => 'h-full w-full rounded-xl object-cover',
                    ]); ?>
                  </button>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
          <!-- Product Gallery End -->

          <!-- Product Summary Start -->
          <div class="rounded-[2rem] bg-white p-6 shadow-2xl sm:p-8 lg:p-10">
            <span class="inline-flex rounded-full bg-[#00C853]/10 px-4 py-2 text-xs font-black uppercase tracking-wider text-[#00A344]">
              Premium Micro Service
            </span>

            <h1 class="mt-5 font-[Poppins] text-3xl font-extrabold leading-tight text-[#0D0F12] sm:text-4xl lg:text-5xl">
              <?php the_title(); ?>
            </h1>

            <div class="mt-4 text-sm font-bold text-[#FFC107]">
              <?php woocommerce_template_single_rating(); ?>
            </div>

            <div class="mt-6 text-3xl font-black text-[#1E3A8A]">
              <?php woocommerce_template_single_price(); ?>
            </div>

            <div class="mt-6 text-base leading-8 text-[#374151]">
              <?php woocommerce_template_single_excerpt(); ?>
            </div>

            <div class="mt-8 rounded-3xl border border-gray-200 bg-[#F9FAFB] p-5">
              <?php woocommerce_template_single_add_to_cart(); ?>
            </div>

            <div class="mt-8 grid gap-3 sm:grid-cols-3">
              <div class="rounded-2xl bg-[#F3F4F6] p-4 text-center">
                <div class="text-xl">⚡</div>
                <p class="mt-1 text-sm font-black text-[#0D0F12]">Fast Delivery</p>
              </div>
              <div class="rounded-2xl bg-[#F3F4F6] p-4 text-center">
                <div class="text-xl">✅</div>
                <p class="mt-1 text-sm font-black text-[#0D0F12]">Quality Work</p>
              </div>
              <div class="rounded-2xl bg-[#F3F4F6] p-4 text-center">
                <div class="text-xl">💬</div>
                <p class="mt-1 text-sm font-black text-[#0D0F12]">Support</p>
              </div>
            </div>
          </div>
          <!-- Product Summary End -->

        </div>

      <?php endwhile; ?>

    </div>
  </section>
  <!-- Product Hero End -->

  <!-- Product Description Start -->
  <section class="py-14 lg:py-20">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
      <div class="rounded-[2rem] bg-white p-6 shadow-xl sm:p-8 lg:p-10">
        <?php woocommerce_output_product_data_tabs(); ?>
      </div>
    </div>
  </section>
  <!-- Product Description End -->

  <?php if (!empty($related_ids)) : ?>
    <!-- Related Products Start -->
    <section class="bg-white py-14 lg:py-20">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-10">
          <p class="inline-flex rounded-full bg-[#1E3A8A]/10 px-4 py-2 text-xs font-black text-[#1E3A8A]">
            Related Services
          </p>
          <h2 class="mt-4 font-[Poppins] text-3xl font-extrabold text-[#0D0F12]">
            Related Products
          </h2>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
          <?php foreach ($related_ids as $related_id) :
            $related_product = wc_get_product($related_id);
            if (!$related_product) {
              continue;
            }
          ?>
            <div class="overflow-hidden rounded-[1.75rem] bg-white shadow-lg ring-1 ring-gray-200 transition hover:-translate-y-1 hover:shadow-2xl">
              <a href="<?php echo esc_url(get_permalink($related_id)); ?>">
                <div class="aspect-[4/3] overflow-hidden bg-[#F3F4F6]">
                  <?php echo $related_product->get_image('woocommerce_thumbnail', [
                    'class' => 'h-full w-full object-cover',
                  ]); ?>
                </div>

                <div class="p-5">
                  <h3 class="font-[Poppins] text-lg font-extrabold text-[#0D0F12]">
                    <?php echo esc_html($related_product->get_name()); ?>
                  </h3>

                  <div class="mt-3 text-lg font-black text-[#1E3A8A]">
                    <?php echo wp_kses_post($related_product->get_price_html()); ?>
                  </div>
                </div>
              </a>

              <div class="px-5 pb-5">
                <a href="<?php echo esc_url(get_permalink($related_id)); ?>"
                  class="inline-flex w-full items-center justify-center rounded-full bg-[#00C853] px-5 py-3 text-sm font-black text-white transition hover:bg-[#00A344]">
                  View Details
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <!-- Related Products End -->
  <?php endif; ?>

</main>

<?php
get_footer();
