<?php
/**
 * Premium Checkout Template
 * File: woocommerce/checkout/form-checkout.php
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_checkout_form', $checkout);

if (! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
    return;
}
?>

<!-- Checkout Page Start -->
<section class="bg-[#F3F4F6] px-4 py-14 sm:px-6 lg:px-8 lg:py-20">
    <div class="mx-auto max-w-7xl">

        <div class="mb-10">
            <span class="inline-flex rounded-full bg-[#00C853]/10 px-5 py-2 text-sm font-black text-[#00A344]">
                Secure Checkout
            </span>

            <h1 class="mt-5 font-[Poppins] text-4xl font-black tracking-tight text-[#0D0F12] sm:text-5xl">
                Complete Your Order
            </h1>

            <p class="mt-4 max-w-2xl text-base font-semibold leading-8 text-[#374151]">
                Fill in your details and confirm your service package securely.
            </p>
        </div>

        <form name="checkout"
              method="post"
              class="checkout woocommerce-checkout"
              action="<?php echo esc_url(wc_get_checkout_url()); ?>"
              enctype="multipart/form-data">

            <div class="grid gap-8 lg:grid-cols-[1.5fr_0.85fr]">

                <!-- Billing Details Start -->
                <div class="space-y-8">

                    <?php if ($checkout->get_checkout_fields()) : ?>

                        <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                        <div class="rounded-[2rem] border border-gray-200 bg-white p-6 shadow-xl sm:p-8 lg:p-10">
                            <div class="mb-8">
                                <p class="text-xs font-black uppercase tracking-widest text-[#1E3A8A]">
                                    Step 01
                                </p>
                                <h2 class="mt-2 font-[Poppins] text-2xl font-black text-[#0D0F12]">
                                    Billing Details
                                </h2>
                            </div>

                            <?php do_action('woocommerce_checkout_billing'); ?>
                        </div>

                        <?php if (WC()->cart && WC()->cart->needs_shipping_address()) : ?>
                            <div class="rounded-[2rem] border border-gray-200 bg-white p-6 shadow-xl sm:p-8 lg:p-10">
                                <div class="mb-8">
                                    <p class="text-xs font-black uppercase tracking-widest text-[#1E3A8A]">
                                        Step 02
                                    </p>
                                    <h2 class="mt-2 font-[Poppins] text-2xl font-black text-[#0D0F12]">
                                        Shipping Details
                                    </h2>
                                </div>

                                <?php do_action('woocommerce_checkout_shipping'); ?>
                            </div>
                        <?php else : ?>
                            <div class="rounded-[2rem] border border-gray-200 bg-white p-6 shadow-xl sm:p-8 lg:p-10">
                                <div class="mb-8">
                                    <p class="text-xs font-black uppercase tracking-widest text-[#1E3A8A]">
                                        Step 02
                                    </p>
                                    <h2 class="mt-2 font-[Poppins] text-2xl font-black text-[#0D0F12]">
                                        Order Notes
                                    </h2>
                                </div>

                                <?php do_action('woocommerce_checkout_shipping'); ?>
                            </div>
                        <?php endif; ?>

                        <?php do_action('woocommerce_checkout_after_customer_details'); ?>

                    <?php endif; ?>

                </div>
                <!-- Billing Details End -->

                <!-- Order Summary Start -->
                <aside class="h-fit rounded-[2rem] border border-gray-200 bg-white p-6 shadow-xl lg:sticky lg:top-28 sm:p-8">
                    <div class="mb-8">
                        <p class="text-xs font-black uppercase tracking-widest text-[#00A344]">
                            Step 03
                        </p>

                        <h2 class="mt-2 font-[Poppins] text-2xl font-black text-[#0D0F12]">
                            Your Order
                        </h2>

                        <p class="mt-2 text-sm font-semibold text-[#374151]">
                            Review your package before payment.
                        </p>
                    </div>

                    <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

                    <?php do_action('woocommerce_checkout_before_order_review'); ?>

                    <div id="order_review" class="woocommerce-checkout-review-order">
                        <?php do_action('woocommerce_checkout_order_review'); ?>
                    </div>

                    <?php do_action('woocommerce_checkout_after_order_review'); ?>

                    <div class="mt-6 rounded-3xl bg-[#F3F4F6] p-5">
                        <div class="grid gap-3 text-sm font-bold text-[#374151]">
                            <p>✅ Secure payment</p>
                            <p>✅ Fast order processing</p>
                            <p>✅ Business support included</p>
                        </div>
                    </div>
                </aside>
                <!-- Order Summary End -->

            </div>

        </form>

    </div>
</section>
<!-- Checkout Page End -->

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>