<?php

/**
 * Premium WooCommerce Cart Template
 * File: woocommerce/cart/cart.php
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart');
?>

<!-- Cart Page Start -->
<section class="bg-[#F3F4F6] px-4 py-14 sm:px-6 lg:px-8 lg:py-20">
  <div class="mx-auto max-w-7xl">

    <div class="mb-10">
      <span class="inline-flex rounded-full bg-[#00C853]/10 px-5 py-2 text-sm font-black text-[#00A344]">
        Your Order
      </span>

      <h1 class="mt-5 font-[Poppins] text-4xl font-black tracking-tight text-[#0D0F12] sm:text-5xl">
        Review Your Cart
      </h1>

      <p class="mt-4 max-w-2xl text-base font-semibold leading-8 text-[#374151]">
        Check your selected services before moving to secure checkout.
      </p>
    </div>

    <?php if (WC()->cart && ! WC()->cart->is_empty()) : ?>

      <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">

        <div class="grid gap-8 lg:grid-cols-[1.6fr_0.8fr]">

          <!-- Cart Items Start -->
          <div class="space-y-5">
            <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
              $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
              $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

              if (! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0) {
                continue;
              }

              $product_permalink = apply_filters(
                'woocommerce_cart_item_permalink',
                $_product->is_visible() ? $_product->get_permalink($cart_item) : '',
                $cart_item,
                $cart_item_key
              );
            ?>

              <div class="rounded-[2rem] border border-gray-200 bg-white p-5 shadow-lg sm:p-6">
                <div class="grid gap-5 sm:grid-cols-[120px_1fr_auto] sm:items-center">

                  <div class="overflow-hidden rounded-2xl bg-[#F3F4F6]">
                    <?php
                    $thumbnail = apply_filters(
                      'woocommerce_cart_item_thumbnail',
                      $_product->get_image('woocommerce_thumbnail', [
                        'class' => 'h-28 w-full object-cover',
                      ]),
                      $cart_item,
                      $cart_item_key
                    );

                    if ($product_permalink) {
                      echo '<a href="' . esc_url($product_permalink) . '">' . wp_kses_post($thumbnail) . '</a>';
                    } else {
                      echo wp_kses_post($thumbnail);
                    }
                    ?>
                  </div>

                  <div>
                    <h2 class="font-[Poppins] text-xl font-black text-[#0D0F12]">
                      <?php
                      $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);

                      if ($product_permalink) {
                        echo '<a class="transition hover:text-[#1E3A8A]" href="' . esc_url($product_permalink) . '">' . wp_kses_post($product_name) . '</a>';
                      } else {
                        echo wp_kses_post($product_name);
                      }
                      ?>
                    </h2>

                    <div class="mt-2 text-sm font-semibold text-[#374151]">
                      <?php echo wc_get_formatted_cart_item_data($cart_item); ?>
                    </div>

                    <div class="mt-4 text-lg font-black text-[#1E3A8A]">
                      <?php
                      echo apply_filters(
                        'woocommerce_cart_item_price',
                        WC()->cart->get_product_price($_product),
                        $cart_item,
                        $cart_item_key
                      );
                      ?>
                    </div>
                  </div>

                  <div class="space-y-4 sm:text-right">
                    <div>
                      <label class="mb-2 block text-xs font-black uppercase tracking-wider text-[#374151]">
                        Quantity
                      </label>

                      <?php
                      if ($_product->is_sold_individually()) {
                        echo '<input type="hidden" name="cart[' . esc_attr($cart_item_key) . '][qty]" value="1" />';
                        echo '<span class="font-black">1</span>';
                      } else {
                        echo woocommerce_quantity_input(
                          [
                            'input_name'   => "cart[{$cart_item_key}][qty]",
                            'input_value'  => $cart_item['quantity'],
                            'max_value'    => $_product->get_max_purchase_quantity(),
                            'min_value'    => '0',
                            'product_name' => $_product->get_name(),
                            'classes'      => ['input-text', 'qty', 'text', 'w-24', 'rounded-full', 'border', 'border-gray-300', 'px-4', 'py-3', 'text-center', 'font-black'],
                          ],
                          $_product,
                          false
                        );
                      }
                      ?>
                    </div>

                    <div class="text-lg font-black text-[#0D0F12]">
                      <?php
                      echo apply_filters(
                        'woocommerce_cart_item_subtotal',
                        WC()->cart->get_product_subtotal($_product, $cart_item['quantity']),
                        $cart_item,
                        $cart_item_key
                      );
                      ?>
                    </div>

                    <?php
                    echo apply_filters(
                      'woocommerce_cart_item_remove_link',
                      sprintf(
                        '<a href="%s" class="inline-flex rounded-full bg-red-50 px-4 py-2 text-xs font-black text-red-600 transition hover:bg-red-600 hover:text-white" aria-label="%s" data-product_id="%s" data-product_sku="%s">Remove</a>',
                        esc_url(wc_get_cart_remove_url($cart_item_key)),
                        esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($_product->get_name()))),
                        esc_attr($product_id),
                        esc_attr($_product->get_sku())
                      ),
                      $cart_item_key
                    );
                    ?>
                  </div>

                </div>
              </div>

            <?php endforeach; ?>

            <!-- Cart Actions Start -->
            <div class="rounded-[2rem] border border-gray-200 bg-white p-5 shadow-lg sm:p-6">
              <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

                <?php if (wc_coupons_enabled()) : ?>
                  <div class="flex flex-col gap-3 sm:flex-row">
                    <input
                      type="text"
                      name="coupon_code"
                      class="rounded-full border border-gray-300 px-5 py-4 text-sm font-bold outline-none focus:border-[#00C853]"
                      placeholder="Coupon code" />

                    <button
                      type="submit"
                      class="rounded-full bg-[#0D0F12] px-6 py-4 text-sm font-black text-white transition hover:bg-[#1E3A8A]"
                      name="apply_coupon"
                      value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>">
                      Apply Coupon
                    </button>
                  </div>
                <?php endif; ?>

                <button
                  type="submit"
                  class="rounded-full bg-[#00C853] px-7 py-4 text-sm font-black text-white shadow-lg transition hover:bg-[#00A344]"
                  name="update_cart"
                  value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>">
                  Update Cart
                </button>

                <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
              </div>
            </div>
            <!-- Cart Actions End -->

          </div>
          <!-- Cart Items End -->

          <!-- Cart Summary Start -->
          <aside class="h-fit rounded-[2rem] border border-gray-200 bg-white p-6 shadow-xl lg:sticky lg:top-28">
            <h2 class="font-[Poppins] text-2xl font-black text-[#0D0F12]">
              Order Summary
            </h2>

            <div class="mt-6">
              <?php woocommerce_cart_totals(); ?>
            </div>

            <div class="mt-6 rounded-3xl bg-[#F3F4F6] p-5">
              <div class="grid gap-3 text-sm font-bold text-[#374151]">
                <p>✅ Secure checkout</p>
                <p>✅ Fast service processing</p>
                <p>✅ Support included</p>
              </div>
            </div>
          </aside>
          <!-- Cart Summary End -->

        </div>

      </form>

    <?php else : ?>

      <div class="rounded-[2rem] bg-white p-10 text-center shadow-xl">
        <h2 class="font-[Poppins] text-3xl font-black text-[#0D0F12]">
          Your cart is empty
        </h2>

        <p class="mt-3 text-[#374151]">
          Add a service package to start your order.
        </p>

        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"
          class="mt-6 inline-flex rounded-full bg-[#00C853] px-8 py-4 text-sm font-black text-white transition hover:bg-[#00A344]">
          Browse Services
        </a>
      </div>

    <?php endif; ?>

  </div>
</section>
<!-- Cart Page End -->

<?php do_action('woocommerce_after_cart'); ?>